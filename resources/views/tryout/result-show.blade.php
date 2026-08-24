<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-3 py-3">
            <a href="{{ route('tryout.results') }}" class="text-decoration-none text-body-secondary hover-dark">
                <i class="bi bi-arrow-left"></i> Riwayat
            </a>
            <h4 class="mb-0 fw-bold text-body">Detail Hasil Tryout</h4>
        </div>
    </x-slot>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                @php
                    $pct =
                        $session->tryout->total_questions > 0
                            ? round(($session->score / $session->tryout->total_questions) * 100)
                            : 0;

                    $isPassed = $pct >= 70;
                    $isBorderline = $pct >= 50 && $pct < 70;
                    $statusColor = $isPassed ? 'success' : ($isBorderline ? 'warning' : 'danger');
                    $statusText = $isPassed
                        ? '✓ Lulus Passing Grade'
                        : ($isBorderline
                            ? '~ Di Ambang Batas'
                            : '✗ Perlu Belajar Lagi');
                @endphp

                {{-- Score Highlight Card --}}
                <div class="card border-0 shadow-sm rounded-4 text-center mb-5 overflow-hidden">
                    <div class="card-body p-5">
                        <h6 class="text-body-secondary text-uppercase tracking-wider fw-bold mb-3"
                            style="letter-spacing: 2px;">
                            Skor Akhir Anda</h6>
                        <h1 class="display-1 fw-bolder text-{{ $statusColor }} mb-2">
                            {{ $session->score }}
                        </h1>
                        <p class="text-body-secondary mb-4 fs-5">dari {{ $session->tryout->total_questions }} soal
                            ({{ $pct }}%)</p>

                        <div class="mb-4">
                            <span
                                class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} border border-{{ $statusColor }} px-4 py-2 rounded-pill fs-6 shadow-sm">
                                {{ $statusText }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-center gap-4 text-body-secondary small border-top pt-4 mt-2">
                            <div>
                                <i class="bi bi-calendar-check me-1"></i>
                                Selesai: <span
                                    class="fw-medium text-body">{{ $session->finished_at?->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            @if ($session->duration_seconds !== null)
                                <div>
                                    <i class="bi bi-stopwatch me-1"></i>
                                    Waktu: <span
                                        class="fw-medium text-body">{{ floor($session->duration_seconds / 60) }}m
                                        {{ $session->duration_seconds % 60 }}s</span>
                                    <span class="text-body-secondary ms-1">(Batas
                                        {{ $session->tryout->duration_minutes }}m)</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- AI Diagnostic & Radar Kelemahan Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
                    <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3 fs-6">
                                <i class="bi bi-cpu-fill"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold text-body mb-0">Analisis Cerdas & Radar Materi AI</h5>
                                <small class="text-secondary">Evaluasi penguasaan materi & prediksi kelulusan</small>
                            </div>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-patch-check-fill me-1"></i> AI Diagnostik
                        </span>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <div class="row align-items-center g-4">
                            <!-- Radar Chart -->
                            <div class="col-lg-6">
                                <div class="text-center mb-2">
                                    <h6 class="fw-bold text-body mb-1">Peta Radar Penguasaan Materi</h6>
                                    <small class="text-secondary">Persentase akurasi jawaban per subtopik</small>
                                </div>
                                <div style="position: relative; height: 280px; width: 100%;">
                                    <canvas id="radarChart"></canvas>
                                </div>
                            </div>

                            <!-- Diagnostic Insights & Probability -->
                            <div class="col-lg-6">
                                <!-- Peluang Lolos Box -->
                                <div class="p-3 rounded-4 bg-body-tertiary border mb-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <small class="text-secondary fw-semibold text-uppercase tracking-wider">Estimasi Peluang Lolos:</small>
                                        <h4 class="fw-bolder text-primary mb-0">{{ $passingProbability }}%</h4>
                                        <small class="text-secondary">Berdasarkan passing grade SKD</small>
                                    </div>
                                    <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary fs-3">
                                        <i class="bi bi-graph-up-arrow"></i>
                                    </div>
                                </div>

                                <!-- Strengths -->
                                @if (!empty($strengths))
                                    <div class="mb-3">
                                        <small class="text-success fw-bold text-uppercase d-flex align-items-center gap-1 mb-1">
                                            <i class="bi bi-trophy-fill"></i> Kekuatan Utama:
                                        </small>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($strengths as $st)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill small">
                                                    {{ $st['name'] }} ({{ $st['accuracy'] }}%)
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Weaknesses -->
                                @if (!empty($weaknesses))
                                    <div class="mb-4">
                                        <small class="text-danger fw-bold text-uppercase d-flex align-items-center gap-1 mb-1">
                                            <i class="bi bi-exclamation-triangle-fill"></i> Perlu Ditingkatkan:
                                        </small>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($weaknesses as $wk)
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill small">
                                                    {{ $wk['name'] }} ({{ $wk['accuracy'] }}%)
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Viral Share & Download Story Buttons -->
                                <div class="d-flex flex-column gap-2 pt-2 border-top">
                                    <button type="button" id="btnDownloadStory" onclick="generateStoryCard()" class="btn btn-warning text-dark fw-bold rounded-pill shadow-sm py-2 d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-camera-fill"></i> Unduh Kartu Story (IG/WA 9:16)
                                    </button>

                                    @php
                                        $waText = urlencode("Alhamdulillah! Saya baru saja menyelesaikan {$session->tryout->title} di Abdinara.id dengan skor {$session->score}! Yuk uji kemampuan CAT kamu juga di https://cat.abdinara.id");
                                    @endphp
                                    <a href="https://api.whatsapp.com/send?text={{ $waText }}" target="_blank" class="btn btn-outline-success fw-bold rounded-pill py-2 d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-whatsapp"></i> Bagikan Hasil ke WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden 9:16 Story Card Element for Export (1080x1920) --}}
                <div style="position: absolute; left: -9999px; top: -9999px;">
                    <div id="storyCard" style="width: 540px; height: 960px; background: linear-gradient(145deg, #0a2647 0%, #144272 50%, #0d1b2a 100%); color: #ffffff; padding: 40px; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; display: flex; flex-direction: column; justify-content: space-between; border-radius: 0; box-sizing: border-box;">
                        <!-- Header -->
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="{{ asset('icon-192.png') }}" style="height: 48px; width: 48px; border-radius: 12px;" onerror="this.src='{{ asset('favicon.ico') }}'">
                                <div>
                                    <h3 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px; color: #ffffff;">
                                        Abdi<span style="color: #d4af37;">nara</span>.id
                                    </h3>
                                    <p style="margin: 0; font-size: 11px; opacity: 0.75; text-transform: uppercase; letter-spacing: 1px;">LMS Portal Resmi CAT</p>
                                </div>
                            </div>
                            <span style="background: rgba(212, 175, 55, 0.2); border: 1px solid #d4af37; color: #d4af37; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                Hasil Simulasi
                            </span>
                        </div>

                        <!-- User & Score Section -->
                        <div style="background: rgba(255, 255, 255, 0.07); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 24px; padding: 28px; text-align: center; margin: 20px 0;">
                            <p style="margin: 0 0 6px 0; font-size: 15px; font-weight: 600; color: #d4af37; text-transform: uppercase; letter-spacing: 1px;">{{ $session->user->name ?? Auth::user()->name }}</p>
                            <h4 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 700; color: #ffffff;">{{ $session->tryout->title }}</h4>

                            <div style="font-size: 72px; font-weight: 900; line-height: 1; color: {{ $isPassed ? '#4ade80' : '#f87171' }}; margin-bottom: 10px;">
                                {{ $session->score }}
                            </div>
                            <p style="margin: 0 0 16px 0; font-size: 14px; opacity: 0.8;">dari {{ $session->tryout->total_questions }} butir soal ({{ $pct }}%)</p>

                            <div style="display: inline-block; background: {{ $isPassed ? 'rgba(74, 222, 128, 0.2)' : 'rgba(248, 113, 113, 0.2)' }}; border: 1px solid {{ $isPassed ? '#4ade80' : '#f87171' }}; color: {{ $isPassed ? '#4ade80' : '#f87171' }}; padding: 8px 24px; border-radius: 30px; font-size: 14px; font-weight: 800;">
                                {{ $statusText }}
                            </div>
                        </div>

                        <!-- Highlights & Stats -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                            <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 14px; text-align: center;">
                                <p style="margin: 0; font-size: 11px; opacity: 0.75; text-transform: uppercase;">Peluang Lolos</p>
                                <h3 style="margin: 4px 0 0 0; font-size: 24px; font-weight: 800; color: #38bdf8;">{{ $passingProbability }}%</h3>
                            </div>
                            <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 14px; text-align: center;">
                                <p style="margin: 0; font-size: 11px; opacity: 0.75; text-transform: uppercase;">Waktu Pengerjaan</p>
                                <h3 style="margin: 4px 0 0 0; font-size: 20px; font-weight: 800; color: #ffffff;">
                                    @if ($session->duration_seconds !== null)
                                        {{ floor($session->duration_seconds / 60) }}m {{ $session->duration_seconds % 60 }}s
                                    @else
                                        -
                                    @endif
                                </h3>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div style="text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.15); padding-top: 16px;">
                            <p style="margin: 0 0 4px 0; font-size: 13px; font-weight: 700; color: #d4af37;">Wujudkan Impian Lolos Seleksi CPNS & Kedinasan</p>
                            <p style="margin: 0; font-size: 11px; opacity: 0.7;">Ikuti simulasi CAT online gratis di <strong>cat.abdinara.id</strong></p>
                        </div>
                    </div>
                </div>

                {{-- Answer Review Cards --}}
                <div class="mb-4">
                    <h5 class="mb-4 fw-bold text-body d-flex align-items-center gap-2">
                        <i class="bi bi-card-checklist text-primary"></i> Review Jawaban Anda
                    </h5>

                    @foreach ($answers as $i => $answer)
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div
                                class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-body-secondary fs-5">Soal {{ $i + 1 }}</span>
                                <div>
                                    @if ($answer->is_correct)
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25">
                                            <i class="bi bi-check-circle-fill me-1"></i> Benar
                                        </span>
                                    @elseif (!$answer->selected_answer)
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill border border-secondary border-opacity-25">
                                            <i class="bi bi-dash-circle-fill me-1"></i> Kosong
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger border-opacity-25">
                                            <i class="bi bi-x-circle-fill me-1"></i> Salah
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-4">
                                {{-- Question Text --}}
                                <div class="text-body mb-4 fs-5" style="line-height: 1.6;">
                                    {!! nl2br(e($answer->question?->question_text ?? '(soal dihapus)')) !!}
                                </div>

                                @if ($answer->question?->image)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $answer->question->image) }}"
                                            alt="Gambar Soal {{ $i + 1 }}"
                                            class="img-fluid rounded-3 border shadow-sm" style="max-height: 300px;">
                                    </div>
                                @endif

                                {{-- Comparison Grid --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0 h-100 dash-card"
                                            style="background-color: var(--bs-tertiary-bg) !important;">
                                            <span
                                                class="d-block text-body-secondary small mb-2 fw-bold text-uppercase tracking-wider">Jawaban
                                                Anda</span>
                                            @if ($answer->selected_answer)
                                                @php
                                                    $optKey = 'option_' . strtolower($answer->selected_answer);
                                                    $optText = $answer->question->$optKey ?? '-';
                                                @endphp
                                                <div class="d-flex align-items-start gap-3">
                                                    <span
                                                        class="badge {{ $answer->is_correct ? 'bg-success' : 'bg-danger' }} rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                        style="width: 32px; height: 32px; flex-shrink: 0;">
                                                        {{ $answer->selected_answer }}
                                                    </span>
                                                    <span class="text-body">{{ $optText }}</span>
                                                </div>
                                            @else
                                                <div class="text-secondary italic">Tidak dijawab</div>
                                            @endif
                                        </div>
                                    </div>

                                    @if (!$answer->is_correct && $answer->question)
                                        <div class="col-md-6">
                                            <div
                                                class="p-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-25 h-100">
                                                <span
                                                    class="d-block text-success small mb-2 fw-bold text-uppercase tracking-wider">Jawaban
                                                    Benar</span>
                                                @php
                                                    $correctKey =
                                                        'option_' . strtolower($answer->question->correct_answer);
                                                    $correctText = $answer->question->$correctKey ?? '-';
                                                @endphp
                                                <div class="d-flex align-items-start gap-3">
                                                    <span
                                                        class="badge bg-success rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                        style="width: 32px; height: 32px; flex-shrink: 0;">
                                                        {{ $answer->question->correct_answer }}
                                                    </span>
                                                    <span class="text-success fw-medium">{{ $correctText }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Explanation --}}
                                @if ($answer->question?->explanation)
                                    <div x-data="{ open: false }" class="mt-2">
                                        <button
                                            @click="open = !open"
                                            class="btn btn-link text-decoration-none p-0 text-primary fw-bold d-flex align-items-center gap-2"
                                            type="button">
                                            <i class="bi bi-lightbulb"></i>
                                            <span x-text="open ? 'Sembunyikan Pembahasan' : 'Lihat Pembahasan'">Lihat Pembahasan</span>
                                            <i class="bi bi-chevron-down small transition-transform" :class="{ 'rotate-180': open }"></i>
                                        </button>

                                        <div x-show="open" x-cloak class="mt-3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                                            <div
                                                class="bg-primary bg-opacity-10 p-4 rounded-4 border-start border-primary border-4">
                                                <h6 class="text-primary fw-bold mb-2">Pembahasan Lengkap:</h6>
                                                <div class="text-body" style="line-height: 1.6;">
                                                    {!! nl2br(e($answer->question->explanation)) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Call to Actions --}}
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="{{ route('tryout.index') }}"
                        class="btn btn-primary btn-lg rounded-pill flex-grow-1 shadow-sm fw-bold">
                        Coba Tryout Lainnya
                    </a>
                    <a href="{{ route('tryout.results') }}"
                        class="btn btn-light border btn-lg rounded-pill flex-grow-1 text-secondary fw-semibold">
                        Lihat Semua Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('radarChart');
                if (ctx) {
                    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                    const gridColor = isDark ? 'rgba(255, 255, 255, 0.15)' : 'rgba(0, 0, 0, 0.1)';
                    const textColor = isDark ? '#cbd5e1' : '#475569';

                    new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: {!! json_encode($radarLabels) !!},
                            datasets: [{
                                label: 'Akurasi Penguasaan (%)',
                                data: {!! json_encode($radarData) !!},
                                fill: true,
                                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                borderColor: '#3b82f6',
                                pointBackgroundColor: '#3b82f6',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#3b82f6',
                                borderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                r: {
                                    angleLines: { color: gridColor },
                                    grid: { color: gridColor },
                                    pointLabels: {
                                        color: textColor,
                                        font: { size: 11, weight: '600' }
                                    },
                                    suggestedMin: 0,
                                    suggestedMax: 100,
                                    ticks: {
                                        stepSize: 20,
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                }
            });

            function generateStoryCard() {
                const btn = document.getElementById('btnDownloadStory');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Membuat Gambar...';
                btn.disabled = true;

                const card = document.getElementById('storyCard');

                html2canvas(card, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#0a2647'
                }).then(canvas => {
                    const link = document.createElement('a');
                    link.download = 'Story-Hasil-Tryout-{{ $session->id }}.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();

                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }).catch(err => {
                    console.error(err);
                    alert('Gagal membuat gambar Story. Silakan coba lagi.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            }
        </script>
    @endpush
</x-app-layout>
