<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center py-2 gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
                        <li class="breadcrumb-item active text-body" aria-current="page">CAT Battle 1 vs 1</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-body mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-swords text-danger"></i> CAT Battle 1 vs 1 (Mode Duel)
                </h3>
            </div>
            <div>
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                    <i class="bi bi-lightning-charge-fill me-1"></i> Mode Cepat 10 Soal
                </span>
            </div>
        </div>
    </x-slot>

    <div class="container py-4 mb-5">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Hero Battle Banner -->
        <div class="card border-0 rounded-4 shadow-sm mb-5 text-white overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold mb-3 fs-6">
                            <i class="bi bi-fire me-1"></i> Arena Kompetisi Head-to-Head
                        </span>
                        <h1 class="display-6 fw-bold mb-3 text-white">Adu Ketangkasan dan Kecepatan Menjawab Soal CAT</h1>
                        <p class="fs-5 opacity-75 mb-4">
                            Tantang teman belajarmu atau bertanding melawan lawan acak dari seluruh Indonesia dalam 10 butir soal cepat!
                        </p>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('battle.quick') }}" class="btn btn-warning text-dark btn-lg rounded-pill px-4 fw-bold shadow">
                                <i class="bi bi-lightning-charge-fill me-1"></i> Cari Lawan Cepat
                            </a>
                            <form action="{{ route('battle.create') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold">
                                    <i class="bi bi-plus-circle me-1"></i> Buat Room & Ajak Teman
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Join via Code Box -->
                    <div class="col-lg-5">
                        <div class="card border-0 rounded-4 p-4 text-dark shadow-lg" style="background: rgba(255, 255, 255, 0.95);">
                            <h5 class="fw-bold mb-2">Punya Kode Room?</h5>
                            <p class="text-secondary small mb-3">Masukkan 6 digit kode room yang dibagikan temanmu untuk langsung mulai duel.</p>

                            <form action="{{ route('battle.join') }}" method="POST">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="text" name="room_code" class="form-control form-control-lg rounded-start-pill text-uppercase fw-bold text-center" placeholder="KODE ROOM" maxlength="10" required>
                                    <button class="btn btn-primary rounded-end-pill px-4 fw-bold" type="submit">
                                        Masuk <i class="bi bi-box-arrow-in-right ms-1"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                    <small class="text-secondary text-uppercase fw-bold">Total Duel</small>
                    <h2 class="display-6 fw-bold text-body mt-1 mb-0">{{ $totalBattles }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                    <small class="text-secondary text-uppercase fw-bold">Total Kemenangan</small>
                    <h2 class="display-6 fw-bold text-success mt-1 mb-0">{{ $totalWins }} 🏆</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                    <small class="text-secondary text-uppercase fw-bold">Win Rate</small>
                    <h2 class="display-6 fw-bold text-primary mt-1 mb-0">{{ $winRate }}%</h2>
                </div>
            </div>
        </div>

        <!-- My Battle History -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-transparent border-bottom p-4">
                <h5 class="fw-bold text-body mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i> Riwayat Pertandingan Terakhir
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="py-3 px-4">Lawan</th>
                            <th class="text-center py-3">Skor Anda</th>
                            <th class="text-center py-3">Skor Lawan</th>
                            <th class="text-center py-3">Hasil</th>
                            <th class="text-end py-3 px-4">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($myBattles as $b)
                            @php
                                $isP1 = $b->player1_id === Auth::id();
                                $myScore = $isP1 ? $b->player1_score : $b->player2_score;
                                $opponentScore = $isP1 ? $b->player2_score : $b->player1_score;
                                $oppName = $isP1 ? ($b->player2_name ?? ($b->player2->name ?? 'Lawan')) : ($b->player1->name ?? 'Player 1');
                                $iWon = $b->winner_id === Auth::id();
                                $isDraw = $myScore === $opponentScore;
                            @endphp
                            <tr>
                                <td class="py-3 px-4 fw-medium text-body">{{ $oppName }}</td>
                                <td class="text-center fw-bold text-primary">{{ $myScore }}</td>
                                <td class="text-center fw-bold text-danger">{{ $opponentScore }}</td>
                                <td class="text-center">
                                    @if ($iWon)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill fw-bold">Menang</span>
                                    @elseif ($isDraw)
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-1 rounded-pill fw-bold">Seri</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill fw-bold">Kalah</span>
                                    @endif
                                </td>
                                <td class="text-end py-3 px-4 text-secondary small">{{ $b->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">
                                    <i class="bi bi-swords fs-1 d-block mb-2 text-muted"></i>
                                    Belum ada riwayat duel. Klik "Cari Lawan Cepat" untuk mulai bertanding!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>