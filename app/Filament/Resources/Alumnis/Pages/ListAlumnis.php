<?php

namespace App\Filament\Resources\Alumnis\Pages;

use App\Filament\Resources\Alumnis\AlumniResource;
use App\Services\AlumniImportResult;
use App\Services\AlumniImportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListAlumnis extends ListRecords
{
    protected static string $resource = AlumniResource::class;

    /**
     * Maximum number of failed-row messages listed in the failure notification
     * before the remainder is summarized as a count.
     */
    private const MAX_ERRORS_SHOWN = 10;

    protected function getHeaderActions(): array
    {
        return [
            $this->templateAction(),
            $this->importAction(),
            CreateAction::make(),
        ];
    }

    private function importAction(): Action
    {
        return Action::make('import')
            ->label('Import Excel')
            ->icon(Heroicon::ArrowUpTray)
            ->color('gray')
            ->modalHeading('Import Data Alumni')
            ->modalDescription('Unggah file Excel (.xlsx) sesuai template. Baris dengan No. Ijazah yang sama akan diperbarui.')
            ->modalSubmitActionLabel('Import')
            ->schema([
                FileUpload::make('file')
                    ->label('File Excel (.xlsx)')
                    ->required()
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->disk('local')
                    ->directory('alumni-imports')
                    ->storeFiles(),
            ])
            ->action(function (array $data): void {
                $disk = Storage::disk('local');
                $path = $data['file'];

                try {
                    $result = app(AlumniImportService::class)->import($disk->path($path));
                } finally {
                    $disk->delete($path);
                }

                $this->notifyImportResult($result);
            });
    }

    private function notifyImportResult(AlumniImportResult $result): void
    {
        if ($result->total() === 0) {
            Notification::make()
                ->title('Tidak ada data')
                ->body('File tidak berisi baris data untuk diimpor.')
                ->warning()
                ->send();

            return;
        }

        if (! $result->hasErrors()) {
            Notification::make()
                ->title('Import berhasil')
                ->body("Semua {$result->processed()} baris berhasil diimpor ({$result->created} ditambahkan, {$result->updated} diperbarui).")
                ->success()
                ->send();

            return;
        }

        $shown = array_slice($result->errors, 0, self::MAX_ERRORS_SHOWN);
        $lines = array_map(fn (string $error): string => e($error), $shown);

        if ($result->failed() > count($shown)) {
            $lines[] = '…dan '.($result->failed() - count($shown)).' baris lainnya.';
        }

        $intro = $result->processed() > 0
            ? "{$result->processed()} baris berhasil, {$result->failed()} baris gagal:"
            : "{$result->failed()} baris gagal diimpor:";

        Notification::make()
            ->title($result->processed() > 0 ? 'Sebagian baris gagal diimpor' : 'Import gagal')
            ->body(new HtmlString($intro.'<br>'.implode('<br>', $lines)))
            ->danger()
            ->persistent()
            ->send();
    }

    private function templateAction(): Action
    {
        return Action::make('downloadTemplate')
            ->label('Template')
            ->icon(Heroicon::ArrowDownTray)
            ->color('gray')
            ->action(function (): StreamedResponse {
                return response()->streamDownload(function (): void {
                    $temp = tempnam(sys_get_temp_dir(), 'alumni_template_').'.xlsx';

                    app(AlumniImportService::class)->writeTemplate($temp);

                    readfile($temp);

                    @unlink($temp);
                }, 'template-import-alumni.xlsx', [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
            });
    }
}
