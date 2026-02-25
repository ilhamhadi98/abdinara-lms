<x-guest-layout>
    <div class="mb-8 text-center sm:text-left">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Buat Akun Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Daftarkan akun untuk mulai akses LMS Abdinara.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
            <input id="name"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm"
                type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="Masukkan nama lengkap Anda" />
            @error('name')
                <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
            <input id="email"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm"
                type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="Masukkan alamat email" />
            @error('email')
                <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                <input id="password"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm"
                    type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                @error('password')
                    <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi
                    Password</label>
                <input id="password_confirmation"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm"
                    type="password" name="password_confirmation" required autocomplete="new-password"
                    placeholder="••••••••" />
                @error('password_confirmation')
                    <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="pt-1">
            <div class="g-recaptcha overflow-hidden rounded-xl border border-slate-200 bg-slate-50/50 min-h-[78px]"
                data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
                <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full bg-[#144272] hover:bg-[#0a2647] text-white font-semibold py-3 px-4 rounded-xl shadow-[0_4px_14px_0_rgba(20,66,114,0.39)] transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                <span>Daftar Akun Gratis</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </button>
        </div>

        <p class="text-center text-sm text-slate-500 mt-6 pt-2 border-t border-slate-100">
            Sudah punya akun?
            <a href="{{ route('login') }}"
                class="font-semibold text-blue-600 hover:text-blue-500 hover:underline transition-colors">Masuk di
                sini</a>
        </p>
    </form>
</x-guest-layout>
