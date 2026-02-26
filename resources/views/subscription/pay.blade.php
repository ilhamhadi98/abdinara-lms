<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0 text-body">
            {{ __('Selesaikan Pembayaran') }}
        </h2>
    </x-slot>

    <div class="container py-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden transition-hover">
                    <div class="card-header bg-primary text-white border-0 py-4">
                        <i class="bi bi-wallet2 display-3 mb-3 mt-2 d-block text-white opacity-75"></i>
                        <h4 class="fw-bold mb-0">Rincian Pembayaran</h4>
                    </div>

                    <div class="card-body p-4 p-md-5 d-flex flex-column align-items-center">
                        <p class="text-body-secondary fs-5 mb-2">Anda akan membeli paket</p>
                        <h3 class="fw-bolder text-body mb-4">{{ $transaction->package->name }}</h3>

                        <div class="bg-body-tertiary rounded-3 p-4 mb-5 border mx-auto w-100" style="max-width: 400px;">
                            <span class="d-block text-secondary text-uppercase fw-semibold mb-1"
                                style="font-size: 0.85rem; letter-spacing: 1px;">Total Tagihan</span>
                            <span class="fs-1 fw-bold text-primary">Rp
                                {{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
                        </div>

                        <button id="pay-button" class="btn btn-success btn-lg rounded-pill px-5 shadow fw-bold w-100">
                            Bayar Sekarang <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>

                        <div id="payment-result"
                            class="d-none mt-4 alert alert-info bg-info bg-opacity-10 border-info border-opacity-25 text-info-emphasis rounded-3 w-100"
                            role="alert">
                            <i class="bi bi-info-circle me-2"></i> Mohon tunggu, sistem sedang memverifikasi pembayaran
                            Anda...
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-4 pt-0">
                        <small class="text-muted"><i class="bi bi-shield-lock me-1"></i> Pembayaran Anda diproses secara
                            aman oleh <strong>Midtrans</strong>.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript"
            src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

        <script type="text/javascript">
            (function() {
                var snapStarted = false;
                var payButton = document.getElementById('pay-button');

                if (payButton) {
                    payButton.onclick = function(e) {
                        e.preventDefault();

                        if (snapStarted) return;
                        snapStarted = true;

                        // Disable button to prevent double click
                        payButton.disabled = true;
                        payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

                        snap.pay('{{ $transaction->snap_token }}', {
                            onSuccess: function(result) {
                                snapStarted = false;
                                const resDiv = document.getElementById('payment-result');
                                resDiv.classList.remove('d-none');
                                resDiv.className =
                                    "mt-4 alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 text-success-emphasis rounded-3 w-100";
                                resDiv.innerHTML =
                                    '<i class="bi bi-check-circle me-2"></i> Pembayaran berhasil! Halaman dialihkan...';
                                setTimeout(function() {
                                    window.location.href = "{{ route('dashboard') }}";
                                }, 3000);
                            },
                            onPending: function(result) {
                                snapStarted = false;
                                payButton.disabled = false;
                                payButton.innerHTML =
                                    'Bayar Sekarang <i class="bi bi-arrow-right-circle ms-2"></i>';
                                const resDiv = document.getElementById('payment-result');
                                resDiv.classList.remove('d-none');
                                resDiv.innerHTML =
                                    '<i class="bi bi-hourglass-split me-2"></i> Menunggu pembayaran Anda.';
                            },
                            onError: function(result) {
                                snapStarted = false;
                                payButton.disabled = false;
                                payButton.innerHTML =
                                    'Bayar Sekarang <i class="bi bi-arrow-right-circle ms-2"></i>';
                                const resDiv = document.getElementById('payment-result');
                                resDiv.classList.remove('d-none');
                                resDiv.className =
                                    "mt-4 alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger-emphasis rounded-3 w-100";
                                resDiv.innerHTML =
                                    '<i class="bi bi-x-circle me-2"></i> Gagal memproses pembayaran. Silakan coba lagi.';
                            },
                            onClose: function() {
                                snapStarted = false;
                                payButton.disabled = false;
                                payButton.innerHTML =
                                    'Bayar Sekarang <i class="bi bi-arrow-right-circle ms-2"></i>';
                            }
                        });
                    };
                }
            })();
        </script>
    @endpush
</x-app-layout>
