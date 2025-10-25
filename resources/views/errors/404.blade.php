<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }} ">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('favicon/safari-pinned-tab.svg') }}">
    <meta name="theme-color" content="{{ \App\Support\Settings::get('theme_form_base', '#8d1e2c') }}">
    <title>404 • Halaman Tidak Ditemukan</title>

    @include('partials.theme-section', ['section' => '404'])

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    /* kita gak pakai warna custom Tailwind yang lama; biar semua via CSS var */
                    fontFamily: {
                        title: ['ChettaVissto', 'Cormorant Garamond', 'serif'],
                        sans: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif']
                    },
                    boxShadow: {
                        glow: '0 0 0 1px rgba(255,246,232,.25), 0 15px 60px rgba(0,0,0,.35)',
                    },
                    backdropBlur: {
                        xs: '1px'
                    },
                    keyframes: {
                        floaty: {
                            '0%,100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-6px)'
                            }
                        },
                        sparkle: {
                            '0%': {
                                transform: 'translateX(-120%) skewX(-10deg)',
                                opacity: .0
                            },
                            '60%': {
                                opacity: .65
                            },
                            '100%': {
                                transform: 'translateX(220%) skewX(-10deg)',
                                opacity: 0
                            }
                        },
                        pulseRing: {
                            '0%': {
                                transform: 'scale(.9)',
                                opacity: .5
                            },
                            '70%': {
                                transform: 'scale(1.15)',
                                opacity: 0
                            },
                            '100%': {
                                transform: 'scale(1.15)',
                                opacity: 0
                            }
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

    <style>
        @font-face {
            font-family: 'ChettaVissto';
            src: url('{{ asset('fonts/ChettaVissto.woff2') }}') format('woff2'),
                url('{{ asset('fonts/ChettaVissto.woff') }}') format('woff');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }

        /* === Background candy: sekarang pakai CSS var brand === */
        .bg-xmas {
            background:
                radial-gradient(1200px 600px at 50% -10%,
                    var(--brand-300) 0%,
                    transparent 60%),
                linear-gradient(180deg,
                    var(--brand-600),
                    var(--brand-900));
            position: fixed;
            inset: 0;
            z-index: -2;
        }

        .bg-vignette::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(120% 100% at 50% 10%, transparent 0%, rgba(0, 0, 0, .18) 70%, rgba(0, 0, 0, .32) 100%);
            mix-blend-mode: multiply;
        }

        .bg-noise {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            opacity: .06;
            background-image:
                url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='120' height='120' viewBox='0 0 120 120'><filter id='n'><feTurbulence baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/><feColorMatrix type='saturate' values='0'/><feComponentTransfer><feFuncA type='table' tableValues='0 0 .2 .35 0'/></feComponentTransfer></filter><rect width='120' height='120' filter='url(%23n)' opacity='.55'/></svg>");
            background-size: 160px 160px;
        }

        /* Button sweep */
        .btn-sweep {
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }

        .btn-sweep::before {
            content: "";
            position: absolute;
            inset: -150% -40%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .25), transparent);
            transform: translateX(-120%) skewX(-10deg);
        }

        .btn-sweep:hover::before {
            animation: sparkle 1s ease-in-out forwards;
        }

        /* Focus ring: pakai --gold biar konsisten sama tema */
        .focus-outline {
            outline: none;
            box-shadow:
                0 0 0 3px color-mix(in srgb, var(--gold) 32%, transparent),
                0 0 0 1px color-mix(in srgb, var(--gold) 50%, transparent);
        }

        @media (prefers-reduced-motion: reduce) {
            .btn-sweep::before {
                animation: none !important;
                transform: none !important;
            }

            [class*="animate-"] {
                animation: none !important;
            }
        }
    </style>
</head>

