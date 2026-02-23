<nav x-data="{ open: false }" class="dash-nav">
    <div class="container dash-nav-wrap">
        <a class="brand" href="{{ route('dashboard') }}">Abdi<span>nara</span>.id</a>

        <div class="dash-nav-links">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

            {{-- Menu Member: Tryout --}}
            @can('take tryout')
                <a href="{{ route('tryout.index') }}"
                    class="{{ request()->routeIs('tryout.index') ? 'active' : '' }}">Tryout</a>
                <a href="{{ route('tryout.results') }}"
                    class="{{ request()->routeIs('tryout.results*') ? 'active' : '' }}">Hasil Saya</a>
            @endcan

            {{-- Menu Admin → Filament Panel --}}
            @role('admin|super-admin')
                <span class="dash-nav-divider">|</span>
                <a href="/admin" class="{{ request()->is('admin*') ? 'active' : '' }}">⚙️ Admin Panel</a>
            @endrole

            <a href="{{ route('subscription.index') }}"
                class="{{ request()->routeIs('subscription.*') ? 'active' : '' }}">Paket Premium</a>

            <a href="{{ route('profile.edit') }}"
                class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profil</a>
        </div>

        <div class="dash-user" style="display: flex; align-items: center; gap: 1rem;">
            <button class="btn btn-ghost p-1 text-secondary" id="themeSwitcher" title="Ganti Tema"
                onclick="toggleTheme()">
                <i class="bi bi-moon-stars" id="themeIcon"></i>
            </button>
            <span class="user-chip">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-ghost">Keluar</button>
            </form>
        </div>

        <button class="dash-menu-btn" @click="open = ! open" type="button" aria-label="Toggle menu">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                    stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                    stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="dash-mobile hidden">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

        @can('take tryout')
            <a href="{{ route('tryout.index') }}"
                class="{{ request()->routeIs('tryout.index') ? 'active' : '' }}">Tryout</a>
            <a href="{{ route('tryout.results') }}"
                class="{{ request()->routeIs('tryout.results*') ? 'active' : '' }}">Hasil Saya</a>
        @endcan

        @role('admin|super-admin')
            <hr style="border-color: rgba(255,255,255,0.2); margin: 0.5rem 0;">
            <a href="/admin" class="{{ request()->is('admin*') ? 'active' : '' }}">⚙️ Admin Panel</a>
        @endrole

        <a href="{{ route('subscription.index') }}"
            class="{{ request()->routeIs('subscription.*') ? 'active' : '' }}">Paket Premium</a>

        <a href="{{ route('profile.edit') }}"
            class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profil</a>
        <p>{{ Auth::user()->email }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Keluar Akun</button>
        </form>
    </div>
</nav>

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
