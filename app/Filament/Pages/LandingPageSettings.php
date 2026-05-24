<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class LandingPageSettings extends Page
{
    protected string $view = 'filament.pages.general-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Halaman Depan';

    protected static ?string $title = 'Pengaturan Halaman Depan';

    protected static ?int $navigationSort = 12;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'section_hero' => (bool) Setting::get('section_hero', true),
            'section_quick_links' => (bool) Setting::get('section_quick_links', true),
            'section_spmb' => (bool) Setting::get('section_spmb', true),
            'section_stats' => (bool) Setting::get('section_stats', true),
            'section_principal' => (bool) Setting::get('section_principal', true),
            'section_spmb_steps' => (bool) Setting::get('section_spmb_steps', true),
            'section_activities' => (bool) Setting::get('section_activities', true),
            'section_gallery' => (bool) Setting::get('section_gallery', true),
            'section_blog' => (bool) Setting::get('section_blog', true),
            'section_contact' => (bool) Setting::get('section_contact', true),
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Visibilitas Seksi')
                ->description('Aktifkan atau nonaktifkan tampilan setiap seksi di halaman depan.')
                ->icon('heroicon-o-eye')
                ->schema([
                    Grid::make(2)->schema([
                        Toggle::make('section_hero')
                            ->label('🖼️  Hero Image Slider')
                            ->helperText('Slider gambar besar di bagian paling atas halaman.')
                            ->onColor('success'),

                        Toggle::make('section_quick_links')
                            ->label('🔗  Tautan Cepat')
                            ->helperText('Bar ikon pintasan (SPMB, E-Learning, Jadwal, dst.).')
                            ->onColor('success'),

                        Toggle::make('section_spmb')
                            ->label('📋  Kartu SPMB')
                            ->helperText('Banner CTA pendaftaran peserta didik baru.')
                            ->onColor('success'),

                        Toggle::make('section_stats')
                            ->label('📊  Statistik Sekolah')
                            ->helperText('4 kartu angka: berdiri, siswa, guru, prestasi.')
                            ->onColor('success'),

                        Toggle::make('section_principal')
                            ->label('👨‍💼  Sambutan Kepala Sekolah')
                            ->helperText('Foto dan pesan sambutan dari kepala sekolah.')
                            ->onColor('success'),

                        Toggle::make('section_spmb_steps')
                            ->label('📝  Tahapan SPMB')
                            ->helperText('4 langkah alur pendaftaran peserta didik baru.')
                            ->onColor('success'),

                        Toggle::make('section_activities')
                            ->label('⚽  Kegiatan & Ekskul')
                            ->helperText('Kartu-kartu kegiatan ekstrakurikuler sekolah.')
                            ->onColor('success'),

                        Toggle::make('section_gallery')
                            ->label('🖼️  Galeri Foto')
                            ->helperText('Grid masonry foto-foto sekolah.')
                            ->onColor('success'),

                        Toggle::make('section_blog')
                            ->label('📰  Blog & Berita')
                            ->helperText('Artikel dan berita terbaru dari sekolah.')
                            ->onColor('success'),

                        Toggle::make('section_contact')
                            ->label('📞  Kontak Kami')
                            ->helperText('Seksi informasi kontak sebelum footer.')
                            ->onColor('success'),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::setMany($data);

        Notification::make()
            ->success()
            ->title('Pengaturan halaman depan disimpan')
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->action('save'),
        ];
    }
}
