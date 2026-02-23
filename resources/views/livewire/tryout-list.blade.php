<div class="container py-5 mt-3" style="max-width: 900px;">
    <div class="mb-5 text-center text-md-start">
        <h2 class="display-6 fw-bold text-dark mb-2">Daftar Tryout</h2>
        <p class="text-secondary fs-5">Pilih paket tryout yang tersedia untuk menguji kemampuan Anda.</p>
    </div>

    @if ($tryouts->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
            <i class="bi bi-inbox fs-1 opacity-50 mb-3 d-block"></i>
            <h5 class="fw-normal">Belum ada tryout yang tersedia saat ini.</h5>
        </div>
    @else
        <div class="row g-4">
            @foreach ($tryouts as $t)
                @php $mySession = $mySessions[$t->id] ?? null; @endphp
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4 transition-hover">
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark mb-3" style="line-height: 1.4;">{{ $t->title }}
                            </h5>

                            <div class="d-flex align-items-center gap-4 mb-4">
                                <div class="d-flex align-items-center text-secondary small">
                                    <i class="bi bi-journal-text fs-5 text-primary me-2"></i>
                                    <span class="fw-medium">{{ $t->questions_count }} Soal</span>
                                </div>
                                <div class="d-flex align-items-center text-secondary small">
                                    <i class="bi bi-stopwatch fs-5 text-warning me-2"></i>
                                    <span class="fw-medium">{{ $t->duration_minutes }} Menit</span>
                                </div>
                            </div>

                            <!-- Spacer to push button to bottom -->
                            <div class="mt-auto pt-3 border-top border-light">
                                @if ($mySession && $mySession->status === 'finished')
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fw-semibold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Selesai (Skor:
                                            {{ $mySession->score }})
                                        </span>
                                        <a href="{{ route('tryout.results.show', $mySession->id) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">Detail</a>
                                    </div>
                                @elseif($mySession && $mySession->status === 'ongoing' && !$mySession->isExpired())
                                    <button wire:click="startTryout({{ $t->id }})"
                                        class="btn btn-warning w-100 rounded-pill py-2 fw-bold text-dark shadow-sm">
                                        <i class="bi bi-play-fill fs-5 align-text-bottom"></i> Lanjutkan Sesi
                                    </button>
                                @else
                                    <button wire:click="startTryout({{ $t->id }})"
                                        class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                                        Mulai Kerjakan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $tryouts->links() }}
        </div>
    @endif
</div>
