<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RSVP • Christmas Celebration 2025</title>

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mint: '#9bdad5', // minty ice light
                        mintMid: '#6fbab4', // mid
                        mintDeep: '#3e8e89', // deep
                        cream: '#f6efe3',
                        paper: '#fff7e6',
                        ribbon: '#caa04a',
                        pine: '#1e5c4a',
                        cherry: '#be3f3a'
                    },
                    fontFamily: {
                        display: ['"Super Joyful"', 'system-ui', 'sans-serif'],
                        sans: ['"DM Sans"', 'Inter', 'system-ui', 'sans-serif'],
                        title: ['ChettaVissto', 'Cormorant Garamond', 'serif']
                    },




                    boxShadow: {
                        soft: '0 14px 38px rgba(0,0,0,.18)',
                        insetTop: 'inset 0 20px 50px rgba(0,0,0,.12)'
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@700;800;900&family=Bebas+Neue&family=DM+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">

    <!-- Optional custom title font -->
    <link rel="preload" href="{{ asset('fonts/ChettaVissto.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/Super Joyful.ttf') }}" as="font" type="font/ttf" crossorigin>


    <style>
        @font-face {
            font-family: 'ChettaVissto';
            src: url('{{ asset('fonts/ChettaVissto.woff2') }}') format('woff2'),
                url('{{ asset('fonts/ChettaVissto.woff') }}') format('woff');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: "Super Joyful";
            src:
                /* url("{{ asset('fonts/Super Joyful.woff2') }}") format("woff2"),
                url("{{ asset('fonts/Super Joyful.woff') }}") format("woff"), */
                url("{{ asset('fonts/Super Joyful.ttf') }}") format("truetype");
            font-weight: 400;
            /* atau 400 kalau cuma regular */
            font-style: normal;
            font-display: swap;
        }


        /* Minty-ice stripes now on BODY (outside the frame) */
        body {
            --mint-top: #9bdad5;
            --mint-bottom: #6fbab4;
            background:
                repeating-linear-gradient(90deg, rgba(255, 255, 255, .32) 0 2px, rgba(255, 255, 255, 0) 2px 16px),
                radial-gradient(1400px 700px at 50% -10%, rgba(255, 255, 255, .16) 0%, transparent 60%),
                linear-gradient(180deg, var(--mint-top), var(--mint-bottom));
            overscroll-behavior-y: none;
            position: relative;
        }

        .page-grain:before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            mix-blend-mode: overlay;
            opacity: .18;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.18'/%3E%3C/svg%3E");
            background-size: 280px 280px;
        }

        /* Poster frame */
        .frame {
            border-radius: 22px;
            position: relative;
            box-shadow: 0 22px 90px rgba(0, 0, 0, .22);
            background: linear-gradient(#efe1c9, #ead7bd);
        }

        .frame:before {
            content: "";
            position: absolute;
            inset: 10px;
            border-radius: 18px;
            border: 2px solid #d9c6a7;
            background: #ecddc0;
        }

        /* Ribbon */
        .ribbon {
            background: linear-gradient(#e6c77a, #b6903f);
            color: #2a2419;
            text-transform: uppercase;
            letter-spacing: .08em;
            clip-path: polygon(6% 0, 94% 0, 100% 50%, 94% 100%, 6% 100%, 0 50%);
            text-shadow: 0 1px 0 rgba(255, 255, 255, .45);
        }

        /* Form card */
        .card {
            background: #fff7e9;
            color: #523e2a;
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: 0 12px 32px rgba(0, 0, 0, .18);
        }

        .input {
            border: 1px solid #ead9bf;
            background: #fff;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, .04);
        }

        .input:focus {
            outline: none;
            border-color: #d4ae59;
            box-shadow: 0 0 0 4px rgba(212, 174, 89, .25), inset 0 2px 4px rgba(0, 0, 0, .06);
        }

        /* CTA button: hover/focus animations + shimmer tipis */
        .cta {
            position: relative;
            background: linear-gradient(180deg, #ff6b6b, #be3f3a);
            color: #fff;
            box-shadow: 0 12px 24px rgba(191, 62, 53, .35);
            transition: transform .18s ease, filter .18s ease, box-shadow .18s ease;
        }

        .cta:hover {
            transform: translateY(-1px) scale(1.01);
            filter: saturate(1.1);
            box-shadow: 0 16px 28px rgba(191, 62, 53, .4);
        }

        .cta:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(212, 174, 89, .35), 0 18px 30px rgba(191, 62, 53, .4);
        }

        /* light sweep ramping */
        /* light sweep ramping & ter-clip di dalam tombol */
        .cta::after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: -20%;
            /* mulai sedikit di luar, tapi masih ke-clip */
            width: 18%;
            /* proporsional terhadap tombol */
            max-width: 70px;
            /* biar nggak kebesaran di tombol lebar */
            min-width: 36px;
            /* tetap kelihatan di tombol kecil */
            background: linear-gradient(115deg,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, .55) 45%,
                    rgba(255, 255, 255, 0) 75%);
            transform: skewX(-18deg) translateX(0);
            border-radius: inherit;
            /* ikuti rounded tombol */
            pointer-events: none;
            opacity: .85;
            will-change: transform;
            /* biar halus */
        }

        .cta:hover::after,
        .cta:focus::after {
            animation: sweep 900ms ease;
        }

        @keyframes sweep {
            to {
                transform: skewX(-18deg) translateX(750%);
            }
        }




        /* Inline spinner for submit state */
        .spinner {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, .55);
            border-top-color: #fff;
            animation: spin .8s linear infinite;
            display: inline-block;
            vertical-align: -3px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Banner gloss */
        .banner-gloss:before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(180deg, rgba(255, 255, 255, .18), rgba(0, 0, 0, 0) 60%), radial-gradient(80% 50% at 50% -10%, rgba(255, 255, 255, .28), rgba(255, 255, 255, 0) 70%);
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .parallax {
                transform: none !important
            }
        }

        h1.font-display {
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        /* Super Joyful biasanya lebih manis tanpa all-caps; kalau tetap caps, kecilkan letter-spacing */
    </style>
</head>

<body class="min-h-screen text-white font-sans page-grain">

    <!-- Poster frame -->
    <div class="mx-auto max-w-5xl p-4 md:p-8">
        <div class="frame p-4 md:p-6">
            <div class="relative rounded-[18px] p-5 md:p-8">

                <!-- Landscape banner -->
                <div class="mt-3 w-full aspect-[16/9] rounded-2xl overflow-hidden shadow-2xl relative banner-gloss">
                    <img src="https://template.canva.com/EAFUP94L0a4/2/0/1600w-QZhOwIvXUI4.jpg" alt="Banner Christmas"
                        class="w-full h-full object-cover transition-transform duration-[1200ms] ease-[cubic-bezier(.2,.7,.2,1)] hover:scale-[1.02] will-change-transform"
                        id="hero-img">
                </div>

                {{-- <!-- Ribbon top -->
                <div class="ribbon mx-auto w-fit px-6 py-1 text-[12px] md:text-sm font-semibold rounded-sm select-none">
                    Zion Teens 55th Anniversary</div>

                <!-- Headline tightened spacing & high-contrast color -->
                <div class="mt-3 text-center">
                    <h1 class="m-0 font-display text-[#fffaf2]"
                        style="font-size:clamp(44px,7.2vw,92px); line-height:.95; letter-spacing:.3px; text-shadow:0 2px 0 rgba(0,0,0,.18); font-weight:900;">
                        CHRISTMAS
                    </h1>

                    <div class="mt-2 inline-block rounded-md px-4 py-1.5"
                        style="background:rgba(255,255,255,.14); backdrop-filter: blur(1px);">
                        <span class="font-sans font-bold text-[#fffdf8]"
                            style="font-size:clamp(16px,2.8vw,28px); letter-spacing:.04em;">
                            CELEBRATION 2025
                        </span>
                    </div>
                </div> --}}

                <!-- Title + Deskripsi -->
<div class="mt-5 text-center">
  <!-- Judul 1 -->
  <h1 class="font-title text-mintMid leading-none"
      style="font-size:clamp(40px,7vw,88px); letter-spacing:.2px; text-shadow:0 2px 0 rgba(0,0,0,.18);">
    RSVP
  </h1>

  <!-- Judul 2 (lebih tebal) -->
  <h2 class="font-title text-mintMid leading-tight font-extrabold"
      style="font-size:clamp(28px,4.8vw,56px); letter-spacing:.15px; text-shadow:0 2px 0 rgba(0,0,0,.14);">
    Born To Bring Peace
  </h2>

  <!-- Deskripsi (sans-serif) -->
  <p class="mt-2 font-sans text-center mx-auto max-w-3xl text-[#5b4833] leading-relaxed">
    Malam perayaan penuh sukacita. Isi data berikut untuk menerima tiket unik berisi QR Code rahasia
    yang hanya dapat dipindai oleh staf pada hari-H.
  </p>
</div>





                <!-- FORM CARD -->
                <div class="mt-8 card rounded-2xl p-5 md:p-6 shadow-soft">
                    <h3 class="m-0 font-title text-[26px] text-pine">Daftarkan dirimu sekarang</h3>
                    <p class="mt-1 text-[14px] text-[#6a5440]">Isi data di bawah untuk menerima tiket unik (QR) yang
                        hanya dapat dipindai panitia.</p>

                    <form id="rsvp-form" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4"
                        action="{{ route('rsvp.store') }}" method="POST" novalidate>
                        @csrf

                        <!-- NAMA -->
                        <div>
                            <label for="nama" class="block text-[13px] font-semibold text-[#5a4631] mb-1.5">Nama
                                Lengkap</label>
                            <input id="nama" name="nama" type="text" required value="{{ old('nama') }}"
                                class="input w-full rounded-xl px-4 py-3">
                            @error('nama')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELP -->
                        <div>
                            <label for="telp" class="block text-[13px] font-semibold text-[#5a4631] mb-1.5">Nomor
                                Telepon</label>
                            <input id="telp" name="telp" type="tel" inputmode="numeric" required
                                placeholder="08xxxxxxxxxx" value="{{ old('telp') }}"
                                class="input w-full rounded-xl px-4 py-3">
                            {{-- <div id="telp-e164" class="text-xs mt-1 text-[#6a5440] select-none">E.164: —</div> --}}
                            @error('telp')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- USIA -->
                        <div>
                            <label for="usia"
                                class="block text-[13px] font-semibold text-[#5a4631] mb-1.5">Usia</label>
                            <input id="usia" name="usia" type="number" min="10" max="50" required
                                value="{{ old('usia') }}" class="input w-full rounded-xl px-4 py-3">
                            @error('usia')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- JENJANG -->
                        <div>
                            <label for="jenjang" class="block text-[13px] font-semibold text-[#5a4631] mb-1.5">Jenjang
                                Pendidikan</label>
                            <select id="jenjang" name="jenjang" required class="input w-full rounded-xl px-4 py-3">
                                <option value="" disabled {{ old('jenjang') ? '' : 'selected' }}>Pilih salah satu
                                </option>
                                @foreach (['SMP 1', 'SMP 2', 'SMP 3', 'SMA 1', 'SMA 2', 'SMA 3', 'Kuliah D3/D4/S1', 'Gap Year', 'Kerja', 'Lainnya'] as $opt)
                                    <option {{ old('jenjang') === $opt ? 'selected' : '' }}>{{ $opt }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenjang')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SEKOLAH -->
                        <div>
                            <label for="sekolah" class="block text-[13px] font-semibold text-[#5a4631] mb-1.5">Nama
                                Sekolah/Kampus</label>
                            <input id="sekolah" name="sekolah" type="text" required value="{{ old('sekolah') }}"
                                class="input w-full rounded-xl px-4 py-3">
                            @error('sekolah')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INFORMASI -->
                        <div>
                            <label for="informasi" class="block text-[13px] font-semibold text-[#5a4631] mb-1.5">Tau
                                acara ini dari mana?</label>
                            <input id="informasi" name="informasi" type="text" required
                                value="{{ old('informasi') }}" class="input w-full rounded-xl px-4 py-3">
                            @error('informasi')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- GEREJA -->
                        <div id="wrap-gereja" class="{{ old('gereja') === 'lainnya' ? '' : 'md:col-span-2' }}">
                            <label for="gereja"
                                class="block text-[13px] font-semibold text-[#5a4631] mb-1.5">Anggota gereja
                                mana</label>
                            <select id="gereja" name="gereja" required class="input w-full rounded-xl px-4 py-3">
                                <option value="" disabled {{ old('gereja') ? '' : 'selected' }}>Pilih gereja
                                </option>
                                @foreach (['GKT 3', 'GKJW', 'GBI', 'GPdI', 'GSJA', 'HKBP', 'Katolik', 'lainnya'] as $opt)
                                    <option value="{{ $opt }}"
                                        {{ old('gereja') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                            @error('gereja')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- GEREJA MANUAL -->
                        <div id="wrap-gereja-lain" class="{{ old('gereja') === 'lainnya' ? '' : 'hidden' }}">
                            <label for="gereja_manual"
                                class="block text-[13px] font-semibold text-[#5a4631] mb-1.5">Nama Gereja
                                (Lainnya)</label>
                            <input id="gereja_manual" name="gereja_manual" type="text"
                                value="{{ old('gereja_manual') }}" class="input w-full rounded-xl px-4 py-3"
                                {{ old('gereja') === 'lainnya' ? 'required' : '' }}>
                            @error('gereja_manual')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONSENT -->
                        <div class="md:col-span-2">
                            <label class="inline-flex items-start gap-3">
                                <input id="consent" type="checkbox" name="consent"
                                    {{ old('consent') ? 'checked' : '' }}
                                    class="mt-1.5 w-5 h-5 rounded border-[#e7d9c3] text-cherry focus:ring-ribbon">
                                <span class="text-sm text-[#6a5440]">Saya menyetujui penggunaan data ini untuk
                                    pembuatan tiket, pengiriman, dan check-in acara sesuai kebijakan privasi
                                    panitia.</span>
                            </label>
                            @error('consent')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SUBMIT -->
                        <div class="md:col-span-2 flex items-center justify-center md:justify-between gap-3 pt-1">
                            <button id="submitBtn" type="submit"
                                class="cta px-5 py-3 rounded-xl font-semibold overflow-hidden">
                                <span class="btn-label">Kirim & Dapatkan Tiket</span>
                                <span class="btn-spinner hidden ml-3 align-middle"><span
                                        class="spinner"></span></span>
                            </button>

                        </div>
                    </form>
                </div>

                <!-- Footer strip -->
                <div
                    class="mt-6 text-center text-[12px] tracking-wide text-[#2a2419] bg-[#f0e3c3] rounded-full inline-block px-4 py-1 select-none">
                    © 2025 Teens X Youth • QR hanya valid saat dipindai staf.</div>

            </div>
        </div>
    </div>

    <script>
        const $ = (q, ctx = document) => ctx.querySelector(q);
        const selGereja = $('#gereja');
        const wrapGereja = $('#wrap-gereja');
        const wrapLain = $('#wrap-gereja-lain');
        const manual = $('#gereja_manual');
        const telp = $('#telp');
        const telpE164 = $('#telp-e164');
        const heroImg = $('#hero-img');
        const submitBtn = $('#submitBtn');

        function normalizePhone(raw) {
            const digits = (raw || '').replace(/\D+/g, '');
            if (digits.startsWith('0')) return '62' + digits.slice(1);
            if (digits.startsWith('62')) return digits;
            return digits;
        }

        function updateTelp() {
            if (!telp) return;
            const cleaned = telp.value.replace(/\D+/g, '');
            if (telp.value !== cleaned) telp.value = cleaned;
            if (telpE164) telpE164.textContent = 'E.164: ' + (cleaned ? normalizePhone(cleaned) : '—');
        }

        function applyGerejaLayout() {
            if (!selGereja) return;
            const isLainnya = selGereja.value === 'lainnya';
            if (wrapGereja) wrapGereja.classList.toggle('md:col-span-2', !isLainnya);
            if (wrapLain) wrapLain.classList.toggle('hidden', !isLainnya);
            if (isLainnya) {
                manual?.setAttribute('required', 'required');
                manual?.focus();
            } else {
                manual?.removeAttribute('required');
                if (manual) manual.value = '';
            }
        }

        // soft parallax for banner
        let ticking = false;

        function onScroll() {
            if (ticking) return;
            window.requestAnimationFrame(() => {
                const y = window.scrollY || 0;
                const offset = Math.min(20, y / 30);
                if (heroImg) heroImg.style.transform = `translateY(${offset}px) scale(1.02)`;
                ticking = false;
            });
            ticking = true;
        }

        // loading state on submit
        function setLoadingState(loading) {
            if (!submitBtn) return;
            const label = submitBtn.querySelector('.btn-label');
            const spin = submitBtn.querySelector('.btn-spinner');
            if (loading) {
                submitBtn.setAttribute('disabled', 'disabled');
                label && (label.textContent = 'Mengirim…');
                spin && spin.classList.remove('hidden');
            } else {
                submitBtn.removeAttribute('disabled');
                label && (label.textContent = 'Kirim & Dapatkan Tiket');
                spin && spin.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            applyGerejaLayout();
            updateTelp();
            selGereja?.addEventListener('change', applyGerejaLayout);
            telp?.addEventListener('input', updateTelp);

            const form = document.getElementById('rsvp-form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    if (!form.reportValidity()) {
                        e.preventDefault();
                        return;
                    }
                    // show loading state until navigation
                    setLoadingState(true);
                });
            }

            window.addEventListener('scroll', onScroll, {
                passive: true
            });
        });
    </script>
</body>

</html>
