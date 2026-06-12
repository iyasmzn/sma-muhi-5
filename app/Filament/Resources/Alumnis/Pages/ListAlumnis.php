<?php

namespace App\Filament\Resources\Alumnis\Pages;

use App\Filament\Resources\Alumnis\AlumniResource;
use App\Services\AlumniImportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListAlumnis extends ListRecords
{
    protected static string $resource = AlumniResource::class;

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

                $notification = Notification::make()
                    ->title('Import selesai')
                    ->body($result->summary());

                if ($result->hasErrors()) {
                    $notification
                        ->warning()
                        ->body($result->summary().' '.count($result->errors).' baris bermasalah: '.implode(' ', array_slice($result->errors, 0, 5)));
                } else {
                    $notification->success();
                }

                $notification->send();
            });
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
