<div class="container py-4 mb-5">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center py-2 gap-2 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a></li>
                    <li class="breadcrumb-item active text-body" aria-current="page">Hasil & Analisis AI Saya</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-body mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-graph-up-arrow text-primary"></i> Statistik & Analisis Performa Pejuang
            </h3>
        </div>
        <div>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                <i class="bi bi-cpu me-1"></i> Analisis AI Terintegrasi (Tryout & Game)
            </span>
        </div>
    </div>

    <!-- 1. GAMER PROFILE & COMBAT LEVEL CARD (HERO) -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #0a2647 0%, #144272 50%, #2c3e50 100%);">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-warning text-dark fs-3 fw-bold d-flex align-items-center justify-content-center shadow" style="width: 58px; height: 58px;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold small">
                                LEVEL {{ $level }} • {{ $gamerTitle }}
                            </span>
                            <h2 class="fw-bold text-white mb-0 mt-1">{{ Auth::user()->name }}</h2>
                        </div>
                    </div>

                    <p class="opacity-75 mb-3" style="max-width: 550px;">
                        Statistik performa belajar, hasil simulasi tryout CAT, dan histori pertempuran duel 1 vs 1 Anda yang terakumulasi sepanjang masa.
                    </p>

                    <!-- EXP Bar -->
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small opacity-90 mb-1 fw-semibold">
                            <span>EXP Pejuang: {{ number_format($totalExp) }} XP</span>
                            <span>Level {{ $level + 1 }} ({{ $levelProgress }}%)</span>
                        </div>
                        <div class="progress rounded-pill bg-white bg-opacity-20" style="height: 10px;">
                            <div class="progress-bar bg-warning rounded-pill progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $levelProgress }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Game Stats Grid -->
                <div class="col-lg-5">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="gamer-stat-box rounded-4 p-3 text-center shadow-sm h-100 d-flex flex-column justify-content-center">
                                <small class="gamer-stat-label d-block">Simulasi Tryout</small>
                                <h3 class="fw-bolder text-primary mb-0 mt-1">{{ $totalTryouts }}</h3>
                                <small class="gamer-stat-sub" style="font-size: 11px;">Rerata: {{ $avgScore }} poin</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="gamer-stat-box rounded-4 p-3 text-center shadow-sm h-100 d-flex flex-column justify-content-center">
                                <small class="gamer-stat-label d-block">Win Rate Duel 1v1</small>
                                <h3 class="fw-bolder text-danger mb-0 mt-1">{{ $battleWinRate }}%</h3>
                                <small class="gamer-stat-sub" style="font-size: 11px;">{{ $totalBattleWins }} Menang / {{ $totalBattles }} Duel</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="gamer-stat-box rounded-4 p-3 text-center shadow-sm h-100 d-flex flex-column justify-content-center">
                                <small class="gamer-stat-label d-block">Liga Nasional</small>
                                <h3 class="fw-bolder text-warning mb-0 mt-1">{{ $totalTournaments }}x</h3>
                                <small class="gamer-stat-sub" style="font-size: 11px;">Peringkat Terbaik: {{ $bestRank ? '#'.$bestRank : '-' }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="gamer-stat-box rounded-4 p-3 text-center shadow-sm h-100 d-flex flex-column justify-content-center">
                                <small class="gamer-stat-label d-block">Akurasi Soal</small>
                                <h3 class="fw-bolder text-success mb-0 mt-1">{{ $overallAccuracy }}%</h3>
                                <small class="gamer-stat-sub" style="font-size: 11px;">{{ $totalAnswered }} Soal Terjawab</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. ACHIEVEMENT BADGES (PIALA & MEDALI GAMER) -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold text-body mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-award-fill text-warning"></i> Lencana & Pencapaian Pejuang (Achievements)
        </h5>
        <div class="row g-3">
            @foreach ($badges as $badge)
                <div class="col-6 col-md-4 col-lg">
                    <div class="card border rounded-4 p-3 text-center h-100 {{ $badge['unlocked'] ? 'bg-'.$badge['color'].'-subtle border-'.$badge['color'] : 'bg-body-tertiary opacity-50' }}">
                        <div class="fs-2 mb-1 {{ $badge['unlocked'] ? 'text-'.$badge['color'] : 'text-secondary' }}">
                            <i class="bi {{ $badge['icon'] }}"></i>
                        </div>
                        <h6 class="fw-bold text-body mb-1 small">{{ $badge['name'] }}</h6>
                        <p class="text-secondary mb-0" style="font-size: 11px;">{{ $badge['desc'] }}</p>
                        <span class="badge {{ $badge['unlocked'] ? 'bg-'.$badge['color'].' text-white' : 'bg-secondary' }} rounded-pill px-2 py-0 mt-2 mx-auto" style="font-size: 10px;">
                            {{ $badge['unlocked'] ? '✓ Tercapai' : 'Terkunci' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 3. MASTER RADAR & AI DIAGNOSTIC PERFORMANCE HUB -->
    <div class="row g-4 mb-5">
        <!-- Radar Chart All-Time -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-body mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-radar text-primary"></i> Radar Penguasaan All-Time
                    </h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill small px-3 py-1">Akumulasi AI</span>
                </div>
                <p class="text-secondary small mb-3">
                    Visualisasi penguasaan per subtopik berdasarkan seluruh pengerjaan tryout Anda.
                </p>

                <div class="position-relative d-flex justify-content-center align-items-center" style="min-height: 320px;">
                    <canvas id="allTimeRadarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Strengths, Weaknesses & AI Action Plan -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold text-body mb-1 d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-charge-fill text-warning"></i> Diagnostik Kelebihan & Kekurangan
                        </h5>
                        <p class="text-secondary small mb-0">Analisis kecerdasan buatan untuk memaksimalkan peluang lolos seleksi.</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-bold">
                            Estimasi Lolos: {{ $passingProb }}%
                        </span>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <!-- Top Strengths -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-success bg-opacity-10 rounded-4 p-3 h-100 border-start border-success border-4">
                            <h6 class="fw-bold text-success mb-2 d-flex align-items-center gap-1">
                                <i class="bi bi-check-circle-fill"></i> Kelebihan (Materi Terkuat)
                            </h6>
                            @if ($topStrengths->isNotEmpty())
                                <ul class="list-unstyled mb-0 small">
                                    @foreach ($topStrengths as $st)
                                        <li class="d-flex justify-content-between align-items-center py-1 border-bottom border-success border-opacity-10">
                                            <span class="text-body fw-medium">{{ $st['name'] }}</span>
                                            <span class="badge bg-success text-white rounded-pill">{{ $st['accuracy'] }}%</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-secondary small mb-0">Belum ada data tryout yang cukup.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Top Weaknesses -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-danger bg-opacity-10 rounded-4 p-3 h-100 border-start border-danger border-4">
                            <h6 class="fw-bold text-danger mb-2 d-flex align-items-center gap-1">
                                <i class="bi bi-exclamation-triangle-fill"></i> Kekurangan (Harus Diperbaiki)
                            </h6>
                            @if ($topWeaknesses->isNotEmpty())
                                <ul class="list-unstyled mb-0 small">
                                    @foreach ($topWeaknesses as $wk)
                                        <li class="d-flex justify-content-between align-items-center py-1 border-bottom border-danger border-opacity-10">
                                            <span class="text-body fw-medium">{{ $wk['name'] }}</span>
                                            <span class="badge bg-danger text-white rounded-pill">{{ $wk['accuracy'] }}%</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-secondary small mb-0">Belum ada data tryout yang cukup.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Plan Recommendations -->
                <div class="card border-0 bg-body-tertiary rounded-4 p-3 mt-auto">
                    <h6 class="fw-bold text-body mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-bullseye text-primary"></i> Rekomendasi Belajar Personal Hari Ini
                    </h6>
                    @if ($topWeaknesses->isNotEmpty() && $topWeaknesses->first()['accuracy'] < 75)
                        @php $worst = $topWeaknesses->first(); @endphp
                        <p class="text-secondary small mb-3">
                            Akurasi Anda pada materi <strong>{{ $worst['name'] }}</strong> saat ini masih <strong>{{ $worst['accuracy'] }}%</strong>. Asah kemampuan Anda dengan latihan soal khusus materi ini untuk mendongkrak skor total!
                        </p>
                        @if ($worst['subtopic_slug'])
                            <a href="{{ route('practice.subtopic', [$worst['category_slug'], $worst['subtopic_slug']]) }}" class="btn btn-primary btn-sm rounded-pill fw-bold px-3">
                                <i class="bi bi-play-fill me-1"></i> Latihan Soal {{ $worst['name'] }} Sekarang
                            </a>
                        @else
                            <a href="{{ route('practice.index') }}" class="btn btn-primary btn-sm rounded-pill fw-bold px-3">
                                <i class="bi bi-play-fill me-1"></i> Buka Bank Soal Latihan
                            </a>
                        @endif
                    @else
                        <p class="text-secondary small mb-2">
                            Performa Anda stabil di seluruh subtopik! Pertahankan dengan mengikuti simulasi tryout berkala atau bertanding di duel 1 vs 1.
                        </p>
                        <a href="{{ route('tryout.index') }}" class="btn btn-primary btn-sm rounded-pill fw-bold px-3">
                            <i class="bi bi-arrow-right me-1"></i> Ikuti Tryout Baru
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 4. RIWAYAT SESI TRYOUT INDIVIDUAL -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-body mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-primary"></i> Riwayat Tryout Individual
        </h4>
        <a href="{{ route('tryout.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Ikuti Tryout Lainnya
        </a>
    </div>

    @if ($sessions->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
            <i class="bi bi-journal-x fs-1 opacity-50 mb-3 d-block"></i>
            <h5 class="fw-normal mb-3">Belum ada riwayat tryout yang diselesaikan.</h5>
            <a href="{{ route('tryout.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                Mulai Tryout Pertama Anda <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach ($sessions as $session)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 transition-hover">
                        <div class="card-body p-4 d-flex flex-column">
                            <h6 class="card-title fw-bold text-body mb-3 text-truncate" title="{{ $session->tryout->title ?? 'Tryout' }}">
                                {{ $session->tryout->title ?? 'Tryout' }}
                            </h6>

                            <div class="mb-3">
                                @php
                                    $passingGrade = ($session->tryout->total_questions ?? 0) * 0.7;
                                    $badgeClass = $session->score >= $passingGrade ? 'success' : 'danger';
                                    $iconClass = $session->score >= $passingGrade ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
                                    $statusText = $session->score >= $passingGrade ? 'Lulus' : 'Belum Lulus';
                                @endphp

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-secondary small">Skor Akhir</span>
                                    <span class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }} border border-{{ $badgeClass }} rounded-pill px-2 py-1 small fw-bold">
                                        <i class="bi {{ $iconClass }} me-1"></i> {{ $session->score }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center text-secondary small">
                                    <span>Tanggal:</span>
                                    <span class="text-body fw-medium">{{ $session->finished_at?->translatedFormat('d M Y') ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <span class="text-body-secondary small">
                                    <i class="bi bi-clock me-1"></i> {{ $session->finished_at?->format('H:i') ?? '-' }}
                                </span>
                                <a href="{{ route('tryout.results.show', $session->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold shadow-sm">
                                    Detail <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $sessions->links() }}
        </div>
    @endif

    <!-- Radar Chart Scripts -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('allTimeRadarChart');
                if (ctx) {
                    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                    const gridColor = isDark ? 'rgba(255, 255, 255, 0.15)' : 'rgba(0, 0, 0, 0.1)';
                    const textColor = isDark ? '#cbd5e1' : '#475569';

                    new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: {!! json_encode($radarLabels) !!},
                            datasets: [{
                                label: 'Tingkat Akurasi (%)',
                                data: {!! json_encode($radarData) !!},
                                fill: true,
                                backgroundColor: 'rgba(14, 165, 233, 0.2)',
                                borderColor: '#0ea5e9',
                                pointBackgroundColor: '#0ea5e9',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#0ea5e9',
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
        </script>
    @endpush
</div>