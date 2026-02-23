<div class="container py-4 pb-5 mb-5 select-none" id="tryout-engine">

    {{-- ==================== TOP BAR - LARGE TIMER ==================== --}}
    <div class="text-center mb-5 mt-3">
        <div class="text-secondary small fw-bold tracking-wide mb-2 text-uppercase" style="letter-spacing: 1px;">
            {{ $session->tryout->title }}
        </div>
        <div id="cat-timer" class="display-1 fw-light text-dark font-monospace transition-colors duration-300">
            <span id="timer-display">--:--:--</span>
        </div>
    </div>

    <div class="row g-4 align-items-lg-start">
        {{-- ==================== QUESTION AREA ==================== --}}
        <div class="col-lg-8 order-2 order-lg-1">
            @if ($question)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    {{-- Question Header --}}
                    <div
                        class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-baseline gap-2">
                            <h4 class="mb-0 fw-bold">Soal {{ $currentIndex + 1 }}</h4>
                            <span class="text-muted fw-medium fs-6">/ {{ $totalCount }}</span>
                        </div>
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium">
                            {{ $question->subtopic->name ?? 'Subtopik' }}
                        </span>
                    </div>

                    {{-- Question Content --}}
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-dark mb-5" style="line-height: 1.8; font-size: 1.05rem;">
                            {!! nl2br(e($question->question_text)) !!}
                        </div>

                        @if ($question->image)
                            <div class="mb-5 text-center">
                                <img src="{{ asset('storage/' . $question->image) }}" alt="Gambar Soal"
                                    class="img-fluid rounded-3 border shadow-sm">
                            </div>
                        @endif

                        {{-- Options --}}
                        <div class="d-grid gap-3">
                            @foreach (['A' => $question->option_a, 'B' => $question->option_b, 'C' => $question->option_c, 'D' => $question->option_d, 'E' => $question->option_e] as $key => $text)
                                @php
                                    $isSelected = ($answers[$question->id] ?? null) === $key;
                                @endphp
                                <label
                                    class="btn text-start p-3 text-wrap rounded-4 d-flex align-items-start gap-3 w-100 border-2 {{ $isSelected ? 'btn-outline-primary bg-primary bg-opacity-10 border-primary' : 'btn-outline-secondary text-dark border-light bg-light' }}"
                                    style="border-color: {{ $isSelected ? 'var(--bs-primary)' : '#e9ecef' }} !important;">

                                    <input type="radio" name="answer" value="{{ $key }}"
                                        wire:click="selectAnswer('{{ $key }}')" class="d-none"
                                        {{ $isSelected ? 'checked' : '' }}>

                                    {{-- Custom Radio Indicator --}}
                                    <div class="mt-1 flex-shrink-0 rounded-circle border d-flex align-items-center justify-content-center {{ $isSelected ? 'border-primary border-4 bg-white' : 'border-secondary bg-white' }}"
                                        style="width: 24px; height: 24px;">
                                    </div>

                                    {{-- Option Text --}}
                                    <div class="pt-1 {{ $isSelected ? 'fw-medium' : '' }}" style="line-height: 1.5;">
                                        <span class="fw-bold me-2">{{ $key }}.</span>
                                        {{ $text }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Navigation Footer --}}
                    <div
                        class="card-footer bg-white border-top px-4 py-4 d-flex justify-content-between align-items-center">
                        <button wire:click="prevQuestion" @if ($currentIndex === 0) disabled @endif
                            class="btn btn-light border border-secondary px-3 px-md-4 py-2 rounded-3 fw-semibold">
                            <i class="bi bi-chevron-left me-1"></i> <span class="d-none d-sm-inline">Sebelumnya</span>
                        </button>

                        @php
                            $isFlagged = isset($flagged[$question->id]) && $flagged[$question->id];
                        @endphp
                        <button wire:click="toggleFlag"
                            class="btn {{ $isFlagged ? 'btn-warning text-dark border-warning' : 'btn-outline-warning text-dark' }} px-3 px-md-4 py-2 rounded-3 fw-semibold">
                            <i class="bi {{ $isFlagged ? 'bi-flag-fill' : 'bi-flag' }} me-1"></i> Ragu-Ragu
                        </button>

                        @if ($currentIndex < $totalCount - 1)
                            <button wire:click="nextQuestion"
                                class="btn btn-primary px-3 px-md-4 py-2 rounded-3 fw-semibold">
                                <span class="d-none d-sm-inline">Selanjutnya</span> <i
                                    class="bi bi-chevron-right ms-1"></i>
                            </button>
                        @else
                            <button type="button" onclick="confirmSubmit()"
                                class="btn btn-success px-3 px-md-4 py-2 rounded-3 fw-bold shadow-sm d-flex align-items-center">
                                <i class="bi bi-check2-circle fs-5 me-2 align-text-bottom"></i> Selesai
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
                    <h5 class="fw-normal">Soal tidak ditemukan.</h5>
                </div>
            @endif
        </div>

        {{-- ==================== SIDE: QUESTION MAP ==================== --}}
        <div class="col-lg-4 order-1 order-lg-2">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 1.5rem; z-index: 10;">
                <h6 class="fw-bold mb-4 text-dark text-uppercase" style="letter-spacing: 1px; font-size: 0.9rem;">
                    Navigasi Soal</h6>

                {{-- Grid Soal --}}
                <div class="mb-4" style="max-height: 320px; overflow-y: auto;">
                    <div class="d-flex flex-wrap gap-2 justify-content-start">
                        @foreach ($questionIds as $idx => $qId)
                            @php
                                $isAnswered = isset($answers[$qId]);
                                $isFlagged = isset($flagged[$qId]) && $flagged[$qId];
                                $isActive = $idx === $currentIndex;

                                $btnClass = 'btn p-0 fw-bold d-flex align-items-center justify-content-center ';
                                // Border logic for active
                                $btnClass .= $isActive ? 'border border-2 border-primary shadow-sm ' : 'border ';

                                // Color logic
                                if ($isFlagged) {
                                    $btnClass .= 'btn-warning text-dark border-warning shadow-sm';
                                } elseif ($isAnswered) {
                                    $btnClass .= 'btn-success text-white border-success shadow-sm';
                                } else {
                                    $btnClass .= 'btn-light text-secondary border-secondary bg-opacity-25';
                                }
                            @endphp

                            <button wire:click="goToQuestion({{ $idx }})" class="{{ $btnClass }}"
                                style="width: 44px; height: 44px; border-radius: 8px; font-size: 1rem;"
                                title="Soal {{ $idx + 1 }}">

                                @if ($isFlagged && !$isAnswered)
                                    <i class="bi bi-flag-fill fs-6 text-dark"></i>
                                @else
                                    {{ $idx + 1 }}
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Status Legends --}}
                <div class="d-flex flex-column gap-2 mb-4">
                    <div class="d-flex align-items-center gap-2 small text-secondary">
                        <div class="bg-success rounded border border-success" style="width: 16px; height: 16px;"></div>
                        Sudah Dijawab
                    </div>
                    <div class="d-flex align-items-center gap-2 small text-secondary">
                        <div class="bg-warning rounded border border-warning" style="width: 16px; height: 16px;"></div>
                        Ragu-ragu
                    </div>
                    <div class="d-flex align-items-center gap-2 small text-secondary">
                        <div class="bg-light rounded border border-secondary" style="width: 16px; height: 16px;"></div>
                        Belum Dijawab
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="button" onclick="confirmSubmit()"
                    class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2">
                    <i class="bi bi-send-check fs-5"></i> Kumpulkan Ujian
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== SUBMIT CONFIRM MODAL ==================== --}}
    <div class="modal fade" id="submitModal" tabindex="-1" aria-labelledby="submitModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="submitModalLabel">Konfirmasi Submit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="text-secondary mb-0">
                        Anda telah menjawab <strong class="text-dark">{{ count($answers) }}</strong> dari <strong
                            class="text-dark">{{ $totalCount }}</strong> soal.
                    </p>
                    <p class="text-secondary small mt-1 mb-0">Setelah disubmit, Anda tidak dapat mengubah jawaban lagi.
                    </p>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light border px-4 rounded-pill"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success px-4 rounded-pill fw-bold"
                        wire:click="submitTryout" onclick="closeModal()">
                        Ya, Kumpulkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== TIMER JS & MODAL ==================== --}}
