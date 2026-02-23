<x-guest-layout>
    <div class="auth-head">
        <h2>Buat Akun Baru</h2>
        <p>Daftarkan akun untuk mulai akses LMS Abdinara.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-row">
            <label for="name">Nama Lengkap</label>
            <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" required
                autofocus autocomplete="name" />
            @error('name')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <label for="email">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required
                autocomplete="username" />
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <label for="password">Password</label>
            <input id="password" class="auth-input" type="password" name="password" required
                autocomplete="new-password" />
            @error('password')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required
                autocomplete="new-password" />
            @error('password_confirmation')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <button type="submit" class="btn btn-primary auth-submit">Register</button>
        </div>

        <p class="auth-switch">
            Sudah punya akun?
            <a class="auth-link" href="{{ route('login') }}">Masuk di sini</a>
        </p>
    </form>
</x-guest-layout>
