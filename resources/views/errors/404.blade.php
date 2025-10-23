<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>404 • Halaman Tidak Ditemukan</title>

  @include('partials.theme-section', ['section' => '404'])
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brandRed: '#8d1e2c',
            brandRedDark: '#6e1423',
            brandRedDeep: '#5b0f1a',
            cream: '#fff6e8',
            pine: '#165b36',
            gold: '#d9b86c',
          },
          fontFamily: {
            title: ['ChettaVissto', 'Cormorant Garamond', 'serif'],
            sans: ['Inter','system-ui','-apple-system','Segoe UI','Roboto','Arial','sans-serif']
          },
          boxShadow: {
            glow: '0 0 0 1px rgba(255,246,232,.25), 0 15px 60px rgba(0,0,0,.35)',
          },
          backdropBlur: {
            xs: '1px'
          },
          keyframes: {
            floaty: {
              '0%,100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-6px)' }
            },
            sparkle: {
              '0%':   { transform: 'translateX(-120%) skewX(-10deg)', opacity: .0 },
              '60%':  { opacity: .65 },
              '100%': { transform: 'translateX(220%) skewX(-10deg)', opacity: 0 }
            },
            pulseRing: {
              '0%': { transform: 'scale(.9)', opacity: .5 },
              '70%': { transform: 'scale(1.15)', opacity: 0 },
              '100%': { transform: 'scale(1.15)', opacity: 0 }
            }
          },
          animation: {
            floaty: 'floaty 5s ease-in-out infinite',
            sparkle: 'sparkle 1.4s ease-in-out',
            pulseRing: 'pulseRing 2.6s ease-out infinite'
          }
        }
      }
    }
  </script>

  <!-- Optional brand title font (Blade): -->
  <!-- <link rel="preload" href="{{ asset('fonts/ChettaVissto.woff2') }}" as="font" type="font/woff2" crossorigin> -->
  <style>
    @font-face {
      font-family: 'ChettaVissto';
      src: url('{{ asset('fonts/ChettaVissto.woff2') }}') format('woff2'),
           url('{{ asset('fonts/ChettaVissto.woff') }}') format('woff');
      font-weight: 700; font-style: normal; font-display: swap;
    }

    /* Background candy: radial + vignette + subtle noise */
    .bg-xmas {
      background:
        radial-gradient(1200px 600px at 50% -10%, #ae2e3a 0%, transparent 60%),
        linear-gradient(180deg, #8d1e2c, #6e1423);
      position: fixed; inset: 0; z-index: -2;
    }
    .bg-vignette::after{
      content:""; position: absolute; inset:0; pointer-events:none;
      background: radial-gradient(120% 100% at 50% 10%, transparent 0%, rgba(0,0,0,.18) 70%, rgba(0,0,0,.32) 100%);
      mix-blend-mode: multiply;
    }
    .bg-noise{
      position: fixed; inset: 0; z-index: -1; pointer-events:none; opacity:.06;
      background-image:
        url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='120' height='120' viewBox='0 0 120 120'><filter id='n'><feTurbulence baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/><feColorMatrix type='saturate' values='0'/><feComponentTransfer><feFuncA type='table' tableValues='0 0 .2 .35 0'/></feComponentTransfer></filter><rect width='120' height='120' filter='url(%23n)' opacity='.55'/></svg>");
      background-size: 160px 160px;
    }

    /* Button sweep */
    .btn-sweep {
      position: relative; overflow: hidden; isolation: isolate;
    }
    .btn-sweep::before{
      content:""; position: absolute; inset: -150% -40%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,.25), transparent);
      transform: translateX(-120%) skewX(-10deg);
    }
    .btn-sweep:hover::before {
      animation: sparkle 1s ease-in-out forwards;
    }

    /* Focus ring nicer */
    .focus-outline {
      outline: none;
      box-shadow: 0 0 0 3px rgba(217,184,108,.32), 0 0 0 1px rgba(217,184,108,.5);
    }

    /* Reduce motion respect */
    @media (prefers-reduced-motion: reduce){
      .btn-sweep::before { animation: none !important; transform: none !important; }
      [class*="animate-"] { animation: none !important; }
    }
  </style>
</head>

