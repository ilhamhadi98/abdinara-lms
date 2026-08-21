<nav x-data="{ open: false }" class="dash-nav">
    <div class="container dash-nav-wrap">
        <a class="brand" href="{{ Auth::check() ? route('dashboard') : url('/') }}">Abdi<span>nara</span>.id</a>

        <div class="dash-nav-links">
            @auth
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

                {{-- Menu Member: Tryout --}}
                @if (Auth::user()->can('take tryout') || Auth::user()->hasAnyRole(['admin', 'super-admin', 'member']))
                    <a href="{{ route('tryout.index') }}"
                        class="{{ request()->routeIs('tryout.index') ? 'active' : '' }}">Tryout</a>
                    <a href="{{ route('tryout.results') }}"
                        class="{{ request()->routeIs('tryout.results*') ? 'active' : '' }}">Hasil Saya</a>
                @endif

                {{-- Menu Admin → Filament Panel --}}
                @role('admin|super-admin')
                    <span class="dash-nav-divider">|</span>
                    <a href="/admin" class="{{ request()->is('admin*') ? 'active' : '' }}">⚙️ Admin Panel</a>
                @endrole

                <a href="{{ route('practice.index') }}"
                    class="{{ request()->routeIs('practice.*') ? 'active' : '' }}">Latihan Soal</a>

                <a href="{{ route('subscription.index') }}"
                    class="{{ request()->routeIs('subscription.index') ? 'active' : '' }}">Paket Premium</a>

                <a href="{{ route('subscription.history') }}"
                    class="{{ request()->routeIs('subscription.history') ? 'active' : '' }}">Riwayat Langganan</a>

                <a href="{{ route('profile.edit') }}"
                    class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profil</a>
            @else
                <a href="{{ url('/') }}">Beranda</a>
                <a href="{{ route('practice.index') }}" class="{{ request()->routeIs('practice.*') ? 'active' : '' }}">Latihan Soal</a>
                <a href="{{ route('subscription.index') }}">Paket Premium</a>
            @endauth
        </div>

        <div class="dash-user" style="display: flex; align-items: center; gap: 0.75rem;">
            <button class="btn btn-ghost p-1 text-secondary" id="themeSwitcher" title="Ganti Tema"
                onclick="toggleTheme()">
                <i class="bi bi-moon-stars" id="themeIcon"></i>
            </button>
            @auth
                <span class="user-chip">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-ghost">Keluar</button>
                </form>
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

        <a href="{{ route('practice.index') }}" class="{{ request()->routeIs('practice.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text{{ request()->routeIs('practice.*') ? '-fill' : '' }}"></i>
            <span>Soal</span>
        </a>

        <a href="{{ route('subscription.index') }}" class="{{ request()->routeIs('subscription.*') ? 'active' : '' }}">
            <i class="bi bi-star{{ request()->routeIs('subscription.*') ? '-fill' : '' }}"></i>
            <span>Premium</span>
        </a>

        <a href="#" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas">
            <i class="bi bi-grid"></i>
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
        <a href="{{ route('subscription.index') }}">
            <i class="bi bi-star"></i>
            <span>Paket</span>
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
    style="height: auto; border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem; margin-bottom: 74px; z-index: 1055;">
    <div class="offcanvas-header pb-0">
        <h5 class="offcanvas-title fw-bold" id="mobileMenuOffcanvasLabel">Menu Utama</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="d-flex align-items-center mb-3">
            <div class="user-chip bg-primary-subtle text-primary fw-bold w-100 text-center py-2 px-3 rounded-pill">
                {{ Auth::user()->name }}
            </div>
        </div>

        <div class="list-group list-group-flush mb-3 rounded border">
            @if (Auth::user()->can('take tryout') || Auth::user()->hasAnyRole(['admin', 'super-admin', 'member']))
                <a href="{{ route('tryout.results') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                    <i class="bi bi-graph-up text-primary"></i> <span class="fw-medium">Hasil & Analisis Saya</span>
                </a>
            @endif

            <a href="{{ route('subscription.history') }}"
                class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                <i class="bi bi-clock-history text-success"></i> <span class="fw-medium">Riwayat Langganan</span>
            </a>

            @role('admin|super-admin')
                <a href="/admin" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                    <i class="bi bi-gear-fill text-warning"></i> <span class="fw-medium">Admin Panel</span>
                </a>
            @endrole
        </div>

        <div class="d-flex justify-content-between align-items-center px-2 mb-3">
            <span class="text-secondary fw-medium">Tampilan Gelap</span>
            <div class="form-check form-switch m-0" style="padding-left: 0;">
                <input class="form-check-input ms-0" type="checkbox" role="switch" id="themeToggleMobile"
                    style="width: 3em; height: 1.5em; cursor: pointer;" onchange="toggleTheme()">
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-2 text-center pb-2">
            @csrf
            <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold shadow-sm">
                <i class="bi bi-box-arrow-right me-2"></i> Keluar Akun
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
