<x-guest-layout>
    <div class="auth-head">
        <h2>Masuk Akun</h2>
        <p>Login untuk melanjutkan proses belajar Anda.</p>
    </div>

    @if (session('status'))
        <div class="auth-alert">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-row">
            <label for="email">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required
                autofocus autocomplete="username" />
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <label for="password">Password</label>
            <input id="password" class="auth-input" type="password" name="password" required
                autocomplete="current-password" />
            @error('password')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-meta">
            <label for="remember_me" class="remember">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <div class="form-row">
            <button type="submit" class="btn btn-primary auth-submit">Login</button>
        </div>

        <p class="auth-switch">
            Belum punya akun?
            <a class="auth-link" href="{{ route('register') }}">Daftar sekarang</a>
        </p>
    </form>
</x-guest-layout>
