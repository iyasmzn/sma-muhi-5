<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ThemeSettings extends Page
{
    protected string $view = 'filament.pages.general-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Tema Warna';

    protected static ?string $title = 'Pengaturan Tema Warna';

    protected static ?int $navigationSort = 16;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $savedColor = Setting::get('theme_primary_color', '#d97706');

        $this->form->fill([
            'theme_preset' => $this->matchPreset($savedColor),
            'theme_primary_color' => $savedColor,
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Warna Utama Website')
                ->description('Warna ini diterapkan ke seluruh elemen utama website: tombol, label, highlight, dan aksen navigasi.')
                ->icon('heroicon-o-swatch')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('theme_preset')
                            ->label('Preset Warna')
                            ->options(self::presets())
                            ->searchable(false)
                            ->placeholder('— Pilih preset —')
                            ->live()
                            ->afterStateUpdated(
                                fn (Set $set, ?string $state) => $state
                                    ? $set('theme_primary_color', $state)
                                    : null
                            )
                            ->hint('Pilih preset atau ubah warna secara kustom di sebelah kanan.'),

                        ColorPicker::make('theme_primary_color')
                            ->label('Warna Kustom (HEX)')
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn (Set $set, ?string $state) => $set(
                                    'theme_preset',
                                    $this->matchPreset($state ?? '#d97706')
                                )
                            )
                            ->hint('Klik kotak warna untuk membuka color picker.'),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('theme_primary_color', $data['theme_primary_color'] ?? '#d97706');

        Notification::make()
            ->success()
            ->title('Tema warna berhasil disimpan')
            ->body('Perubahan akan langsung terlihat di website.')
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Tema')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->action('save'),

            Action::make('reset')
                ->label('Reset ke Default')
                ->color('gray')
                ->icon(Heroicon::OutlinedArrowPath)
                ->requiresConfirmation()
                ->modalHeading('Reset Tema Warna?')
                ->modalDescription('Warna akan dikembalikan ke tema Amber (default).')
                ->action(function (): void {
                    Setting::set('theme_primary_color', '#d97706');

                    $this->form->fill([
                        'theme_preset' => '#d97706',
                        'theme_primary_color' => '#d97706',
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Tema warna direset ke Amber (default)')
                        ->send();
                }),
        ];
    }

    /**
     * Returns preset color options.
     *
     * @return array<string, string>
     */
    public static function presets(): array
    {
        return [
            '#d97706' => '🟠 Amber (Default)',
            '#f59e0b' => '🟡 Kuning',
            '#2563eb' => '🔵 Biru',
            '#4f46e5' => '🟣 Indigo',
            '#9333ea' => '🟣 Ungu',
            '#e11d48' => '🌸 Rose',
            '#dc2626' => '🔴 Merah',
            '#16a34a' => '🟢 Hijau',
            '#0d9488' => '🩵 Teal',
            '#0891b2' => '🔵 Cyan',
            '#ea580c' => '🟠 Oranye',
        ];
    }

    /**
     * Returns the preset key if the given color matches one, otherwise null.
     */
    private function matchPreset(string $color): ?string
    {
        $normalized = strtolower(trim($color));

        return array_key_exists($normalized, self::presets()) ? $normalized : null;
    }
}