<body
    class="min-h-screen text-[var(--cream)] font-sans antialiased selection:bg-[color-mix(in_srgb,var(--gold)_30%,transparent)] selection:text-white">
    <!-- BG layers -->
    <div class="bg-xmas bg-vignette"></div>
    <div class="bg-noise"></div>

    <main class="min-h-screen grid place-items-center p-6">
        <section class="relative w-full max-w-3xl">
            <div class="absolute -inset-4 blur-2xl opacity-40 pointer-events-none"
                style="background: radial-gradient(60% 40% at 50% 0%, color-mix(in srgb, var(--cream) 12%, transparent), transparent 70%);">
            </div>

            <div
                class="relative rounded-3xl border border-white/10 bg-white/5 shadow-glow backdrop-blur-[2px] p-8 md:p-12">
                <!-- Top badge -->
                <div class="mb-6 flex items-center gap-3">
                    <div class="relative">
                        <span
                            class="absolute inset-0 rounded-full bg-[color-mix(in_srgb,var(--gold)_25%,transparent)] blur-lg animate-pulseRing"></span>
                        <span
                            class="relative grid h-9 w-9 place-items-center rounded-full bg-[color-mix(in_srgb,var(--gold)_30%,transparent)] text-white font-semibold">404</span>
                    </div>
                    <p class="text-[color-mix(in_srgb,var(--cream)_80%,transparent)]">Halaman tidak ditemukan</p>
                </div>

                <!-- Big title -->
                <h1 class="font-title font-bold leading-none" style="font-size:clamp(2.2rem,4.8vw,4rem)">
                    Ups... kamu nyasar.
                </h1>
                <p class="mt-3 text-[color-mix(in_srgb,var(--cream)_85%,transparent)] leading-relaxed">
                    Link yang kamu buka sudah pindah, salah tulis, atau memang tidak ada.
                    Tenang, kita siap mengantar balik ke tempat yang benar.
                </p>

                <!-- CTA -->
                <div class="mt-7 flex flex-col sm:flex-row gap-3 sm:items-center">
                    <!-- Primary: grad dari brand -->
                    <a href="{{ url('/') }}"
                        class="btn-sweep inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3
                    font-semibold text-white shadow-[0_10px_30px_rgba(0,0,0,.35)]
                    bg-gradient-to-b
                    from-[color-mix(in_srgb,var(--brand)_82%,white)]
                    to-[var(--brand)]
                    focus:outline-none focus-visible:focus-outline
                    active:translate-y-[1px]">
                        <x-fas-arrow-left class="h-5 w-5"/>
                        <span>Kembali ke Beranda</span>
                    </a>

                    <!-- Secondary: teks pakai brand -->
                    <a href="https://wa.me/62xxxxxxxxxx?text=Halo%20Admin,%20aku%20ketemu%20404%20di%20situs.%20Bisa%20bantu%3F"
                        class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 font-semibold
                    text-[var(--brand)] bg-[color-mix(in_srgb,var(--cream)_95%,transparent)] hover:bg-[var(--cream)]
                    transition active:translate-y-[1px]
                    focus:outline-none focus-visible:focus-outline">
                        <x-fab-whatsapp class="h-5 w-5"/>
                        <span>Hubungi Panitia</span>
                    </a>
                </div>

                <!-- Small suggestions -->
                <div class="mt-6 text-sm text-[color-mix(in_srgb,var(--cream)_70%,transparent)]">
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
                <svg width="140" height="140" viewBox="0 0 140 140" fill="none"
                    xmlns="http://www.w3.org/2000/svg" class="drop-shadow-xl">
                    <circle cx="70" cy="70" r="68" fill="url(#g1)" opacity=".18" />
                    <path d="M70 20l16 26h-32l16-26zm0 100l16-26h-32l16 26z" fill="var(--cream)" opacity=".35" />
                    <defs>
                        <radialGradient id="g1" cx="0" cy="0" r="1"
                            gradientUnits="userSpaceOnUse" gradientTransform="translate(70 70) rotate(90) scale(68)">
                            <stop stop-color="var(--cream)" />
                            <stop offset="1" stop-color="var(--cream)" stop-opacity="0" />
                        </radialGradient>
                    </defs>
                </svg>
            </div>
        </section>
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const home = document.querySelector('a[href="{{ url('/') }}"]') || document.querySelector(
                'a[href="/"]');
            if (home) home.focus();
        });
    </script>
</body>

</html>
