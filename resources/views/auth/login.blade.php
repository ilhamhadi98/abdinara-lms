<x-guest-layout>
    <div class="mb-8 text-center sm:text-left">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Masuk Akun</h2>
        <p class="text-sm text-slate-500 mt-1">Login untuk melanjutkan proses belajar Anda.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50/50 p-4 text-sm font-medium text-blue-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
            <input id="email"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm"
                type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="Masukkan alamat email" />
            @error('email')
                <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
            <input id="password"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm"
                type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            @error('password')
                <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-1">
            <div class="g-recaptcha overflow-hidden rounded-xl border border-slate-200 bg-slate-50/50 min-h-[78px]"
                data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
                <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between py-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
                <span class="ml-2.5 text-sm text-slate-500 group-hover:text-slate-700 transition-colors">Remember
                    me</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-blue-600 hover:text-blue-500 hover:underline transition-all"
                    href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full bg-[#144272] hover:bg-[#0a2647] text-white font-semibold py-3 px-4 rounded-xl shadow-[0_4px_14px_0_rgba(20,66,114,0.39)] transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                <span>Login ke Dashboard</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </button>
        </div>

        <p class="text-center text-sm text-slate-500 mt-6 pt-2 border-t border-slate-100">
            Belum punya akun?
            <a href="{{ route('register') }}"
                class="font-semibold text-blue-600 hover:text-blue-500 hover:underline transition-colors">Daftar
                sekarang</a>
        </p>
    </form>
</x-guest-layout>
