<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0 text-body">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="container py-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div>
                        <h4 class="fw-bold mb-1 col">Daftar Transaksi</h4>
                        <p class="text-body-secondary mb-0">Pantau semua riwayat pembelian paket langganan Anda.</p>
                    </div>
                    <div>
                        <a href="{{ route('subscription.index') }}" class="btn btn-primary rounded-pill shadow-sm">
                            <i class="bi bi-cart-plus me-1"></i> Beli Paket Baru
                        </a>
                    </div>
                </div>

                @if ($transactions->isEmpty())
                    <div
                        class="text-center py-5 text-body-secondary card border-0 shadow-sm rounded-4 bg-transparent border-dashed">
                        <div class="card-body py-5">
                            <i class="bi bi-inbox display-4 d-block mb-3 text-muted opacity-50"></i>
                            <h5 class="fw-medium">Belum ada riwayat transaksi</h5>
                            <p class="mb-0">Anda belum pernah melakukan pembelian paket langganan sebelumnya.</p>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($transactions as $trx)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-4 p-4 transition-hover">
                                    <div class="row align-items-center g-3">
                                        <!-- Header & Status -->
                                        <div class="col-md-3 border-md-end">
                                            <p class="text-muted fw-bold mb-1 small text-uppercase"
                                                style="letter-spacing: 0.5px;">Order ID</p>
                                            <h6 class="font-monospace text-primary fw-medium mb-3">{{ $trx->order_id }}
                                            </h6>

                                            <p class="text-muted fw-bold mb-1 small text-uppercase"
                                                style="letter-spacing: 0.5px;">Status</p>
                                            @if ($trx->status == 'success')
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">Berhasil</span>
                                            @elseif ($trx->status == 'pending')
                                                <span
                                                    class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2">Menunggu
                                                    Pembayaran</span>
                                            @else
                                                <span
                                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">Gagal
                                                    / Expired</span>
                                            @endif
                                        </div>

                                        <!-- Detail Paket -->
                                        <div class="col-md-6 px-md-4">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center"
                                                    style="width: 48px; height: 48px;">
                                                    <i class="bi bi-box-seam fs-4"></i>
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold mb-1 text-body">{{ $trx->package->name }}</h5>
                                                    <p class="text-body-secondary mb-0 small"><i
                                                            class="bi bi-clock-history me-1"></i> Masa Aktif:
                                                        {{ $trx->package->duration_days }} Hari</p>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-6">
                                                    <p class="text-muted mb-0 small">Tanggal Pembelian</p>
                                                    <p class="fw-medium mb-0">
                                                        {{ $trx->created_at->format('d M Y, H:i') }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="text-muted mb-0 small">Total Tagihan</p>
                                                    <p class="fw-bold text-primary mb-0">Rp
                                                        {{ number_format($trx->gross_amount, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Area -->
                                        <div
                                            class="col-md-3 text-md-end d-flex flex-md-column gap-2 justify-content-center justify-content-md-center align-items-stretch mt-4 mt-md-0 border-top border-md-top-0 pt-3 pt-md-0">
                                            @if ($trx->status == 'pending')
                                                <a href="{{ route('subscription.pay', $trx->id) }}"
                                                    class="btn btn-primary rounded-pill fw-medium shadow-sm w-100">
                                                    Selesaikan <i class="bi bi-arrow-right-short"></i>
                                                </a>
                                            @elseif ($trx->status == 'success')
                                                <a href="{{ route('subscription.invoice', $trx->id) }}"
                                                    class="btn btn-outline-info rounded-pill fw-medium w-100">
                                                    <i class="bi bi-receipt me-1"></i> Lihat Invoice
                                                </a>
                                            @else
                                                <button
                                                    class="btn btn-outline-secondary rounded-pill fw-medium w-100 disabled"
                                                    disabled>
                                                    -
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
