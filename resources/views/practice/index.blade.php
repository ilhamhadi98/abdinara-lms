<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center py-2 gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
                        <li class="breadcrumb-item active text-body" aria-current="page">Latihan Soal & Kisi-Kisi SKD</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-body mb-0">Bank Latihan Soal & Kisi-Kisi SKD CPNS / Kedinasan</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('tryout.index') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-play-circle-fill me-1"></i> Simulasi CAT Lengkap
                </a>
            </div>
        </div>
    </x-slot>

    @section('has_custom_title', true)
    @push('meta')
        <title>Bank Latihan Soal CPNS 2026 & Kisi-Kisi SKD Kedinasan Gratis - Abdinara.id</title>
        <meta name="title" content="Bank Latihan Soal CPNS 2026 & Kisi-Kisi SKD Kedinasan Gratis - Abdinara.id">
        <meta name="description" content="Kumpulan bank latihan soal SKD CPNS dan Sekolah Kedinasan terlengkap 2026 (TWK, TIU, TKP) dengan kunci jawaban dan pembahasan lengkap sesuai kisi-kisi resmi Permenpan-RB di Abdinara.id.">
        <meta name="keywords" content="latihan soal cpns 2026, latihan cpns, soal kedinasan, simulasi cat gratis, soal twk hots, soal tiu deret angka, soal tkp pelayanan publik, abdinara">
        <link rel="canonical" href="https://cat.abdinara.id/latihan-soal">
        <meta property="og:title" content="Bank Latihan Soal CPNS 2026 & Kisi-Kisi SKD Kedinasan Gratis - Abdinara.id">
        <meta property="og:description" content="Bank latihan soal SKD CPNS terlengkap (TWK, TIU, TKP) dengan kunci jawaban dan pembahasan analitis.">
        <meta property="og:url" content="https://cat.abdinara.id/latihan-soal">
        <meta property="og:type" content="website">
    @endpush

    <div class="container py-4 mb-5">
        <!-- Hero SEO Banner -->
        <div class="card border-0 rounded-4 shadow-sm mb-5 text-white overflow-hidden" style="background: linear-gradient(135deg, #0a2647 0%, #144272 60%, #1e3a8a 100%);">
            <div class="card-body p-4 p-md-5 position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3 fs-6">
                            <i class="bi bi-stars me-1"></i> Materi Resmi Sesuai Permenpan-RB 2026
                        </span>
                        <h1 class="display-6 fw-bold mb-3 text-white">Siapkan Dirimu Lolos Passing Grade SKD CASN & Kedinasan</h1>
                        <p class="fs-5 opacity-75 mb-4" style="max-width: 650px;">
                            Pelajari kisi-kisi dan uji pemahamanmu dengan ribuan kumpulan contoh soal SKD berkualitas tinggi lengkap dengan pembahasan analitis.
                        </p>
                        <div class="d-flex flex-wrap gap-4 pt-2 border-top border-white border-opacity-10">
                            <div>
                                <h3 class="fw-bold mb-0 text-warning">{{ $totalQuestions }}+</h3>
                                <small class="opacity-75">Bank Soal Terupdate</small>
                            </div>
                            <div class="border-start border-white border-opacity-10 ps-4">
                                <h3 class="fw-bold mb-0 text-warning">{{ $totalSubtopics }}</h3>
                                <small class="opacity-75">Subtopik Materi</small>
                            </div>
                            <div class="border-start border-white border-opacity-10 ps-4">
                                <h3 class="fw-bold mb-0 text-warning">3 Bidang</h3>
                                <small class="opacity-75">TWK, TIU, & TKP</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories & Subtopics Grid -->
        <h4 class="fw-bold text-body mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-grid-fill text-primary"></i> Pilih Kategori Tes SKD
        </h4>

        <div class="row g-4 mb-5">
            @foreach ($categories as $category)
                @php
                    $catSlug = \Illuminate\Support\Str::slug($category->name);
                    $icon = match(strtoupper($category->name)) {
                        'TWK' => 'bi-flag-fill text-danger',
                        'TIU' => 'bi-cpu-fill text-primary',
                        'TKP' => 'bi-people-fill text-success',
                        default => 'bi-journal-bookmark-fill text-warning'
                    };
                    $desc = match(strtoupper($category->name)) {
                        'TWK' => 'Tes Wawasan Kebangsaan menguji penguasaan nilai nasionalisme, integritas, bela negara, pilar negara, dan bahasa Indonesia.',
                        'TIU' => 'Tes Inteligensia Umum menguji kemampuan verbal (analogi, silogisme), numerik (deret angka, berhitung), dan logika figural.',
                        'TKP' => 'Tes Karakteristik Pribadi menguji integritas pelayanan publik, jejaring kerja, sosio-kultural, TIK, dan anti radikalisme.',
                        default => 'Kumpulan bank soal dan materi latihan persiapan ujian kedinasan dan CASN.'
                    };
                @endphp
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 transition-hover d-flex flex-column">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-3 rounded-4 bg-body-tertiary fs-3">
                                <i class="bi {{ $icon }}"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-body mb-0">{{ $category->name }}</h4>
                                <small class="text-secondary">{{ $category->subtopics_count }} Subtopik Materi</small>
                            </div>
                        </div>

                        <p class="text-secondary small mb-4" style="line-height: 1.6;">
                            {{ $desc }}
                        </p>

                        <h6 class="fw-bold text-body-secondary small text-uppercase tracking-wider mb-3">Daftar Subtopik Materi:</h6>
                        <div class="list-group list-group-flush mb-4 flex-grow-1">
                            @foreach ($category->subtopics as $subtopic)
                                @php $subSlug = \Illuminate\Support\Str::slug($subtopic->name); @endphp
                                <a href="{{ url('/latihan-soal/' . $catSlug . '/' . $subSlug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-0 bg-transparent border-bottom border-light">
                                    <span class="text-body fw-medium small">
                                        <i class="bi bi-chevron-right text-secondary small me-1"></i> {{ $subtopic->name }}
                                    </span>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill small">
                                        {{ $subtopic->questions_count }} Soal
                                    </span>
                                </a>
                            @endforeach
                        </div>

                        <a href="{{ url('/latihan-soal/' . $catSlug) }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold">
                            Lihat Materi {{ $category->name }} <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Conversion Lead CTA Box -->
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-body-tertiary text-center">
            <div class="mx-auto" style="max-width: 700px;">
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold mb-3 fs-6">
                    <i class="bi bi-check-circle-fill me-1"></i> Simulasi Asli Mirip BKN
                </span>
                <h2 class="fw-bold text-body mb-3">Siap Menghadapi Sistem Ujian CAT Asli?</h2>
                <p class="text-secondary fs-5 mb-4">
                    Jangan hanya latihan soal per materi. Uji ketahanan fisik dan mentalmu dalam simulasi CAT 110 soal berwaktu 100 menit dengan sistem passing grade otomatis dan leaderboard nasional di Abdinara.id.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">
                        Daftar Akun Gratis <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="{{ route('tryout.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4 fw-bold">
                        Jelajahi Paket Tryout
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>