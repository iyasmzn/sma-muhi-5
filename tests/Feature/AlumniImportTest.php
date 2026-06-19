<?php

namespace Tests\Feature;

use App\Models\Alumni;
use App\Services\AlumniImportService;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Tests\TestCase;

class AlumniImportTest extends TestCase
{
    use RefreshDatabase;

    private AlumniImportService $service;

    /** @var list<string> */
    private array $tempFiles = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AlumniImportService;
    }

    protected function tearDown(): void
    {
        foreach ($this->tempFiles as $file) {
            @unlink($file);
        }

        parent::tearDown();
    }

    // ── Date normalization (the "Excel mangles dates" requirement) ──────────

    public function test_normalizes_native_datetime_cell(): void
    {
        $this->assertSame('2004-05-20', $this->service->normalizeDate(new DateTimeImmutable('2004-05-20 13:00:00')));
    }

    public function test_normalizes_excel_serial_number(): void
    {
        // Excel serial 45000 == 2023-03-15 (Windows 1900 date system).
        $this->assertSame('2023-03-15', $this->service->normalizeDate(45000));
        $this->assertSame('2023-03-15', $this->service->normalizeDate('45000'));
    }

    public function test_normalizes_day_first_string_dates(): void
    {
        $this->assertSame('2005-08-17', $this->service->normalizeDate('17/08/2005'));
        $this->assertSame('2005-08-17', $this->service->normalizeDate('17-08-2005'));
        $this->assertSame('2005-08-17', $this->service->normalizeDate('17.08.2005'));
    }

    public function test_normalizes_iso_string_date(): void
    {
        $this->assertSame('2005-08-17', $this->service->normalizeDate('2005-08-17'));
    }

    public function test_returns_null_for_unparseable_or_blank_date(): void
    {
        $this->assertNull($this->service->normalizeDate('bukan tanggal'));
        $this->assertNull($this->service->normalizeDate(''));
        $this->assertNull($this->service->normalizeDate(null));
    }

    // ── Full import ─────────────────────────────────────────────────────────

    public function test_imports_rows_with_mixed_date_formats(): void
    {
        $path = $this->makeXlsx([
            $this->header(),
            ['Andi Wijaya', 'Andi', 'Surabaya', new DateTimeImmutable('2004-05-20'), 'Jl. A', '0811', 'IPA', 2022, 'IJ-001', 'andi', '', '', '', 'Mahasiswa', 'Ya', 'ITB'],
            ['Bunga Lestari', 'Bunga', 'Medan', 45000, 'Jl. B', '0812', 'IPS', '2021', 'IJ-002', '', '', '', '', 'Wirausaha', 'Tidak', 'Harusnya dikosongkan'],
            ['Citra Dewi', 'Citra', 'Bandung', '17/08/2005', 'Jl. C', '0813', 'Bahasa', 'Lulus 2020', '', '', '', '', '', '', 'ya', 'UNPAD'],
        ]);

        $result = $this->service->import($path);

        $this->assertSame(3, $result->created);
        $this->assertSame(0, $result->updated);
        $this->assertFalse($result->hasErrors());

        $andi = Alumni::where('certificate_number', 'IJ-001')->firstOrFail();
        $this->assertSame('2004-05-20', $andi->birth_date->format('Y-m-d'));
        $this->assertTrue($andi->entered_ptn);
        $this->assertSame('ITB', $andi->ptn_name);

        $bunga = Alumni::where('certificate_number', 'IJ-002')->firstOrFail();
        $this->assertSame('2023-03-15', $bunga->birth_date->format('Y-m-d'));
        $this->assertFalse($bunga->entered_ptn);
        $this->assertNull($bunga->ptn_name, 'PTN name must be cleared when the alumnus did not enter PTN.');

        $citra = Alumni::where('full_name', 'Citra Dewi')->firstOrFail();
        $this->assertSame('2005-08-17', $citra->birth_date->format('Y-m-d'));
        $this->assertSame(2020, $citra->graduation_year);
        $this->assertTrue($citra->entered_ptn);
    }

    public function test_reports_rows_without_a_name_as_failed(): void
    {
        $path = $this->makeXlsx([
            $this->header(),
            ['', '', '', '', '', '', '', '', 'IJ-009', '', '', '', '', '', '', ''],
        ]);

        $result = $this->service->import($path);

        $this->assertSame(0, $result->created);
        $this->assertSame(1, $result->failed());
        $this->assertTrue($result->hasErrors());
        $this->assertStringContainsString('Nama Lengkap', $result->errors[0]);
        $this->assertSame(0, Alumni::count());
    }

    public function test_imports_valid_rows_and_reports_invalid_ones(): void
    {
        $path = $this->makeXlsx([
            $this->header(),
            ['Orang Valid', '', '', '', '', '', 'IPA', 2022, 'IJ-A', '', '', '', '', '', 'Tidak', ''],
            ['Tahun Salah', '', '', '', '', '', 'IPS', 3000, 'IJ-B', '', '', '', '', '', 'Tidak', ''],
        ]);

        $result = $this->service->import($path);

        $this->assertSame(1, $result->created);
        $this->assertSame(1, $result->failed());
        $this->assertTrue($result->hasErrors());
        $this->assertStringContainsString('Baris 3', $result->errors[0]);

        $this->assertDatabaseHas(Alumni::class, ['certificate_number' => 'IJ-A']);
        $this->assertDatabaseMissing(Alumni::class, ['certificate_number' => 'IJ-B']);
    }

    public function test_successful_import_has_no_errors(): void
    {
        $path = $this->makeXlsx([
            $this->header(),
            ['Orang Satu', '', '', '', '', '', 'IPA', 2022, 'IJ-X', '', '', '', '', '', 'Ya', 'ITB'],
            ['Orang Dua', '', '', '', '', '', 'IPS', 2023, 'IJ-Y', '', '', '', '', '', 'Tidak', ''],
        ]);

        $result = $this->service->import($path);

        $this->assertSame(2, $result->created);
        $this->assertSame(2, $result->processed());
        $this->assertSame(0, $result->failed());
        $this->assertFalse($result->hasErrors());
    }

    public function test_updates_existing_record_matched_by_certificate_number(): void
    {
        Alumni::factory()->create([
            'full_name' => 'Nama Lama',
            'certificate_number' => 'IJ-100',
            'graduation_year' => 2010,
        ]);

        $path = $this->makeXlsx([
            $this->header(),
            ['Nama Baru', '', '', '', '', '', 'IPA', 2011, 'IJ-100', '', '', '', '', '', 'Tidak', ''],
        ]);

        $result = $this->service->import($path);

        $this->assertSame(0, $result->created);
        $this->assertSame(1, $result->updated);
        $this->assertSame(1, Alumni::count());

        $this->assertDatabaseHas(Alumni::class, [
            'certificate_number' => 'IJ-100',
            'full_name' => 'Nama Baru',
            'graduation_year' => 2011,
        ]);
    }

    public function test_template_can_be_generated_and_round_trips(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'alumni_tpl_').'.xlsx';
        $this->tempFiles[] = $path;

        $this->service->writeTemplate($path);

        // The generated template must be importable by the same service.
        $result = $this->service->import($path);

        $this->assertSame(1, $result->created);
        $this->assertDatabaseHas(Alumni::class, ['full_name' => 'Budi Santoso']);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * @return list<string>
     */
    private function header(): array
    {
        return array_values(AlumniImportService::TEMPLATE_COLUMNS);
    }

    /**
     * @param  list<list<mixed>>  $rows
     */
    private function makeXlsx(array $rows): string
    {
        $path = tempnam(sys_get_temp_dir(), 'alumni_test_').'.xlsx';
        $this->tempFiles[] = $path;

        $writer = new Writer;
        $writer->openToFile($path);

        foreach ($rows as $row) {
            $writer->addRow(Row::fromValues($row));
        }

        $writer->close();

        return $path;
    }
}
