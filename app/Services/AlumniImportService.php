<?php

namespace App\Services;

use App\Models\Alumni;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Support\Facades\Validator;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Writer\XLSX\Writer;
use Throwable;

class AlumniImportService
{
    /**
     * Canonical column order used when generating the import template.
     *
     * @var array<string, string>
     */
    public const TEMPLATE_COLUMNS = [
        'full_name' => 'Nama Lengkap',
        'nickname' => 'Nama Panggilan',
        'birth_place' => 'Tempat Lahir',
        'birth_date' => 'Tanggal Lahir',
        'address' => 'Alamat',
        'phone' => 'Phone',
        'major' => 'Jurusan',
        'graduation_year' => 'Tahun Lulus',
        'certificate_number' => 'No Ijazah',
        'instagram' => 'Instagram',
        'twitter' => 'Twitter',
        'facebook' => 'Facebook',
        'youtube' => 'Youtube Channel',
        'occupation' => 'Pekerjaan',
        'entered_ptn' => 'Masuk PTN',
        'ptn_name' => 'Nama PTN',
    ];

    /**
     * Header aliases (normalized) mapped to the canonical attribute name.
     *
     * @var array<string, string>
     */
    private const HEADER_ALIASES = [
        'nama lengkap' => 'full_name',
        'nama' => 'full_name',
        'nama panggilan' => 'nickname',
        'panggilan' => 'nickname',
        'tempat lahir' => 'birth_place',
        'tanggal lahir' => 'birth_date',
        'tgl lahir' => 'birth_date',
        'alamat' => 'address',
        'phone' => 'phone',
        'no hp' => 'phone',
        'nomor hp' => 'phone',
        'telepon' => 'phone',
        'whatsapp' => 'phone',
        'jurusan' => 'major',
        'tahun lulus' => 'graduation_year',
        'angkatan' => 'graduation_year',
        'no ijazah' => 'certificate_number',
        'nomor ijazah' => 'certificate_number',
        'instagram' => 'instagram',
        'ig' => 'instagram',
        'twitter' => 'twitter',
        'x' => 'twitter',
        'facebook' => 'facebook',
        'fb' => 'facebook',
        'youtube channel' => 'youtube',
        'youtube' => 'youtube',
        'pekerjaan' => 'occupation',
        'masuk ptn' => 'entered_ptn',
        'apakah masuk ptn' => 'entered_ptn',
        'nama ptn' => 'ptn_name',
        'ptn' => 'ptn_name',
    ];

    private const TRUTHY = ['ya', 'yes', 'y', 'true', '1', 'benar', 'masuk', 'sudah'];

    /**
     * Import alumni from an .xlsx file at the given absolute path.
     *
     * Rows are matched on `certificate_number` when present (update), otherwise
     * a new record is created. Date and boolean cells that Excel may store in
     * inconsistent shapes are normalized before persisting.
     */
    public function import(string $absolutePath): AlumniImportResult
    {
        $result = new AlumniImportResult;

        $reader = new Reader;
        $reader->open($absolutePath);

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                $columnMap = null;

                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $values = $row->toArray();

                    if ($columnMap === null) {
                        $columnMap = $this->mapHeaders($values);

                        continue;
                    }

                    if ($this->isBlankRow($values)) {
                        continue;
                    }

                    $this->importRow($values, $columnMap, $rowNumber, $result);
                }

