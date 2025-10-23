@php
    $form = $get('theme_form_base') ?? '#8d1e2c';
    $ticket = $get('theme_ticket_base') ?? '#8d1e2c';
    $nf = $get('theme_404_base') ?? '#0b0b0b';
@endphp

<style>
    :root {
        --preview-form: {{ $form }};
        --preview-ticket: {{ $ticket }};
        --preview-404: {{ $nf }};
    }

    .preview-card {
        border-radius: 16px;
        padding: 16px;
        border: 1px solid #e5e7eb;
        background: white
    }

    .preview-banner {
        height: 64px;
        border-radius: 12px;
        background:
            radial-gradient(120% 80% at 50% -10%, color-mix(in srgb, var(--color) 85%, white) 0 60%, transparent 70%),
            linear-gradient(180deg, color-mix(in srgb, var(--color) 78%, black), color-mix(in srgb, var(--color) 90%, black));
        box-shadow: inset 0 10px 30px rgba(0, 0, 0, .25);
    }

    .preview-chip {
        display: inline-block;
        font: 12px/1.8 system-ui;
        padding: 0 10px;
        border-radius: 999px;
        color: #fff;
        background: color-mix(in srgb, var(--color) 85%, black)
    }

    .grid-3 {
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(1, minmax(0, 1fr))
    }

    @media (min-width: 1024px) {
        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr))
        }
    }
</style>

<div class="grid-3">
    <div class="preview-card">
        <div class="preview-banner" style="--color: var(--preview-form)"></div>
        <h4 style="margin:12px 0 6px;font:600 14px/1.2 system-ui">Form RSVP</h4>
        <div class="preview-chip" style="--color: var(--preview-form)">CTA • Kirim</div>
    </div>

    <div class="preview-card">
        <div class="preview-banner" style="--color: var(--preview-ticket)"></div>
        <h4 style="margin:12px 0 6px;font:600 14px/1.2 system-ui">Halaman Tiket</h4>
        <div class="preview-chip" style="--color: var(--preview-ticket)">Badge • Belum digunakan</div>
    </div>

    <div class="preview-card">
        <div class="preview-banner" style="--color: var(--preview-404)"></div>
        <h4 style="margin:12px 0 6px;font:600 14px/1.2 system-ui">404 Not Found</h4>
        <div class="preview-chip" style="--color: var(--preview-404)">Back to home</div>
    </div>
</div>
