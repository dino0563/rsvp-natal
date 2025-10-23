@props(['section' => 'form'])

@php
use App\Support\Settings;

$brand = match ($section) {
    'form'   => Settings::get('theme_form_base',   '#8d1e2c'),
    'ticket' => Settings::get('theme_ticket_base', '#8d1e2c'),
    '404'    => Settings::get('theme_404_base',    '#0b0b0b'),
    default  => '#8d1e2c',
};
@endphp

<style>
  :root{
    /* Base dari admin */
    --brand: {{ $brand }};
    /* Turunan otomatis via color-mix (butuh browser modern, which you have in 2025) */
    --brand-700: color-mix(in srgb, var(--brand) 70%, black);
    --brand-800: color-mix(in srgb, var(--brand) 80%, black);
    --brand-900: color-mix(in srgb, var(--brand) 90%, black);
    --brand-50:  color-mix(in srgb, var(--brand) 10%,  white);
    --brand-100: color-mix(in srgb, var(--brand) 20%,  white);

    /* Palet pendukung (tetap sama seperti punyamu) */
    --cream: #fff6e8;
    --paper: #fff9f1;
    --pine:  #165b36;
    --gold:  #d9b86c;

    /* Alias sesuai kebutuhan masing-masing halaman */
    /* 404 */
    --brandRed:      var(--brand);
    --brandRedDark:  var(--brand-800);
    --brandRedDeep:  var(--brand-900);

    /* Ticket (mapping ke nama lama) */
    --red-700: color-mix(in srgb, var(--brand) 60%, white);
    --red-800: var(--brand);
    --red-900: var(--brand-900);
    --ink:     #1b1b1b;
  }
</style>
