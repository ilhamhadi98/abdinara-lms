<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <!-- Primary SEO Meta Tags -->
    <title>Latihan Soal CPNS 2026 & Simulasi Tryout CAT SKD Kedinasan Online Gratis | Abdinara.id</title>
    <meta name="title" content="Latihan Soal CPNS 2026 & Simulasi Tryout CAT SKD Kedinasan Online Gratis | Abdinara.id">
    <meta name="description" content="Platform latihan soal CPNS 2026 dan simulasi tryout CAT SKD Kedinasan (IPDN, STAN, STIS, Poltekip, Poltekim) online gratis terlengkap. Ribuan bank soal TWK, TIU, TKP sesuai kisi-kisi resmi BKN & Permenpan-RB 2026 dilengkapi pembahasan analitis, ranking nasional, duel 1v1, dan radar evaluasi AI.">
    <meta name="keywords" content="latihan soal cpns 2026, latihan cpns, tryout cpns online, simulasi cat bkn, tryout skd kedinasan, soal twk hots, soal tiu pecahan deret, soal tkp passing grade, bimbel cpns online gratis, bank soal skd bkn, abdinara">
    <meta name="author" content="Abdinara.id">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="https://cat.abdinara.id/">

    @if(config('services.google.site_verification'))
        <meta name="google-site-verification" content="{{ config('services.google.site_verification') }}">
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://cat.abdinara.id/">
    <meta property="og:title" content="Latihan Soal CPNS 2026 & Simulasi Tryout CAT SKD Kedinasan Online Gratis | Abdinara.id">
    <meta property="og:description" content="Akses ribuan latihan soal TWK, TIU, TKP berstandar CAT BKN 2026 gratis beserta kunci jawaban dan pembahasan analitis di Abdinara LMS.">
    <meta property="og:image" content="{{ asset('images/og-banner.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Abdinara LMS">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://cat.abdinara.id/">
    <meta name="twitter:title" content="Latihan Soal CPNS 2026 & Simulasi Tryout CAT SKD Kedinasan Online Gratis | Abdinara.id">
    <meta name="twitter:description" content="Platform latihan soal dan simulasi CAT SKD CPNS 2026 terlengkap sesuai kisi-kisi resmi Permenpan-RB.">
    <meta name="twitter:image" content="{{ asset('images/og-banner.png') }}">

    @if(config('services.google.gtag_id'))
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.gtag_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('services.google.gtag_id') }}');
        </script>
    @endif

    <!-- Favicon & PWA -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icon-192.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icon-192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Fonts & CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Structured Data: WebSite, Organization & FAQPage Schema -->
    @php
        $structuredData = [
            "@context" => "https://schema.org",
            "@graph" => [
                [
                    "@type" => "WebSite",
                    "@id" => "https://cat.abdinara.id/#website",
                    "url" => "https://cat.abdinara.id/",
                    "name" => "Abdinara.id - Latihan Soal CPNS & Tryout CAT SKD",
                    "description" => "Platform persiapan seleksi CPNS dan Sekolah Kedinasan dengan bank latihan soal dan simulasi CAT BKN terintegrasi.",
                    "potentialAction" => [
                        "@type" => "SearchAction",
                        "target" => "https://cat.abdinara.id/latihan-soal?q={search_term_string}",
                        "query-input" => "required name=search_term_string",
                    ],
                ],
                [
                    "@type" => "EducationalOrganization",
                    "@id" => "https://cat.abdinara.id/#organization",
                    "name" => "Abdinara LMS",
                    "url" => "https://cat.abdinara.id/",
                    "logo" => "https://cat.abdinara.id/favicon.ico",
                    "sameAs" => [
                        "https://instagram.com/abdinara.id",
                        "https://tiktok.com/@abdinara.id",
                    ],
                ],
                [
                    "@type" => "FAQPage",
                    "@id" => "https://cat.abdinara.id/#faq",
                    "mainEntity" => [
                        [
                            "@type" => "Question",
                            "name" => "Apakah latihan soal CPNS dan Kedinasan di Abdinara.id gratis?",
                            "acceptedAnswer" => [
                                "@type" => "Answer",
                                "text" => "Ya! Anda dapat mengakses ribuan bank latihan soal TWK, TIU, dan TKP secara gratis tanpa batas di menu Latihan Soal Publik Abdinara.id.",
                            ],
                        ],
                        [
                            "@type" => "Question",
                            "name" => "Apakah format ujian di Abdinara sesuai dengan sistem CAT BKN resmi?",
                            "acceptedAnswer" => [
                                "@type" => "Answer",
                                "text" => "Tentu saja. Sistem simulasi CAT di Abdinara dirancang 100% presisi mengikuti mekanisme resmi CAT BKN, mencakup durasi waktu 100 menit untuk 110 butir soal, pembobotan nilai SKD, dan passing grade resmi Permenpan-RB 2026.",
                            ],
                        ],
                        [
                            "@type" => "Question",
                            "name" => "Apa saja materi yang diujikan dalam Seleksi Kompetensi Dasar (SKD)?",
                            "acceptedAnswer" => [
                                "@type" => "Answer",
                                "text" => "Materi SKD terdiri dari 3 bidang utama: 1) Tes Wawasan Kebangsaan (TWK) meliputi Nasionalisme, Integritas, Bela Negara, Pilar Negara, Bahasa Indonesia; 2) Tes Intelegensia Umum (TIU) meliputi Kemampuan Verbal, Numerik/Hitung Cepat, Deret Angka, Figural; 3) Tes Karakteristik Pribadi (TKP) meliputi Pelayanan Publik, Jejaring Kerja, Sosial Budaya, TIK, Profesionalisme, dan Anti Radikalisme.",
                            ],
                        ],
                        [
                            "@type" => "Question",
                            "name" => "Bagaimana fitur AI Diagnostik & Radar Kelemahan di Abdinara membantu peserta?",
                            "acceptedAnswer" => [
                                "@type" => "Answer",
                                "text" => "AI Abdinara secara otomatis menganalisis riwayat pengerjaan soal dan memetakan subtopik mana yang sudah Anda kuasai dan subtopik mana yang menjadi titik lemah Anda, lengkap dengan rekomendasi latihan terarah.",
                            ],
                        ],
                    ],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
</head>

<body class="lms-body">
    <!-- Clean & Modern Header -->
    <header class="site-header sticky-top bg-white border-bottom shadow-sm">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            <a class="brand text-decoration-none d-flex align-items-center gap-1" href="{{ url('/') }}">
                <span class="fs-4 fw-bold text-dark">Abdi<span class="text-warning">nara</span><span class="text-primary fs-6">.id</span></span>
            </a>

            <!-- Desktop Nav Menu -->
            <nav class="d-none d-lg-flex align-items-center gap-3">
                <a href="{{ url('/') }}" class="text-secondary text-decoration-none fw-semibold px-2 py-1">Beranda</a>

                <!-- Dropdown Materi & Latihan -->
                <div class="dropdown">
                    <a class="text-secondary text-decoration-none fw-semibold dropdown-toggle d-flex align-items-center gap-1 px-2 py-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>Latihan Soal</span>
                    </a>
                    <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2 mt-2" style="min-width: 270px;">
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('practice.index') }}">
                                <i class="bi bi-journal-text text-primary fs-5"></i>
                                <div>
                                    <strong class="d-block text-body">Bank Latihan Soal</strong>
                                    <small class="text-secondary">Ribuan soal TWK, TIU, TKP</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('practice.calculator') }}">
                                <i class="bi bi-calculator text-success fs-5"></i>
                                <div>
                                    <strong class="d-block text-body">🧮 Kalkulator SKD</strong>
                                    <small class="text-secondary">Hitung skor & passing grade BKN</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('practice.kisi-kisi') }}">
                                <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
                                <div>
                                    <strong class="d-block text-body">📜 Kisi-Kisi Resmi 2026</strong>
                                    <small class="text-secondary">Panduan materi & cetak ringkasan</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Dropdown Kompetisi -->
                <div class="dropdown">
                    <a class="text-secondary text-decoration-none fw-semibold dropdown-toggle d-flex align-items-center gap-1 px-2 py-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>Kompetisi</span>
                    </a>
                    <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2 mt-2" style="min-width: 260px;">
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center justify-content-between" href="{{ route('tournament.index') }}">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-trophy-fill text-warning fs-5"></i>
                                    <div>
                                        <strong class="d-block text-body">🏆 Liga Tryout</strong>
                                        <small class="text-secondary">Kompetisi mingguan nasional</small>
                                    </div>
                                </div>
                                <span class="badge bg-danger rounded-pill">Event</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center justify-content-between" href="{{ route('battle.index') }}">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-lightning-charge-fill text-danger fs-5"></i>
                                    <div>
                                        <strong class="d-block text-body">⚔️ Duel 1 vs 1</strong>
                                        <small class="text-secondary">Tanding 10 soal lawan teman</small>
                                    </div>
                                </div>
                                <span class="badge bg-warning text-dark rounded-pill">Duel</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <a href="#keunggulan" class="text-secondary text-decoration-none fw-semibold px-2 py-1">Keunggulan</a>
                <a href="#faq" class="text-secondary text-decoration-none fw-semibold px-2 py-1">FAQ</a>
            </nav>

            <!-- Auth Actions -->
            <div class="d-flex align-items-center gap-2">
                @auth
                    <a class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-fill me-1"></i> Dashboard
                    </a>
                @else
                    <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="{{ route('login') }}">Masuk</a>
                    <a class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" href="{{ route('register') }}">Daftar Gratis</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        <!-- HERO SECTION: High-Intent SEO Keyword Rich Banner -->
        <section id="beranda" class="hero py-5" style="background: linear-gradient(135deg, #0a2647 0%, #144272 60%, #1e3a8a 100%);">
            <div class="container py-4">
                <div class="row align-items-center g-5">
                    <div class="col-lg-7 text-white">
                        <div class="d-inline-flex align-items-center gap-2 bg-dark bg-opacity-50 border border-warning border-opacity-50 rounded-pill px-3 py-1 mb-3">
                            <span class="badge bg-warning text-dark rounded-pill fw-bold mb-0 px-2 py-1">Update 2026</span>
                            <span class="text-warning small fw-bold">Platform Latihan Soal CPNS & Tryout SKD No. 1</span>
                        </div>
                        
                        <h1 class="display-5 fw-bold text-white mb-3" style="line-height: 1.2;">
                            Latihan Soal CPNS 2026 & Simulasi Tryout CAT SKD Kedinasan Online
                        </h1>
                        
                        <p class="fs-5 opacity-90 mb-4" style="line-height: 1.6; max-width: 620px;">
                            Kuasai materi <strong>TWK, TIU, dan TKP</strong> sesuai standar resmi Permenpan-RB & BKN. Latihan soal gratis, simulasi CAT realistis, diagnosis kelemahan berbasis AI, dan bertanding di Liga Nasional.
                        </p>
                        
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <a href="{{ route('practice.index') }}" class="btn btn-warning text-dark btn-lg rounded-pill px-4 py-3 fw-bold shadow-lg">
                                <i class="bi bi-play-circle-fill me-2"></i> Mulai Latihan Soal Gratis
                            </a>
                            <a href="{{ route('tournament.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 fw-bold">
                                <i class="bi bi-trophy-fill text-warning me-2"></i> Ikuti Liga Mingguan
                            </a>
                        </div>
                        
                        <!-- Quick Stats Counter -->
                        <div class="row g-3 pt-3 border-top border-white border-opacity-10 text-white">
                            <div class="col-4">
                                <h3 class="fw-bolder text-warning mb-0">10.000+</h3>
                                <small class="opacity-75">Bank Soal & Pembahasan</small>
                            </div>
                            <div class="col-4 border-start border-white border-opacity-10 ps-3">
                                <h3 class="fw-bolder text-warning mb-0">98%</h3>
                                <small class="opacity-75">Kepuasan Pejuang</small>
                            </div>
                            <div class="col-4 border-start border-white border-opacity-10 ps-3">
                                <h3 class="fw-bolder text-warning mb-0">100%</h3>
                                <small class="opacity-75">Standar CAT BKN</small>
                            </div>
                        </div>
                    </div>

                    <!-- Right Card: Fast Access -->
                    <div class="col-lg-5">
                        <div class="card border-0 rounded-4 shadow-lg p-4 p-md-5 text-dark" style="background: rgba(255, 255, 255, 0.98);">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold mb-3 d-inline-block">
                                🚀 Akses Cepat Pejuang CPNS
                            </span>
                            <h3 class="fw-bold mb-3 text-body">Siap Lolos Ujian CASN?</h3>
                            <p class="text-secondary small mb-4">Pilih jalur persiapan impianmu untuk mendapatkan materi terarah dan simulasi real-time.</p>

                            <div class="d-flex flex-column gap-2 mb-4">
                                <a href="{{ url('/latihan-soal/twk') }}" class="btn btn-outline-secondary text-start p-3 rounded-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-2"><i class="bi bi-flag-fill"></i></div>
                                        <div>
                                            <strong class="text-body d-block">Tes Wawasan Kebangsaan (TWK)</strong>
                                            <small class="text-secondary">Pilar Negara, Nasionalisme, Bela Negara</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-secondary"></i>
                                </a>

                                <a href="{{ url('/latihan-soal/tiu') }}" class="btn btn-outline-secondary text-start p-3 rounded-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2"><i class="bi bi-calculator-fill"></i></div>
                                        <div>
                                            <strong class="text-body d-block">Tes Intelegensia Umum (TIU)</strong>
                                            <small class="text-secondary">Numerik, Deret Angka, Silogisme, Figural</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-secondary"></i>
                                </a>

                                <a href="{{ url('/latihan-soal/tkp') }}" class="btn btn-outline-secondary text-start p-3 rounded-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-2"><i class="bi bi-people-fill"></i></div>
                                        <div>
                                            <strong class="text-body d-block">Tes Karakteristik Pribadi (TKP)</strong>
                                            <small class="text-secondary">Pelayanan Publik, Anti Radikalisme, TIK</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-secondary"></i>
                                </a>
                            </div>

                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold shadow-sm">
                                Buat Akun & Mulai Belajar Gratis →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 2: Kisi-Kisi Resmi SKD CPNS & Kedinasan 2026 -->
        <section id="kisi-kisi" class="py-5 bg-body-tertiary">
            <div class="container py-4">
                <div class="text-center mb-5">
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold mb-2">
                        KISI-KISI RESMI PERMENPAN-RB 2026
                    </span>
                    <h2 class="display-6 fw-bold text-body">Materi Pokok Seleksi Kompetensi Dasar (SKD)</h2>
                    <p class="text-secondary fs-5 mx-auto" style="max-width: 680px;">
                        Pelajari seluruh subtopik kisi-kisi resmi yang diujikan dalam seleksi CPNS dan Sekolah Kedinasan (IPDN, STAN, STIS, Poltekip, Poltekim, STIN, Kemenhub).
                    </p>
                </div>

                <div class="row g-4">
                    <!-- TWK Card -->
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 p-md-5">
                            <div class="rounded-3 bg-danger bg-opacity-10 text-danger p-3 d-inline-block mb-3" style="width: 54px; height: 54px; text-align: center;">
                                <i class="bi bi-flag-fill fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-body mb-2">Tes Wawasan Kebangsaan (TWK)</h3>
                            <p class="text-secondary mb-4">Menguji penguasaan pengetahuan dan kemampuan mengimplementasikan nilai-nilai kebangsaan Indonesia.</p>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-4 text-secondary small">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Nasionalisme</strong>: Kepentingan nasional & identitas bangsa</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Integritas</strong>: Kejujuran & etika moral pejabat publik</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Bela Negara</strong>: Peran aktif pertahanan kedaulatan negara</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Pilar Negara</strong>: Pancasila, UUD 1945, NKRI, Bhinneka Tunggal Ika</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Bahasa Indonesia</strong>: Penggunaan bahasa resmi negara</li>
                            </ul>
                            <div class="mt-auto">
                                <a href="{{ url('/latihan-soal/twk') }}" class="btn btn-outline-danger rounded-pill w-100 fw-bold">
                                    Latihan Soal TWK Gratis →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- TIU Card -->
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 p-md-5">
                            <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3 d-inline-block mb-3" style="width: 54px; height: 54px; text-align: center;">
                                <i class="bi bi-calculator-fill fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-body mb-2">Tes Intelegensia Umum (TIU)</h3>
                            <p class="text-secondary mb-4">Menguji kemampuan kognitif verbal, numerik, dan penalaran logis figural analitis.</p>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-4 text-secondary small">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Kemampuan Verbal</strong>: Analogi, Silogisme, & Penarikan Kesimpulan</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Kemampuan Numerik</strong>: Berhitung Cepat, Deret Angka, Soal Cerita</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Perbandingan Kuantitatif</strong>: Hubungan variabel x & y</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Kemampuan Figural</strong>: Analogi gambar, Ketidaksamaan, Serial</li>
                            </ul>
                            <div class="mt-auto">
                                <a href="{{ url('/latihan-soal/tiu') }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold">
                                    Latihan Soal TIU Gratis →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- TKP Card -->
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 p-md-5">
                            <div class="rounded-3 bg-success bg-opacity-10 text-success p-3 d-inline-block mb-3" style="width: 54px; height: 54px; text-align: center;">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-body mb-2">Tes Karakteristik Pribadi (TKP)</h3>
                            <p class="text-secondary mb-4">Mengukur kepribadian, integritas pelayanan publik, profesionalisme kerja, dan jejaring sosial.</p>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-4 text-secondary small">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Pelayanan Publik</strong>: Keramahan & kepuasan masyarakat</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Jejaring Kerja</strong>: Kolaborasi tim & komunikasi efektif</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Sosial Budaya</strong>: Adaptasi di tengah keberagaman suku/agama</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Teknologi Informasi (TIK)</strong>: Pemanfaatan digitalisasi kerja</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Anti Radikalisme</strong>: Komitmen pada ideologi Pancasila</li>
                            </ul>
                            <div class="mt-auto">
                                <a href="{{ url('/latihan-soal/tkp') }}" class="btn btn-outline-success rounded-pill w-100 fw-bold">
                                    Latihan Soal TKP Gratis →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 3: Fitur Canggih Abdinara LMS -->
        <section id="keunggulan" class="py-5">
            <div class="container py-4">
                <div class="text-center mb-5">
                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 rounded-pill px-3 py-2 fw-bold mb-2">
                        KENAPA MEMILIH ABDINARA.ID?
                    </span>
                    <h2 class="display-6 fw-bold text-body">Teknologi Pembelajaran Terdepan untuk Kelulusan Anda</h2>
                </div>

                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-laptop fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-body mb-2">Simulasi CAT Presisi</h5>
                            <p class="text-secondary small mb-0">Tampilan antarmuka, navigasi nomor, timer 100 menit, dan sistem scoring persis CAT BKN resmi.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-radar fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-body mb-2">AI Radar Kelemahan</h5>
                            <p class="text-secondary small mb-0">Mendiagnosis akurasi per subtopik secara all-time untuk memandu latihan terarah Anda.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-lightning-charge fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-body mb-2">CAT Battle 1 vs 1</h5>
                            <p class="text-secondary small mb-0">Ajak teman tanding duel 10 soal cepat head-to-head untuk melatih kecepatan dan ketepatan.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-dark p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-award fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-body mb-2">Liga Tryout Nasional</h5>
                            <p class="text-secondary small mb-0">Kompetisi mingguan skala nasional dengan live leaderboard dan E-Sertifikat kelulusan terverifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 4: FAQ -->
        <section id="faq" class="py-5 bg-body-tertiary">
            <div class="container py-4" style="max-width: 900px;">
                <div class="text-center mb-5">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-bold mb-2">
                        FAQ SEPUTAR SELEKSI CPNS & KEDINASAN
                    </span>
                    <h2 class="display-6 fw-bold text-body">Pertanyaan yang Sering Diajukan</h2>
                    <p class="text-secondary">Informasi penting mengenai pendaftaran, materi ujian, dan cara belajar di Abdinara.</p>
                </div>

                <div class="accordion border-0 shadow-sm rounded-4 overflow-hidden" id="faqAccordion">
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold text-body py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Apakah latihan soal di Abdinara.id benar-benar gratis?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary pb-4">
                                Ya, Anda dapat mengakses ratusan bank latihan soal interaktif untuk kategori TWK, TIU, dan TKP secara gratis di menu Latihan Soal. Kami juga menyediakan simulasi tryout gratis dan liga tryout mingguan untuk menguji skor kemampuan Anda.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-body py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Apakah sistem ujian CAT di Abdinara sesuai dengan standar BKN resmi?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary pb-4">
                                Tentu saja. Format simulasi ujian CAT di Abdinara dirancang mengikuti standar resmi CAT BKN (Badan Kepegawaian Negara), mencakup 110 butir soal, alokasi waktu 100 menit, bobot skor (TIU/TWK: Benar 5, Salah 0; TKP: Skala 1-5), serta kalkulasi kelulusan passing grade resmi.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-body py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Bagaimana cara mengikuti Liga Tryout Nasional Mingguan?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary pb-4">
                                Anda cukup mendaftar akun di Abdinara.id, lalu membuka menu <strong>🏆 Liga Tryout</strong>. Kompetisi dibuka setiap minggu dengan peserta dari seluruh Indonesia. Peserta yang lulus passing grade berhak mendapatkan E-Sertifikat resmi berbarcode unik.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-body py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Apa keunggulan fitur AI Radar Kelemahan di Abdinara?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary pb-4">
                                Fitur AI Radar memetakan akurasi jawaban Anda di setiap subtopik secara mendalam. Jika Anda lemah pada materi silogisme atau bela negara, sistem akan langsung memberikan saran tindakan dan tautan materi latihan yang perlu diperdalam.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA SECTION -->
        <section class="py-5" style="background: linear-gradient(135deg, #0a2647 0%, #144272 100%);">
            <div class="container py-5 text-center text-white">
                <h2 class="display-6 fw-bold mb-3">Siap Menjadi Abdi Negara Impian Anda?</h2>
                <p class="fs-5 opacity-75 mb-4 mx-auto" style="max-width: 600px;">
                    Bergabunglah bersama ribuan calon praja, taruna, dan ASN yang mempersiapkan diri secara terstruktur di Abdinara.id.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-warning text-dark btn-lg rounded-pill px-5 fw-bold shadow-lg">
                            Buka Dashboard Pembelajaran →
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-warning text-dark btn-lg rounded-pill px-5 fw-bold shadow-lg">
                            Daftar Sekarang Gratis
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold">
                            Sudah Punya Akun
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </main>

    <!-- Footer with Internal Links -->
    <footer class="site-footer bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <span class="fs-4 fw-bold text-white">Abdi<span class="text-warning">nara</span><span class="text-info fs-6">.id</span></span>
                    <p class="text-secondary small mt-2">
                        Platform persiapan seleksi CASN, CPNS, PPPK, TNI, Polri, dan Sekolah Kedinasan (IPDN, STAN, STIS, Poltekip, Poltekim, STIN) terintegrasi di Indonesia.
                    </p>
                </div>
                <div class="col-lg-3 col-6">
                    <h6 class="fw-bold text-white mb-3">Latihan Soal SKD</h6>
                    <ul class="list-unstyled small text-secondary d-flex flex-column gap-2">
                        <li><a href="{{ url('/latihan-soal/twk') }}" class="text-secondary text-decoration-none hover-white">Latihan Soal TWK</a></li>
                        <li><a href="{{ url('/latihan-soal/tiu') }}" class="text-secondary text-decoration-none hover-white">Latihan Soal TIU</a></li>
                        <li><a href="{{ url('/latihan-soal/tkp') }}" class="text-secondary text-decoration-none hover-white">Latihan Soal TKP</a></li>
                        <li><a href="{{ route('practice.index') }}" class="text-secondary text-decoration-none hover-white">Bank Soal Terlengkap</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-6">
                    <h6 class="fw-bold text-white mb-3">Fitur Unggulan</h6>
                    <ul class="list-unstyled small text-secondary d-flex flex-column gap-2">
                        <li><a href="{{ route('tournament.index') }}" class="text-secondary text-decoration-none hover-white">Liga Tryout Nasional</a></li>
                        <li><a href="{{ route('battle.index') }}" class="text-secondary text-decoration-none hover-white">CAT Battle 1 vs 1</a></li>
                        <li><a href="{{ route('tryout.index') }}" class="text-secondary text-decoration-none hover-white">Simulasi CAT Lengkap</a></li>
                        <li><a href="{{ url('/sitemap.xml') }}" class="text-secondary text-decoration-none hover-white">Peta Situs (Sitemap)</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h6 class="fw-bold text-white mb-3">Legalitas</h6>
                    <p class="text-secondary small mb-0">&copy; {{ date('Y') }} Abdinara.id. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <x-pwa-install-prompt />
</body>

</html>
