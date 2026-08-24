<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Sertifikat Prestasi - {{ $participant->user->name }} - Abdinara.id</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body { background: #ffffff !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .certificate-container { border: 8px double #d4af37 !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
        }
        .cert-border {
            border: 12px solid #0a2647;
            outline: 3px solid #d4af37;
            outline-offset: -8px;
        }
    </style>
</head>
<body class="bg-dark bg-opacity-75 py-5">
    <div class="container" style="max-width: 950px;">
        <!-- Top Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="{{ route('tournament.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Liga
            </a>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-warning rounded-pill px-4 fw-bold shadow">
                    <i class="bi bi-printer-fill me-1"></i> Cetak / Simpan PDF
                </button>
            </div>
        </div>

        <!-- Certificate Card -->
        <div class="card cert-border rounded-4 bg-white text-dark p-5 shadow-lg position-relative certificate-container" style="min-height: 620px;">
            <!-- Corner Accents -->
            <div class="text-center mb-4">
                <img src="{{ asset('icon-192.png') }}" alt="Abdinara Logo" style="height: 58px; width: 58px;" onerror="this.src='{{ asset('favicon.ico') }}'">
                <h4 class="fw-bolder text-uppercase tracking-wider mt-2 mb-0" style="color: #0a2647; letter-spacing: 2px;">
                    Abdi<span style="color: #d4af37;">nara</span>.id
                </h4>
                <small class="text-muted text-uppercase tracking-widest fw-semibold" style="letter-spacing: 3px; font-size: 10px;">Lembaga Pelatihan & Simulasi CAT Resmi</small>
            </div>

            <div class="text-center mb-4">
                <h1 class="display-5 fw-bolder text-uppercase" style="color: #0a2647; letter-spacing: 4px; font-family: 'Times New Roman', serif;">
                    SERTIFIKAT PRESTASI
                </h1>
                <p class="text-secondary fw-medium fs-6" style="letter-spacing: 1px;">Nomor: ABD-CERT/{{ date('Y') }}/{{ str_pad($participant->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="text-center my-3">
                <p class="fs-5 text-secondary mb-1">Diberikan dengan bangga kepada:</p>
                <h2 class="display-6 fw-bold text-uppercase border-bottom border-warning pb-2 d-inline-block px-5" style="color: #0a2647;">
                    {{ $participant->user->name }}
                </h2>
            </div>

            <div class="text-center mx-auto mb-4" style="max-width: 750px;">
                <p class="fs-5 text-secondary" style="line-height: 1.6;">
                    Telah berpartisipasi dan berhasil <strong>LULUS PASSING GRADE</strong> pada ajang kompetisi 
                    <strong class="text-dark">{{ $participant->tournament->title }}</strong> dengan perolehan skor akhir 
                    <strong class="text-primary fs-4">{{ $participant->score }}</strong> poin
                    @if ($participant->rank_position)
                        (Peringkat Nasional #{{ $participant->rank_position }})
                    @endif.
                </p>
            </div>

            <div class="row align-items-end mt-auto pt-4 border-top">
                <div class="col-6 text-start">
                    <small class="text-muted d-block">Diterbitkan pada:</small>
                    <span class="fw-bold text-dark">{{ $participant->created_at->translatedFormat('d F Y') }}</span>
                    <br>
                    <small class="text-muted" style="font-size: 9px;">Verifikasi Keaslian: cat.abdinara.id/verify</small>
                </div>
                <div class="col-6 text-end">
                    <p class="fw-bold mb-0 text-dark">Tim Akademik Abdinara</p>
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px;">Direktur Pelaksana</small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>