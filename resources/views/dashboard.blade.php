<x-app-layout>
    <div class="container py-5 mt-4">
        <!-- Welcome Section -->
        <div class="row mb-5 align-items-center">
            <div class="col-lg-8">
                <p class="text-primary fw-semibold mb-1 text-uppercase tracking-wide" style="letter-spacing: 1px;">
                    Dashboard LMS</p>
                <h2 class="display-6 fw-bold mb-3 text-body">Selamat datang, {{ explode(' ', Auth::user()->name)[0] }}!
                    👋</h2>
                <p class="text-body-secondary fs-5 mb-0">Pantau progres belajar harian, lanjutkan modul aktif, dan capai
                    target
                    kelulusan Anda secara terukur.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="#materi" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm me-2">Lanjut Belajar</a>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Edit
                    Profil</a>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 p-2 transition-hover">
                    <div class="card-body d-flex flex-column justify-content-center align-items-start">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-3 mb-3">
                            <i class="bi bi-book fs-4"></i>
                        </div>
                        <h6 class="card-title text-body-secondary fw-bold mb-1 text-uppercase"
                            style="font-size: 0.8rem; letter-spacing: 0.5px;">Progress Modul</h6>
                        <h3 class="fw-bolder text-body mb-0">{{ $moduleProgressPercent }}%</h3>
                        <small class="text-body-secondary mt-2">{{ $completedModules }} dari {{ $totalModules }}
                            selesai</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 p-2 transition-hover">
                    <div class="card-body d-flex flex-column justify-content-center align-items-start">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle p-3 mb-3">
                            <i class="bi bi-laptop fs-4"></i>
                        </div>
                        <h6 class="card-title text-body-secondary fw-bold mb-1 text-uppercase"
                            style="font-size: 0.8rem; letter-spacing: 0.5px;">Simulasi CAT</h6>
                        <h3 class="fw-bolder text-body mb-0">{{ $totalSessions }} Sesi</h3>
                        <small class="text-body-secondary mt-2">Rata-rata skor {{ $avgScore }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 p-2 transition-hover">
                    <div class="card-body d-flex flex-column justify-content-center align-items-start">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle p-3 mb-3">
                            <i class="bi bi-bullseye fs-4"></i>
                        </div>
                        <h6 class="card-title text-body-secondary fw-bold mb-1 text-uppercase"
                            style="font-size: 0.8rem; letter-spacing: 0.5px;">Target Saya</h6>
                        @if ($userTarget)
                            <h3 class="fw-bolder text-body mb-0">
                                {{ $userTarget->current_value }}/{{ $userTarget->target_value }}</h3>
                            <small
                                class="text-body-secondary mt-2">{{ $userTarget->target_value - $userTarget->current_value }}
                                target lagi</small>
                        @else
                            <h3 class="fw-bolder text-body mb-0">-</h3>
                            <small class="text-body-secondary mt-2">Belum ada target diatur</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 p-2 transition-hover">
                    <div class="card-body d-flex flex-column justify-content-center align-items-start">
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle p-3 mb-3">
                            <i class="bi bi-trophy fs-4"></i>
                        </div>
                        <h6 class="card-title text-body-secondary fw-bold mb-1 text-uppercase"
                            style="font-size: 0.8rem; letter-spacing: 0.5px;">Ranking Internal</h6>
                        <h3 class="fw-bolder text-body mb-0">#{{ $rank ?: '-' }}</h3>
                        <small class="text-body-secondary mt-2">Dari {{ $totalParticipants }} peserta</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Map component (Github Style Login/Testing Chart) -->
        <div class="row mb-5">
            <div class="col-12">
                <livewire:activity-map />
            </div>
        </div>

        <!-- Subscription Status Alert -->
        @if (Auth::user()->isSubscribed())
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-success d-flex align-items-center justify-content-between rounded-4 shadow-sm border-0 border-start border-5 border-success p-4 mb-4"
                        role="alert" style="background-color: var(--bs-success-bg-subtle, #d1e7dd);">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-shield-check display-6 text-success"></i>
                            <div>
                                <h5 class="fw-bold text-success mb-1">Status Premium Aktif!</h5>
                                <p class="text-body-secondary mb-0">Sisa masa aktif Anda sampai dengan
                                    <strong>{{ Auth::user()->subscription_expires_at->format('d M Y, H:i') }}</strong>
                                    ({{ round(now()->diffInDays(Auth::user()->subscription_expires_at)) }} hari lagi).
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('subscription.history') }}"
                            class="btn btn-outline-success rounded-pill fw-bold shadow-sm px-4">Riwayat Transaksi</a>
                    </div>
                </div>
            </div>
        @else
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-secondary d-flex align-items-center justify-content-between rounded-4 shadow-sm border-0 border-start border-5 border-secondary p-4 mb-4"
                        role="alert" style="background-color: var(--bs-secondary-bg-subtle, #e2e3e5);">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-shield-x display-6 text-secondary"></i>
                            <div>
                                <h5 class="fw-bold text-secondary mb-1">Status Gratis / Tidak Aktif!</h5>
                                <p class="text-body-secondary mb-0">Akses terbatas. Silakan berlangganan untuk membuka
                                    semua fitur tryout CAT dan materi eksklusif.</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('subscription.history') }}"
                                class="btn btn-outline-secondary rounded-pill fw-bold shadow-sm px-4">Riwayat</a>
                            <a href="{{ route('subscription.index') }}"
                                class="btn btn-primary rounded-pill fw-bold shadow-sm px-4"><i
                                    class="bi bi-star me-1"></i> Upgrade</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4" id="materi">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <!-- Modul Aktif -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div
                        class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-body">Modul Aktif</h5>
                        <a href="#" class="text-decoration-none fw-semibold">Lihat Semua</a>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-4">
                            @forelse($activeModules as $module)
                                @php
                                    $prog = $module->progress->first()?->progress_percentage ?? 0;
                                @endphp
                                <div>
                                    <div class="d-flex justify-content-between align-items-end mb-2">
                                        <div>
                                            <h6 class="fw-bold text-body mb-1">{{ $module->category }}:
                                                {{ $module->title }}</h6>
                                            <p class="text-body-secondary small mb-0">
                                                {{ $module->description ?? 'Materi pembelajaran' }}</p>
                                        </div>
                                        <span class="fw-bold text-primary">{{ $prog }}%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 8px;">
                                        <div class="progress-bar bg-primary rounded-pill" role="progressbar"
                                            style="width: {{ $prog }}%" aria-valuenow="{{ $prog }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-body-secondary py-3">
                                    <p>Belum ada modul yang tersedia atau aktif.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Agenda -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div
                        class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-body mb-0">Agenda Belajar</h5>
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">Fokus</span>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush">
                            @forelse($agendas as $agenda)
                                <li
                                    class="list-group-item bg-transparent px-0 py-3 border-secondary border-opacity-25 d-flex gap-3 align-items-start {{ $loop->last ? 'border-bottom-0' : '' }}">
                                    <span
                                        class="badge bg-body-secondary text-body border border-secondary border-opacity-50 py-2 px-3 rounded-3 font-monospace">
                                        {{ \Carbon\Carbon::parse($agenda->time)->format('H:i') }}
                                    </span>
                                    <div>
                                        <h6 class="fw-bold text-body mb-1">{{ $agenda->title }}</h6>
                                        <p class="text-body-secondary small mb-0">{{ $agenda->description }}</p>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item bg-transparent px-0 py-3 border-0 text-body-secondary">
                                    Tidak ada agenda khusus hari ini.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar Area -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-bell-fill text-warning"></i> Pengumuman
                        </h5>
                        <div class="d-flex flex-column gap-3">
                            @forelse($announcements as $announcement)
                                <div class="bg-white bg-opacity-10 p-3 rounded-3">
                                    <h6 class="fw-bold mb-1 border-bottom border-light border-opacity-25 pb-1">
                                        {{ $announcement->title }}</h6>
                                    <p class="mb-0 fw-medium small">{{ $announcement->content }}</p>
                                </div>
                            @empty
                                <div class="bg-white bg-opacity-10 p-3 rounded-3">
                                    <p class="mb-0 fw-medium">Belum ada pengumuman terbaru.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <livewire:user-target-widget />

                <livewire:leaderboard />

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 text-body">
                        <h5 class="fw-bold mb-4 text-body">Rekomendasi Berikutnya</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="btn btn-outline-secondary rounded-pill btn-sm px-3 py-2">Latihan
                                50 Soal TIU</a>
                            <a href="#" class="btn btn-outline-secondary rounded-pill btn-sm px-3 py-2">Video
                                Psikotes Dasar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
