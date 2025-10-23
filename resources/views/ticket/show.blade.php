<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="robots" content="noindex,nofollow">
  <title>Tiket RSVP • {{ $settings->event_name ?? 'Event' }}</title>

  <!-- Palet & font -->
  <link rel="preload" href="{{ asset('fonts/ChettaVissto.woff2') }}" as="font" type="font/woff2" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
   @include('partials.theme-section', ['section' => 'ticket'])
  <style>
    @font-face {
      font-family: 'ChettaVissto';
      src: url('{{ asset('fonts/ChettaVissto.woff2') }}') format('woff2'),
           url('{{ asset('fonts/ChettaVissto.woff') }}') format('woff');
      font-weight: 700;
      font-style: normal;
      font-display: swap;
    }

    :root{
      --red-900:#6e1423; --red-800:#8d1e2c; --red-700:#a32232;
      --paper:#fff9f1; --ink:#1b1b1b; --gold:#d9b86c;
    }
    *,*::before,*::after{ box-sizing:border-box; }
    html,body{ min-height:100svh; }
    body{
      margin:0; font-family: Inter, system-ui, Segoe UI, Roboto, Arial, sans-serif; color:var(--ink);
      background:
        radial-gradient(130% 70% at 50% -10%, #ae2e3a 0 40%, transparent 70%) no-repeat,
        linear-gradient(180deg, var(--red-800), var(--red-900));
      background-color: var(--red-900);
      display:grid; place-items:center;
      padding: clamp(16px, 4vw, 28px);
      padding-left:  max(clamp(16px,4vw,28px), env(safe-area-inset-left));
      padding-right: max(clamp(16px,4vw,28px), env(safe-area-inset-right));
      padding-top:   max(clamp(16px,4vw,28px), env(safe-area-inset-top));
      padding-bottom:max(clamp(16px,4vw,28px), env(safe-area-inset-bottom));
    }
    img{ max-width:100%; height:auto; display:block; }

    .card{
      width:100%; max-width:900px; margin-inline:auto; position:relative;
      border-radius:26px; border:1px solid transparent; overflow:visible;
      background:
        linear-gradient(var(--paper), var(--paper)) padding-box,
        linear-gradient(180deg, #fffef8, #ecd9bf) border-box;
      box-shadow: 0 30px 60px rgba(0,0,0,.30),
                  inset 0 1px 0 rgba(255,255,255,.65),
                  inset 0 -1px 0 rgba(0,0,0,.06);
    }
    .card::before{
      content:""; position:absolute; inset:0; border-radius:inherit; pointer-events:none;
      box-shadow: 0 0 0 1px rgba(255,255,255,.35) inset;
    }
    .card::after{
      content:""; position:absolute; inset:0; border-radius:inherit; pointer-events:none;
      background:
        radial-gradient(120px 60px at 22px 22px, rgba(255,255,255,.35), transparent 65%),
        radial-gradient(120px 60px at calc(100% - 22px) calc(100% - 22px), rgba(0,0,0,.06), transparent 65%);
      mix-blend-mode: normal;
    }

    .card-header{
      padding: calc(28px + env(safe-area-inset-top)) 36px 28px 36px;
      color:#fff; background: linear-gradient(180deg, #b51f2d, #6e1423);
      border-top-left-radius:26px; border-top-right-radius:26px;
    }
    .title{ margin:0; font-family:'ChettaVissto','Cormorant Garamond',serif; font-size:clamp(36px,5vw,64px); line-height:1.1; letter-spacing:.4px; }
    .event-title{ margin:0; font-family:'ChettaVissto','Cormorant Garamond',serif; font-size:clamp(30px,4vw,40px); letter-spacing:.4px; }
    .subtitle{ margin:6px 0 0; opacity:.92 }

    .card-body{ padding:30px clamp(18px,5vw,36px) 34px; }
    .ticket{
      border:1px dashed #e6d8c3; border-radius:18px; background:#fff; padding:26px;
      display:grid; place-items:center; text-align:center;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.8), inset 0 -1px 0 rgba(0,0,0,.05);
    }
    .qr{ width:100%; max-width:320px; margin:10px auto 6px; }
    .meta{ margin:18px 0 0; text-align:left; font-size:16px; line-height:1.5; }
    .meta p{ margin:6px 0; }
    .note{ font-size:12px; color:#6a5b45; margin-top:10px; }

    .footer{
      background:#fff2de; color:#7a5b3b; text-align:center; font-size:12px; padding:14px 20px;
      border-bottom-left-radius:26px; border-bottom-right-radius:26px;
    }
    .actions{ display:flex; justify-content:center; gap:10px; margin-top:18px; }
    .btn{
      appearance:none; border:0; border-radius:12px; padding:12px 16px; cursor:pointer;
      color:#fff; font-weight:700; text-decoration:none; display:inline-block;
      background: linear-gradient(180deg, #e04848, #b51f2d);
      box-shadow: 0 10px 20px rgba(181,31,45,.35);
    }
    .badge{ display:inline-block; font-size:12px; padding:4px 8px; border-radius:999px; background:#0f766e; color:#eafff9; }

    @media print{
      body{ background:none; padding:0; }
      .card{ box-shadow:none; border:none; }
      .actions{ display:none; }
    }
  </style>
</head>
<body>

  <article class="card" role="main" aria-label="Tiket RSVP {{ $settings->event_name ?? 'Event' }}">
    <header class="card-header">
      <h1 class="title">Tiket RSVP</h1>
      <h2 class="event-title">{{ $settings->event_name ?? 'Event' }}</h2>
      <p class="subtitle">QR Code hanya bisa dipindai oleh staf. Jangan dibagikan ke siapapun.</p>
    </header>

    <section class="card-body">
      <div class="ticket" aria-label="QR Ticket">
        <img class="qr" src="{{ $qrUrl }}" alt="QR Ticket {{ $ticket->code }}" loading="lazy" decoding="async">
        @if($ticket->used_at)
          <div class="badge" style="margin-top:8px">Sudah digunakan {{ $ticket->used_at->timezone($settings->timezone ?? config('app.timezone'))->format('d M Y H:i') }}</div>
        @else
          <p class="note">Tunjukkan QR ini pada gate. Simpan baik-baik.</p>
        @endif
      </div>

      <div class="meta">
        <p><strong>Nama:</strong> {{ $reg->name }}</p>
        @if(!empty($reg->email))
          <p><strong>Email:</strong> {{ \Illuminate\Support\Str::mask($reg->email, '*', 3, 6) }}</p>
        @endif
        <p><strong>Telepon:</strong> {{ $reg->phone }}</p>

        <p><strong>Kode Tiket:</strong> {{ $ticket->code }}</p>

        <p><strong>Acara:</strong> {{ $settings->event_name ?? '—' }}</p>
        <p><strong>Lokasi/Tanggal:</strong>
          {{ $settings->location ?? '—' }}
          @if(!empty($settings->event_date)) / {{ $settings->event_date }} @endif
        </p>
        @if(!empty($settings->gate_time))
          <p><strong>Gate:</strong> {{ $settings->gate_time }}</p>
        @endif
        @if(!empty($settings->dresscode))
          <p><strong>Dresscode:</strong> {{ $settings->dresscode }}</p>
        @endif
      </div>

      <div class="actions">
        <a class="btn" href="{{ route('ticket.qr', $ticket->code) }}" download="ticket-{{ $ticket->code }}.png">Unduh QR</a>
        @if(!empty($settings->map_link))
          <a class="btn" target="_blank" rel="noopener" href="{{ $settings->map_link }}">Lihat Lokasi</a>
        @endif
      </div>
    </section>

    <footer class="footer">
      © {{ now()->year }} {{ $settings->event_name ?? 'Event' }} • Valid jika dipindai oleh staf resmi.
    </footer>
  </article>

</body>
</html>
