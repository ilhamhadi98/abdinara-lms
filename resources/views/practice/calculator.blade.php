<x-app-layout>
    @section('has_custom_title', true)
    @push('meta')
        <title>Kalkulator Skor SKD CPNS 2026 & Simulasi Nilai Passing Grade Online - Abdinara.id</title>
        <meta name="title" content="Kalkulator Skor SKD CPNS 2026 & Simulasi Nilai Passing Grade Online - Abdinara.id">
        <meta name="description" content="Hitung estimasi nilai SKD CPNS 2026 Anda secara online dan akurat sesuai aturan resmi Permenpan-RB (TWK: 65, TIU: 80, TKP: 166). Cek status kelulusan passing grade dan rekomendasi belajar gratis di Abdinara.">
        <meta name="keywords" content="kalkulator skd cpns 2026, hitung nilai passing grade cpns, passing grade skd 2026, simulasi skor skd bkn, nilai aman cpns 2026, abdinara">
        <link rel="canonical" href="https://cat.abdinara.id/kalkulator-skd">

        <meta property="og:title" content="Kalkulator Skor SKD CPNS 2026 & Simulasi Passing Grade - Abdinara.id">
        <meta property="og:description" content="Hitung estimasi skor SKD CPNS 2026 dan cek kelulusan passing grade TWK, TIU, TKP secara instan.">
        <meta property="og:image" content="{{ asset('images/og-banner.png') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:url" content="https://cat.abdinara.id/kalkulator-skd">
        <meta property="og:type" content="website">

        <!-- WebApplication & FAQPage Schema -->
        @php
            $calcSchema = [
                "@context" => "https://schema.org",
                "@graph" => [
                    [
                        "@type" => "WebApplication",
                        "name" => "Kalkulator Nilai SKD CPNS & Passing Grade 2026",
                        "url" => "https://cat.abdinara.id/kalkulator-skd",
                        "applicationCategory" => "EducationalApplication",
                        "operatingSystem" => "All",
                        "description" => "Alat interaktif untuk menghitung skor simulasi ujian SKD CPNS dan Sekolah Kedinasan sesuai ambang batas resmi BKN.",
                    ],
                    [
                        "@type" => "FAQPage",
                        "mainEntity" => [
                            [
                                "@type" => "Question",
                                "name" => "Berapa passing grade resmi SKD CPNS 2026?",
                                "acceptedAnswer" => [
                                    "@type" => "Answer",
                                    "text" => "Berdasarkan Permenpan-RB, nilai ambang batas (Passing Grade) jalur umum adalah: TWK minimal 65 (dari maks 150), TIU minimal 80 (dari maks 175), dan TKP minimal 166 (dari maks 225) dengan total kumulatif minimal 311.",
                                ],
                            ],
                            [
                                "@type" => "Question",
                                "name" => "Bagaimana cara penilaian bobot nilai di ujian CAT BKN?",
                                "acceptedAnswer" => [
                                    "@type" => "Answer",
                                    "text" => "Untuk TWK (30 soal) dan TIU (35 soal), jawaban benar bernilai 5 poin dan salah bernilai 0 poin. Untuk TKP (45 soal), setiap opsi bernilai skala 1 sampai 5 poin, dan tidak menjawab bernilai 0 poin.",
                                ],
                            ],
                            [
                                "@type" => "Question",
                                "name" => "Berapa target skor aman agar lolos ke tahap SKB (3 kali formasi)?",
                                "acceptedAnswer" => [
                                    "@type" => "Answer",
                                    "text" => "Untuk masuk perangkingan 3 kali formasi di instansi favorit kementerian dan pemda, peserta disarankan menargetkan skor kumulatif minimal 400 - 450+ poin.",
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        @endphp
        <script type="application/ld+json">
        {!! json_encode($calcSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush

    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center py-2 gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('practice.index') }}" class="text-decoration-none text-secondary">Latihan Soal</a></li>
                        <li class="breadcrumb-item active text-body" aria-current="page">Kalkulator SKD</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-body mb-0">🧮 Kalkulator Skor & Passing Grade SKD CPNS 2026</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('tryout.index') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-play-circle-fill me-1"></i> Mulai Tryout Nyata
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container py-4 mb-5" x-data="skdCalculator()">
        <!-- Top Hero Title -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 p-4 p-md-5 bg-body-tertiary text-center">
            <div class="d-inline-flex align-items-center gap-2 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill fw-bold mb-3 mx-auto">
                <i class="bi bi-patch-check-fill"></i> Standar Resmi Permenpan-RB 2026
            </div>
            <h1 class="display-6 fw-bold text-body mb-3">Simulasi & Hitung Nilai Ambang Batas SKD</h1>
            <p class="text-secondary fs-5 mx-auto mb-0" style="max-width: 720px;">
                Geser slider atau masukkan perkiraan jumlah soal benar untuk mengetahui skor kumulatif dan status kelulusan <em>Passing Grade</em> Anda secara real-time.
            </p>
        </div>

        <div class="row g-4 mb-5">
            <!-- Left Column: Inputs & Sliders -->
            <div class="col-lg-7">
                <div class="card border-0 rounded-4 shadow-sm p-4 p-md-4 mb-4">
                    <h5 class="fw-bold text-body mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-sliders text-primary"></i> Input Estimasi Jawaban Anda
                    </h5>

                    <!-- TWK Input -->
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong class="text-danger"><i class="bi bi-flag-fill me-1"></i> TWK (Tes Wawasan Kebangsaan)</strong>
                                <span class="text-secondary small d-block">Ambang Batas: 65 | Maks: 150 (30 Soal x 5 Poin)</span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-danger fs-6 px-3 py-2" x-text="twkScore + ' Poin'"></span>
                                <small class="text-secondary d-block" x-text="twkCorrect + ' / 30 Soal Benar'"></small>
                            </div>
                        </div>
                        <input type="range" class="form-range" min="0" max="30" step="1" x-model.number="twkCorrect">
                        <div class="d-flex justify-content-between text-muted small">
                            <span>0 Benar (0 Poin)</span>
                            <span class="fw-bold text-danger">Passing Grade (13 Benar = 65)</span>
                            <span>30 Benar (150 Poin)</span>
                        </div>
                    </div>

                    <!-- TIU Input -->
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong class="text-primary"><i class="bi bi-calculator-fill me-1"></i> TIU (Tes Intelegensia Umum)</strong>
                                <span class="text-secondary small d-block">Ambang Batas: 80 | Maks: 175 (35 Soal x 5 Poin)</span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary fs-6 px-3 py-2" x-text="tiuScore + ' Poin'"></span>
                                <small class="text-secondary d-block" x-text="tiuCorrect + ' / 35 Soal Benar'"></small>
                            </div>
                        </div>
                        <input type="range" class="form-range" min="0" max="35" step="1" x-model.number="tiuCorrect">
                        <div class="d-flex justify-content-between text-muted small">
                            <span>0 Benar (0 Poin)</span>
                            <span class="fw-bold text-primary">Passing Grade (16 Benar = 80)</span>
                            <span>35 Benar (175 Poin)</span>
                        </div>
                    </div>

                    <!-- TKP Input -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong class="text-success"><i class="bi bi-people-fill me-1"></i> TKP (Tes Karakteristik Pribadi)</strong>
                                <span class="text-secondary small d-block">Ambang Batas: 166 | Maks: 225 (45 Soal skala 1-5)</span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success fs-6 px-3 py-2" x-text="tkpScore + ' Poin'"></span>
                                <small class="text-secondary d-block" x-text="'Rata-rata: ' + (tkpScore / 45).toFixed(2) + ' / 5.0'"></small>
                            </div>
                        </div>
                        <input type="range" class="form-range" min="45" max="225" step="1" x-model.number="tkpScore">
                        <div class="d-flex justify-content-between text-muted small">
                            <span>45 Min</span>
                            <span class="fw-bold text-success">Passing Grade (166 Poin)</span>
                            <span>225 Maks</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Presets -->
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-body-tertiary">
                    <h6 class="fw-bold text-body mb-3">🎯 Preset Simulasi Nilai Cepat:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" @click="setPreset(13, 16, 166)">
                            Pas Passing Grade (311)
                        </button>
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3" @click="setPreset(20, 26, 185)">
                            Target Aman Kementerian (415)
                        </button>
                        <button class="btn btn-outline-success btn-sm rounded-pill px-3" @click="setPreset(26, 32, 205)">
                            Top 1% Nasional (495)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Realtime Score & Status Card -->
            <div class="col-lg-5">
                <div class="card border-0 rounded-4 shadow-lg p-4 p-md-4 sticky-top" style="top: 100px;">
                    <div class="text-center mb-4">
                        <small class="text-secondary text-uppercase fw-bold letter-spacing-1">Total Skor Kumulatif</small>
                        <div class="display-3 fw-black text-body my-1" x-text="totalScore"></div>
                        <div class="text-secondary small">dari skor maksimal <strong>550 Poin</strong></div>
                    </div>

                    <!-- Passing Status Banner -->
                    <template x-if="isOverallPass">
                        <div class="alert alert-success border-0 rounded-4 p-3 mb-4 text-center">
                            <div class="fs-4 mb-1">🎉 <strong>LULUS PASSING GRADE!</strong></div>
                            <p class="small mb-0 opacity-90">Seluruh subtes memenuhi ambang batas resmi Permenpan-RB 2026.</p>
                        </div>
                    </template>
                    <template x-if="!isOverallPass">
                        <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 text-center">
                            <div class="fs-5 fw-bold mb-1">⚠️ BELUM MEMENUHI PASSING GRADE</div>
                            <p class="small mb-0 opacity-90" x-text="failReason"></p>
                        </div>
                    </template>

                    <!-- Breakdown per category -->
                    <div class="d-flex flex-column gap-3 mb-4">
                        <div>
                            <div class="d-flex justify-content-between small fw-bold mb-1">
                                <span class="text-danger">TWK: <span x-text="twkScore"></span> / 65</span>
                                <span x-show="twkScore >= 65" class="text-success"><i class="bi bi-check-circle-fill"></i> Lulus</span>
                                <span x-show="twkScore < 65" class="text-danger"><i class="bi bi-x-circle-fill"></i> Kurang <span x-text="65 - twkScore"></span></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" :style="'width: ' + (twkScore / 150 * 100) + '%'"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between small fw-bold mb-1">
                                <span class="text-primary">TIU: <span x-text="tiuScore"></span> / 80</span>
                                <span x-show="tiuScore >= 80" class="text-success"><i class="bi bi-check-circle-fill"></i> Lulus</span>
                                <span x-show="tiuScore < 80" class="text-danger"><i class="bi bi-x-circle-fill"></i> Kurang <span x-text="80 - tiuScore"></span></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" :style="'width: ' + (tiuScore / 175 * 100) + '%'"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between small fw-bold mb-1">
                                <span class="text-success">TKP: <span x-text="tkpScore"></span> / 166</span>
                                <span x-show="tkpScore >= 166" class="text-success"><i class="bi bi-check-circle-fill"></i> Lulus</span>
                                <span x-show="tkpScore < 166" class="text-danger"><i class="bi bi-x-circle-fill"></i> Kurang <span x-text="166 - tkpScore"></span></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" :style="'width: ' + (tkpScore / 225 * 100) + '%'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column gap-2">
                        <a :href="shareUrl" target="_blank" class="btn btn-success rounded-pill fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-whatsapp"></i> Bagikan Hasil ke WhatsApp
                        </a>
                        <a href="{{ route('tryout.index') }}" class="btn btn-warning text-dark rounded-pill fw-bold py-2 shadow-sm">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Coba Tryout CAT Realistis Gratis →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-body-tertiary">
            <h4 class="fw-bold text-body mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-question-circle-fill text-primary"></i> Tanya Jawab Seputar Passing Grade SKD 2026
            </h4>
            <div class="accordion border-0 rounded-3 overflow-hidden" id="calcFaq">
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-bold text-body" type="button" data-bs-toggle="collapse" data-bs-target="#cf1">
                            Apakah salah menjawab soal TWK atau TIU akan mengurangi nilai (sistem minus)?
                        </button>
                    </h2>
                    <div id="cf1" class="accordion-collapse collapse show" data-bs-parent="#calcFaq">
                        <div class="accordion-body text-secondary">
                            Tidak ada sistem minus di ujian SKD CPNS. Jawaban benar bernilai 5 poin, sedangkan jawaban salah atau tidak menjawab bernilai 0 poin. Oleh karena itu, peserta sangat disarankan untuk menjawab seluruh soal.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold text-body" type="button" data-bs-toggle="collapse" data-bs-target="#cf2">
                            Mengapa saya tidak lulus meskipun total skor saya lebih dari 350?
                        </button>
                    </h2>
                    <div id="cf2" class="accordion-collapse collapse" data-bs-parent="#calcFaq">
                        <div class="accordion-body text-secondary">
                            Kelulusan SKD menggunakan sistem <strong>Ambang Batas Parsial</strong>. Artinya, setiap kategori (TWK, TIU, TKP) harus memenuhi passing grade masing-masing. Jika nilai TWK Anda 60 (kurang dari 65), Anda tetap dinyatakan tidak lulus passing grade meskipun skor TIU dan TKP Anda sangat tinggi.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold text-body" type="button" data-bs-toggle="collapse" data-bs-target="#cf3">
                            Berapa skor yang dibutuhkan agar lolos ke tahap SKB (3 kali formasi)?
                        </button>
                    </h2>
                    <div id="cf3" class="accordion-collapse collapse" data-bs-parent="#calcFaq">
                        <div class="accordion-body text-secondary">
                            Lolos passing grade adalah syarat mutlak, namun untuk melaju ke tahap SKB (Seleksi Kompetensi Bidang), Anda harus masuk dalam peringkat 3 kali jumlah formasi yang dibuka. Untuk posisi kementerian favorit dan instansi pusat, skor aman yang direkomendasikan adalah minimal <strong>410 - 450+ poin</strong>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function skdCalculator() {
            return {
                twkCorrect: 20,
                tiuCorrect: 24,
                tkpScore: 180,

                get twkScore() {
                    return this.twkCorrect * 5;
                },
                get tiuScore() {
                    return this.tiuCorrect * 5;
                },
                get totalScore() {
                    return this.twkScore + this.tiuScore + this.tkpScore;
                },
                get isTwkPass() {
                    return this.twkScore >= 65;
                },
                get isTiuPass() {
                    return this.tiuScore >= 80;
                },
                get isTkpPass() {
                    return this.tkpScore >= 166;
                },
                get isOverallPass() {
                    return this.isTwkPass && this.isTiuPass && this.isTkpPass;
                },
                get failReason() {
                    let fails = [];
                    if (!this.isTwkPass) fails.push('TWK kurang ' + (65 - this.twkScore) + ' poin');
                    if (!this.isTiuPass) fails.push('TIU kurang ' + (80 - this.tiuScore) + ' poin');
                    if (!this.isTkpPass) fails.push('TKP kurang ' + (166 - this.tkpScore) + ' poin');
                    return fails.join(', ');
                },
                get shareUrl() {
                    const status = this.isOverallPass ? 'LULUS PASSING GRADE 🎉' : 'BELUM LULUS PG ⚠️';
                    const text = `Saya mencoba Simulasi Skor SKD CPNS 2026 di Abdinara.id!\n\n📊 Skor Saya:\n• TWK: ${this.twkScore} (Ambang Batas: 65)\n• TIU: ${this.tiuScore} (Ambang Batas: 80)\n• TKP: ${this.tkpScore} (Ambang Batas: 166)\n👉 Total: ${this.totalScore} Poin\n🏆 Status: ${status}\n\nHitung skor dan cek passing grade kamu di: https://cat.abdinara.id/kalkulator-skd`;
                    return 'https://api.whatsapp.com/send?text=' + encodeURIComponent(text);
                },
                setPreset(twk, tiu, tkp) {
                    this.twkCorrect = twk;
                    this.tiuCorrect = tiu;
                    this.tkpScore = tkp;
                }
            };
        }
    </script>
    @endpush
</x-app-layout>