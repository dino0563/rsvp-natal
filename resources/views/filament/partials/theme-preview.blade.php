@php
  // Palet global
  $cream  = $cream  ?? '#fff6e8';
  $paper  = $paper  ?? '#fff9f1';
  $pine   = $pine   ?? '#165b36';
  $gold   = $gold   ?? '#d9b86c';

  // Brand global dan override
  $brand     = $brand     ?? '#8d1e2c';
  $form      = $form      ?? null;
  $ticket    = $ticket    ?? null;
  $brand404  = $brand404  ?? null;

  // Mode
  $split  = (bool) ($split ?? false);   // true kalau toggle custom per halaman ON
  $target = $target ?? 'form';          // 'form' | 'ticket' | '404' (dipakai kalau split = false)

  // Brand efektif per halaman
  $brandForm   = $form     ?: $brand;
  $brandTicket = $ticket   ?: $brand;
  $brandError  = $brand404 ?: $brand;

  // Brand tunggal untuk non-split
  $brandSingle = match ($target) {
    'ticket' => $brandTicket,
    '404'    => $brandError,
    default  => $brandForm,
  };

  // Background untuk swatch brand:
  // - split: satu kotak dibelah menjadi 3 kolom
  // - non-split: satu warna padat
  $brandBg = $split
    ? "linear-gradient(90deg, {$brandForm} 0%, {$brandForm} 33.333%, {$brandTicket} 33.333%, {$brandTicket} 66.666%, {$brandError} 66.666%, {$brandError} 100%)"
    : $brandSingle;
@endphp

<div class="theme-preview p-5 rounded-2xl">
  {{-- Swatch global (selalu sama di semua halaman) --}}
  <div class="grid gap-4 lg:grid-cols-4">
    <div class="theme-swatch" style="background: {{ $cream }}; color:#111">
      <div class="font-medium">Background Cream</div>
      <div class="opacity-80 text-xs">{{ $cream }}</div>
    </div>
    <div class="theme-swatch" style="background: {{ $paper }}; color:#111">
      <div class="font-medium">Surface Paper</div>
      <div class="opacity-80 text-xs">{{ $paper }}</div>
    </div>
    <div class="theme-swatch" style="background: {{ $pine }}; color:#fff">
      <div class="font-medium">Accent Pine</div>
      <div class="opacity-80 text-xs">{{ $pine }}</div>
    </div>
    <div class="theme-swatch" style="background: {{ $gold }}; color:#111">
      <div class="font-medium">Accent Gold</div>
      <div class="opacity-80 text-xs">{{ $gold }}</div>
    </div>
  </div>

  {{-- Satu swatch brand: dibelah tiga saat split = true --}}
  <div class="grid gap-4 lg:grid-cols-5 items-stretch">
    <div class="theme-swatch relative overflow-hidden lg:col-span-3"
         style="background: {{ $brandBg }}; color:#fff;">
      <div class="font-medium">
        Brand
        @if ($split)
          <span class="opacity-70 text-xs">(Form | Ticket | 404)</span>
        @else
          <span class="opacity-70 text-xs">({{ strtoupper($target) }} preview)</span>
        @endif
      </div>
      <div class="opacity-80 text-xs leading-relaxed">
        @if ($split)
          <span>Form: {{ $brandForm }}</span> &middot;
          <span>Ticket: {{ $brandTicket }}</span> &middot;
          <span>404: {{ $brandError }}</span>
        @else
          {{ $brandSingle }}
        @endif
      </div>

      @if ($split)
        {{-- pemisah visual tipis di 1/3 dan 2/3 --}}
        <div style="position:absolute; top:0; bottom:0; left:33.333%; width:1px; background:rgba(255,255,255,.25)"></div>
        <div style="position:absolute; top:0; bottom:0; left:66.666%; width:1px; background:rgba(255,255,255,.25)"></div>
      @endif
    </div>

    {{-- Contoh tombol & badge (sekadar context visual). Tidak perlu dibelah, ini cuma contoh. --}}
  </div>
</div>

<style>
  .theme-preview{
    border-radius: 24px;
    box-shadow:
      0 1px 2px rgba(0,0,0,0.07),
      0 8px 24px rgba(0,0,0,0.08);
  }
  .theme-swatch{
    padding: 16px;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.06);
  }
</style>