<script>
    function confirmSubmit() {
        const modalEl = document.getElementById('submitModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (!modal) {
                modal = new bootstrap.Modal(modalEl);
            }
            modal.show();
        }
    }

    function closeModal() {
        const modalEl = document.getElementById('submitModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        }
        // Pastikan backdrop hilang meskipun transisi diganggu Livewire
        setTimeout(() => {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        }, 300);
    }

    (function() {
        const expiredAt = @json($expiredAt);
        const timerEl = document.getElementById('timer-display');

        if (!expiredAt || !timerEl) return;

        const deadline = new Date(expiredAt).getTime();
        let autoSubmitted = false;

        function tick() {
            const remaining = Math.max(0, Math.floor((deadline - Date.now()) / 1000));
            const h = Math.floor(remaining / 3600);
            const m = String(Math.floor((remaining % 3600) / 60)).padStart(2, '0');
            const s = String(remaining % 60).padStart(2, '0');

            if (h > 0) {
                timerEl.textContent = `${String(h).padStart(2, '0')}:${m}:${s}`;
            } else {
                timerEl.textContent = `${m}:${s}`;
            }

            // Color warning using Bootstrap text-danger class
            const wrapper = document.getElementById('cat-timer');
            if (remaining <= 300) {
                wrapper.classList.remove('text-dark');
                wrapper.classList.add('text-danger');
            }
            if (remaining <= 60) {
                // Pulse effect fallback using opacity toggle if needed
            }

            if (remaining <= 0 && !autoSubmitted) {
                autoSubmitted = true;
                timerEl.textContent = '00:00';
                // Auto-submit via Livewire
                Livewire.find(document.querySelector('[wire\\:id]')?.getAttribute('wire:id'))?.call('submitTryout');
            }
        }

        tick();
        setInterval(tick, 1000);
    })();
</script>
