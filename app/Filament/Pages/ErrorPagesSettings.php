<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ErrorPagesSettings extends Page
{
    protected string $view = 'filament.pages.error-pages-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Halaman Error';

    protected static ?string $title = 'Halaman Error';

    protected static ?int $navigationSort = 17;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /**
     * Error codes managed here, with their default content.
     *
     * @var array<int, array{label: string, title: string, message: string, hint: string}>
     */
    protected const ERROR_PAGES = [
        403 => [
            'label' => 'Akses Ditolak',
            'title' => 'Kamu tidak punya akses',
            'message' => 'Halaman ini bersifat terbatas. Jika kamu merasa ini sebuah kesalahan, silakan hubungi administrator.',
            'hint' => 'Tampil ketika pengunjung mencoba membuka halaman yang dilarang.',
        ],
        404 => [
            'label' => 'Halaman Tidak Ditemukan',
            'title' => 'Sepertinya kamu tersesat',
            'message' => 'Halaman yang kamu cari mungkin sudah dipindahkan, dihapus, atau alamatnya salah ketik.',
            'hint' => 'Tampil ketika halaman atau alamat tidak ditemukan.',
        ],
        419 => [
            'label' => 'Sesi Berakhir',
            'title' => 'Sesi kamu telah berakhir',
            'message' => 'Demi keamanan, sesi kamu telah kedaluwarsa. Silakan muat ulang halaman lalu coba lagi.',
            'hint' => 'Tampil ketika sesi/form kedaluwarsa (CSRF token).',
        ],
        429 => [
            'label' => 'Terlalu Banyak Permintaan',
            'title' => 'Pelan-pelan dulu',
            'message' => 'Kamu mengirim terlalu banyak permintaan dalam waktu singkat. Mohon tunggu beberapa saat lalu coba lagi.',
            'hint' => 'Tampil ketika pengunjung melampaui batas permintaan (rate limit).',
        ],
        500 => [
            'label' => 'Kesalahan Server',
            'title' => 'Ada yang tidak beres',
            'message' => 'Terjadi kesalahan di server kami. Tim kami sudah diberi tahu dan sedang menanganinya.',
            'hint' => 'Tampil ketika terjadi kesalahan tak terduga di server.',
        ],
        503 => [
            'label' => 'Sedang Pemeliharaan',
            'title' => 'Website sedang dalam pemeliharaan',
            'message' => 'Kami sedang melakukan perbaikan agar layanan menjadi lebih baik. Silakan kembali beberapa saat lagi.',
            'hint' => 'Tampil ketika website dalam mode pemeliharaan.',
        ],
    ];

    public function mount(): void
    {
        $values = [];

        foreach (self::ERROR_PAGES as $code => $defaults) {
            $values["error_{$code}_label"] = Setting::get("error_{$code}_label", $defaults['label']);
            $values["error_{$code}_title"] = Setting::get("error_{$code}_title", $defaults['title']);
            $values["error_{$code}_message"] = Setting::get("error_{$code}_message", $defaults['message']);
        }

        $this->form->fill($values);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        $sections = [];

        foreach (self::ERROR_PAGES as $code => $defaults) {
            $sections[] = Section::make("Error {$code}")
                ->description($defaults['hint'])
                ->icon('heroicon-o-exclamation-triangle')
                ->collapsible()
                ->collapsed($code !== 404)
                ->schema([
                    TextInput::make("error_{$code}_label")
                        ->label('Label')
                        ->maxLength(60)
                        ->placeholder($defaults['label'])
                        ->hint('Teks kecil di atas judul.'),

                    TextInput::make("error_{$code}_title")
                        ->label('Judul')
                        ->maxLength(120)
                        ->placeholder($defaults['title']),

                    Textarea::make("error_{$code}_message")
                        ->label('Pesan')
                        ->rows(3)
                        ->maxLength(300)
                        ->placeholder($defaults['message']),
                ]);
        }

        return $schema->components($sections);
    }

    public function save(): void
    {
        Setting::setMany($this->form->getState());

        Notification::make()
            ->success()
            ->title('Halaman error berhasil disimpan')
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
