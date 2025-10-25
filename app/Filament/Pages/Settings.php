<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

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
use Filament\Forms\Components\ViewField; // <-- alias biar gak nabrak \App\...View
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Route;
use App\Support\Settings as AppSettings;

class Settings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|UnitEnum|null $navigationGroup = 'Setup';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static ?string $navigationLabel = 'Pengaturan';

    public function getView(): string
    {
        return 'filament.pages.settings';
    }

    public array $data = [];

    public function mount(): void
    {
        $g = fn(string $k, $d = null) => method_exists(AppSettings::class, 'get') ? AppSettings::get($k, $d) : $d;

        $formBase   = $g('theme_form_base');
        $ticketBase = $g('theme_ticket_base');
        $errBase    = $g('theme_404_base');

        $this->data = [
            'event_name' => $g('event_name', ''),
            'event_date' => $g('event_date'),
            'location'   => $g('location', ''),
            'map_link'   => $g('map_link', ''),
            'dresscode'  => $g('dresscode', ''),
            'gate_time'  => $g('gate_time', ''),
            'timezone'   => $g('timezone', 'Asia/Jakarta'),
            'fonnte_token'   => $g('fonnte_token', ''),
            'callback_token' => $g('callback_token', ''),
            'retention_days' => (int) $g('retention_days', 90),
            'callback_url'   => Route::has('webhooks.fonnte') ? route('webhooks.fonnte') : url('/webhooks/fonnte'),
            'banner_path'        => $g('banner_path', ''),
            'theme_bg_cream'     => $g('theme_bg_cream', '#fff6e8'),
            'theme_surface_paper' => $g('theme_surface_paper', '#fff9f1'),
            'theme_accent_pine'  => $g('theme_accent_pine', '#165b36'),
            'theme_accent_gold'  => $g('theme_accent_gold', '#d9b86c'),
            'theme_brand_primary' => $g('theme_brand_primary', '#8d1e2c'),

            // override per-section (opsional)
            'theme_form_base'    => $formBase,
            'theme_ticket_base'  => $ticketBase,
            'theme_404_base'     => $errBase,

            // toggle untuk menyalakan override per halaman
            'use_custom_page_colors' => (bool) ($formBase || $ticketBase || $errBase),
        ];
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
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(4096)
                            ->preserveFilenames()
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
                            Select::make('timezone')
                                ->label('Timezone')
                                ->options([
                                    'Asia/Jakarta'  => 'Asia/Jakarta',
                                    'Asia/Makassar' => 'Asia/Makassar',
                                    'Asia/Jayapura' => 'Asia/Jayapura',
                                ])
                                ->default('Asia/Jakarta')
                                ->native(false)
                                ->columnSpan(6),
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
                        ])
                    ]),

                Section::make('Retensi Data')
                    ->description('Hapus data otomatis setelah N hari.')
                    ->schema([
                        TextInput::make('retention_days')
                            ->label('Retensi (hari)')
                            ->numeric()->minValue(0)->maxValue(3650)->default(90)
                            ->helperText('Default 90 hari. 0 untuk menonaktifkan pembersihan otomatis.')
                            ->columnSpan(4)
                    ]),

                Section::make('Tema & Warna')
                    ->description('Ubah warna dasar UI publik dan brand utama.')
                    ->schema([
                        Grid::make(15)->schema([
                            ColorPicker::make('theme_bg_cream')->label('Background Cream')->default('#fff6e8')->live()->columnSpan(3),
                            ColorPicker::make('theme_surface_paper')->label('Surface Paper')->default('#fff9f1')->live()->columnSpan(3),
                            ColorPicker::make('theme_accent_pine')->label('Accent Pine')->default('#165b36')->live()->columnSpan(3),
                            ColorPicker::make('theme_accent_gold')->label('Accent Gold')->default('#d9b86c')->live()->columnSpan(3),
                            ColorPicker::make('theme_brand_primary')->label('Main Brand Color')->default('#8d1e2c')->live()->columnSpan(3),
                        ]),

                        Toggle::make('use_custom_page_colors')
                            ->label('Custom warna per halaman (Ticket, Form, 404)')
                            ->helperText('Matikan untuk pakai Main Brand Color di semua halaman.')
                            ->live()
                            ->inline(false)
                            ->afterStateUpdated(function (bool $state, Set $set) {
                                if (! $state) {
                                    // bersihkan override ketika dimatikan
                                    $set('theme_form_base', null);
                                    $set('theme_ticket_base', null);
                                    $set('theme_404_base', null);
                                }
                            }),

                        Grid::make(15)->schema([
                            // override khusus per-section: muncul hanya saat toggle aktif
                            ColorPicker::make('theme_form_base')
                                ->label('Base: Form (opsional)')
                                ->nullable()
                                ->live()
                                ->visible(fn(Get $get) => (bool) $get('use_custom_page_colors'))
                                ->dehydrated(fn(Get $get) => (bool) $get('use_custom_page_colors'))
                                ->columnSpan(5),

                            ColorPicker::make('theme_ticket_base')
                                ->label('Base: Ticket (opsional)')
                                ->nullable()
                                ->live()
                                ->visible(fn(Get $get) => (bool) $get('use_custom_page_colors'))
                                ->dehydrated(fn(Get $get) => (bool) $get('use_custom_page_colors'))
                                ->columnSpan(5),

                            ColorPicker::make('theme_404_base')
                                ->label('Base: 404 (opsional)')
                                ->nullable()
                                ->live()
                                ->visible(fn(Get $get) => (bool) $get('use_custom_page_colors'))
                                ->dehydrated(fn(Get $get) => (bool) $get('use_custom_page_colors'))
                                ->columnSpan(5),
                        ]),

                        ViewField::make('theme_preview')
                            ->view('filament.partials.theme-preview')
                            ->reactive()
                            ->viewData(fn(Get $get) => [
                                'cream'    => $get('theme_bg_cream')      ?: '#fff6e8',
                                'paper'    => $get('theme_surface_paper') ?: '#fff9f1',
                                'pine'     => $get('theme_accent_pine')   ?: '#165b36',
                                'gold'     => $get('theme_accent_gold')   ?: '#d9b86c',

                                // brand global default
                                'brand'    => $get('theme_brand_primary') ?: '#8d1e2c',

                                // override per halaman (bisa null)
                                'form'     => $get('theme_form_base')   ?? null,
                                'ticket'   => $get('theme_ticket_base') ?? null,
                                'brand404' => $get('theme_404_base')    ?? null,

                                // split mode kalau custom ON
                                'split'    => (bool) $get('use_custom_page_colors'),

                                // single-target fallback saat custom OFF
                                'target'   => $get('preview_target') ?: 'form',
                            ])
                            ->columnSpanFull(),

                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->data;

        $useCustom = (bool) ($data['use_custom_page_colors'] ?? false);

        AppSettings::setMany([
            'event_name' => $data['event_name'] ?? '',
            'event_date' => $data['event_date'] ?? null,
            'location'   => $data['location'] ?? '',
            'map_link'   => $data['map_link'] ?? '',
            'dresscode'  => $data['dresscode'] ?? '',
            'gate_time'  => $data['gate_time'] ?? '',
            'timezone'   => $data['timezone'] ?? 'Asia/Jakarta',
            'fonnte_token'   => $data['fonnte_token'] ?? '',
            'callback_token' => $data['callback_token'] ?? '',
            'retention_days' => (string) (int) ($data['retention_days'] ?? 90),

            'theme_bg_cream'      => $data['theme_bg_cream'] ?? '#fff6e8',
            'theme_surface_paper' => $data['theme_surface_paper'] ?? '#fff9f1',
            'theme_accent_pine'   => $data['theme_accent_pine'] ?? '#165b36',
            'theme_accent_gold'   => $data['theme_accent_gold'] ?? '#d9b86c',
            'theme_brand_primary' => $data['theme_brand_primary'] ?? '#8d1e2c',

            // override per-section: dipaksa null jika toggle dimatikan
            'theme_form_base'     => $useCustom ? ($data['theme_form_base']   ?? null) : null,
            'theme_ticket_base'   => $useCustom ? ($data['theme_ticket_base'] ?? null) : null,
            'theme_404_base'      => $useCustom ? ($data['theme_404_base']    ?? null) : null,

            'banner_path' => $data['banner_path'] ?? '',
        ]);

        AppSettings::forget();

        Notification::make()
            ->title('Pengaturan disimpan')
            ->success()
            ->send();
    }
}