<body class="min-h-screen text-cream font-sans antialiased selection:bg-gold/30 selection:text-white">
  <!-- BG layers -->
  <div class="bg-xmas bg-vignette"></div>
  <div class="bg-noise"></div>

  <main class="min-h-screen grid place-items-center p-6">
    <section class="relative w-full max-w-3xl">
      <!-- halo rings -->
      <div class="absolute -inset-4 blur-2xl opacity-40 pointer-events-none"
           style="background: radial-gradient(60% 40% at 50% 0%, rgba(255,246,232,.12), transparent 70%);"></div>

      <div class="relative rounded-3xl border border-white/10 bg-white/5 shadow-glow backdrop-blur-[2px] p-8 md:p-12">
        <!-- Top badge -->
        <div class="mb-6 flex items-center gap-3">
          <div class="relative">
            <span class="absolute inset-0 rounded-full bg-gold/25 blur-lg animate-pulseRing"></span>
            <span class="relative grid h-9 w-9 place-items-center rounded-full bg-gold/30 text-white font-semibold">404</span>
          </div>
          <p class="text-cream/80">Halaman tidak ditemukan</p>
        </div>

        <!-- Big title -->
        <h1 class="font-title font-bold leading-none"
            style="font-size:clamp(2.2rem,4.8vw,4rem)">
          Ups... kamu nyasar.
        </h1>
        <p class="mt-3 text-cream/85 leading-relaxed">
          Link yang kamu buka sudah pindah, salah tulis, atau memang tidak ada.
          Tenang, kita siap mengantar balik ke tempat yang benar.
        </p>

        <!-- CTA -->
        <div class="mt-7 flex flex-col sm:flex-row gap-3 sm:items-center">
          <a href="{{ url('/') }}"
             class="btn-sweep inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3
                    font-semibold text-white shadow-[0_10px_30px_rgba(0,0,0,.35)]
                    bg-gradient-to-b from-[#e04848] to-brandRed focus:outline-none focus-visible:focus-outline
                    active:translate-y-[1px]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="currentColor"><path d="M10.828 11H20a1 1 0 1 1 0 2h-9.172l3.536 3.536a1 1 0 0 1-1.414 1.414l-5.243-5.243a1 1 0 0 1 0-1.414l5.243-5.243a1 1 0 1 1 1.414 1.414L10.828 11Z"/></svg>
            <span>Kembali ke Beranda</span>
          </a>

          <a href="https://wa.me/62xxxxxxxxxx?text=Halo%20Admin,%20aku%20ketemu%20404%20di%20situs.%20Bisa%20bantu%3F"
             class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 font-semibold
                    text-brandRed bg-cream/95 hover:bg-cream transition active:translate-y-[1px]
                    focus:outline-none focus-visible:focus-outline">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-90" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.94 11.94 0 0 0 12.06 0 12 12 0 0 0 .06 12.27L0 24l11.9-2.98A12 12 0 0 0 24 11.93c0-3.19-1.24-6.18-3.48-8.45ZM12.05 21.2l-7.2 1.8.03-7.24a9.2 9.2 0 1 1 7.17 5.44Z"/><path d="M17.18 13.8c-.3-.15-1.75-.86-2.02-.95s-.47-.15-.67.15-.77.95-.95 1.15-.35.22-.65.07c-.3-.15-1.27-.47-2.4-1.5-.9-.8-1.52-1.78-1.7-2.08s0-.46.14-.61.3-.35.45-.53c.15-.18.22-.3.33-.5.11-.2.06-.38 0-.53-.07-.15-.67-1.62-.92-2.2-.24-.58-.49-.5-.67-.5h-.57c-.2 0-.52.07-.8.38s-1.05 1.02-1.05 2.49 1.08 2.88 1.23 3.08c.15.2 2.13 3.25 5.16 4.56.72.31 1.28.5 1.72.64.72.23 1.37.2 1.88.12.58-.09 1.75-.72 2-1.42.25-.7.25-1.3.18-1.42-.07-.12-.27-.2-.57-.35Z"/></svg>
            <span>Hubungi Panitia</span>
          </a>
        </div>

        <!-- Small suggestions -->
        <div class="mt-6 text-sm text-cream/70">
          <p class="mb-1">Coba ini:</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Periksa kembali ejaan URL kamu</li>
            <li>Kembali ke beranda lalu navigasi dari menu</li>
            <li>Kalau tetap buntu, chat panitia lewat WhatsApp</li>
          </ul>
        </div>
      </div>

      <!-- Floating decor -->
      <div class="pointer-events-none absolute -left-8 -bottom-8 opacity-80 animate-floaty">
        <svg width="140" height="140" viewBox="0 0 140 140" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-xl">
          <circle cx="70" cy="70" r="68" fill="url(#g1)" opacity=".18"/>
          <path d="M70 20l16 26h-32l16-26zm0 100l16-26h-32l16 26z" fill="#fff6e8" opacity=".35"/>
          <defs>
            <radialGradient id="g1" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(70 70) rotate(90) scale(68)">
              <stop stop-color="#fff6e8"/><stop offset="1" stop-color="#fff6e8" stop-opacity="0"/>
            </radialGradient>
          </defs>
        </svg>
      </div>
    </section>
  </main>

  <!-- Optional: auto-focus the home link for keyboard users -->
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      const home = document.querySelector('a[href="{{ url('/') }}"]') || document.querySelector('a[href="/"]');
      if (home) home.focus();
    });
  </script>
</body>
</html>
