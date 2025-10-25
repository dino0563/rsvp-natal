<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon & PWA -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }} ">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('favicon/safari-pinned-tab.svg') }}">
    <meta name="theme-color" content="{{ \App\Support\Settings::get('theme_brand_primary', '#8d1e2c') }}">
    <title>RSVP Natal Teens X Youth 2025</title>

    <!-- Tailwind Play CDN -->
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
                        paper: '#fff9f1',
                        pine: '#165b36',
                        gold: '#d9b86c',
                    },
                    boxShadow: {
                        card: '0 12px 30px rgba(0,0,0,.25)',
                    },
                    fontFamily: {
                        title: ['ChettaVissto', 'Cormorant Garamond', 'serif'],
                        sans: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Inter:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="preload" href="{{ asset('fonts/ChettaVissto.woff2') }}" as="font" type="font/woff2" crossorigin>

    @include('partials.theme-section', ['section' => 'form'])
    <!-- CSS eksternal (biarkan untuk tahap berikutnya) -->
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <!-- Face font -->
    <style>
        @font-face {
            font-family: 'ChettaVissto';
            src: url('{{ asset('fonts/ChettaVissto.woff2') }}') format('woff2'),
                url('{{ asset('fonts/ChettaVissto.woff') }}') format('woff');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }
    </style>
</head>

<body class="min-h-screen text-white font-sans">

    <!-- ===== Xmas Cookies Pattern (SEAMLESS) ===== -->
    <svg class="xmas-pattern" viewBox="0 0 1080 1920" preserveAspectRatio="xMidYMid slice"
        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">


        <defs>
            <!-- ================== SYMBOLS ================== -->
            <!-- ico-bell -->
            <symbol id="ico-bell" viewBox="0 0 94.89 108.96">
                <g fill="currentColor">
                    <g id="Layer_1-2" data-name="Layer 1">
                        <g>
                            <path class="cls-1"
                                d="M49.96,76.12c-2.84-1.84-5.52,1.14-6.85,3.62,3.64,2.56,12.98.36,6.85-3.62Z" />
                            <path class="cls-1"
                                d="M56.52,80.74c4.82,2.08,7.75-5.51,2.31-5.59-1.5-.02-5.2,4.34-2.31,5.59Z" />
                            <path class="cls-1"
                                d="M65.32,79.67c.1,1.08,9.45,1.71,9.75-1.61-3.96-3.74-7.16-3.28-9.75,1.61Z" />
                            <path class="cls-1"
                                d="M39.2,80.04c1.63-5.65-6.91-5.93-8.29-.43.18,1.26,7.82,2.03,8.29.43Z" />
                            <path class="cls-1" d="M72.2,64.92l1.73-2.44c-1.85-1.66-4.24-3.12-5.8-.48l4.06,2.91Z" />
                            <path class="cls-1"
                                d="M13.33,92.91c5.33,3.51,28.55,3.73,35.73,3.38,5.07-.25,31.02-2.96,33.57-5.09.84-.7,2.43-2.35,2.48-3.31.08-1.42-4.61-5.3-6.7-4.81-21.07,1.56-42.56-.34-63.43,2.06-3.62,2.78-6.67,4.46-1.65,7.77Z" />
                            <path class="cls-1"
                                d="M28.1,80.08c-2.78-6.46-5.94-6.22-8.59-.03,1.11,1.69,8.57.89,8.59.03Z" />
                            <path class="cls-1"
                                d="M35.97,64.79c2.63,1.48,5.06-.37,6.35-2.83-.25-2.1-13.31-1.09-6.35,2.83Z" />
                            <path class="cls-1"
                                d="M72.36,50.92c-.38-9.18-2.16-20.99-11.6-24.95-1.41,1.57,2.56,2.41,2.67,3.14.02.15-1.6,2.41-1.74,2.43-.73.13-2.8-3.36-3.83-1.53,1.95,1.84,2.77,5.01,4.66,6.82,3.18,3.06,10.17,3.56,3.51,9.29-3.88,3.34-5.42-6.12-6.11-7.49-1.35-2.71-4.26-5.57-6.27-7.92-1.09,2.3,2.5,7.99,1.9,9.36-1.43,3.27-4.85-.54-4.17-3.62-1.51.41-1.51,8.91-1.13,10.22.62,2.13,2.93,3.24,3.11,5.22.41,4.53-6.11,6.35-7.71,1.27-.79-2.52,1.28-4.35,1.46-6.69.23-3.06-.58-6.8-.02-10.03-.54-2.06-1.35.23-1.8,1.03-1.02,1.81-.72,4.5-4.01,2.81l1.63-7.43c-3.83-.88-6.73,9.72-9.15,11.89-13.24-2.29,2.56-10.13,5.48-14.52-1.21-1.19-2.35,1.52-3.78.89-.82-.36-1.59-2.73-2.24-2.76-3.65-.18-9.95,13.37-9.23,18-.9,2.1-3.03,10.48-.62,11.7l47.31-.37c2.33-.93,1.77-4.64,1.68-6.76Z" />
                            <path class="cls-1"
                                d="M25.8,65.98l4.32-4.16c-.04-.33-6.31-1.15-6.62-1.04-2.18.76.2,4.96,2.3,5.2Z" />
                            <path class="cls-1"
                                d="M12.56,74.08c-3.56,8.97-4.33,6.25-9.64,11.24-5.68,5.35-2.29,9.31,3.49,12.33,9.9,5.18,24.1,2.7,31.08,5.59,3.67,1.52,3.58,5.45,10.91,5.7,7.3.25,6.37-4.43,10.47-6.06,2.92-1.16,16.64-1.45,21.77-2.73,5.45-1.36,18.1-4.4,13.13-12.23-2.78-4.39-8.23-6.17-10.6-11.87-2.18-5.23-1.85-18.92-2.76-25.34-1.36-9.53-2.49-20.66-10.94-26.92-2.68-1.98-8.43-3.23-10.27-5.4C55.81,14.44,60.37-.84,49.08.04c-10.34.81-5.83,13.11-8.7,17.1-1.73,2.39-10.26,4.73-13.68,7.98-14.11,13.41-7.57,32.45-14.13,48.97ZM48.98,6.61c1.16.08,2.8,2.08,4.02,2.71l-4.74,3.98c-3.52-1.23-1.86-6.87.72-6.69ZM30.36,26.32c10.38-7.74,26.7-9.03,37.08-.45,10.95,9.05,6.47,34.1,10.73,47.02,2.17,6.57,8.12,8.68,9.93,13.41,5.14,13.42-27.73,9.79-32.25,12.48-2.57,1.53-1.88,7.11-7.29,7.6-6.2.56-6.2-5.57-8.94-6.78-2.16-.95-13.91-.42-17.42-.96-3.43-.53-9.22-1.66-12.07-3.42-2.48-1.54-3.69-5.17-2.77-7.86.96-2.84,3.74-2.87,5.73-5.14,11.21-12.79-.84-42.41,17.27-55.91Z" />
                            <path class="cls-1"
                                d="M25,72.21c1.56.61,4.91,3.19,5.21,3.14.24-.04,1.54-2.15,2.73-2.82,4.59-2.59,8.15,3,9.46,2.89.43-.04,2.93-3.42,5.53-3.39,2.92.03,4.43,2.77,5.85,2.87.45.03,2.28-1.79,3.57-2.14,5.57-1.48,6.84,2.77,7.26,2.72.54-.07,5.74-5.5,9.83-3.43.3-6.24-3.1-4.06-7.53-6.66-5.79,5.5-7.04,1.17-10.58,1.02-1.89-.08-4.93,4.64-9.07,1.61-.98-.72-2.62-3.61-3.06-3.51-.2.04-1.63,2.27-2.73,3-5.76,3.81-8.28-2.34-10.05-2.19-.36.03-2.24,2.54-4.16,3.02-1.95.49-4.51-.99-4.82-.88-.58.19-2.15,4.26-2.09,4.56.41,2.1,2.95-.48,4.64.18Z" />
                            <path class="cls-1" d="M55.11,61.28l-7.5.19c-1.03,6.76,5.45,5.23,7.5-.19Z" />
                            <path class="cls-1"
                                d="M58.44,61.81c-1.33,3.18,4.9,4.76,6.08.08-.08-.8-5.3-1.95-6.08-.08Z" />
                        </g>
                    </g>
                </g>
            </symbol>

            <!-- ico-cane -->
            <symbol id="ico-cane" viewBox="0 0 85.94 104.51">
                <g fill="currentColor">
                    <g id="Layer_1-2" data-name="Layer 1">
                        <path class="cls-1"
                            d="M46.58.17c-14.86,1.41-23.24,12.26-29.15,24.84C12.06,36.43-.28,76.73,0,88.39c.26,10.95,8.25,19.58,19.54,14.75,6.91-2.96,13.15-30.29,14.96-38.04,1.95-8.35,3.82-32.49,10.33-36.67,3.66-2.34,8.27-2.62,10.97.94,5.17,6.81-2.67,19.53,9.29,25.71,10.89,5.64,19.39-3.21,20.58-13.81C88.33,17.9,70.29-2.08,46.58.17ZM3.8,91.89c-.38-2.02.91-2.4,2.42-3.07,2.59-1.15,17.2-6.42,19.29-6.73,1.28-.19,3.28.23,3.26,1.31-.04,2.69-23.35,9.86-24.97,8.49ZM29.18,69.97c-2.57,2.08-15.09,5.36-18.84,5.99-1.63.27-2.01,1.71-3.53-.57-.39-2.08,2.03-2.21,3.33-2.65,2.43-.82,18.94-6.56,19.66-5.83.2.21.33,2.31-.62,3.07ZM27.78,55.38c-1.97.84-14.26,4.25-14.98,3.51-.26-.27-.28-2.48.58-3.09.82-.58,18.64-5.55,19.41-4.9.31,2.67-3.1,3.67-5.01,4.48ZM19.8,39.89c-.26-.27-.28-2.48.58-3.09.69-.49,15.81-4.49,16.41-3.9,4.29,4.19-16.3,7.7-16.99,6.99ZM39.35,23.94c-1.17,0-13.13-2.5-13.54-3.06-.61-.84.54-2.54,1.67-2.88.62-.18,11,1.57,12.79,1.92,4.01.79,3.04,4-.92,4.01ZM52.79,22.89c-2.19,2.49-9.41-13.61-7.99-15.99,2.21-.4,2.69,1.45,3.54,2.95.99,1.73,5.39,11.97,4.44,13.05ZM61.8,29.89c-.64-.67.87-17.55,4.99-16.99.39,1.99-.65,21.55-4.99,16.99ZM65.32,46.9c-1.52-.21-1.38-1.19-.52-2.5.89-1.37,11.62-13.3,12.52-13.5l2.48,2.48c-3.56,2.11-10.77,14.04-14.48,13.52Z" />
                    </g>
                </g>
            </symbol>

            <!-- ico-ginger -->
            <symbol id="ico-ginger" viewBox="0 0 75.43 106.86">
                <g fill="currentColor">
                    <g id="Layer_1-2" data-name="Layer 1">
                        <path class="cls-1"
                            d="M18.95,13.55c-2.4,6.57.59,12.41,3.28,18.21-4.7,6.27-16.55,5.75-20.63,12.04-4.79,7.39,2.03,13.91,9.63,14.46,2.18.16,7.66-1.65,8.85.8.67,1.37-4.81,26.22-4.39,30.27-1.66,3-12.77,3.69-9.31,11.42,3.66,8.15,17.86,7.43,23.46,2.02,5.14-4.98,5.28-17.97,6.33-18.91,2.58-2.31,3.45,6.89,3.61,7.42,2.39,8.09,4.35,13.51,13.54,15.07,6.83,1.16,17.88-1.03,14.18-10.13-2.02-4.96-5.13-4.92-7.62-8.7-4.12-6.27-3.49-21.71-5.43-29.14.31-1.07,1.97-1.82,2.67-2.71,11.45,4.11,26.29-5.28,13.21-16-3.15-2.58-6.47-2.8-9.47-3.56-.51-.22-1.15-.42-1.9-.6-12.46-4.97-4.86-8.41-4.31-15.89.07-1.02-.43-4.32-.76-5.37-5.78-18.6-28.21-19.15-34.94-.7ZM30.84,98.54c.01,1.4-5.54,1.29-5.94,1.18-1.57-.43-2.41-2.42-3.81-3.27-1.19-.73-6.97.4-6.12-3.25.17-.71,1.8-2.44,2.11-2.44.37,0,.7,1.87,1.37,2.38.95.72,2.94-.1,3.92.32,1.45.62,1.39,2.1,2.17,2.7.77.59,6.27-.16,6.3,2.4ZM13.8,56.91c-.3,0-3.57-4.54-3.59-4.86-.09-2.01,2.14-3.27,2.15-3.62,0-.49-2.06-1.7-2.35-3.17-.13-.67.64-1.76.58-2.82-.05-.82-2.47-3,.48-3.55.17-.03,2.68,1.46,2.93,1.97.64,1.31-1.35,2.86-1.25,3.94.08.96,6.15,2.49.14,6.46,3.79.56,3.39,5.71.91,5.64ZM58.94,90.28c.52.87-2.78,5.43-3.58,6.12-1.41,1.2-3,.74-4,1.5-1.38,1.05-2.13,4.96-4.61,3.6-.49-.27-1.89-1.71-1.89-1.89,0-.32,1.87-.84,2.46-1.42,2.81-2.73,4.06-2.73,5.79-4.22.75-.65,4.03-6.69,5.84-3.69ZM39.02,67.65c-.79,3.71-6,2.07-4.39-.73,1.54-2.67,4.92-1.75,4.39.73ZM36.75,59.43c-4.32-2.8-1.98-6.99,2.08-3.59l-2.08,3.59ZM38.5,46.86c-2.32,4.05-5.88-.28-4.19-2.44,1.21-1.55,5.58.01,4.19,2.44ZM30.8,13.99c2.68-1.14,1.65,3.14.17,3.08-.84-.03-2.46-2.1-.17-3.08ZM30.51,21.84c3.78,1.08,6.33,2.82,9.32-1.08l1.62,1.74c-2.05,4.61-13.03,5.06-10.94-.66ZM61.05,40.07c2.09-1.5,3.5-3.08,5.07-.39-4.36,2.13-.03,4.32-.29,5.62-.26,1.27-3.38,1.92-3.36,2.12.03.24,1.52,1.47,1.86,2.6.83,2.74-3.62,6.25-5.43,2.9.46-.66,2.29-1.74,2.28-2.16,0-.44-2.18-1.8-1.89-3.16.3-1.44,3.29-3.1,3.3-3.54,0-.23-1.52-.81-1.54-3.99ZM41.97,15.51c-1.48,2.37-4.44.58-3.3-1.24,1.48-2.37,4.44-.58,3.3,1.24Z" />
                    </g>
                </g>
            </symbol>

            <!-- ico-house -->
            <symbol id="ico-house" viewBox="0 0 88.25 94.65">
                <g fill="currentColor">
                    <g id="Layer_1-2" data-name="Layer 1">
                        <g>
                            <path class="cls-1"
                                d="M23.6,21.89c-4.54,2.45-7.03,4.3-5.98,9.99,5.36.57,6.16-5.9,5.98-9.99Z" />
                            <path class="cls-1"
                                d="M32.25,23.8c4.56-1.94,6.53-5.59,3.24-9.83-5.91,1.15-9.88,5.96-3.24,9.83Z" />
                            <path class="cls-1"
                                d="M24.29,33.04c-5.39.43-4.28,7.4.79,6.91,4.92-.47,2.93-7.21-.79-6.91Z" />
                            <path class="cls-1"
                                d="M43.6,7.89c-.93.97-5.79,2.74-5.98,3.02-2.01,2.91,8.19,5.92,5.98-3.02Z" />
                            <path class="cls-1"
                                d="M33.12,33.89c-3.05.81-.81,6.39,1.85,5.92,4.18-.73,2.17-6.99-1.85-5.92Z" />
                            <path class="cls-1"
                                d="M27.61,24.89c-.88,2.11.44,9.32,3.97,6.99,2.46-1.63.14-7.88-3.97-6.99Z" />
                            <path class="cls-1"
                                d="M68.6,19.88c-1.57-2.72-.25-9.34-1.44-11.05-2.82-4.08-7.08,1.62-5.08,6.03.42.92,5.58,5.96,6.52,5.03Z" />
                            <path class="cls-1"
                                d="M52.81,22.17c1.93,6.31,8.96-1.02,6.58-5.08-2.36-4.02-8.6-1.52-6.58,5.08Z" />
                            <path class="cls-1"
                                d="M40.98,19.91c1.42,8.44,9.6,1.74,7.48-1.86-1.35-2.31-8.27-2.89-7.48,1.86Z" />
                            <path class="cls-1" d="M52.59,12.39l-4.97-3.49c-.57,5.1,1.21,8.37,4.97,3.49Z" />
                            <path class="cls-1"
                                d="M12.12,38.88c2.12.65,4.23-1.88,4.3-3.66,0-.28-1.62-5.13-1.82-5.34-2.01-2.09-6.36,7.8-2.48,8.99Z" />
                            <path class="cls-1"
                                d="M75.61,34.89c-.45,4.39.03,6.69,4.99,5.99,1.27-2.18-3.95-7.2-4.99-5.99Z" />
                            <path class="cls-1"
                                d="M70.61,28.89c-1.09,4.28,4.82,6.27,3.99,2.51-.18-.82-3.27-3.26-3.99-2.51Z" />
                            <path class="cls-1"
                                d="M75.68,21.29c-1.76-4.9-1.16-15.3-5.77-17.73-4.78-2.52-12.02-1.71-11,5-.99,1.06-5.78-3.16-7-4-3.61-2.52-4.57-5.81-9.5-4.07C35.12,3.07,4.61,28.72,1.42,35.55c-2.84,6.08-1.27,7.23,3.5,10.01h0c3.75,2.19,6.06,2.32,9,6h0s.96,35.59.96,35.59c-.26,1.07.83,1.84,1.04,2.41,0,0,0,0,0,0,.88,2.37,1.54,3.15,4.73,3.77,11.04,2.16,31.24,1.24,42.81.27,6.02-.51,8.8-1.35,11.46-7.04,3.94-8.42-2.84-32.92,3.91-38.59,1.93-1.62,8.32-2.57,8.93-3.56,3.25-5.27-10.39-18.44-12.07-23.11ZM19.97,84.11c-.18,1.72-.4,4.46-3.05,2.45,1.42-12.05-1.05-26.66,0-39h3c-.92,11.66,1.24,25.11.05,36.55ZM26.92,84.06c0,3.37-4,2.66-4,1v-36c0-1.06,2.84-1.9,4-1.5v36.5ZM53.94,88.99c-.88-.8-1.12-3.89-3-2.42-.48.37.02,3.81-3.38,2.89-2.14-.58-2.13-3.32-2.65-3.89-.7-.77-1.77,3.05-3.9,3.55-1.98.46-4.12-1.6-4.11-3.56-5.32,6.75-8.94,2.5-7.98-5l3.98,3.99c1.89-5.83,5.88-6.98,7.02,0,.92.7,3.08-4.64,4.58-4.96,2.48-.53,3.2,3.8,3.42,3.96.49.35,6.41-7.9,7.99,1.99,1.9-.22,1.32-3.36,2.98-4.51,3.59-2.48,1.71,14-4.97,7.95ZM31.88,64.02c1.64-1.08,9.41-.42,10.79.77,2.27,1.97,2,10.99-2.25,11.76-1.27.23-8.77.23-9.36-.14-1.83-1.14-1.35-10.97.82-12.39ZM31.85,46.99c1.16-.81,8.86-.8,10.1.03,2.06,1.36,2.62,10.26.82,11.4-.57.36-10.14.36-10.71,0-1.23-.77-1.98-10.19-.21-11.42ZM54.36,76.6c-5.83-.74-3.87-11.23-1.81-11.9,6.05-1.98,10.41-.21,10.43,6.35.03,6.39-2.43,6.34-8.62,5.56ZM61.92,58.56c-2.91.15-6.5,2.01-9.86.86-1.94-3.71-.8-7.93-1.14-11.86,9.48-3.43,14.82,1.28,11,11ZM67.92,86.56c-.98.1-2.01-.06-3,0,.35-8.62-.5-17.41,0-26.01h0c.21-3.73,1.91-8.64,1-13.01h2.99l-1,39.01ZM73.92,81.56h0c-.2,1.73.09,2.73-1.47,4.12-1.64,1.46-2.39-.15-2.53-.12l1-38.01h2.99c-.91,10.77,1.25,23.49,0,34.01h0ZM72.06,45.9c-6.83.19-16.1-.82-23.46-1.01-13.78-.36-27.96-1.67-42-1-6.27-3.44-1.15-9.55,2.02-13.48,4.29-5.32,27.25-23.38,33.29-25.77,2.41-.96,6.57-.13,8.69,1.25,1.19.77,6.35,6.95,7.5,6.98,2.38-1.68.75-5.36,2.89-7.08,1.95-1.56,4.47-.65,6.61-.9,5.25,3.29,2.57,13.59,4.2,19.3,1.47,5.12,27.7,20.93.26,21.72Z" />
                            <path class="cls-1"
                                d="M64.62,38.37c1.59,4.37,7.96,2.31,6.99-2.48-1.61-1.53-7.79.26-6.99,2.48Z" />
                            <path class="cls-1"
                                d="M54.46,28.05c-.16-.27-4.88-3.17-5.34-3.2-1.4-.09-3.25,4.05-.98,7.01,4.08,5.31,7.51-1.78,6.32-3.81Z" />
                            <path class="cls-1"
                                d="M62.62,20.89c-.1,4.74-6.39,6.48-2.06,11.54,5.11,5.97,11.09-8.88,2.06-11.54Z" />
                            <path class="cls-1"
                                d="M38.23,23.95c-3.74.66-2.33,8.98.87,8.99,5.81,0,3.03-9.67-.87-8.99Z" />
                            <path class="cls-1"
                                d="M42.06,35.88c-1.13,2.56,4.13,5.36,5.54,4.01,1.89-1.81-3.18-9.35-5.54-4.01Z" />
                            <path class="cls-1"
                                d="M57.11,32.89c-12.13,9.68,6.92,9.38,3.3,3.17-.31-.54-3.31-1.49-3.3-3.17Z" />
                        </g>
                    </g>
                </g>
            </symbol>

            <!-- ico-tree -->
            <symbol id="ico-tree" viewBox="0 0 103.23 121.75">
                <g fill="currentColor">
                    <g id="Layer_1-2" data-name="Layer 1">
                        <path class="cls-1"
                            d="M23.67,109.71c1.4-.03,2.69-.88,3.38-.9,2.1-.07,11.59-.58,12.62-.11,6.36,2.87-2.33,11.4,7.6,13,2.5.4,10.63-1.21,12.49-2.8,2.36-2.02,1.46-6.21,2.56-8.04,6.7-11.08,45.9,3.61,40.39-19.66-1.82-7.69-13.17-17.5-13.15-18.7.01-.85,4.68-4.44,5.27-7.27,2.33-11.22-14.45-18.92-19.87-26.46,5.34-2.61,7.41-6.89,4.8-12.41-3.03-6.41-20.65-23.76-27.37-25.89-1.55-.49-5.71-.65-7.16-.19-6.33,2.01-23.38,20.38-26.25,26.73-4.86,10.77.17,9.35,7.19,14.68-6.9,9.94-28.74,22-13,34.24-6.67,5.13-12.49,11.89-12.57,20.64,0,.64-1.14,1.14-.24,3.16,4.39,9.94,13.92,10.19,23.33,9.98ZM81.16,51.46c-.17.9-4.6,3.09-5.52.75-1.44-3.66,6.13-3.93,5.52-.75ZM55.52,6.63c1.84,1.72-1.83,5.03-2.73,4.86-3.17-.6-.14-7.54,2.73-4.86ZM64,24.43l-4.14,2.81c-4.56-5.13.94-9.03,4.14-2.81ZM37.96,15.6c2.16-.67,3.45,2.95,3.11,3.74-2.35,5.37-10.05-1.6-3.11-3.74ZM22.52,36.33c-.22-.55.51-3.57,1.42-3.96,1.94-.83,4.05,2.12,5.02,2.26.89.12,2.07-1.15,3.38-.71.89.3,1.61,1.88,2.13,1.96.95.14,2.68-2.26,4.4-2.27,1.19,0,2.36,2.17,3.89,2.24,1.21.06,5.27-1.6,7.57-1.81,6.2-.57,13.19-.21,18.74-.39,2.04-.07,4.23-3.48,4.99.27.91,4.45-5.1,3.04-7.1,3.33-7.8,1.11-14.39.99-21.49,1.32-7.49.34-12.01,1.11-19.13-1.41-1.14-.4-3.11.94-3.82-.82ZM69.9,62.64c-3.73,4.67-7.22-1.67-4.77-3.61,2.1-1.66,7.04.77,4.77,3.61ZM51.19,48.28l-3.56,5.42-1.55-4.98,5.11-.44ZM29.62,55.69c1.89,1.69-1.91,5.03-2.73,4.85-3.59-.77-1.36-8.51,2.73-4.85ZM13.74,71.01c.89-5.57,6.09,1.06,6.63,1.11.94.08,2.78-2.19,3.27-2.12.47.07,1.76,3.25,2.25,3.33.38.07,2.5-2.33,4.41-2.4,7.4-.28,20.33,3.91,26.75,2.57.87-.18,2.1-1.96,3.22-1.93,1.21.04,1.59,1.87,2.25,1.98.46.08,6.95-2.52,8.82-2.63,3.91-.23,6.14.16,11.1-1.48,1.36-.45,1.84-2.31,3.3-2.37,2.52-.22,1.83,4.15-.42,5.58-2.29,1.45-4.44.83-6.66,1.41-3.98,1.04-4.25.83-7.07,1.28-5.5.89-15.26,3.24-20.38,3.02-2.08-.09-6.8-2.06-9.3-2.18-1.88-.09-6.86,1.31-8.13,1.14-2.72-.38-6.53-1.67-10.45-1.83-2.73-.11-10.49,1.22-9.58-4.48ZM94.69,91.83c-2.96,3.65-6.36-2.14-4.17-4.4,2.05-2.1,5.83,2.34,4.17,4.4ZM77.01,82.68c-1.84-1.72,1.83-5.03,2.73-4.86,3.17.6.14,7.54-2.73,4.86ZM64.1,95.71c-2.32-.43-2.87-6.46,1.89-6.1,3.06,1.8-.17,6.42-1.89,6.1ZM44.88,87c-2.9-.55-1.84-6.09,1.06-5.54,2.9.55,1.84,6.09-1.06,5.54ZM27.19,93c7.47-4.58,5.96,4.39,2.26,3.15-.75-.25-1.52-2.36-2.26-3.15ZM11.73,85.39c5.17.19,5.26,3.02.73,5.52l-.73-5.52ZM5.35,101.79h0c2.15-5.26,3.81-1.43,5.93-.85,7.15,1.98,15.04-2,20.36-.87,5.01,1.06,16.37,1.63,22.62,1.05,5.32-.49,10.7-1.48,14.93-1.97,6.12-.7,19.51.2,23.67-1.01,2.26-.66,6.62-5.8,5.81-.53-.59,3.84-3.01,4.36-6.69,4.85-5.93.8-13.03-1.1-18.2-.87-14.15.64-29.7,6.74-45.37,3.86-.18-.03-.53-1.4-1.14-1.43-.97-.04-2.66,1.97-4.41,2.21-.54.07-8.52-1.03-10.07-1.11-3.26-.17-4.56,2.03-7.43-3.34h0Z" />
                    </g>
                </g>
            </symbol>

            <!-- ico-snowstar -->
            <symbol id="ico-snowstar" viewBox="0 0 103.16 94.31">
                <g fill="currentColor">
                    <g id="Layer_1-2" data-name="Layer 1">
                        <path class="cls-1"
                            d="M95.54,43.21c-2.21-.34-4.93.41-6.75-.03-.93-.22-1.73-4.11-5.44-4.85-2.43-.49-9.39,2.58-9.24.33.05-.73,6.97-2.38,7.04-7.95.02-1.58-1.35-3.51-.96-4.9.36-1.29,3.27-2.3,4.45-3.6,8.63-9.57-5.4-21.37-14.08-15.07-3.11,2.26-3.55,6.26-5.46,8.53-1.52,1.81-4.65,2.51-5.73,3.77-.78.9-2.53,10.12-4.28,7.72-.2-.28-.05-4.83-.67-6.83-1.18-3.83-4.88-5.03-5.82-7.17-1.03-2.33-.19-4.88-.72-7.28-2.23-10.04-23.01-7-20.74,7.73.29,1.89,1.78,3.41,1.95,5.15.25,2.55-1.32,4.92-.99,7.82.44,3.77,4.39,4.74,3.98,8.59-2.95-.27-5.35-2.32-8.51-2.09-2.42.18-3.82,2.3-5.91,2.13-3.67-.29-5.33-6.01-12.53-1.49-7.61,4.77-6.44,17.2,1.38,20.53,4.29,1.83,5.61-.47,7.88.07,1.72.41,2.49,3.35,5.23,3.86,3.25.6,6.26-1.2,9.45-1-.27,3.38-3.93,4.26-5.1,7.47-.98,2.69.46,5.02-.33,6.51-.67,1.27-2.98,1.84-4,3.08-7.4,9,5.77,21.46,14.99,16,3.28-1.94,3.13-7.21,4.68-8.82,4.16-4.33,3.64-.25,7.68-8.81,3.68-7.78,3.98,4.14,5.16,5.98,1.35,2.09,4.65,2.26,5.43,3.57,1.12,1.89-.19,4.7,1.42,7.58,4.2,7.53,18.7,5.59,19.02-4.16.11-3.27-2.79-5.3-2.96-7.98-.24-3.96,5.01-8.2-1-13.41,3.96,1.57,7.78-2.05,10.48-2.1,2.28-.04,3.97,1.74,6.09,2.05,14.01,2.1,17.11-19.05,4.88-20.91ZM95.65,55.21c-2.68,2.57-5.36-.57-8.11-1.01-8.81-1.41-18.64-.94-27.44-3.05-.17,2.9,3.41,3,5,5.5.81,1.27,1.12,2.15-.52,2.5-3.41.71-4.31-4.26-7.48-3.99,3.14,8.74,5.86,18.3,9.79,26.7.65,1.39,6.28,6.96,1.67,8.28-4.8,1.37-4.17-5.77-4.95-8-2.95-8.4-7.03-16.42-9.53-24.98l-3.5,7.99c-4.39-.62-.72-5.08-1.5-7.99-.82-1.11-15.89,20.06-16.83,21.66-1.3,2.21-2.18,10.47-7.21,6.9-4.19-2.97,2.67-6.43,4.05-8.07,6.11-7.28,10.82-16.2,17.01-23.48-2.73-.64-7.32,3.33-9.4,2.88-1.03-.22-2.16-2.13-1.6-2.88.47-.62,8.3-2.35,8.99-4-10.1-1.33-20.48-3.28-30.64-3.96-2.09-.14-4.19,1.13-5.65.79-2.75-.65-3.58-3.89-.68-5.39,2.44-1.26,3.36,1.08,5.56,1.47,10.34,1.85,21.9,2.11,32.42,3.06-1.41-2.86-5.31-3.85-7.01-6.49-.84-1.31-1.02-2.28.53-2.5,2.13-.3,9,6.57,9.47,6-3.85-8.99-6.37-20.11-10.79-28.7-1.6-3.1-5.72-4.06-3.1-7.68,5.06-7,5.78,5.41,6.38,7.4,2.76,9.13,7.64,17.86,10.53,26.97l2.97-9.51,2.01-.48.5,7.99c4.73-6.68,11.83-14.23,15.91-21.09.94-1.58.7-3.9,1.51-5.24,3.21-5.29,10.91,4.23,1.05,6.78l-16.96,24.55c2.4.52,10.73-5.26,11.99-3.99,3.16,3.19-6.56,5.62-7.51,5.99-1.19.47-1.88-.76-1.49,2,8.59.11,18.12,2.7,26.5,3.01,3.69.14,10.03-4.05,8.06,4.06Z" />
                    </g>
                </g>
            </symbol>

            <!-- ico-star5 -->
            <symbol id="ico-star5" viewBox="0 0 90.13 90.5">
                <g fill="currentColor">
                    <g id="Layer_1-2" data-name="Layer 1">
                        <g>
                            <path class="cls-1"
                                d="M43.95,43.99c.72-7.44-2.47-15.19-.4-22.46,2.2.42,2.26,2.88,2.6,4.63.45,2.28.97,16.82,1.78,17.12,3.14-1.33,7.93-11.73,7.5-14.06l-12.25-18.45-9.96,19.07c1.12,1.97,9.43,14.75,10.73,14.15Z" />
                            <path class="cls-1"
                                d="M72.09,38.22c.43.92,1.23,1.76.57,2.82-.94,1.5-15.46,6.58-18.21,7.82-1.24.56-2.23.59-2.43,2.29,3.66-.14,9.46,3.31,12.48,2.46,1.26-.36,16.19-14.71,15.67-15.69l-21.34-5.51-6.99,13.36c.83,2.1,17.56-8.18,20.26-7.55Z" />
                            <path class="cls-1"
                                d="M39.36,50.85c-5.58-3.08-14.76-5.29-19.88-8.24-1.17-.67-2.34-1.84-1.51-3.18l3.82-.32,18.1,8.76c.79.4,1.21-1.55,1.15-1.71l-11.05-14.5-21.16,6.58c.08,1.17,15.9,15.84,17.02,16.2,1.67.54,11.7-1.92,13.5-3.58Z" />
                            <path class="cls-1"
                                d="M71.86,54.36c3.7-4.32,26.42-16.25,15.16-22.4-8.3-4.54-17.87-3.11-26.82-4.67-3.56-8.1-4.69-16.95-9.88-24.58l-7.53-2.71c-9.66,4-8.88,21.65-15.37,26.3-5.21,3.74-23.73-3-27.13,8.85-2.86,9.98,14.91,16.25,20.57,22.03.15,5.22-4.67,27.22-2.19,30.34,5.8,7.31,19.52-8.66,25.02-8.32,2.35.14,7.78,2.4,10.2,3.48,5.98,2.67,18.86,13.94,22.74,3.42,3.69-10-11.14-24.32-4.79-31.74ZM71.42,85.78c-8.21-1.18-18.67-9.87-26.35-10.34-3.74-.23-21.39,13.95-24.19,9.1-.69-1.19,3.57-23.66,3.17-27.28L2.45,38.51c.22-1.12.54-2.73,1.37-3.45.86-.74,18.61-4.47,21.92-5.69,10.13-3.73,9.8-18.27,18.06-24.75l14.85,24.29,27.56,6.94c1.08,2.22-17.79,17.17-18.92,20.28-1.63,4.49,7.79,27.88,4.12,29.65Z" />
                            <path class="cls-1"
                                d="M42.98,58.52c-.81.41-7.93,12.24-10.19,13.77-.97.66-2.98-.25-3.1-.49-.61-1.18,11.03-14.21,12.41-16.77l-14.88,2.72-2.81,22.78c1.48,1.01,2.12,0,3.33-.38,2.27-.73,14.59-6.88,15.32-8.27.34-.65,1.22-9.63,1.32-11.29.07-1.12.69-3.13-1.4-2.06Z" />
                            <path class="cls-1"
                                d="M63.39,57.9c-1.03-1.29-7.78-2.59-10.05-3.05-4.95-1.01.08,2.68.66,3.54,2.09,3.09,6.51,8.42,7.82,11.2.69,1.46,1.11,3.16-1.02,2.81-3.18-.52-11.4-16.15-13.05-15.33l-1.16,15.38,21.13,8.81c1.3-.57-3.52-22.33-4.34-23.36Z" />
                        </g>
                    </g>
                </g>
            </symbol>

            <!-- pattern -->
            <pattern id="xmasTile" width="720" height="720" patternUnits="userSpaceOnUse"
                patternContentUnits="userSpaceOnUse">
                <rect width="720" height="720" fill="none" />
                <g id="tile"></g> <!-- JS bakal ngisi ini -->
            </pattern>
        </defs>

        {{--
        <rect x="0" y="0" width="100%" height="100%" fill="url(#xmasTile)" /> --}}
        <g id="bg-1080x1920"></g>
    </svg>


    <!-- Salju -->
    <div id="snow" aria-hidden="true"></div>

    <main class="relative z-10 mx-auto max-w-5xl p-5 md:p-8">
        <section
            class="relative isolate z-[5] rounded-3xl p-5 shadow-[inset_0_20px_50px_rgba(0,0,0,.35),0_30px_80px_rgba(0,0,0,.35)] backdrop-blur-[0.5px]"
            style="background:radial-gradient(120% 80% at 50% -10%,var(--brand-600) 0,var(--brand-800) 55%,var(--brand-900) 85%),linear-gradient(180deg,var(--brand-700),var(--brand-900));">

            @php
                use Illuminate\Support\Facades\Storage;
                $bannerPath = \App\Support\Settings::get('banner_path');
                $bannerUrl = $bannerPath ? Storage::disk('public')->url($bannerPath) : null;
            @endphp
            <!-- Banner -->
            <div class="w-full rounded-2xl overflow-hidden shadow-2xl relative group">
                @if ($bannerUrl)
                    <img src="{{ $bannerUrl }}" alt="Banner" class="w-full h-full object-cover">
                @else
                    <img src="https://template.canva.com/EAFUP94L0a4/2/0/1600w-QZhOwIvXUI4.jpg" alt="Banner default"
                        class="w-full h-full object-cover">
                @endif
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/25 via-black/0 to-black/0 pointer-events-none">
                </div>
            </div>

            <!-- Judul -->
            <h1 class="mt-6 text-center font-title font-bold tracking-[.4px]"
                style="font-size:clamp(2.2rem,3.6vw + 1rem,3.6rem);line-height:1">RSVP</h1>
            <h2 class="mt-1 text-center font-title font-bold tracking-[.3px] text-cream"
                style="font-size:clamp(1.6rem,2.6vw + .5rem,2.6rem);line-height:1.1">Born To Bring Peace</h2>
            <p class="mt-2 text-center max-w-3xl mx-auto text-cream/90 leading-relaxed">
                Malam perayaan penuh sukacita. Isi data berikut untuk menerima tiket unik berisi QR Code rahasia yang
                hanya dapat dipindai oleh staf pada hari-H.
            </p>

            <!-- Form -->
            <div
                class="mt-5 md:mt-6 bg-[var(--paper)] text-[var(--brand-900)] rounded-2xl p-5 md:p-6 shadow-card border border-black/5">
                <h3 class="m-0 font-title text-2xl text-[var(--brand-900)]">Formulir Kehadiran</h3>

                <form id="rsvp-form" class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4"
                    action="{{ route('rsvp.store') }}" method="POST" novalidate>
                    @csrf

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-[var(--brand-900)] mb-1.5">Nama
                            Lengkap</label>
                        <input id="nama" name="nama" type="text" required value="{{ old('nama') }}"
                            class="w-full rounded-xl border border-[#e7d9c3] bg-white px-4 py-3 shadow-inner focus:outline-none focus:border-gold focus:ring-4 focus:ring-[#d9b86c38]"
                            autocomplete="name">
                        @error('nama')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Telp -->
                    <div>
                        <label for="telp" class="block text-sm font-semibold text-[var(--brand-900)] mb-1.5">Nomor
                            Telepon</label>
                        <input id="telp" name="telp" type="tel" inputmode="numeric" required
                            placeholder="08xxxxxxxxxx" value="{{ old('telp') }}"
                            class="w-full rounded-xl border border-[#e7d9c3] bg-white px-4 py-3 shadow-inner focus:outline-none focus:border-gold focus:ring-4 focus:ring-[#d9b86c38]">
                        @error('telp')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Usia -->
                    <div>
                        <label for="usia"
                            class="block text-sm font-semibold text-[var(--brand-900)] mb-1.5">Usia</label>
                        <input id="usia" name="usia" type="number" min="10" max="50" required
                            value="{{ old('usia') }}"
                            class="w-full rounded-xl border border-[#e7d9c3] bg-white px-4 py-3 shadow-inner focus:outline-none focus:border-gold focus:ring-4 focus:ring-[#d9b86c38]">
                        @error('usia')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenjang -->
                    <div>
                        <label for="jenjang"
                            class="block text-sm font-semibold text-[var(--brand-900)] mb-1.5">Jenjang
                            Pendidikan</label>
                        <select id="jenjang" name="jenjang" required
                            class="w-full rounded-xl border border-[#e7d9c3] bg-white px-4 py-3 shadow-inner focus:outline-none focus:border-gold focus:ring-4 focus:ring-[#d9b86c38]">
                            <option value="" disabled {{ old('jenjang') ? '' : 'selected' }}>Pilih salah satu
                            </option>
                            @foreach (['SMP 1', 'SMP 2', 'SMP 3', 'SMA 1', 'SMA 2', 'SMA 3', 'Kuliah D3/D4/S1', 'Gap Year', 'Kerja', 'Lainnya'] as $opt)
                                <option {{ old('jenjang') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('jenjang')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sekolah -->
                    <div id="wrap-sekolah">
                        <label for="sekolah" class="block text-sm font-semibold text-[var(--brand-900)] mb-1.5">Nama
                            Sekolah/Kampus</label>
                        <input id="sekolah" name="sekolah" type="text" required value="{{ old('sekolah') }}"
                            class="w-full rounded-xl border border-[#e7d9c3] bg-white px-4 py-3 shadow-inner focus:outline-none focus:border-gold focus:ring-4 focus:ring-[#d9b86c38]">
                        @error('sekolah')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Info -->
                    <div id="wrap-informasi">
                        <label for="informasi" class="block text-sm font-semibold text-[var(--brand-900)] mb-1.5">Tau
                            acara ini dari mana?</label>
                        <input id="informasi" name="informasi" type="text" required
                            value="{{ old('informasi') }}"
                            class="w-full rounded-xl border border-[#e7d9c3] bg-white px-4 py-3 shadow-inner focus:outline-none focus:border-gold focus:ring-4 focus:ring-[#d9b86c38]">
                        @error('informasi')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gereja -->
                    <div id="wrap-gereja" class="{{ old('gereja') === 'lainnya' ? '' : 'md:col-span-2' }}">
                        <label for="gereja"
                            class="block text-sm font-semibold text-[var(--brand-900)] mb-1.5">Anggota gereja
                            mana</label>
                        <select id="gereja" name="gereja" required
                            class="w-full rounded-xl border border-[#e7d9c3] bg-white px-4 py-3 shadow-inner focus:outline-none focus:border-gold focus:ring-4 focus:ring-[#d9b86c38]">
                            <option value="" disabled {{ old('gereja') ? '' : 'selected' }}>Pilih gereja
                            </option>
                            @foreach (['GKT 3', 'GKJW', 'GBI', 'GPdI', 'GSJA', 'HKBP', 'Katolik', 'lainnya'] as $opt)
                                <option value="{{ $opt }}" {{ old('gereja') === $opt ? 'selected' : '' }}>
                                    {{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('gereja')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gereja manual -->
                    <div id="wrap-gereja-lain" class="{{ old('gereja') === 'lainnya' ? '' : 'hidden' }}">
                        <label for="gereja_manual"
                            class="block text-sm font-semibold text-[var(--brand-900)] mb-1.5">Nama Gereja
                            (Lainnya)</label>
                        <input id="gereja_manual" name="gereja_manual" type="text"
                            value="{{ old('gereja_manual') }}"
                            class="w-full rounded-xl border border-[#e7d9c3] bg-white px-4 py-3 shadow-inner focus:outline-none focus:border-gold focus:ring-4 focus:ring-[#d9b86c38]"
                            {{ old('gereja') === 'lainnya' ? 'required' : '' }}>
                        @error('gereja_manual')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Consent -->
                    <div class="md:col-span-2">
                        <label class="inline-flex items-start gap-3">
                            <input id="consent" type="checkbox" name="consent"
                                {{ old('consent') ? 'checked' : '' }}
                                class="mt-1.5 w-5 h-5 rounded border-[#e7d9c3] text-brandRed focus:ring-gold">
                            <span class="text-sm text-[var(--brand-900)]">Saya menyetujui penggunaan data ini untuk
                                keperluan pembuatan tiket, pengiriman, dan check-in acara sesuai kebijakan privasi
                                panitia.</span>
                        </label>
                        @error('consent')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="md:col-span-2 flex items-center justify-center md:justify-start gap-3 pt-1">
                        <button id="submitBtn" type="submit"
                            class="relative px-5 py-3 rounded-xl font-semibold text-white bg-gradient-to-b from-[var(--brand-700)] to-[var(--brand)] shadow-[0_10px_20px_rgba(181,31,45,.35)] transition-transform focus:outline-none focus:ring-4 focus:ring-[color-mix(in_srgb,var(--gold)_22%,transparent)] active:translate-y-[1px] overflow-hidden">
                            <span class="btn-label">Kirim &amp; Dapatkan Tiket</span>
                            <span class="btn-spinner" aria-hidden="true"></span>
                        </button>

                        <div id="submitHint" class="hidden text-cream/90 text-sm select-none">
                            Mengirim... sebentar.
                        </div>
                    </div>

                </form>

                {{-- <p class="text-xs text-brandRedDeep/60 mt-3">Nomor telepon dibersihkan otomatis ke digit saja.
                    Pratinjau format E.164 tampil di bawah field.</p> --}}
            </div>

            <p class="text-center text-[var(--brand-900)] text-xs mt-4">© 2025 Teens X Youth • QR hanya valid saat dipindai staf.
            </p>
        </section>
    </main>

    <script>
        // Helpers
        const $ = (q, ctx = document) => ctx.querySelector(q);
        const $$ = (q, ctx = document) => [...ctx.querySelectorAll(q)];

        const selGereja = $('#gereja');
        const wrapGereja = $('#wrap-gereja');
        const wrapLain = $('#wrap-gereja-lain');
        const manual = $('#gereja_manual');
        const telp = $('#telp');
        const telpE164 = $('#telp-e164');
        const heroImg = $('#hero-img');

        function normalizePhone(raw) {
            const digits = (raw || '').replace(/\D+/g, '');
            if (digits.startsWith('0')) return '62' + digits.slice(1);
            if (digits.startsWith('62')) return digits;
            return digits;
        }

        function applyGerejaLayout() {
            const isLainnya = selGereja.value === 'lainnya';
            wrapGereja.classList.toggle('md:col-span-2', !isLainnya);
            wrapLain.classList.toggle('hidden', !isLainnya);
            if (isLainnya) {
                manual.setAttribute('required', 'required');
                manual.focus();
            } else {
                manual.removeAttribute('required');
                manual.value = '';
            }
        }

        function updateTelp() {
            if (!telp) return;
            const cleaned = telp.value.replace(/\D+/g, '');
            if (telp.value !== cleaned) telp.value = cleaned;

            // Hanya update preview kalau elemen-nya ADA
            if (telpE164) {
                const e164 = cleaned ? normalizePhone(cleaned) : '—';
                telpE164.textContent = 'E.164: ' + e164;
            }
        }

        // Snow spawn (CSS nanti)
        function spawnSnow() {
            const snow = document.getElementById('snow');
            if (!snow) return;
            const isDesktop = matchMedia('(min-width: 1024px)').matches;
            const total = isDesktop ? 110 : 48;
            for (let i = 0; i < total; i++) {
                const d = document.createElement('div');
                d.className = 'flake';
                const size = (Math.random() < 1) ? (2 + Math.random() * 4) : (4 + Math.random() * 8);
                d.style.width = size + 'px';
                d.style.height = size + 'px';
                const x = Math.round(2 + Math.random() * 96);
                const t = (10 + Math.random() * 14).toFixed(1) + 's';
                const sx = (Math.random() > .5 ? 1 : -1) * (10 + Math.random() * 40) + 'px';
                const delay = (Math.random() * 10).toFixed(2) + 's';
                d.style.left = x + '%';
                d.style.setProperty('--t', t);
                d.style.setProperty('--sx', sx);
                d.style.animationDelay = delay;
                d.style.opacity = String(0.5 + Math.random() * 0.5);
                snow.appendChild(d);
            }
        }

        // Parallax
        let ticking = false;

        function onScroll() {
            if (ticking) return;
            requestAnimationFrame(() => {
                const y = window.scrollY || 0;
                const offset = Math.min(20, y / 30);
                if (heroImg) heroImg.style.transform = `translateY(${offset}px) scale(1.02)`;
                ticking = false;
            });
            ticking = true;
        }


        (function() {
            const NS = 'http://www.w3.org/2000/svg';
            const XLINK = 'http://www.w3.org/1999/xlink';

            // ===== KNOBS (boleh kamu pertahankan dari versi kamu) =====
            const BASE = 0.05; // skala global ikon
            const DENSITY = 2; // kerapatan (mengalikan count tiap ikon)
            const OPACITY = 0.12; // transparansi
            const ROTATE_STEP = 15; // 0 = bebas, 15/30 = lebih “rapi”

            const W = 1080,
                H = 1920; // kanvas
            const PAD = 10; // jarak dari tepi
            const K = 30; // kandidat per iterasi (20–50 bagus)
            const R_MULT = 0.85; // pengali radius target (0.85–1.0)

            // ===== RNG seeded biar konsisten =====
            function rng(seed) {
                return () => (seed = (seed * 1664525 + 1013904223) >>> 0) / 4294967296
            }
            const rand = rng(20251224);
            const between = ([a, b]) => a + rand() * (b - a);
            const quantize = (deg, step) => step > 0 ? Math.round(deg / step) * step : deg;
            const shuffle = (a) => {
                for (let i = a.length - 1; i > 0; i--) {
                    const j = Math.floor(rand() * (i + 1));
                    [a[i], a[j]] = [a[j], a[i]]
                }
                return a
            }

            // ===== Konfigurasi ikon (punyamu) =====
            const ICONS_BASE = [{
                    id: 'ico-tree',
                    count: 18,
                    rotate: [-18, 18],
                    scale: [0.90, 1.20]
                },
                {
                    id: 'ico-cane',
                    count: 20,
                    rotate: [-36, 36],
                    scale: [0.90, 1.20]
                },
                {
                    id: 'ico-ginger',
                    count: 12,
                    rotate: [-14, 14],
                    scale: [0.90, 1.12]
                },
                {
                    id: 'ico-bell',
                    count: 14,
                    rotate: [-22, 22],
                    scale: [0.90, 1.18]
                },
                {
                    id: 'ico-star5',
                    count: 18,
                    rotate: [0, 360],
                    scale: [0.70, 1.10]
                },
                {
                    id: 'ico-snowstar',
                    count: 16,
                    rotate: [0, 360],
                    scale: [0.70, 1.10]
                },
                {
                    id: 'ico-house',
                    count: 10,
                    rotate: [-12, 12],
                    scale: [0.90, 1.12]
                },
            ].map(i => ({
                ...i,
                count: Math.max(1, Math.round(i.count * DENSITY))
            }));

            const TOTAL = ICONS_BASE.reduce((s, i) => s + i.count, 0);

            // ===== Target layer =====
            const svg = document.querySelector('.xmas-pattern');
            if (!svg) return;
            const layer = svg.querySelector('#bg-1080x1920');
            if (!layer) return;
            while (layer.firstChild) layer.removeChild(layer.firstChild);

            // ===== Best-Candidate (dart throwing) =====
            function sqr(x) {
                return x * x
            }

            function dist2(a, b) {
                return sqr(a.x - b.x) + sqr(a.y - b.y)
            }

            // radius target berdasar luas & jumlah titik (aproksimasi disk-packing)
            const area = (W - 2 * PAD) * (H - 2 * PAD);
            const rTarget = Math.sqrt(area / (TOTAL * Math.PI)) * R_MULT;
            const r2 = rTarget * rTarget;

            function randomPoint() {
                return {
                    x: PAD + rand() * (W - 2 * PAD),
                    y: PAD + rand() * (H - 2 * PAD)
                };
            }

            const pts = [];
            // seed pertama
            pts.push(randomPoint());

            while (pts.length < TOTAL) {
                let best, bestMinD2 = -1;
                for (let i = 0; i < K; i++) {
                    const p = randomPoint();
                    // hitung jarak kuadrat terdekat ke titik eksisting
                    let minD2 = Infinity;
                    for (let j = 0; j < pts.length; j++) {
                        const d2 = dist2(p, pts[j]);
                        if (d2 < minD2) minD2 = d2;
                        // early break jika sudah di bawah r2 (biar cepat)
                        if (minD2 < r2) break;
                    }
                    // pilih kandidat dengan jarak terdekat terbesar (maksimalkan coverage)
                    if (minD2 > bestMinD2) {
                        bestMinD2 = minD2;
                        best = p;
                    }
                }
                pts.push(best);
            }

            // acak stabil assignment ikon ↔ posisi
            const bag = [];
            ICONS_BASE.forEach(cfg => {
                for (let i = 0; i < cfg.count; i++) bag.push(cfg.id)
            });
            shuffle(bag);
            shuffle(pts);

            const cfgById = ICONS_BASE.reduce((m, i) => (m[i.id] = i, m), {});
            // Render
            const NS_USE = 'use';
            for (let i = 0; i < TOTAL; i++) {
                const id = bag[i];
                const cfg = cfgById[id];
                const p = pts[i];

                const s = (between(cfg.scale) * BASE).toFixed(3);
                const rawRot = between(cfg.rotate);
                const r = quantize(rawRot, ROTATE_STEP).toFixed(2);

                const use = document.createElementNS(NS, NS_USE);
                use.setAttribute('href', '#' + id); // SVG2
                use.setAttributeNS(XLINK, 'href', '#' + id); // fallback xlink
                use.setAttribute('fill', 'currentColor');
                use.setAttribute('color', '#fff6e8'); // krem lembut
                use.setAttribute('opacity', String(OPACITY));
                use.setAttribute('transform', `translate(${p.x} ${p.y}) rotate(${r}) scale(${s})`);
                layer.appendChild(use);
            }

        })();


        // Single init
        document.addEventListener('DOMContentLoaded', () => {
            // init visual
            applyGerejaLayout();
            if (telp) telp.addEventListener('input', updateTelp);
            spawnSnow();

            // scatter: amanin kalau variabel config belum ada
            if (typeof ICONS_CONFIG !== 'undefined') {
                scatter(ICONS_CONFIG);
            } else if (typeof ICONS !== 'undefined') {
                scatter(ICONS);
            } else {
                try {
                    scatter();
                } catch (_) {}
            }

            // listeners kecil
            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            if (selGereja) selGereja.addEventListener('change', applyGerejaLayout);
            if (telp) telp.addEventListener('input', updateTelp);

            // ===== SUBMIT =====
            const form = document.querySelector('#rsvp-form');
            const submitBtn = document.querySelector('#submitBtn');
            const submitHint = document.querySelector('#submitHint');

            if (!form || !submitBtn) return;

            form.addEventListener('submit', (e) => {
                if (!form.reportValidity()) {
                    e.preventDefault();
                    return;
                }

                e.preventDefault(); // kasih waktu browser render state loading
                submitBtn.classList.add('loading', 'cursor-not-allowed');
                submitBtn.disabled = true;
                submitBtn.setAttribute('aria-busy', 'true');
                const lbl = submitBtn.querySelector('.btn-label');
                if (lbl) lbl.textContent = 'Mengirim...';
                if (submitHint) submitHint.classList.remove('hidden');

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        form.submit();
                    });
                });
            });
        });
    </script>
</body>

</html>
