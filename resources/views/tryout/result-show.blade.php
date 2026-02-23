<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-3 py-3">
            <a href="{{ route('tryout.results') }}" class="text-decoration-none text-body-secondary hover-dark">
                <i class="bi bi-arrow-left"></i> Riwayat
            </a>
            <h4 class="mb-0 fw-bold text-body">Detail Hasil Tryout</h4>
        </div>
    </x-slot>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                @php
                    $pct =
                        $session->tryout->total_questions > 0
                            ? round(($session->score / $session->tryout->total_questions) * 100)
                            : 0;

                    $isPassed = $pct >= 70;
                    $isBorderline = $pct >= 50 && $pct < 70;
                    $statusColor = $isPassed ? 'success' : ($isBorderline ? 'warning' : 'danger');
                    $statusText = $isPassed
                        ? '✓ Lulus Passing Grade'
                        : ($isBorderline
                            ? '~ Di Ambang Batas'
                            : '✗ Perlu Belajar Lagi');
                @endphp

                {{-- Score Highlight Card --}}
                <div class="card border-0 shadow-sm rounded-4 text-center mb-5 overflow-hidden">
                    <div class="card-body p-5">
                        <h6 class="text-body-secondary text-uppercase tracking-wider fw-bold mb-3"
                            style="letter-spacing: 2px;">
                            Skor Akhir Anda</h6>
                        <h1 class="display-1 fw-bolder text-{{ $statusColor }} mb-2">
                            {{ $session->score }}
                        </h1>
                        <p class="text-body-secondary mb-4 fs-5">dari {{ $session->tryout->total_questions }} soal
                            ({{ $pct }}%)</p>

                        <div class="mb-4">
                            <span
                                class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} border border-{{ $statusColor }} px-4 py-2 rounded-pill fs-6 shadow-sm">
                                {{ $statusText }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-center gap-4 text-body-secondary small border-top pt-4 mt-2">
                            <div>
                                <i class="bi bi-calendar-check me-1"></i>
                                Selesai: <span
                                    class="fw-medium text-body">{{ $session->finished_at?->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            @if ($session->duration_seconds !== null)
                                <div>
                                    <i class="bi bi-stopwatch me-1"></i>
                                    Waktu: <span
                                        class="fw-medium text-body">{{ floor($session->duration_seconds / 60) }}m
                                        {{ $session->duration_seconds % 60 }}s</span>
                                    <span class="text-body-secondary ms-1">(Batas
                                        {{ $session->tryout->duration_minutes }}m)</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Answer Review Cards --}}
                <div class="mb-4">
                    <h5 class="mb-4 fw-bold text-body d-flex align-items-center gap-2">
                        <i class="bi bi-card-checklist text-primary"></i> Review Jawaban Anda
                    </h5>

                    @foreach ($answers as $i => $answer)
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div
                                class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-body-secondary fs-5">Soal {{ $i + 1 }}</span>
                                <div>
                                    @if ($answer->is_correct)
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25">
                                            <i class="bi bi-check-circle-fill me-1"></i> Benar
                                        </span>
                                    @elseif (!$answer->selected_answer)
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill border border-secondary border-opacity-25">
                                            <i class="bi bi-dash-circle-fill me-1"></i> Kosong
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger border-opacity-25">
                                            <i class="bi bi-x-circle-fill me-1"></i> Salah
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-4">
                                {{-- Question Text --}}
                                <div class="text-body mb-4 fs-5" style="line-height: 1.6;">
                                    {!! nl2br(e($answer->question?->question_text ?? '(soal dihapus)')) !!}
                                </div>

                                @if ($answer->question?->image)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $answer->question->image) }}"
                                            alt="Gambar Soal {{ $i + 1 }}"
                                            class="img-fluid rounded-3 border shadow-sm" style="max-height: 300px;">
                                    </div>
                                @endif

                                {{-- Comparison Grid --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0 h-100 dash-card"
                                            style="background-color: var(--bs-tertiary-bg) !important;">
                                            <span
                                                class="d-block text-body-secondary small mb-2 fw-bold text-uppercase tracking-wider">Jawaban
                                                Anda</span>
                                            @if ($answer->selected_answer)
                                                @php
                                                    $optKey = 'option_' . strtolower($answer->selected_answer);
                                                    $optText = $answer->question->$optKey ?? '-';
                                                @endphp
                                                <div class="d-flex align-items-start gap-3">
                                                    <span
                                                        class="badge {{ $answer->is_correct ? 'bg-success' : 'bg-danger' }} rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                        style="width: 32px; height: 32px; flex-shrink: 0;">
                                                        {{ $answer->selected_answer }}
                                                    </span>
                                                    <span class="text-body">{{ $optText }}</span>
                                                </div>
                                            @else
                                                <div class="text-secondary italic">Tidak dijawab</div>
                                            @endif
                                        </div>
                                    </div>

                                    @if (!$answer->is_correct && $answer->question)
                                        <div class="col-md-6">
                                            <div
                                                class="p-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-25 h-100">
                                                <span
                                                    class="d-block text-success small mb-2 fw-bold text-uppercase tracking-wider">Jawaban
                                                    Benar</span>
                                                @php
                                                    $correctKey =
                                                        'option_' . strtolower($answer->question->correct_answer);
                                                    $correctText = $answer->question->$correctKey ?? '-';
                                                @endphp
                                                <div class="d-flex align-items-start gap-3">
                                                    <span
                                                        class="badge bg-success rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                        style="width: 32px; height: 32px; flex-shrink: 0;">
                                                        {{ $answer->question->correct_answer }}
                                                    </span>
                                                    <span class="text-success fw-medium">{{ $correctText }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Explanation --}}
                                @if ($answer->question?->explanation)
                                    <div>
                                        <button
                                            class="btn btn-link text-decoration-none p-0 text-primary fw-bold d-flex align-items-center gap-2"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#explanation-{{ $i }}" aria-expanded="false">
                                            <i class="bi bi-lightbulb"></i> Lihat Pembahasan
                                            <i class="bi bi-chevron-down small transition-transform"></i>
                                        </button>

                                        <div class="collapse mt-3" id="explanation-{{ $i }}">
                                            <div
                                                class="bg-primary bg-opacity-10 p-4 rounded-4 border-start border-primary border-4">
                                                <h6 class="text-primary fw-bold mb-2">Pembahasan Lengkap:</h6>
                                                <div class="text-body" style="line-height: 1.6;">
                                                    {!! nl2br(e($answer->question->explanation)) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Call to Actions --}}
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="{{ route('tryout.index') }}"
                        class="btn btn-primary btn-lg rounded-pill flex-grow-1 shadow-sm fw-bold">
                        Coba Tryout Lainnya
                    </a>
                    <a href="{{ route('tryout.results') }}"
                        class="btn btn-light border btn-lg rounded-pill flex-grow-1 text-secondary fw-semibold">
                        Lihat Semua Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
