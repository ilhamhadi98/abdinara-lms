<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Auth | {{ config('app.name', 'Laravel') }}</title>
    <meta name="theme-color" content="#0a2647">

    <!-- Favicon & PWA Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icon-192.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icon-192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="font-sans text-slate-800 antialiased bg-slate-50 selection:bg-blue-200"
    style="font-family: 'Inter', sans-serif;">
    <div class="min-h-screen flex w-full">

        <!-- Left Panel: Branding & Motivation (Vibrant & Immersive) -->
        <div
            class="hidden lg:flex lg:w-[45%] xl:w-1/2 relative bg-[linear-gradient(135deg,#0a2647_0%,#144272_100%)] text-white overflow-hidden flex-col items-center justify-center p-12">

            <!-- Abstract decorative shapes -->
            <div class="absolute inset-0 overflow-hidden z-0 pointer-events-none">
                <div
                    class="absolute -top-[15%] -left-[10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-br from-blue-400/20 to-indigo-500/0 blur-3xl mix-blend-screen">
                </div>
                <div
                    class="absolute bottom-[0%] -right-[20%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-l from-indigo-400/10 to-transparent blur-3xl mix-blend-screen">
                </div>
            </div>

            <!-- Content Container -->
            <div class="relative z-10 w-full max-w-lg lg:pe-4 xl:pe-12 flex flex-col justify-center h-full">
                <!-- Modern Logo / Text with glowing effect -->
                <a href="{{ url('/') }}"
                    class="inline-flex items-center gap-2 mb-14 drop-shadow-md hover:scale-[1.02] transition-transform duration-300">
                    <span class="text-4xl font-extrabold tracking-tight text-white">
                        Abdi<span class="text-[#d4af37]">nara</span><span
                            class="text-blue-200/90 font-semibold">.id</span>
                    </span>
                </a>

                <h1 class="text-4xl xl:text-5xl font-bold leading-[1.15] mb-6 text-white drop-shadow-sm">
                    Portal LMS untuk pejuang TNI, Polri, dan Kedinasan.
                </h1>

                <p class="text-lg xl:text-xl text-blue-100/90 mb-12 leading-relaxed font-light">
                    Akses kelas, bank soal CAT, progres belajar, dan dashboard performa dalam satu akun.
                </p>

                <ul class="space-y-6 text-blue-50/90">
                    <li class="flex items-center gap-5 group">
                        <div
                            class="flex-shrink-0 w-12 h-12 rounded-2xl bg-white/5 group-hover:bg-white/10 transition-colors flex items-center justify-center border border-white/10 shadow-lg backdrop-blur-md">
                            <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <span class="text-lg font-medium tracking-wide">Modul belajar terstruktur</span>
                    </li>
                    <li class="flex items-center gap-5 group">
                        <div
                            class="flex-shrink-0 w-12 h-12 rounded-2xl bg-white/5 group-hover:bg-white/10 transition-colors flex items-center justify-center border border-white/10 shadow-lg backdrop-blur-md">
                            <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-medium tracking-wide">Simulasi dan evaluasi berkala</span>
                    </li>
                    <li class="flex items-center gap-5 group">
                        <div
                            class="flex-shrink-0 w-12 h-12 rounded-2xl bg-white/5 group-hover:bg-white/10 transition-colors flex items-center justify-center border border-white/10 shadow-lg backdrop-blur-md">
                            <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-medium tracking-wide">Pendampingan progres siswa</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Panel: Form (Clean, Modern, Soft Shadows) -->
        <div class="w-full lg:w-[55%] xl:w-1/2 flex items-center justify-center p-6 sm:p-12 relative bg-slate-50/50">
            <!-- Decorative subtle blobs for the form side -->
            <div
                class="absolute top-0 right-0 w-[400px] h-[400px] rounded-full bg-blue-100/60 blur-[100px] -z-10 pointer-events-none">
            </div>

            <div class="w-full max-w-md relative z-10 mt-6 lg:mt-0">
                <!-- Fallback Logo For Mobile -->
                <div class="lg:hidden text-center mb-10">
                    <a href="{{ url('/') }}"
                        class="inline-block text-3xl font-extrabold tracking-tight text-[#0a2647]">
                        Abdi<span class="text-[#d4af37]">nara</span>.id
                    </a>
                    <p class="text-slate-500 text-sm mt-3 px-4">Portal LMS terpadu TNI, Polri & Kedinasan</p>
                </div>

                <!-- Form Card wrapper (Glassmorphism + soft shadow) -->
                <div
                    class="bg-white/80 backdrop-blur-xl border border-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-[1.5rem] p-8 sm:p-10 w-full">
                    {{ $slot }}
                </div>

                <!-- Footer link if needed -->
                <div class="text-center mt-8 text-slate-400 text-sm font-medium">
                    &copy; {{ date('Y') }} Abdinara.id. All rights reserved.
                </div>
            </div>
        </div>

    </div>
</body>

</html>
