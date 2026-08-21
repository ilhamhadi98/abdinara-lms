<x-app-layout>
    @php
        $catSlug = \Illuminate\Support\Str::slug($category->name);
        $subSlug = \Illuminate\Support\Str::slug($subtopic->name);
        $pageTitle = "Contoh Soal {$subtopic->name} ({$category->name}) SKD CPNS & Kedinasan 2026";
        $pageDesc = "Kumpulan contoh soal latihan {$subtopic->name} materi {$category->name} SKD CPNS & Sekolah Kedinasan beserta kunci jawaban dan pembahasan lengkap HOTS di Abdinara.id.";
    @endphp

    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center py-2 gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/latihan-soal') }}" class="text-decoration-none text-secondary">Latihan Soal</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/latihan-soal/' . $catSlug) }}" class="text-decoration-none text-secondary">{{ $category->name }}</a></li>
                        <li class="breadcrumb-item active text-body" aria-current="page">{{ $subtopic->name }}</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-body mb-0">{{ $subtopic->name }} - {{ $category->name }}</h3>
            </div>
            <div>
                <a href="{{ route('tryout.index') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-play-circle-fill me-1"></i> Simulasi CAT Lengkap
                </a>
            </div>
        </div>
    </x-slot>

    @push('styles')
        <title>{{ $pageTitle }} - Abdinara.id</title>
        <meta name="description" content="{{ $pageDesc }}">
        <meta name="keywords" content="soal {{ strtolower($subtopic->name) }}, contoh soal {{ strtolower($category->name) }}, pembahasan soal cpns, kedinasan 2026, abdinara">
        <link rel="canonical" href="{{ url('/latihan-soal/' . $catSlug . '/' . $subSlug) }}">

        <!-- OpenGraph & Twitter Cards for Social Media Sharing -->
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $pageDesc }}">
        <meta property="og:url" content="{{ url('/latihan-soal/' . $catSlug . '/' . $subSlug) }}">
        <meta property="og:type" content="article">

        <!-- Google Schema.org JSON-LD FAQPage for Google Rich Snippets -->
        @php
            $schemaData = [
                "@context" => "https://schema.org",
                "@type" => "FAQPage",
                "mainEntity" => $questions->map(function ($q) {
                    $ansKey = 'option_' . strtolower($q->correct_answer);
                    $ansText = $q->$ansKey ?? '';
                    $cleanExp = strip_tags($q->explanation ?? 'Kunci: ' . $q->correct_answer . '. ' . $ansText);
                    return [
                        "@type" => "Question",
                        "name" => \Illuminate\Support\Str::limit(strip_tags($q->question_text), 150),
                        "acceptedAnswer" => [
                            "@type" => "Answer",
                            "text" => 'Jawaban: ' . $q->correct_answer . '. ' . $ansText . ' | Pembahasan: ' . $cleanExp,
                        ],
                    ];
                })->values()->all(),
            ];
        @endphp
        <script type="application/ld+json">
        {!! json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush

    <div class="container py-4 mb-5">
        <!-- Subtopic Header Box -->
        <div class="card border-0 rounded-4 shadow-sm mb-5 p-4 p-md-5 bg-body-tertiary">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-primary text-white rounded-pill px-3 py-2 fw-bold">
                            {{ $category->name }}
                        </span>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-semibold">
                            <i class="bi bi-patch-check-fill me-1"></i> Materi Resmi CASN / Kedinasan
                        </span>
                    </div>
                    <h1 class="display-6 fw-bold text-body mb-3">
                        Kumpulan Contoh Soal {{ $subtopic->name }} dan Pembahasan
                    </h1>
                    <p class="text-secondary fs-5 mb-0" style="line-height: 1.6;">
                        Uji pemahamanmu pada materi <strong>{{ $subtopic->name }}</strong> di bawah ini. Klik salah satu opsi jawaban untuk langsung melihat kunci jawaban dan pembahasan analitisnya secara interaktif.
                    </p>
                </div>
            </div>
        </div>

        <!-- Interactive Questions Section -->
        <div class="row">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-body mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-card-checklist text-primary"></i> Latihan Soal Interaktif (Gratis)
                    </h4>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 small">
                        {{ count($questions) }} Soal Contoh
                    </span>
                </div>

                @forelse ($questions as $i => $q)
                    @php
                        $correctOpt = $q->correct_answer;
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" 
                         x-data="{ 
                             selected: null, 
                             revealed: false, 
                             correct: '{{ $correctOpt }}',
                             choose(opt) {
                                 this.selected = opt;
                                 this.revealed = true;
                             }
                         }">
                        <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-secondary">Soal No. {{ $i + 1 }}</span>
                            <div x-show="revealed" x-cloak>
                                <template x-if="selected === correct">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                                        <i class="bi bi-check-circle-fill me-1"></i> Jawaban Anda Benar!
                                    </span>
                                </template>
                                <template x-if="selected !== correct">
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                                        <i class="bi bi-x-circle-fill me-1"></i> Jawaban Kurang Tepat
                                    </span>
                                </template>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Question Text -->
                            <div class="fs-5 text-body mb-4" style="line-height: 1.6;">
                                {!! nl2br(e($q->question_text)) !!}
                            </div>

                            @if ($q->image)
                                <div class="mb-4 text-center">
                                    <img src="{{ asset('storage/' . $q->image) }}" alt="Ilustrasi Soal {{ $i + 1 }}" class="img-fluid rounded-3 border shadow-sm" style="max-height: 300px;">
                                </div>
                            @endif

                            <!-- Options List (Interactive) -->
                            <div class="d-flex flex-column gap-3 mb-4">
                                @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                                    @php
                                        $propName = 'option_' . strtolower($opt);
                                        $optText = $q->$propName;
                                    @endphp
                                    @if ($optText)
                                        <button type="button" 
                                                @click="choose('{{ $opt }}')"
                                                class="btn text-start p-3 rounded-3 border d-flex align-items-start gap-3 transition-all"
                                                :class="{
                                                    'btn-light text-body border-light': !revealed,
                                                    'border-success bg-success bg-opacity-10 text-success fw-bold': revealed && ('{{ $opt }}' === correct),
                                                    'border-danger bg-danger bg-opacity-10 text-danger': revealed && (selected === '{{ $opt }}' && selected !== correct),
                                                    'btn-light opacity-50': revealed && ('{{ $opt }}' !== correct && selected !== '{{ $opt }}')
                                                }">
                                            <span class="badge rounded-circle d-flex align-items-center justify-content-center fw-bold mt-1"
                                                  :class="{
                                                      'bg-primary text-white': !revealed && (selected !== '{{ $opt }}'),
                                                      'bg-success text-white': revealed && ('{{ $opt }}' === correct),
                                                      'bg-danger text-white': revealed && (selected === '{{ $opt }}' && selected !== correct),
                                                      'bg-secondary bg-opacity-25 text-body': revealed && ('{{ $opt }}' !== correct && selected !== '{{ $opt }}')
                                                  }"
                                                  style="width: 28px; height: 28px; flex-shrink: 0;">
                                                {{ $opt }}
                                            </span>
                                            <span class="flex-grow-1 fs-6" style="line-height: 1.5;">{{ $optText }}</span>
                                        </button>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Explanation Box -->
                            <div x-show="revealed" x-cloak class="mt-4" x-transition>
                                <div class="bg-primary bg-opacity-10 p-4 rounded-4 border-start border-primary border-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-primary fw-bold mb-0">
                                            <i class="bi bi-lightbulb-fill me-1"></i> Pembahasan & Kunci Jawaban:
                                        </h6>
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-1 fw-bold">
                                            Kunci: {{ $q->correct_answer }}
                                        </span>
                                    </div>
                                    <div class="text-body" style="line-height: 1.6;">
                                        {!! nl2br(e($q->explanation ?? 'Jawaban yang tepat adalah opsi ' . $q->correct_answer . '.')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
                        <i class="bi bi-info-circle fs-1 mb-3 text-secondary"></i>
                        <h5>Soal untuk subtopik ini sedang diperbarui.</h5>
                    </div>
                @endforelse

                <!-- Bottom CTA -->
                <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-primary text-white text-center mt-5 mb-5 overflow-hidden position-relative">
                    <h3 class="fw-bold mb-3">Mau Latihan 110 Soal CAT Berwaktu Seperti Aslinya?</h3>
                    <p class="fs-5 opacity-75 mb-4 mx-auto" style="max-width: 600px;">
                        Dapatkan akses simulasi CAT lengkap dengan timer ujian BKN, analisis passing grade instan, dan ranking nasional di Abdinara.id.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-warning text-dark btn-lg rounded-pill px-5 fw-bold shadow">
                            Daftar Gratis Sekarang <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar / Sibling Subtopics Navigation -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 sticky-top" style="top: 90px; z-index: 10;">
                    <h5 class="fw-bold text-body mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-bookmark-star text-primary"></i> Subtopik {{ $category->name }} Lainnya
                    </h5>
                    <p class="text-secondary small mb-3">
                        Pelajari materi dan latihan soal lainnya dalam bidang {{ $category->name }}:
                    </p>

                    <div class="list-group list-group-flush mb-4">
                        @foreach ($siblingSubtopics as $sibling)
                            @php $sibSlug = \Illuminate\Support\Str::slug($sibling->name); @endphp
                            <a href="{{ url('/latihan-soal/' . $catSlug . '/' . $sibSlug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-0 bg-transparent border-bottom border-light">
                                <span class="text-body fw-medium small">
                                    <i class="bi bi-arrow-right-short text-primary fs-5"></i> {{ $sibling->name }}
                                </span>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill small">
                                    {{ $sibling->questions_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>

                    <a href="{{ url('/latihan-soal/' . $catSlug) }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold small">
                        Semua Materi {{ $category->name }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>