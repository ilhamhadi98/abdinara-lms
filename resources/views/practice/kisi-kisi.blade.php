<x-app-layout>
    @section('has_custom_title', true)
    @push('meta')
        <title>Kisi-Kisi Resmi SKD CPNS 2026 & Sekolah Kedinasan PDF Permenpan-RB - Abdinara.id</title>
        <meta name="title" content="Kisi-Kisi Resmi SKD CPNS 2026 & Sekolah Kedinasan PDF Permenpan-RB - Abdinara.id">
        <meta name="description" content="Download dan pelajari kisi-kisi resmi materi pokok SKD CPNS 2026 & Sekolah Kedinasan (TWK, TIU, TKP) sesuai Permenpan-RB. Dilengkapi jumlah butir soal, passing grade, dan contoh latihan gratis.">
        <meta name="keywords" content="kisi kisi cpns 2026 pdf, download kisi kisi skd cpns 2026, materi skd permenpan rb 2026, kisi kisi kedinasan 2026, passing grade skd 2026, abdinara">
        <link rel="canonical" href="https://cat.abdinara.id/kisi-kisi-cpns-2026">

        <meta property="og:title" content="Kisi-Kisi Resmi SKD CPNS 2026 & Sekolah Kedinasan PDF - Abdinara.id">
        <meta property="og:description" content="Panduan materi pokok SKD CPNS & Kedinasan 2026 sesuai ketetapan resmi BKN dan Permenpan-RB.">
        <meta property="og:image" content="{{ asset('images/og-banner.png') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:url" content="https://cat.abdinara.id/kisi-kisi-cpns-2026">
        <meta property="og:type" content="article">

        <!-- Article & FAQ Schema -->
        @php
            $kisiSchema = [
                "@context" => "https://schema.org",
                "@graph" => [
                    [
                        "@type" => "Article",
                        "headline" => "Kisi-Kisi Resmi Materi Pokok SKD CPNS 2026 dan Sekolah Kedinasan",
                        "description" => "Panduan komprehensif materi ujian SKD CPNS mencakup TWK, TIU, dan TKP sesuai regulasi Permenpan-RB 2026.",
                        "author" => [
                            "@type" => "Organization",
                            "name" => "Abdinara.id",
                        ],
                        "publisher" => [
                            "@type" => "Organization",
                            "name" => "Abdinara LMS",
                            "logo" => [
                                "@type" => "ImageObject",
                                "url" => "https://cat.abdinara.id/favicon.ico",
                            ],
                        ],
                    ],
                    [
                        "@type" => "FAQPage",
                        "mainEntity" => [
                            [
                                "@type" => "Question",
                                "name" => "Berapa jumlah butir soal dan waktu ujian SKD CPNS 2026?",
                                "acceptedAnswer" => [
                                    "@type" => "Answer",
                                    "text" => "Ujian SKD terdiri dari 110 butir soal dengan durasi pengerjaan 100 menit (30 butir TWK, 35 butir TIU, dan 45 butir TKP).",
                                ],
                            ],
                            [
                                "@type" => "Question",
                                "name" => "Apakah ada materi Anti Radikalisme di ujian CPNS 2026?",
                                "acceptedAnswer" => [
                                    "@type" => "Answer",
                                    "text" => "Ya, materi Anti Radikalisme diujikan baik pada Tes Karakteristik Pribadi (TKP) maupun Tes Wawasan Kebangsaan (TWK) untuk menjaring ASN yang loyal pada ideologi Pancasila dan NKRI.",
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        @endphp
        <script type="application/ld+json">
        {!! json_encode($kisiSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush

    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center py-2 gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('practice.index') }}" class="text-decoration-none text-secondary">Latihan Soal</a></li>
                        <li class="breadcrumb-item active text-body" aria-current="page">Kisi-Kisi SKD 2026</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-body mb-0">📜 Kisi-Kisi Resmi SKD CPNS & Kedinasan 2026</h3>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm d-print-none">
                    <i class="bi bi-printer-fill me-1"></i> Cetak / Simpan PDF
                </button>
                <a href="{{ route('practice.calculator') }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm d-print-none">
                    <i class="bi bi-calculator-fill me-1"></i> Kalkulator SKD
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container py-4 mb-5">
        <!-- Hero Header -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 p-4 p-md-5 bg-body-tertiary">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill fw-bold mb-3">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Regulasi Resmi BKN & Permenpan-RB 2026
                    </span>
                    <h1 class="display-6 fw-bold text-body mb-3">Panduan Materi Pokok & Kisi-Kisi SKD</h1>
                    <p class="text-secondary fs-5 mb-0" style="line-height: 1.6;">
                        Pelajari rincian lengkap subtopik yang diujikan dalam Seleksi Kompetensi Dasar (SKD) Calon Pegawai Negeri Sipil (CPNS) dan Sekolah Kedinasan (IPDN, STAN, STIS, Poltekip, Poltekim, STIN, Kemenhub).
                    </p>
                </div>
            </div>
        </div>

        <!-- Ketentuan Format Ujian -->
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-4 mb-4">
            <h4 class="fw-bold text-body mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill text-primary"></i> Format & Struktur Penilaian Ujian CAT SKD
            </h4>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold">Komponen Ujian</th>
                            <th class="fw-bold text-center">Jumlah Soal</th>
                            <th class="fw-bold text-center">Sistem Penilaian</th>
                            <th class="fw-bold text-center">Skor Maksimal</th>
                            <th class="fw-bold text-center">Passing Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Tes Wawasan Kebangsaan (TWK)</strong></td>
                            <td class="text-center">30 Soal</td>
                            <td>Benar: +5 Poin, Salah/Kosong: 0 Poin</td>
                            <td class="text-center fw-bold">150</td>
                            <td class="text-center fw-bold text-danger">65</td>
                        </tr>
                        <tr>
                            <td><strong>Tes Intelegensia Umum (TIU)</strong></td>
                            <td class="text-center">35 Soal</td>
                            <td>Benar: +5 Poin, Salah/Kosong: 0 Poin</td>
                            <td class="text-center fw-bold">175</td>
                            <td class="text-center fw-bold text-primary">80</td>
                        </tr>
                        <tr>
                            <td><strong>Tes Karakteristik Pribadi (TKP)</strong></td>
                            <td class="text-center">45 Soal</td>
                            <td>Skala 1 - 5 Poin (Kosong: 0)</td>
                            <td class="text-center fw-bold">225</td>
                            <td class="text-center fw-bold text-success">166</td>
                        </tr>
                        <tr class="table-light fw-bold">
                            <td>TOTAL KUMULATIF</td>
                            <td class="text-center">110 Soal</td>
                            <td>Durasi: 100 Menit</td>
                            <td class="text-center text-primary fs-5">550</td>
                            <td class="text-center text-success fs-5">311</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rincian Kisi-Kisi Per Kategori -->
        <h4 class="fw-bold text-body mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-layers-fill text-primary"></i> Rincian Subtopik Materi Ujian SKD Resmi
        </h4>

        <div class="row g-4 mb-5">
            <!-- TWK Detail -->
            <div class="col-lg-4">
                <div class="card h-100 border-0 rounded-4 shadow-sm p-4 border-top border-danger border-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-2"><i class="bi bi-flag-fill"></i></div>
                        <h5 class="fw-bold text-body mb-0">1. TWK (30 Butir)</h5>
                    </div>
                    <p class="text-secondary small mb-3">Tujuan: Menilai penguasaan wawasan dan implementasi nilai kebangsaan.</p>
                    <ul class="list-unstyled d-flex flex-column gap-2 text-secondary small mb-4">
                        <li><strong>1. Nasionalisme</strong>: Mempertahankan identitas nasional dan integritas bangsa.</li>
                        <li><strong>2. Integritas</strong>: Kejujuran, ketangguhan, dan komitmen etika pejabat negara.</li>
                        <li><strong>3. Bela Negara</strong>: Peran aktif membela kedaulatan NKRI.</li>
                        <li><strong>4. Pilar Negara</strong>: Pancasila, UUD 1945, NKRI, Bhinneka Tunggal Ika.</li>
                        <li><strong>5. Bahasa Indonesia</strong>: Tata bahasa baku dan pemahaman wacana resmi.</li>
                    </ul>
                    <div class="mt-auto d-print-none">
                        <a href="{{ url('/latihan-soal/twk') }}" class="btn btn-outline-danger rounded-pill w-100 fw-bold">
                            Latihan Soal TWK Gratis →
                        </a>
                    </div>
                </div>
            </div>

            <!-- TIU Detail -->
            <div class="col-lg-4">
                <div class="card h-100 border-0 rounded-4 shadow-sm p-4 border-top border-primary border-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2"><i class="bi bi-calculator-fill"></i></div>
                        <h5 class="fw-bold text-body mb-0">2. TIU (35 Butir)</h5>
                    </div>
                    <p class="text-secondary small mb-3">Tujuan: Mengukur kecerdasan kognitif verbal, numerik, dan logika figural.</p>
                    <ul class="list-unstyled d-flex flex-column gap-2 text-secondary small mb-4">
                        <li><strong>1. Kemampuan Verbal</strong>: Analogi kata, Silogisme, dan Penarikan Kesimpulan.</li>
                        <li><strong>2. Kemampuan Numerik</strong>: Berhitung Cepat, Deret Angka, dan Perbandingan Kuantitatif (x & y).</li>
                        <li><strong>3. Penalaran Analitis</strong>: Soal cerita urutan posisi dan jadwal kombinatorik.</li>
                        <li><strong>4. Kemampuan Figural</strong>: Analogi gambar, Ketidaksamaan pola, dan Serial visual.</li>
                    </ul>
                    <div class="mt-auto d-print-none">
                        <a href="{{ url('/latihan-soal/tiu') }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold">
                            Latihan Soal TIU Gratis →
                        </a>
                    </div>
                </div>
            </div>

            <!-- TKP Detail -->
            <div class="col-lg-4">
                <div class="card h-100 border-0 rounded-4 shadow-sm p-4 border-top border-success border-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-2"><i class="bi bi-people-fill"></i></div>
                        <h5 class="fw-bold text-body mb-0">3. TKP (45 Butir)</h5>
                    </div>
                    <p class="text-secondary small mb-3">Tujuan: Menilai integritas pelayanan masyarakat dan karakter profesional.</p>
                    <ul class="list-unstyled d-flex flex-column gap-2 text-secondary small mb-4">
                        <li><strong>1. Pelayanan Publik</strong>: Keramahan dan orientasi kepuasan masyarakat.</li>
                        <li><strong>2. Jejaring Kerja</strong>: Kolaborasi tim lintas divisi dan komunikasi efektif.</li>
                        <li><strong>3. Sosial Budaya</strong>: Adaptasi di lingkungan majemuk dan multikultural.</li>
                        <li><strong>4. Teknologi Informasi (TIK)</strong>: Efisiensi kerja berbasis digitalisasi.</li>
                        <li><strong>5. Profesionalisme</strong>: Tanggung jawab tugas dan integritas kerja.</li>
                        <li><strong>6. Anti Radikalisme</strong>: Komitmen kebangsaan dan penolakan paham radikal.</li>
                    </ul>
                    <div class="mt-auto d-print-none">
                        <a href="{{ url('/latihan-soal/tkp') }}" class="btn btn-outline-success rounded-pill w-100 fw-bold">
                            Latihan Soal TKP Gratis →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Box -->
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 text-center text-white d-print-none" style="background: linear-gradient(135deg, #0a2647 0%, #144272 100%);">
            <h3 class="fw-bold mb-2">Sudah Paham Kisi-Kisi? Saatnya Uji Kemampuan!</h3>
            <p class="opacity-75 mb-4 mx-auto" style="max-width: 600px;">
                Kerjakan simulasi tryout CAT SKD 110 butir soal berstandar BKN dengan radar diagnostik AI di Abdinara.id.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('tryout.index') }}" class="btn btn-warning text-dark btn-lg rounded-pill px-5 fw-bold shadow-sm">
                    Mulai Tryout Lengkap Gratis →
                </a>
            </div>
        </div>
    </div>
</x-app-layout>