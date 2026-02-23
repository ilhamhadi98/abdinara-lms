<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0 text-body">
            {{ __('Pilih Paket Premium') }}
        </h2>
    </x-slot>

    <div class="container py-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <p class="text-primary fw-semibold mb-1 text-uppercase tracking-wide" style="letter-spacing: 1px;">
                        Upgrade Akun Anda
                    </p>
                    <h2 class="display-6 fw-bold mb-3 text-body">Akses Fitur Eksklusif LMS</h2>
                    <p class="text-body-secondary fs-5 mb-0">Berlangganan sekarang untuk mendapatkan akses penuh ke
                        simulasi CAT, materi belajar terstruktur, dan pemantauan progres yang akurat.</p>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row g-4 justify-content-center">
                    @foreach ($packages as $pkg)
                        <div class="col-md-6">
                            <div
                                class="card border-{{ $loop->iteration % 2 == 0 ? 'primary' : 'secondary' }} border-opacity-25 shadow-sm h-100 rounded-4 p-2 transition-hover position-relative overflow-hidden">
                                @if ($loop->iteration % 2 == 0)
                                    <div class="position-absolute top-0 end-0 bg-primary text-white px-3 py-1 rounded-bottom-start-3 shadow-sm fw-bold"
                                        style="font-size: 0.8rem;">REKOMENDASI</div>
                                @endif
                                <div class="card-body p-4 d-flex flex-column text-center">
                                    <h3 class="fw-bolder mb-3 text-body">{{ $pkg->name }}</h3>
                                    <p class="text-body-secondary mb-4 flex-grow-1">{{ $pkg->description }}</p>

                                    <div class="mb-4">
                                        <span class="fs-1 fw-bold text-primary">Rp
                                            {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="bg-body-tertiary rounded-3 py-3 px-2 border mb-4">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <i class="bi bi-clock-history text-primary fs-5"></i>
                                            <span class="fw-semibold text-body">Masa Aktif: {{ $pkg->duration_days }}
                                                Hari</span>
                                        </div>
                                    </div>

                                    <form action="{{ route('subscription.checkout', $pkg->id) }}" method="POST"
                                        class="w-full mt-auto">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-{{ $loop->iteration % 2 == 0 ? 'primary' : 'outline-primary' }} btn-lg rounded-pill w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                                            <i class="bi bi-cart2 fs-5"></i> Beli Sekarang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Status Info -->
                <div class="mt-5">
                    @if (Auth::user()->isSubscribed())
                        <div class="card border-0 bg-success bg-opacity-10 shadow-sm rounded-4">
                            <div class="card-body p-4 d-flex align-items-center gap-4">
                                <div class="text-success display-5 bg-white rounded-circle p-3 shadow-sm">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-success mb-2">Status Akun: Premium</h5>
                                    <p class="text-body-secondary mb-0">
                                        Akses paket Anda berlaku hingga <strong
                                            class="text-body">{{ Auth::user()->subscription_expires_at->format('d M Y, H:i') }}</strong>.
                                        Membeli paket baru otomatis akan <strong>mengakumulasi</strong> sisa masa aktif
                                        langganan Anda saat ini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card border-0 bg-secondary bg-opacity-10 shadow-sm rounded-4">
                            <div class="card-body p-4 d-flex align-items-center gap-4">
                                <div class="text-secondary display-5 bg-white rounded-circle p-3 shadow-sm">
                                    <i class="bi bi-shield-x"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-secondary-emphasis mb-2">Status Akun: Gratis (Tidak Aktif)
                                    </h5>
                                    <p class="text-body-secondary mb-0">
                                        Silakan pilih salah satu paket langganan premium di atas untuk segera memulai
                                        belajar terstruktur, mengikuti tryout sistem CAT, dan membuka semua materi
                                        eksklusif.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
