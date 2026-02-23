<div class="container py-5 mt-3" style="max-width: 900px;">
    <div class="mb-5 text-center text-md-start">
        <h2 class="display-6 fw-bold text-body mb-2">Riwayat Hasil Tryout</h2>
        <p class="text-body-secondary fs-5">Lihat kembali skor dan detail dari tryout yang telah Anda kerjakan.</p>
    </div>

    @if ($sessions->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
            <i class="bi bi-clock-history fs-1 opacity-50 mb-3 d-block"></i>
            <h5 class="fw-normal mb-3">Belum ada riwayat tryout.</h5>
            <a href="{{ route('tryout.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Mulai Tryout Sekarang
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach ($sessions as $session)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4 transition-hover">
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="card-title fw-bold text-body mb-3" style="line-height: 1.4;">
                                {{ $session->tryout->title ?? 'Tryout Tidak Diketahui' }}
                            </h5>

                            <div class="d-flex align-items-center gap-4 mb-4">
                                @php
                                    $passingGrade = ($session->tryout->total_questions ?? 0) * 0.7;
                                    $badgeClass = $session->score >= $passingGrade ? 'success' : 'danger';
                                    $iconClass =
                                        $session->score >= $passingGrade ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
                                    $statusText = $session->score >= $passingGrade ? 'Lulus' : 'Belum Lulus';
                                @endphp

                                <div class="text-center">
                                    <span class="d-block text-secondary small mb-1">Skor Akhir</span>
                                    <span
                                        class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }} border border-{{ $badgeClass }} rounded-pill px-3 py-2 fs-6">
                                        <i class="bi {{ $iconClass }} me-1"></i> {{ $session->score }}
                                    </span>
                                </div>

                                <div class="border-start ps-4">
                                    <div class="d-flex align-items-center text-secondary small mb-2">
                                        <i class="bi bi-journal-text fs-6 text-primary me-2"></i>
                                        <span class="fw-medium">{{ $session->tryout->total_questions ?? '-' }}
                                            Soal</span>
                                    </div>
                                    <div class="d-flex align-items-center text-secondary small">
                                        <i class="bi bi-calendar-check fs-6 text-info me-2"></i>
                                        <span
                                            class="fw-medium">{{ $session->finished_at?->translatedFormat('d M Y') ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Spacer to push button to bottom -->
                            <div
                                class="mt-auto pt-3 border-top border-light d-flex justify-content-between align-items-center">
                                <span class="text-body-secondary small">
                                    <i class="bi bi-clock me-1"></i> {{ $session->finished_at?->format('H:i') ?? '-' }}
                                </span>
                                <a href="{{ route('tryout.results.show', $session->id) }}"
                                    class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                                    Detail <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $sessions->links() }}
        </div>
    @endif
</div>
