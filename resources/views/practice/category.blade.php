<x-app-layout>
    @php
        $catSlug = \Illuminate\Support\Str::slug($category->name);
        $catName = strtoupper($category->name);
        $catTitle = match($catName) {
            'TWK' => 'Tes Wawasan Kebangsaan (TWK)',
            'TIU' => 'Tes Inteligensia Umum (TIU)',
            'TKP' => 'Tes Karakteristik Pribadi (TKP)',
            default => $category->name
        };
    @endphp

    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center py-2 gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/latihan-soal') }}" class="text-decoration-none text-secondary">Latihan Soal</a></li>
                        <li class="breadcrumb-item active text-body" aria-current="page">{{ $catName }}</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-body mb-0">{{ $catTitle }}</h3>
            </div>
            <div>
                <a href="{{ route('tryout.index') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-play-circle-fill me-1"></i> Simulasi CAT Lengkap
                </a>
            </div>
        </div>
    </x-slot>

    @push('styles')
        <title>Kumpulan Soal {{ $catTitle }} SKD CPNS & Kedinasan 2026 - Abdinara.id</title>
        <meta name="description" content="Kumpulan contoh latihan soal {{ $catTitle }} terlengkap beserta kisi-kisi resmi dan pembahasan kunci jawaban. Latihan gratis persiapan ujian CPNS dan Sekolah Kedinasan di Abdinara.id.">
        <link rel="canonical" href="{{ url('/latihan-soal/' . $catSlug) }}">
    @endpush

    <div class="container py-4 mb-5">
        <!-- Category Intro Banner -->
        <div class="card border-0 rounded-4 shadow-sm mb-5 p-4 p-md-5 bg-body-tertiary">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill fw-bold mb-3">
                        <i class="bi bi-bookmark-check me-1"></i> Kisi-Kisi SKD Terstandar
                    </span>
                    <h1 class="display-6 fw-bold text-body mb-3">Materi & Kumpulan Soal {{ $catTitle }}</h1>
                    <p class="text-secondary fs-5 mb-0" style="line-height: 1.6;">
                        Pilih subtopik di bawah ini untuk mulai mengerjakan latihan soal interaktif, mempelajari konsep kunci, dan menguasai tipe soal HOTS (Higher Order Thinking Skills).
                    </p>
                </div>
            </div>
        </div>

        <!-- Subtopics Grid -->
        <h4 class="fw-bold text-body mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-layers-fill text-primary"></i> Subtopik Materi {{ $category->name }}
        </h4>

        <div class="row g-4 mb-5">
            @foreach ($category->subtopics as $subtopic)
                @php $subSlug = \Illuminate\Support\Str::slug($subtopic->name); @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 transition-hover d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-semibold">
                                {{ $category->name }}
                            </span>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 small">
                                {{ $subtopic->questions_count }} Soal
                            </span>
                        </div>

                        <h5 class="fw-bold text-body mb-2">{{ $subtopic->name }}</h5>
                        <p class="text-secondary small mb-4 flex-grow-1">
                            Latihan soal dan pembahasan analitis materi {{ $subtopic->name }} untuk memperdalam pemahaman konsep tes.
                        </p>

                        <a href="{{ url('/latihan-soal/' . $catSlug . '/' . $subSlug) }}" class="btn btn-primary rounded-pill w-100 fw-bold shadow-sm">
                            Buka Soal & Pembahasan <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Other Categories Navigation -->
        @if ($otherCategories->isNotEmpty())
            <div class="border-top pt-5 mb-5">
                <h5 class="fw-bold text-body mb-4">Pelajari Kategori Tes Lainnya:</h5>
                <div class="row g-3">
                    @foreach ($otherCategories as $other)
                        @php $otherSlug = \Illuminate\Support\Str::slug($other->name); @endphp
                        <div class="col-md-6">
                            <a href="{{ url('/latihan-soal/' . $otherSlug) }}" class="card border-0 shadow-sm rounded-4 p-4 text-decoration-none transition-hover h-100 d-flex flex-row justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold text-body mb-1">{{ $other->name }}</h5>
                                    <p class="text-secondary small mb-0">{{ $other->subtopics_count }} Subtopik Materi Pembelajaran</p>
                                </div>
                                <i class="bi bi-arrow-right-circle-fill fs-2 text-primary"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Conversion Lead CTA Box -->
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-body-tertiary text-center">
            <div class="mx-auto" style="max-width: 680px;">
                <h3 class="fw-bold text-body mb-3">Siap Uji Kemampuan dengan Timer Asli?</h3>
                <p class="text-secondary mb-4">
                    Tingkatkan kecepatan dan ketepatan menjawabmu melalui simulasi CAT lengkap 110 butir soal di Abdinara.id.
                </p>
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">
                    Daftar Akun & Mulai Tryout <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>