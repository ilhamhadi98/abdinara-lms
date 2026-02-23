<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Abdinara.id</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="lms-body">
    <header class="site-header">
        <div class="container nav-wrap">
            <a class="brand" href="#beranda">Abdi<span>nara</span>.id</a>
            <nav class="main-nav">
                <a href="#beranda">Beranda</a>
                <a href="#tentang">Tentang</a>
                <a href="#program">Program</a>
                <a href="#keunggulan">Keunggulan</a>
                <a href="#kontak">Kontak</a>
            </nav>
            <div class="auth-actions">
                @auth
                    <a class="btn btn-ghost" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a class="btn btn-ghost" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-primary" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        <section id="beranda" class="hero">
            <div class="container hero-grid">
                <div>
                    <p class="eyebrow">LMS Starter</p>
                    <h1>Wujudkan Mimpi Mengabdi pada Negara</h1>
                    <p class="lead">Platform persiapan seleksi TNI, Polri, Sekolah Kedinasan, dan CPNS dengan
                        pengalaman belajar terstruktur.</p>
                    <div class="hero-actions">
                        <a href="#program" class="btn btn-primary">Lihat Program</a>
                        <a href="#kontak" class="btn btn-ghost">Konsultasi Gratis</a>
                    </div>
                    <div class="stats">
                        <div><strong>98%</strong><span>Tingkat Kelulusan</span></div>
                        <div><strong>5000+</strong><span>Alumni Sukses</span></div>
                    </div>
                </div>
                <div class="hero-card">
                    <h3>Alur Belajar LMS</h3>
                    <ul>
                        <li>1. Daftar akun dan pilih jalur seleksi</li>
                        <li>2. Ikuti modul, video, dan bank soal</li>
                        <li>3. Pantau skor simulasi CAT dan ranking</li>
                        <li>4. Konsultasi progres bersama mentor</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-block">Mulai Sekarang</a>
                </div>
            </div>
        </section>

        <section id="tentang" class="section">
            <div class="container two-col">
                <div>
                    <h2>Tentang Abdinara.id</h2>
                    <p>Abdinara.id membantu putra-putri terbaik bangsa mempersiapkan diri menuju karir pengabdian
                        melalui pembelajaran akademik, mental, dan strategi ujian.</p>
                    <p>Layout ini disiapkan sebagai fondasi LMS, sehingga tahap berikutnya bisa langsung fokus ke modul
                        kelas, quiz, jadwal, dan progres siswa.</p>
                </div>

            </div>
        </section>

        <section id="program" class="section section-soft">
            <div class="container">
                <h2>Program Unggulan</h2>
                <p class="sub">Meniru struktur situs utama: program utama CAT + program tambahan.</p>
                <div class="cards">
                    <article class="card card-featured">
                        <p class="badge">Unggulan</p>
                        <h3>Sistem CAT Terintegrasi</h3>
                        <p>Simulasi tes berbasis komputer dengan bank soal terbarui, analitik realtime, dan target skor
                            terukur.</p>
                        <ul>
                            <li>TWK, TIU, TKP, TPA, Psikotes</li>
                            <li>Pembahasan detail per sesi</li>
                            <li>Laporan performa mingguan</li>
                        </ul>
                    </article>
                    <article class="card">
                        <h3>Kelas Interaktif Online</h3>
                        <p>Sesi live bersama mentor untuk bedah soal, strategi, dan evaluasi berkala.</p>
                    </article>
                    <article class="card">
                        <h3>Kelas Offline Intensif</h3>
                        <p>Program tatap muka untuk pendampingan menyeluruh akademik hingga kesiapan tes fisik.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="keunggulan" class="section">
            <div class="container">
                <h2>Kenapa Memilih Kami?</h2>
                <div class="cards three">
                    <article class="card">
                        <h3>Integritas Terjamin</h3>
                        <p>Materi selaras dengan kisi-kisi resmi dan pendekatan belajar yang bertanggung jawab.</p>
                    </article>
                    <article class="card">
                        <h3>Keseimbangan Belajar</h3>
                        <p>Persiapan akademik dan latihan fisik dirancang berjalan seimbang.</p>
                    </article>
                    <article class="card">
                        <h3>Pendampingan Total</h3>
                        <p>Dari pendaftaran hingga simulasi akhir, peserta tetap didampingi.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="kontak" class="section cta">
            <div class="container cta-wrap">
                <h2>Siap Menjadi Abdi Negara?</h2>
                <p>Daftar akun untuk mulai akses LMS dan pantau perkembangan belajar dari dashboard pribadi.</p>
                <div class="hero-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Masuk Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar Sekarang</a>
                        <a href="{{ route('login') }}" class="btn btn-ghost btn-light">Saya Sudah Punya Akun</a>
                    @endauth
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-wrap">
            <p>@ {{ date('Y') }} Abdinara LMS</p>
            <p>Starter layout inspired by abdinara.id</p>
        </div>
    </footer>
    <x-pwa-install-prompt />
</body>

</html>
