<div class="container py-5 mt-3" style="max-width: 960px;">
    {{-- Header & Search Bar --}}
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h2 class="display-6 fw-bold text-dark mb-1">Daftar Tryout</h2>
            <p class="text-secondary fs-6 mb-0">Pilih paket tryout yang tersedia untuk menguji kemampuan Anda.</p>
        </div>

        {{-- Search Input --}}
        <div style="min-width: 280px; max-width: 380px;" class="w-100">
            <div class="input-group shadow-sm rounded-pill overflow-hidden border border-1 border-secondary-subtle bg-white">
                <span class="input-group-text bg-transparent border-0 pe-2 ps-3 text-secondary">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                    wire:model.live.debounce.300ms="search"
                    class="form-control border-0 bg-transparent py-2 shadow-none text-dark"
                    placeholder="Cari judul tryout..."
                    aria-label="Cari tryout">
                @if (trim($search) !== '')
                    <button class="btn btn-link text-secondary text-decoration-none px-3"
                        type="button"
                        wire:click="clearSearch"
                        title="Hapus pencarian">
                        <i class="bi bi-x-circle-fill text-muted"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Search info banner if searching --}}
    @if (trim($search) !== '')
        <div class="d-flex align-items-center justify-content-between bg-primary-subtle text-primary-emphasis px-3 py-2 rounded-3 mb-4 small">
            <div>
                <i class="bi bi-funnel-fill me-1"></i> Menampilkan hasil pencarian: <strong>"{{ $search }}"</strong> ({{ $tryouts->total() }} tryout ditemukan)
            </div>
            <button wire:click="clearSearch" class="btn btn-sm btn-link text-primary text-decoration-none p-0 fw-semibold">
                Reset Filter
            </button>
        </div>
    @endif

    {{-- Tryout Cards / Empty States --}}
    @if ($tryouts->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted my-4">
            <i class="bi bi-inbox fs-1 opacity-50 mb-3 d-block"></i>
            @if (trim($search) !== '')
                <h5 class="fw-bold text-dark">Tidak ada tryout ditemukan</h5>
                <p class="text-secondary small mb-3">Tidak ditemukan tryout yang cocok dengan kata kunci "<strong>{{ $search }}</strong>".</p>
                <div>
                    <button wire:click="clearSearch" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                        Lihat Semua Tryout
                    </button>
                </div>
            @else
                <h5 class="fw-normal">Belum ada tryout yang tersedia saat ini.</h5>
            @endif
        </div>
    @else
        <div class="row g-4 mb-4">
            @foreach ($tryouts as $t)
                @php $mySession = $mySessions[$t->id] ?? null; @endphp
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4 transition-hover">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <h5 class="card-title fw-bold text-dark mb-0 pe-2" style="line-height: 1.4;">
                                    {{ $t->title }}
                                </h5>
                                @if($t->category)
                                    <span class="badge bg-light text-primary border border-primary-subtle rounded-pill px-2 py-1 small">
                                        {{ $t->category->name }}
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-3 my-3">
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
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fw-semibold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Selesai (Skor: {{ $mySession->score }})
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

        {{-- Pagination & Jump to Page Section --}}
        @if ($tryouts->hasPages())
            <div class="card border-0 shadow-sm rounded-4 p-3 mt-4 bg-light-subtle">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                    {{-- Page Summary --}}
                    <div class="small text-secondary fw-medium">
                        Halaman <strong>{{ $tryouts->currentPage() }}</strong> dari <strong>{{ $tryouts->lastPage() }}</strong> 
                        <span class="text-muted">({{ $tryouts->total() }} total tryout)</span>
                    </div>

                    {{-- Main Pagination Links --}}
                    <div class="d-flex justify-content-center">
                        {{ $tryouts->links() }}
                    </div>

                    {{-- Jump to Page Input Form --}}
                    <form wire:submit.prevent="jumpToPage" class="d-flex align-items-center gap-2">
                        <label for="jumpPageInput" class="small text-secondary text-nowrap mb-0">Lompat ke:</label>
                        <div class="input-group input-group-sm" style="width: 110px;">
                            <input type="number"
                                id="jumpPageInput"
                                wire:model="jumpPage"
                                min="1"
                                max="{{ $tryouts->lastPage() }}"
                                class="form-control form-control-sm text-center rounded-start-pill"
                                placeholder="{{ $tryouts->currentPage() }}">
                            <button type="submit" class="btn btn-primary btn-sm rounded-end-pill px-2">
                                Go
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>

