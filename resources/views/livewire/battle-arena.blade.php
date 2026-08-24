<div class="container py-4" style="max-width: 820px;" @if ($isWaitingForOpponent ?? false) wire:poll.2s="checkOpponentJoined" @endif>
    @if ($isWaitingForOpponent ?? false)
        <!-- ========================================== -->
        <!-- WAITING ROOM (MENUNGGU LAWAN BERGABUNG)   -->
        <!-- ========================================== -->
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 text-center mb-4">
            <div class="mb-4">
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 py-2 fw-bold">
                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Lawan Bergabung...
                </span>
                <h3 class="fw-bold text-body mt-3 mb-1">⚔️ Ruang Tunggu Arena Duel CAT</h3>
                <p class="text-secondary small">Bagikan kode room atau tautan di bawah kepada teman Anda untuk langsung memulai duel head-to-head.</p>
            </div>

            <!-- VS Avatar Display -->
            <div class="card border-0 bg-body-tertiary rounded-4 p-4 mb-4">
                <div class="row align-items-center g-3">
                    <!-- Player 1 (Host) -->
                    <div class="col-5">
                        <div class="rounded-circle bg-primary text-white fs-3 fw-bold mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 64px; height: 64px;">
                            {{ substr($battle->player1->name ?? 'Anda', 0, 1) }}
                        </div>
                        <h6 class="fw-bold text-body mb-0 text-truncate">{{ $battle->player1->name ?? 'Host' }}</h6>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-0 small mt-1">✓ Siap Bertanding</span>
                    </div>

                    <!-- VS Badge -->
                    <div class="col-2">
                        <div class="badge bg-danger rounded-circle p-2 fs-6 fw-bold shadow-sm animate-pulse">
                            VS
                        </div>
                    </div>

                    <!-- Player 2 (Waiting) -->
                    <div class="col-5">
                        <div class="rounded-circle border border-2 border-dashed border-secondary text-secondary fs-3 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="bi bi-person-plus animate-bounce"></i>
                        </div>
                        <h6 class="fw-bold text-secondary mb-0">Menunggu Lawan...</h6>
                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0 small mt-1">Mencari...</span>
                    </div>
                </div>
            </div>

            <!-- Big Room Code Box -->
            <div class="card border-0 bg-primary bg-opacity-10 rounded-4 p-4 mb-4 text-center">
                <small class="text-uppercase fw-bold text-primary mb-2 d-block" style="letter-spacing: 2px;">KODE ROOM DUEL</small>
                
                <div class="d-inline-flex align-items-center justify-content-center gap-3 bg-body border border-primary border-opacity-25 rounded-pill px-4 py-2 mb-3 mx-auto shadow-sm cursor-pointer"
                     onclick="copyRoomCode('{{ $battle->room_code }}', document.getElementById('btnCopyCode'))"
                     style="cursor: pointer; max-width: 340px;"
                     title="Klik untuk salin kode room">
                    <span class="display-5 fw-bolder text-primary font-monospace" style="letter-spacing: 4px;" id="roomCodeText">
                        {{ $battle->room_code }}
                    </span>
                    <button type="button" class="btn btn-sm btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                        <i class="bi bi-copy fs-6" id="roomCodeInlineIcon"></i>
                    </button>
                </div>

                <!-- Toast feedback alert -->
                <div id="copyAlertToast" class="d-none alert alert-success py-1 px-3 rounded-pill d-inline-flex align-items-center gap-1 mx-auto mb-3 small fw-bold">
                    <i class="bi bi-check-circle-fill"></i> Kode room berhasil disalin!
                </div>

                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <button type="button" id="btnCopyCode" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2" onclick="copyRoomCode('{{ $battle->room_code }}', this)">
                        <i class="bi bi-copy"></i> <span>Salin Kode Room</span>
                    </button>
                    <button type="button" id="btnCopyLink" class="btn btn-outline-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2" onclick="copyRoomLink('{{ route('battle.join.direct', $battle->room_code) }}', this)">
                        <i class="bi bi-link-45deg"></i> <span>Salin Tautan</span>
                    </button>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode('Ayo tanding duel cepat soal CAT SKD bersamaku di Abdinara! Masuk dengan kode room: ' . $battle->room_code . ' atau klik link berikut: ' . route('battle.join.direct', $battle->room_code)) }}" 
                       target="_blank" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
                        <i class="bi bi-whatsapp"></i> <span>Bagikan WA</span>
                    </a>
                </div>
            </div>

            <!-- 5-Minute Countdown Auto-Cancel -->
            <div class="mb-4">
                @php
                    $mins = floor($waitingSecondsLeft / 60);
                    $secs = $waitingSecondsLeft % 60;
                    $timeFormatted = sprintf('%02d:%02d', $mins, $secs);
                    $percentLeft = ($waitingSecondsLeft / 300) * 100;
                @endphp
                <div class="d-flex justify-content-between align-items-center small text-secondary mb-1">
                    <span><i class="bi bi-stopwatch me-1"></i> Batas Waktu Menunggu:</span>
                    <strong class="text-danger fs-6">{{ $timeFormatted }}</strong>
                </div>
                <div class="progress rounded-pill bg-body-tertiary" style="height: 6px;">
                    <div class="progress-bar bg-danger rounded-pill" role="progressbar" style="width: {{ $percentLeft }}%"></div>
                </div>
                <small class="text-secondary opacity-75 mt-1 d-block" style="font-size: 11px;">
                    Room akan otomatis dibatalkan jika tidak ada lawan yang bergabung dalam 5 menit.
                </small>
            </div>

            <div class="pt-2 border-top">
                <button type="button" wire:click="cancelRoom" class="btn btn-outline-danger rounded-pill px-4 fw-bold">
                    <i class="bi bi-x-circle me-1"></i> Batalkan & Keluar Room
                </button>
            </div>
        </div>

    @elseif (!$isFinished)
        <!-- ========================================== -->
        <!-- LIVE BATTLE ARENA (PERTANDINGAN AKTIF)     -->
        <!-- ========================================== -->

        <!-- Scoreboard Bar -->
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4" style="background: linear-gradient(135deg, #0a2647 0%, #144272 100%);">
            <div class="row align-items-center text-white text-center">
                <!-- Player 1 -->
                <div class="col-4 d-flex align-items-center gap-2 text-start">
                    <div class="rounded-circle bg-primary text-white fs-5 fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; flex-shrink: 0;">
                        {{ substr($battle->player1->name ?? 'P1', 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="fw-bold text-white mb-0 text-truncate">
                            {{ $battle->player1->name ?? 'Player 1' }}
                            @if (Auth::id() === $battle->player1_id)
                                <span class="badge bg-primary text-white small" style="font-size: 10px;">Anda</span>
                            @endif
                        </h6>
                        <span class="badge bg-primary-subtle text-primary fw-bold mt-1">Skor: {{ $player1Score }}</span>
                    </div>
                </div>

                <!-- VS Badge & Round Progress -->
                <div class="col-4">
                    <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold fs-6 shadow-sm">VS</span>
                    <small class="d-block text-white opacity-75 mt-1 font-monospace">Soal {{ $currentIndex + 1 }}/{{ count($questionList) }}</small>
                </div>

                <!-- Player 2 -->
                <div class="col-4 d-flex align-items-center justify-content-end gap-2 text-end">
                    <div class="overflow-hidden">
                        <h6 class="fw-bold text-white mb-0 text-truncate">
                            {{ $battle->player2_name ?? ($battle->player2->name ?? 'Lawan') }}
                            @if (Auth::id() === $battle->player2_id)
                                <span class="badge bg-danger text-white small" style="font-size: 10px;">Anda</span>
                            @endif
                        </h6>
                        <span class="badge bg-danger-subtle text-danger fw-bold mt-1">Skor: {{ $player2Score }}</span>
                    </div>
                    <div class="rounded-circle bg-danger text-white fs-5 fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; flex-shrink: 0;">
                        {{ substr($battle->player2_name ?? ($battle->player2->name ?? 'P2'), 0, 1) }}
                    </div>
                </div>
            </div>

            <!-- Live Score Bar Comparison -->
            @php
                $totalPoints = max(1, $player1Score + $player2Score);
                $p1Percent = ($player1Score / $totalPoints) * 100;
                if ($player1Score === 0 && $player2Score === 0) {
                    $p1Percent = 50;
                }
            @endphp
            <div class="progress rounded-pill mt-3 bg-danger" style="height: 8px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $p1Percent }}%"></div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-semibold">
                    Pertanyaan #{{ $currentIndex + 1 }}
                </span>

                <div class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill fw-bold fs-6">
                    <i class="bi bi-stopwatch me-1"></i> Mode Cepat
                </div>
            </div>

            <!-- Question Text -->
            <div class="fs-5 text-body mb-4" style="line-height: 1.6;">
                {!! nl2br(e($currentQuestion['text'])) !!}
            </div>

            @if ($currentQuestion['image'])
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $currentQuestion['image']) }}" alt="Soal" class="img-fluid rounded-3 border" style="max-height: 250px;">
                </div>
            @endif

            <!-- Options Grid -->
            <div class="d-flex flex-column gap-3 mb-4">
                @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                    @php
                        $key = 'option_' . strtolower($opt);
                        $optText = $currentQuestion[$key] ?? null;
                    @endphp
                    @if ($optText)
                        <button type="button" 
                                wire:click="selectAnswer('{{ $opt }}')"
                                @if ($isAnswered) disabled @endif
                                class="btn battle-option-btn text-start p-3 rounded-3 d-flex align-items-start gap-3
                                    @if ($isAnswered && $opt === $lastCorrectAnswer)
                                        opt-correct
                                    @elseif ($isAnswered && $selectedOption === $opt && $opt !== $lastCorrectAnswer)
                                        opt-wrong
                                    @elseif ($isAnswered)
                                        opt-muted
                                    @endif">
                            <span class="badge rounded-circle d-flex align-items-center justify-content-center fw-bold mt-1
                                @if ($isAnswered && $opt === $lastCorrectAnswer)
                                    bg-success text-white
                                @elseif ($isAnswered && $selectedOption === $opt && $opt !== $lastCorrectAnswer)
                                    bg-danger text-white
                                @else
                                    bg-primary text-white
                                @endif" style="width: 28px; height: 28px; flex-shrink: 0;">
                                {{ $opt }}
                            </span>
                            <span class="flex-grow-1 fs-6" style="line-height: 1.5;">{{ $optText }}</span>
                        </button>
                    @endif
                @endforeach
            </div>

            <!-- Next Button when answered -->
            @if ($isAnswered)
                <div class="d-flex justify-content-end pt-3 border-top">
                    <button type="button" wire:click="nextQuestion" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                        {{ $currentIndex + 1 < count($questionList) ? 'Soal Berikutnya →' : 'Selesaikan Duel 🏆' }}
                    </button>
                </div>
            @endif
        </div>

    @else
        <!-- ========================================== -->
        <!-- VICTORY / DEFEAT SUMMARY SCREEN            -->
        <!-- ========================================== -->
        @php
            $isPlayer1 = Auth::id() === $battle->player1_id;
            $myScore = $isPlayer1 ? $player1Score : $player2Score;
            $opponentScore = $isPlayer1 ? $player2Score : $player1Score;
            $isWinner = $myScore > $opponentScore;
            $isDraw = $myScore === $opponentScore;
        @endphp

        <div class="card border-0 shadow rounded-4 p-5 text-center mb-4">
            <div class="mb-4">
                @if ($isWinner)
                    <div class="display-1 text-warning mb-2 animate-bounce">🏆</div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-4 py-2 rounded-pill fs-5 fw-bold">
                        VICTORY! ANDA MENANG!
                    </span>
                    <h2 class="fw-bold text-body mt-3">Luar Biasa, Pejuang Tangguh!</h2>
                @elseif ($isDraw)
                    <div class="display-1 text-secondary mb-2">🤝</div>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-4 py-2 rounded-pill fs-5 fw-bold">
                        HASIL SERI / DRAW
                    </span>
                    <h2 class="fw-bold text-body mt-3">Pertarungan Sangat Sengit!</h2>
                @else
                    <div class="display-1 text-danger mb-2">💔</div>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-4 py-2 rounded-pill fs-5 fw-bold">
                        DEFEAT / BELUM BERHASIL
                    </span>
                    <h2 class="fw-bold text-body mt-3">Tetap Semangat, Coba Lagi!</h2>
                @endif
            </div>

            <!-- Match Scoreboard Result -->
            <div class="card border-0 bg-body-tertiary rounded-4 p-4 mb-4 mx-auto" style="max-width: 500px;">
                <div class="row align-items-center">
                    <div class="col-5">
                        <small class="text-secondary fw-bold d-block">Skor Anda</small>
                        <h2 class="fw-bolder {{ $isWinner ? 'text-success' : ($isDraw ? 'text-primary' : 'text-secondary') }} mb-0">
                            {{ $myScore }}
                        </h2>
                    </div>
                    <div class="col-2">
                        <span class="fs-4 fw-bold text-muted">-</span>
                    </div>
                    <div class="col-5">
                        <small class="text-secondary fw-bold d-block">Skor Lawan</small>
                        <h2 class="fw-bolder {{ !$isWinner && !$isDraw ? 'text-success' : 'text-secondary' }} mb-0">
                            {{ $opponentScore }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('battle.quick') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                    <i class="bi bi-arrow-repeat me-1"></i> Tanding Lagi
                </a>
                <a href="{{ route('battle.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold">
                    Kembali ke Lobby
                </a>
            </div>
        </div>
    @endif

    <script>
        function copyRoomCode(code, btn) {
            navigator.clipboard.writeText(code).then(() => {
                const toast = document.getElementById('copyAlertToast');
                if (toast) {
                    toast.classList.remove('d-none');
                    setTimeout(() => toast.classList.add('d-none'), 2500);
                }
                const inlineIcon = document.getElementById('roomCodeInlineIcon');
                if (inlineIcon) {
                    inlineIcon.className = 'bi bi-check2-circle fs-6 text-success';
                    setTimeout(() => {
                        inlineIcon.className = 'bi bi-copy fs-6';
                    }, 2500);
                }
                if (btn) {
                    const original = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-check2"></i> <span>Tersalin!</span>';
                    btn.classList.add('btn-success');
                    btn.classList.remove('btn-primary');
                    setTimeout(() => {
                        btn.innerHTML = original;
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-primary');
                    }, 2500);
                }
            }).catch(() => {
                prompt('Salin kode room:', code);
            });
        }

        function copyRoomLink(link, btn) {
            navigator.clipboard.writeText(link).then(() => {
                if (btn) {
                    const original = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-check2"></i> <span>Tautan Tersalin!</span>';
                    btn.classList.add('btn-success', 'text-white');
                    btn.classList.remove('btn-outline-primary');
                    setTimeout(() => {
                        btn.innerHTML = original;
                        btn.classList.remove('btn-success', 'text-white');
                        btn.classList.add('btn-outline-primary');
                    }, 2500);
                }
            }).catch(() => {
                prompt('Salin tautan duel:', link);
            });
        }
    </script>
</div>