<nav x-data="{ open: false }" class="dash-nav">
    <div class="container dash-nav-wrap">
        <a class="brand" href="{{ Auth::check() ? route('dashboard') : url('/') }}">Abdi<span>nara</span>.id</a>

        <div class="dash-nav-links">
            @auth
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

                {{-- Menu Member: Tryout --}}
                @if (Auth::user()->can('take tryout') || Auth::user()->hasAnyRole(['admin', 'super-admin', 'member']))
                    <a href="{{ route('tryout.index') }}"
                        class="{{ request()->routeIs('tryout.index') ? 'active' : '' }}">Tryout CAT</a>
                @endif

                <a href="{{ route('practice.index') }}"
                    class="{{ request()->routeIs('practice.*') ? 'active' : '' }}">Latihan Soal</a>

                {{-- Dropdown Kompetisi & Duel --}}
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center gap-1 {{ request()->routeIs('tournament.*') || request()->routeIs('battle.*') || request()->routeIs('tryout.results*') ? 'active' : '' }}" 
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>🏆 Kompetisi & Duel</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start shadow border-0 rounded-4 p-2 mt-2">
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center justify-content-between gap-3 {{ request()->routeIs('tournament.*') ? 'active bg-primary text-white' : '' }}" 
                               href="{{ route('tournament.index') }}">
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bi bi-trophy-fill text-warning"></i>
                                    <strong>Liga Tryout Mingguan</strong>
                                </span>
                                <span class="badge bg-danger rounded-pill px-2 py-1 small">Event</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center justify-content-between gap-3 mt-1 {{ request()->routeIs('battle.*') ? 'active bg-primary text-white' : '' }}" 
                               href="{{ route('battle.index') }}">
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bi bi-swords text-danger"></i>
                                    <strong>CAT Battle 1 vs 1</strong>
                                </span>
                                <span class="badge bg-warning text-dark rounded-pill px-2 py-1 small">Duel</span>
                            </a>
                        </li>
                        @if (Auth::user()->can('take tryout') || Auth::user()->hasAnyRole(['admin', 'super-admin', 'member']))
                            <li><hr class="dropdown-divider my-2"></li>
                            <li>
                                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 {{ request()->routeIs('tryout.results*') ? 'active bg-primary text-white' : '' }}" 
                                   href="{{ route('tryout.results') }}">
                                    <i class="bi bi-graph-up text-primary"></i>
                                    <span>Hasil & Analisis AI Saya</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @else
                <a href="{{ url('/') }}">Beranda</a>
                <a href="{{ route('practice.index') }}" class="{{ request()->routeIs('practice.*') ? 'active' : '' }}">Latihan Soal</a>
                <a href="{{ route('tournament.index') }}" class="{{ request()->routeIs('tournament.*') ? 'active' : '' }}">🏆 Liga Tryout</a>
                <a href="{{ route('battle.index') }}">⚔️ Duel 1 vs 1</a>
                <a href="{{ route('subscription.index') }}">Paket Premium</a>
            @endauth
        </div>

        <div class="dash-user" style="display: flex; align-items: center; gap: 0.75rem;">
            <button class="btn btn-ghost p-1 text-secondary" id="themeSwitcher" title="Ganti Tema"
                onclick="toggleTheme()">
                <i class="bi bi-moon-stars" id="themeIcon"></i>
            </button>

            @auth
                {{-- User Profile Dropdown on Desktop --}}
                <div class="dropdown">
                    <button class="btn user-profile-btn d-flex align-items-center gap-2 py-1 px-3 rounded-pill shadow-sm dropdown-toggle" 
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 13px;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="fw-bold user-name-text" style="font-size: 0.88rem; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ Auth::user()->name }}
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2 mt-2" style="min-width: 220px;">
                        <li class="px-3 py-2">
                            <small class="text-secondary d-block">Masuk sebagai:</small>
                            <strong class="text-body d-block text-truncate">{{ Auth::user()->name }}</strong>
                            <span class="badge bg-primary-subtle text-primary mt-1">{{ Auth::user()->roles->pluck('name')->first() ?? 'Member' }}</span>
                        </li>
                        <li><hr class="dropdown-divider my-2"></li>
                        @role('admin|super-admin')
                            <li>
                                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 text-warning" href="/admin">
                                    <i class="bi bi-gear-fill"></i> ⚙️ Admin Panel
                                </a>
                            </li>
                        @endrole
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 {{ request()->routeIs('subscription.index') ? 'active bg-primary text-white' : '' }}" 
                               href="{{ route('subscription.index') }}">
                                <i class="bi bi-star-fill text-warning"></i> Paket Premium
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 {{ request()->routeIs('subscription.history') ? 'active bg-primary text-white' : '' }}" 
                               href="{{ route('subscription.history') }}">
                                <i class="bi bi-clock-history text-success"></i> Riwayat Langganan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 {{ request()->routeIs('profile.edit') ? 'active bg-primary text-white' : '' }}" 
                               href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-fill text-info"></i> Pengaturan Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-2"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Keluar Akun
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Mobile Bottom Navigation --}}
<div class="dash-bottom-nav d-lg-none">
    @auth
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house{{ request()->routeIs('dashboard') ? '-fill' : '' }}"></i>
            <span>Beranda</span>
        </a>

        @if (Auth::user()->can('take tryout') || Auth::user()->hasAnyRole(['admin', 'super-admin', 'member']))
            <a href="{{ route('tryout.index') }}" class="{{ request()->routeIs('tryout.index') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text{{ request()->routeIs('tryout.index') ? '-fill' : '' }}"></i>
                <span>Tryout</span>
            </a>
        @endif

        <a href="{{ route('tournament.index') }}" class="{{ request()->routeIs('tournament.*') ? 'active' : '' }}">
            <i class="bi bi-trophy{{ request()->routeIs('tournament.*') ? '-fill' : '' }}"></i>
            <span>Liga</span>
        </a>

        <a href="{{ route('battle.index') }}" class="{{ request()->routeIs('battle.*') ? 'active' : '' }}">
            <i class="bi bi-lightning-charge{{ request()->routeIs('battle.*') ? '-fill' : '' }}"></i>
            <span>Duel</span>
        </a>

        <a href="#" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas">
            <i class="bi bi-grid-fill"></i>
            <span>Menu</span>
        </a>
    @else
        <a href="{{ url('/') }}">
            <i class="bi bi-house-fill"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('practice.index') }}" class="{{ request()->routeIs('practice.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i>
            <span>Soal</span>
        </a>
        <a href="{{ route('tournament.index') }}">
            <i class="bi bi-trophy-fill"></i>
            <span>Liga</span>
        </a>
        <a href="{{ route('battle.index') }}">
            <i class="bi bi-lightning-charge-fill"></i>
            <span>Duel</span>
        </a>
        <a href="{{ route('login') }}">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Masuk</span>
        </a>
    @endauth
</div>

<!-- Extra Mobile Menu Offcanvas -->
@auth
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="mobileMenuOffcanvas"
    aria-labelledby="mobileMenuOffcanvasLabel"
    style="height: auto; max-height: 85vh; border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem; margin-bottom: 74px; z-index: 1055;">
    <div class="offcanvas-header pb-2 border-bottom">
        <h5 class="offcanvas-title fw-bold text-body d-flex align-items-center gap-2" id="mobileMenuOffcanvasLabel">
            <i class="bi bi-grid-fill text-primary"></i> Menu Lengkap LMS
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body pt-3">
        <!-- User Info Card -->
        <div class="card border-0 bg-primary bg-opacity-10 rounded-4 p-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary text-white fs-5 fw-bold d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <h6 class="fw-bold text-body mb-0 text-truncate">{{ Auth::user()->name }}</h6>
                    <small class="text-secondary d-block text-truncate">{{ Auth::user()->email }}</small>
                </div>
            </div>
        </div>

        <!-- Section 1: Belajar & Kompetisi -->
        <div class="mb-3">
            <small class="text-uppercase fw-bold text-secondary px-2 d-block mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">
                Belajar & Simulasi
            </small>
            <div class="list-group list-group-flush rounded-4 border overflow-hidden">
                <a href="{{ route('practice.index') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-journal-text text-info fs-5"></i>
                        <div>
                            <span class="fw-bold text-body d-block">Bank Latihan Soal Gratis</span>
                            <small class="text-secondary">1.373+ soal latihan & pembahasan kisi-kisi</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                <a href="{{ route('practice.calculator') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-calculator text-primary fs-5"></i>
                        <div>
                            <span class="fw-bold text-body d-block">Kalkulator Skor SKD 2026</span>
                            <small class="text-secondary">Hitung estimasi nilai & cek passing grade BKN</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                <a href="{{ route('practice.kisi-kisi') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
                        <div>
                            <span class="fw-bold text-body d-block">Kisi-Kisi Resmi Permenpan-RB</span>
                            <small class="text-secondary">Panduan materi SKD & unduh ringkasan</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                <a href="{{ route('tournament.index') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-trophy-fill text-warning fs-5"></i>
                        <div>
                            <span class="fw-bold text-body d-block">🏆 Liga Tryout Mingguan</span>
                            <small class="text-secondary">Simulasi nasional akhir pekan & hadiah e-wallet</small>
                        </div>
                    </div>
                    <span class="badge bg-danger rounded-pill px-2 py-1 small">Event</span>
                </a>

                <a href="{{ route('battle.index') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-lightning-charge-fill text-danger fs-5"></i>
                        <div>
                            <span class="fw-bold text-body d-block">⚔️ CAT Battle 1 vs 1</span>
                            <small class="text-secondary">Mode duel seru 10 soal cepat lawan teman/bot</small>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1 small">Duel</span>
                </a>

                @if (Auth::user()->can('take tryout') || Auth::user()->hasAnyRole(['admin', 'super-admin', 'member']))
                    <a href="{{ route('tryout.results') }}"
                        class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-graph-up text-primary fs-5"></i>
                            <div>
                                <span class="fw-bold text-body d-block">Hasil & Analisis Radar AI</span>
                                <small class="text-secondary">Riwayat ujian & unduh Story Card 9:16</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                @endif
            </div>
        </div>

        <!-- Section 2: Layanan & Akun -->
        <div class="mb-3">
            <small class="text-uppercase fw-bold text-secondary px-2 d-block mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">
                Layanan & Akun
            </small>
            <div class="list-group list-group-flush rounded-4 border overflow-hidden">
                <a href="{{ route('subscription.index') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-star-fill text-warning fs-5"></i>
                        <div>
                            <span class="fw-bold text-body d-block">Paket Premium VIP</span>
                            <small class="text-secondary">Akses penuh bank soal & tryout akbar</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                <a href="{{ route('subscription.history') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-clock-history text-success fs-5"></i>
                        <div>
                            <span class="fw-bold text-body d-block">Riwayat Langganan & Invoice</span>
                            <small class="text-secondary">Status pembayaran & bukti transaksi resmi</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-person-gear text-secondary fs-5"></i>
                        <div>
                            <span class="fw-bold text-body d-block">Pengaturan Profil</span>
                            <small class="text-secondary">Ubah password, data diri, target impian</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                @role('admin|super-admin')
                    <a href="/admin" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 text-warning">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-gear-fill fs-5"></i>
                            <div>
                                <span class="fw-bold d-block">⚙️ Admin Panel Filament</span>
                                <small class="text-secondary">Kelola bank soal, user, paket & tryout</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                @endrole
            </div>
        </div>

        <!-- Section 3: Tema & Logout -->
        <div class="card border-0 bg-body-tertiary rounded-4 p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-moon-stars text-body fs-5"></i>
                    <span class="text-body fw-bold">Mode Tampilan Gelap</span>
                </div>
                <div class="form-check form-switch m-0" style="padding-left: 0;">
                    <input class="form-check-input ms-0" type="checkbox" role="switch" id="themeToggleMobile"
                        style="width: 3em; height: 1.5em; cursor: pointer;" onchange="toggleTheme()">
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mb-4">
            @csrf
            <button type="submit" class="btn btn-danger w-100 rounded-pill py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-right fs-5"></i> Keluar Akun
            </button>
        </form>
    </div>
</div>
@endauth

<script>
    function updateThemeIcon(theme) {
        const icon = document.getElementById('themeIcon');
        if (icon) {
            if (theme === 'dark') {
                icon.classList.remove('bi-moon-stars');
                icon.classList.add('bi-sun-fill');
            } else {
                icon.classList.remove('bi-sun-fill');
                icon.classList.add('bi-moon-stars');
            }
        }
    }

    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        // Dispatch Custom Event so app.blade.php handles it properly
        document.dispatchEvent(new CustomEvent('theme-changed', {
            detail: {
                theme: newTheme
            }
        }));

        updateThemeIcon(newTheme);
    }

    // Set initial icon correctly
    document.addEventListener('DOMContentLoaded', () => {
        const initTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
        updateThemeIcon(initTheme);
    });
</script>
