@props(['section' => 'form'])

@php
use App\Support\Settings;

// brand utama (global)
$brandPrimary = Settings::get('theme_brand_primary', '#8d1e2c');

// kompatibel sama pengaturan lama per-section, kalau kosong fallback ke brand utama
$brand = match ($section) {
    'form'   => Settings::get('theme_form_base')   ?: $brandPrimary,
    'ticket' => Settings::get('theme_ticket_base') ?: $brandPrimary,
    '404'    => Settings::get('theme_404_base')    ?: $brandPrimary,
    default  => $brandPrimary,
};

// warna lain dari settings baru (v4)
$cream = Settings::get('theme_bg_cream',      '#fff6e8');
$paper = Settings::get('theme_surface_paper', '#fff9f1');
$pine  = Settings::get('theme_accent_pine',   '#165b36');
$gold  = Settings::get('theme_accent_gold',   '#d9b86c');
@endphp

<style>
  /* Fallback dasar biar tetap jalan walau browser gak ngerti color-mix */
  :root{
    --brand: {{ $brand }};

    /* kalau @supports gagal, minimal brand-700 didefinisikan */
    --brand-50:  var(--brand);
    --brand-100: var(--brand);
    --brand-200: var(--brand);
    --brand-300: var(--brand);
    --brand-400: var(--brand);
    --brand-500: var(--brand);
    --brand-600: var(--brand);
    --brand-700: var(--brand);
    --brand-800: var(--brand);
    --brand-900: var(--brand);

    /* palette lain dari settings */
    --cream: {{ $cream }};
    --paper: {{ $paper }};
    --pine:  {{ $pine }};
    --gold:  {{ $gold }};

    /* alias lama kalau masih ada view jadul yang make */
    --brandRed:      var(--brand);
    --brandRedDark:  var(--brand-800);
    --brandRedDeep:  var(--brand-900);
  }

  /* Kalau browser bisa color-mix, pakai skala yang bener */
  @supports (color: color-mix(in srgb, black, white)) {
    :root{
      /* Lighter: tambah porsi putih (hasilnya lebih terang) */
      --brand-50:  color-mix(in srgb, var(--brand) 10%,  white);
      --brand-100: color-mix(in srgb, var(--brand) 20%,  white);
      --brand-200: color-mix(in srgb, var(--brand) 35%,  white);
      --brand-300: color-mix(in srgb, var(--brand) 50%,  white);
      --brand-400: color-mix(in srgb, var(--brand) 65%,  white);
      --brand-500: color-mix(in srgb, var(--brand) 75%,  white);
      --brand-600: color-mix(in srgb, var(--brand) 85%,  white);

      /* Darker: tambah porsi hitam */
      --brand-700: color-mix(in srgb, var(--brand) 82%, black 18%);
      --brand-800: color-mix(in srgb, var(--brand) 70%, black 30%);
      --brand-900: color-mix(in srgb, var(--brand) 55%, black 45%);
    }
  }
</style>
