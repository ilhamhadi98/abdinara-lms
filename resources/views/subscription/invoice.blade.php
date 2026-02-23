<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0 text-body">
            {{ __('Invoice Pembayaran') }}
        </h2>
    </x-slot>

    <div class="container py-5 mb-5">
        <div class="row w-100 justify-content-center">

            <div class="col-lg-8" id="invoiceArea">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white text-dark" style="position: relative;">
                    <!-- Invoice Header -->
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary text-white rounded p-3 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-briefcase-fill fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bolder mb-0" style="color: #1e3a8a; letter-spacing: -0.5px;">ABDINARA.ID
                                </h3>
                                <p class="text-body-secondary mb-0 small" style="margin-top: -2px;">LMS Portal Resmi</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <h2 class="fw-bolder text-uppercase mb-1" style="color: #1e3a8a;">INVOICE</h2>
                            <p class="text-body-secondary fw-semibold mb-0">#{{ $transaction->order_id }}</p>
                        </div>
                    </div>

                    <!-- Client & Meta Info -->
                    <div class="row mb-5">
                        <div class="col-sm-6">
                            <p class="text-muted fw-bold mb-1 small text-uppercase" style="letter-spacing: 1px;">
                                Ditagihkan Kepada:</p>
                            <h5 class="fw-bold mb-1">{{ $transaction->user->name }}</h5>
                            <p class="text-body-secondary mb-0">{{ $transaction->user->email }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                            <div class="mb-3">
                                <p class="text-muted fw-bold mb-1 small text-uppercase" style="letter-spacing: 1px;">
                                    Tanggal Transaksi:</p>
                                <p class="fw-medium mb-0">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-muted fw-bold mb-1 small text-uppercase" style="letter-spacing: 1px;">
                                    Status Pembayaran:</p>
                                @if ($transaction->status == 'success')
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 fw-bold fs-6">LUNAS
                                        / BERHASIL</span>
                                @else
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 fw-bold fs-6">MENUNGGU
                                        / {{ strtoupper($transaction->status) }}</span>
                                @endif
                                <p class="text-body-secondary small mt-1 mb-0">via
                                    {{ strtoupper(str_replace('_', ' ', $transaction->payment_type)) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items Table -->
                    <div class="table-responsive mb-5">
                        <table class="table border-0 table-borderless align-middle">
                            <thead class="bg-body-tertiary border-bottom border-top">
                                <tr>
                                    <th class="py-3 px-3 fw-bold text-uppercase text-muted"
                                        style="font-size: 0.85rem; letter-spacing: 1px;">Item Deskripsi</th>
                                    <th class="py-3 px-3 text-center fw-bold text-uppercase text-muted"
                                        style="font-size: 0.85rem; letter-spacing: 1px;">Durasi</th>
                                    <th class="py-3 px-3 text-end fw-bold text-uppercase text-muted"
                                        style="font-size: 0.85rem; letter-spacing: 1px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-bottom">
                                    <td class="py-4 px-3">
                                        <h6 class="fw-bold mb-1">{{ $transaction->package->name }}</h6>
                                        <p class="text-body-secondary small mb-0">
                                            {{ $transaction->package->description }}</p>
                                    </td>
                                    <td class="py-4 px-3 text-center fw-semibold">
                                        {{ $transaction->package->duration_days }} Hari
                                    </td>
                                    <td class="py-4 px-3 text-end fw-bold text-primary">
                                        Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Section -->
                    <div class="row justify-content-end mb-4">
                        <div class="col-sm-5 text-end">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                <span class="text-body-secondary fw-semibold">Subtotal:</span>
                                <span class="fw-medium">Rp
                                    {{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                <span class="text-body-secondary fw-semibold">PPN / Tax (0%):</span>
                                <span class="fw-medium">Rp 0</span>
                            </div>
                            <div
                                class="d-flex justify-content-between align-items-center bg-body-tertiary p-3 rounded-3 border">
                                <span class="fw-bolder fs-5 text-uppercase" style="letter-spacing: 1px;">Total
                                    Pembayaran:</span>
                                <span class="fw-bolder fs-4 text-primary">Rp
                                    {{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="border-top pt-4 text-center">
                        <p class="text-body-secondary small mb-1">Terima kasih telah mempercayakan persiapan masa depan
                            Anda pada LMS Abdinara.</p>
                        <p class="text-muted" style="font-size: 0.75rem;">Invoice ini sah dan diterbitkan secara
                            elektronik oleh sistem Abdinara.</p>
                    </div>

                </div>
            </div>

            <!-- Floating Actions -->
            <div class="col-lg-8 mt-4 d-flex justify-content-center gap-3 no-print">
                <a href="{{ route('subscription.history') }}"
                    class="btn btn-outline-secondary rounded-pill fw-bold bg-white shadow-sm px-4">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="button" id="btn-download" class="btn btn-primary rounded-pill fw-bold shadow px-4">
                    <i class="bi bi-download"></i> Download Gambar (PNG)
                </button>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script>
            document.getElementById('btn-download').addEventListener('click', function() {
                const invoiceArea = document.getElementById('invoiceArea');

                // Tambahkan class sementara untuk mengatur lebar elemen agak besar jika diperlukan, atau ganti bg warnanya
                const originalBg = invoiceArea.style.backgroundColor;

                // Mulai konversi
                html2canvas(invoiceArea, {
                    scale: 2, // Biar resolusi tinggi
                    useCORS: true,
                    backgroundColor: '#ffffff' // Ensure it's white to avoid transparency issues
                }).then(canvas => {
                    // Reset
                    let link = document.createElement('a');
                    link.download = 'Invoice_{{ $transaction->order_id }}.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            });
        </script>
    @endpush
</x-app-layout>