                break; // Only the first sheet is imported.
            }
        } finally {
            $reader->close();
        }

        return $result;
    }

    /**
     * Write a ready-to-fill .xlsx import template (header row + one example
     * row) to the given absolute path.
     */
    public function writeTemplate(string $absolutePath): void
    {
        $writer = new Writer;
        $writer->openToFile($absolutePath);

        try {
            $writer->addRow(Row::fromValues(array_values(self::TEMPLATE_COLUMNS)));
            $writer->addRow(Row::fromValues([
                'Budi Santoso', 'Budi', 'Bandung', '17/08/2005', 'Jl. Merdeka No. 1, Bandung',
                '081234567890', 'IPA', 2023, 'DN-12-Ma/0001234', 'budi.santoso', 'budisantoso',
                'budi.santoso', 'BudiSantosoChannel', 'Mahasiswa', 'Ya', 'Universitas Padjadjaran',
            ]));
        } finally {
            $writer->close();
        }
    }

    /**
     * @param  list<mixed>  $values
     * @param  array<int, string>  $columnMap
     */
    private function importRow(array $values, array $columnMap, int $rowNumber, AlumniImportResult $result): void
    {
        $attributes = $this->extractAttributes($values, $columnMap);

        if (blank($attributes['full_name'] ?? null)) {
            $result->skipped++;

            return;
        }

        $validator = Validator::make($attributes, [
            'full_name' => ['required', 'string', 'max:150'],
            'graduation_year' => ['nullable', 'integer', 'min:1950', 'max:'.((int) date('Y') + 1)],
            'certificate_number' => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            $result->errors[] = "Baris {$rowNumber}: ".implode(' ', $validator->errors()->all());

            return;
        }

        try {
            $certificateNumber = $attributes['certificate_number'] ?? null;

            $existing = filled($certificateNumber)
                ? Alumni::query()->where('certificate_number', $certificateNumber)->first()
                : null;

            if ($existing !== null) {
                $existing->fill($attributes)->save();
                $result->updated++;

                return;
            }

            Alumni::create($attributes);
            $result->created++;
        } catch (Throwable $e) {
            $result->errors[] = "Baris {$rowNumber}: gagal disimpan ({$e->getMessage()}).";
        }
    }

    /**
     * @param  list<mixed>  $values
     * @param  array<int, string>  $columnMap
     * @return array<string, mixed>
     */
    private function extractAttributes(array $values, array $columnMap): array
    {
        $attributes = [];

        foreach ($columnMap as $index => $attribute) {
            $attributes[$attribute] = $values[$index] ?? null;
        }

        foreach (['full_name', 'nickname', 'birth_place', 'address', 'phone', 'major', 'certificate_number', 'instagram', 'twitter', 'facebook', 'youtube', 'occupation', 'ptn_name'] as $field) {
            if (array_key_exists($field, $attributes)) {
                $attributes[$field] = $this->toNullableString($attributes[$field]);
            }
        }

        if (array_key_exists('birth_date', $attributes)) {
            $attributes['birth_date'] = $this->normalizeDate($attributes['birth_date']);
        }

        if (array_key_exists('graduation_year', $attributes)) {
            $attributes['graduation_year'] = $this->normalizeYear($attributes['graduation_year']);
        }

        $attributes['entered_ptn'] = $this->normalizeBoolean($attributes['entered_ptn'] ?? null);

        if (! $attributes['entered_ptn']) {
            $attributes['ptn_name'] = null;
        }

        return $attributes;
    }

    /**
     * Convert any Excel cell value into a `Y-m-d` string, or null.
     *
     * Handles three shapes Excel produces: native date cells (DateTime),
     * raw serial numbers, and free-text dates in day-first or ISO order.
     */
    public function normalizeDate(mixed $value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return CarbonImmutable::instance($value)->format('Y-m-d');
        }

        if (is_int($value) || is_float($value) || (is_string($value) && is_numeric(trim($value)))) {
            $serial = (float) $value;

            if ($serial <= 0) {
                return null;
            }

            // Excel's epoch is 1899-12-30 (this base already absorbs the
            // fictitious 1900-02-29 leap day for serials >= 60).
            return CarbonImmutable::create(1899, 12, 30)
                ->addDays((int) floor($serial))
                ->format('Y-m-d');
        }

        $string = $this->toNullableString($value);

        if ($string === null) {
            return null;
        }

        // Normalize every separator to "/" and drop any trailing time component
        // so day-first, ISO, and dotted Indonesian dates all share one shape.
        $string = str_replace(['-', '.', '\\'], '/', $string);
        $string = explode(' ', $string)[0];

        foreach (['d/m/Y', 'd/m/y', 'Y/m/d'] as $format) {
            try {
                $parsed = CarbonImmutable::createFromFormat('!'.$format, $string);
            } catch (Throwable) {
                continue;
            }

            // Reject formats that silently overflowed (e.g. day 32 → next month).
            if ($parsed->format($format) === $string) {
                return $parsed->format('Y-m-d');
            }
        }

        return null;
    }

    private function normalizeYear(mixed $value): ?int
    {
        if ($value instanceof DateTimeInterface) {
            return (int) $value->format('Y');
        }

        $string = $this->toNullableString($value);

        if ($string === null || ! preg_match('/(\d{4})/', $string, $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    private function normalizeBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $string = $this->toNullableString($value);

        if ($string === null) {
            return false;
        }

        return in_array(mb_strtolower($string), self::TRUTHY, true);
    }

    private function toNullableString(mixed $value): ?string
    {
        if ($value === null || $value instanceof DateTimeInterface) {
            return $value instanceof DateTimeInterface ? $value->format('Y-m-d') : null;
        }

        if (is_float($value) && floor($value) === $value) {
            $value = (int) $value;
        }

        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }

    /**
     * @param  list<mixed>  $headerValues
     * @return array<int, string> Column index => canonical attribute name.
     */
    private function mapHeaders(array $headerValues): array
    {
        $map = [];

        foreach ($headerValues as $index => $header) {
            $normalized = $this->normalizeHeader($header);

            if ($normalized !== null && isset(self::HEADER_ALIASES[$normalized])) {
                $map[$index] = self::HEADER_ALIASES[$normalized];
            }
        }

        return $map;
    }

    private function normalizeHeader(mixed $header): ?string
    {
        if (! is_string($header) && ! is_numeric($header)) {
            return null;
        }

        $normalized = mb_strtolower(trim((string) $header));
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $normalized) ?? '';
        $normalized = trim(preg_replace('/\s+/', ' ', $normalized) ?? '');

        return $normalized === '' ? null : $normalized;
    }

    /**
     * @param  list<mixed>  $values
     */
    private function isBlankRow(array $values): bool
    {
        foreach ($values as $value) {
            if ($this->toNullableString($value) !== null) {
                return false;
            }
        }

        return true;
    }
}
