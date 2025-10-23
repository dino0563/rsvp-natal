<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
// use Filament\Forms;
// use Filament\Forms\Form;

// Schemas API
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;

// Layout components (Schemas)
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

// Field components (Forms)
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Get; // <-- wajib kalau pakai fn (Get $get)
use Filament\Forms\Components\View as ViewField; // <-- alias biar gak nabrak \App\...View
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;


use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Route;
use App\Support\Settings as AppSettings;

// use Filament\Forms\Contracts\HasForms;
// use Filament\Forms\Concerns\InteractsWithForms;

class Settings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|UnitEnum|null $navigationGroup = 'Setup';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static ?string $navigationLabel = 'Pengaturan';

    // Explicitly tell Filament which view to render
    public function getView(): string
    {
        return 'filament.pages.settings';
    }

    public array $data = [];

    public function mount(): void
    {
        $g = fn(string $k, $d = null) => method_exists(AppSettings::class, 'get')
            ? AppSettings::get($k, $d)
            : $d;

        $this->form->fill([
            'event_name'     => $g('event_name', ''),
            'event_date'     => $g('event_date'),
            'location'       => $g('location', ''),
            'map_link'       => $g('map_link', ''),
            'dresscode'      => $g('dresscode', ''),
            'gate_time'      => $g('gate_time', ''),
            'timezone'       => $g('timezone', 'Asia/Jakarta'),
            'fonnte_token'   => $g('fonnte_token', ''),
            'callback_token' => $g('callback_token', ''),
            'retention_days' => (int) $g('retention_days', 90),
            'callback_url'   => Route::has('webhooks.fonnte')
                ? route('webhooks.fonnte')
                : url('/webhooks/fonnte'),
            'banner_path' => AppSettings::get('banner_path', ''),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Branding & Media')
                    ->description('Upload banner hero untuk halaman Form/Tiket.')
                    ->schema([
                        FileUpload::make('banner_path')
                            ->label('Banner hero (16:9 disarankan)')
                            ->image()
                            ->directory('banners')
                            ->disk('public')            // simpan ke storage/app/public
                            ->visibility('public')      // biar bisa diakses via /storage
                            ->maxSize(4096)             // 4 MB
                            ->preserveFilenames()       // opsional: pakai nama file asli
                            ->helperText('Saran 1600×900 JPG/PNG, max 4 MB.')
                            ->columnSpan(6),
                    ]),
                Section::make('Detail Event')
                    ->description('Nama event, tanggal, lokasi, gate time, dresscode.')
                    ->schema([
                        Grid::make(12)->schema([
                            TextInput::make('event_name')->label('Nama event')->required()->maxLength(255)->columnSpan(6),
                            DatePicker::make('event_date')->label('Tanggal event')->native(false)->columnSpan(3),
                            TimePicker::make('gate_time')->label('Gate time')->seconds(false)->columnSpan(3),
                            TextInput::make('location')->label('Lokasi')->maxLength(255)->columnSpan(6),
                            TextInput::make('map_link')->label('Link peta (opsional)')->url()->maxLength(255)->columnSpan(6),
                            TextInput::make('dresscode')->label('Dresscode')->maxLength(255)->columnSpan(6),
                            Select::make('timezone')->label('Timezone')
                                ->options([
                                    'Asia/Jakarta'  => 'Asia/Jakarta',
                                    'Asia/Makassar' => 'Asia/Makassar',
                                    'Asia/Jayapura' => 'Asia/Jayapura',
                                ])
                                ->default('Asia/Jakarta')->native(false)->columnSpan(6),
                        ]),
                    ]),

                Section::make('Integrasi WhatsApp (Fonnte)')
                    ->description('Token API dan callback token untuk webhook status.')
                    ->schema([
                        Grid::make(12)->schema([
                            TextInput::make('fonnte_token')->label('Fonnte Token')->password()->revealable()->required()->columnSpan(6),
                            TextInput::make('callback_token')->label('Callback Token')->password()->revealable()->required()->columnSpan(6),
                            TextInput::make('callback_url')->label('Callback URL')->disabled()->dehydrated(false)->columnSpan(12)
                                ->helperText('Set di dashboard Fonnte sebagai webhook. Header: X-Callback-Token = callback token di atas.'),
                        ]),
                    ]),

                Section::make('Retensi Data')
                    ->description('Hapus data otomatis setelah N hari.')
                    ->schema([
                        TextInput::make('retention_days')->label('Retensi (hari)')
                            ->numeric()->minValue(0)->maxValue(3650)->default(90)
                            ->helperText('Default 90 hari. 0 untuk menonaktifkan pembersihan otomatis.')->columnSpan(4),
                    ]),

                Section::make('Tema & Warna')
                    ->description('Ubah warna dasar halaman Form RSVP, Tiket, dan 404.')
                    ->schema([
                        Grid::make(12)->schema([
                            ColorPicker::make('theme_form_base')
                                ->label('Form base color')
                                ->default('#8d1e2c')
                                ->columnSpan(4),

                            ColorPicker::make('theme_ticket_base')
                                ->label('Ticket base color')
                                ->default('#8d1e2c')
                                ->columnSpan(4),

                            ColorPicker::make('theme_404_base')
                                ->label('404 base color')
                                ->default('#0b0b0b')
                                ->columnSpan(4),
                        ]),

                        // // Pakai alias ViewField, bukan View mentah
                        // ViewField::make('theme_preview')
                        //     ->view('filament.partials.theme-preview')
                        //     ->viewData([
                        //         'formColor'   => fn (Get $get) => $get('theme_form_base'),
                        //         'ticketColor' => fn (Get $get) => $get('theme_ticket_base'),
                        //         'nfColor'     => fn (Get $get) => $get('theme_404_base'),
                        //     ])
                        //     ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data'); // <- ini bagian dari chaining di return, jangan dipisah
    }

    public function save(): void
    {
        $data = $this->form->getState();

        AppSettings::setMany([
            'event_name'     => $data['event_name'] ?? '',
            'event_date'     => $data['event_date'] ?? null,
            'location'       => $data['location'] ?? '',
            'map_link'       => $data['map_link'] ?? '',
            'dresscode'      => $data['dresscode'] ?? '',
            'gate_time'      => $data['gate_time'] ?? '',
            'timezone'       => $data['timezone'] ?? 'Asia/Jakarta',
            'fonnte_token'   => $data['fonnte_token'] ?? '',
            'callback_token' => $data['callback_token'] ?? '',
            'retention_days' => (string) (int) ($data['retention_days'] ?? 90),
            'theme_form_base'   => $data['theme_form_base']   ?? '#8d1e2c',
            'theme_ticket_base' => $data['theme_ticket_base'] ?? '#8d1e2c',
            'theme_404_base'    => $data['theme_404_base']    ?? '#0b0b0b',
            'banner_path' => $data['banner_path'] ?? '',
        ]);

        AppSettings::forget();

        Notification::make()->title('Pengaturan disimpan')->success()->send();
    }
}
