<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Auth | {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="lms-body auth-body">
    <div class="auth-shell">
        <aside class="auth-aside">
            <a class="brand" href="{{ url('/') }}">Abdi<span>nara</span>.id</a>
            <h1>Portal LMS untuk pejuang TNI, Polri, dan Kedinasan.</h1>
            <p>Akses kelas, bank soal CAT, progres belajar, dan dashboard performa dalam satu akun.</p>

            <ul>
                <li>Modul belajar terstruktur</li>
                <li>Simulasi dan evaluasi berkala</li>
                <li>Pendampingan progres siswa</li>
            </ul>
        </aside>

        <main class="auth-main">
            <div class="auth-card">
                {{ $slot }}
            </div>
    </div>
    </div>
</body>

</html>
