<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center py-2 gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
                        <li class="breadcrumb-item active text-body" aria-current="page">Liga Tryout Mingguan</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-body mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill text-warning"></i> Liga Tryout Nasional Mingguan
                </h3>
            </div>
            <div>
                @if ($tournament && $tournament->isOngoing())
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-bold fs-6">
                        <i class="bi bi-broadcast me-1"></i> EVENT SEDANG BERLANGSUNG
                    </span>
                @else
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-2 rounded-pill fw-bold">
                        <i class="bi bi-clock-history me-1"></i> Edisi Terjadwal
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="container py-4 mb-5">
        @if ($tournament)
            <!-- Hero Tournament Banner -->
            <div class="card border-0 rounded-4 shadow-sm mb-5 text-white overflow-hidden" style="background: linear-gradient(135deg, #0a2647 0%, #144272 50%, #b45309 100%);">
                <div class="card-body p-4 p-md-5 position-relative">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-8">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3 fs-6">
                                <i class="bi bi-award-fill me-1"></i> Edisi ke-{{ $tournament->edition_number }} • Hadiah Total Ratusan Ribu Rupiah
                            </span>
                            <h1 class="display-6 fw-bold mb-3 text-white">{{ $tournament->title }}</h1>
                            <p class="fs-5 opacity-75 mb-4" style="max-width: 650px;">
                                Uji kemampuanmu serentak bersama ribuan pejuang CPNS & Kedinasan dari seluruh Indonesia. Raih peringkat tertinggi nasional dan menangkan hadiah eksklusif!
                            </p>

                            <div class="d-flex flex-wrap gap-4 pt-2 border-top border-white border-opacity-10">
                                <div>
                                    <small class="opacity-75 d-block">Mulai Ujian:</small>
                                    <span class="fw-bold text-white fs-6">{{ $tournament->start_at->translatedFormat('d M Y H:i') }} WIB</span>
                                </div>
                                <div class="border-start border-white border-opacity-10 ps-4">
                                    <small class="opacity-75 d-block">Batas Akhir:</small>
                                    <span class="fw-bold text-white fs-6">{{ $tournament->end_at->translatedFormat('d M Y H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 text-center">
                            <div class="card border-0 rounded-4 p-4 text-dark shadow-lg" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                                <h5 class="fw-bold mb-2">Status Partisipasi</h5>
                                @if ($myParticipation)
                                    <div class="my-3">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                                            ✓ Sudah Mengerjakan
                                        </span>
                                        <h2 class="display-5 fw-bolder text-primary mt-2 mb-0">{{ $myParticipation->score }}</h2>
                                        <small class="text-secondary">Peringkat Sementara: <strong>#{{ $myParticipation->rank_position ?? '-' }}</strong></small>
                                    </div>
                                    @if ($myParticipation->is_passed)
                                        <a href="{{ route('tournament.certificate', $myParticipation->id) }}" class="btn btn-warning fw-bold w-100 rounded-pill shadow-sm">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Unduh E-Sertifikat
                                        </a>
                                    @endif
                                @else
                                    <p class="text-secondary small mb-3">Ikuti simulasi ujian edisi minggu ini dan masuk ke papan klasemen nasional!</p>
                                    @auth
                                        <a href="{{ route('tryout.index') }}" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold shadow">
                                            Mulai Liga Tryout <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold shadow">
                                            Daftar & Ikut Liga <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prizes & Rewards -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 border-top border-warning border-4">
                        <div class="fs-1 text-warning mb-2">🥇</div>
                        <h5 class="fw-bold mb-1">Juara 1 Nasional</h5>
                        <p class="text-secondary small mb-0">Saldo E-Wallet Rp 100.000 + E-Sertifikat Juara Emas</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 border-top border-secondary border-4">
                        <div class="fs-1 text-secondary mb-2">🥈</div>
                        <h5 class="fw-bold mb-1">Juara 2 Nasional</h5>
                        <p class="text-secondary small mb-0">Saldo E-Wallet Rp 50.000 + E-Sertifikat Juara Perak</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 border-top border-danger border-4">
                        <div class="fs-1 text-danger mb-2">🥉</div>
                        <h5 class="fw-bold mb-1">Juara 3 Nasional</h5>
                        <p class="text-secondary small mb-0">Paket VIP Premium 1 Bulan + E-Sertifikat Juara Perunggu</p>
                    </div>
                </div>
            </div>

            <!-- Live National Leaderboard -->
            <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
                <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-bar-chart-line-fill text-primary fs-4"></i>
                        <h4 class="fw-bold text-body mb-0">Klasemen Peringkat Nasional (Live)</h4>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold">
                        Top 50 Peserta
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-body-tertiary">
                            <tr>
                                <th class="text-center py-3" style="width: 80px;">Posisi</th>
                                <th class="py-3">Nama Peserta</th>
                                <th class="text-center py-3">Skor Total</th>
                                <th class="text-center py-3">Waktu</th>
                                <th class="text-center py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaderboard as $index => $item)
                                @php
                                    $rank = $index + 1;
                                    $medal = match($rank) {
                                        1 => '🥇',
                                        2 => '🥈',
                                        3 => '🥉',
                                        default => '#' . $rank
                                    };
                                    $isMe = Auth::check() && $item->user_id === Auth::id();
                                @endphp
                                <tr class="{{ $isMe ? 'table-primary fw-bold' : '' }}">
                                    <td class="text-center fs-5 fw-bold">{{ $medal }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-body">{{ $item->user->name ?? 'Peserta' }}</span>
                                            @if ($isMe)
                                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 small">Anda</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center fw-bolder fs-5 text-primary">{{ $item->score }}</td>
                                    <td class="text-center text-secondary small">
                                        {{ $item->duration_seconds ? floor($item->duration_seconds / 60) . 'm ' . ($item->duration_seconds % 60) . 's' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($item->is_passed)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1">Lulus</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">
                                        <i class="bi bi-people fs-1 d-block mb-2 text-muted"></i>
                                        Belum ada peserta yang menyelesaikan ujian liga minggu ini. Jadilah yang pertama!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
                <i class="bi bi-trophy fs-1 mb-3 text-warning"></i>
                <h4 class="text-body fw-bold">Liga Tryout Sedang Dipersiapkan</h4>
                <p>Jadwal liga mingguan baru akan otomatis dibuka menjelang akhir pekan. Pantau terus halaman ini!</p>
            </div>
        @endif
    </div>
</x-app-layout>