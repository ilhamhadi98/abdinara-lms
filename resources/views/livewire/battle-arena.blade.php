<div class="container py-4">
    @if (!$isFinished && $currentQuestion)
        <!-- Top Duel Header & Scoreboard -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 bg-body-tertiary p-4">
            <div class="row align-items-center g-3">
                <!-- Player 1 -->
                @php
                    $isP1 = Auth::id() === $battle->player1_id;
                    $p1Name = $battle->player1->name ?? 'Player 1';
                    $p2Name = $battle->player2_name ?? ($battle->player2->name ?? 'Menunggu Lawan...');
                @endphp
                <div class="col-5">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2 p-md-3 rounded-circle bg-primary text-white fs-4 fw-bold text-center" style="width: 52px; height: 52px; line-height: 1;">
                            {{ substr($p1Name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-body mb-0 text-truncate">
                                {{ $p1Name }} @if ($isP1) <span class="badge bg-primary-subtle text-primary rounded-pill small">Anda</span> @endif
                            </h6>
                            <small class="text-primary fw-bold">Skor: {{ $player1Score }}</small>
                        </div>
                    </div>
                </div>

                <!-- VS Badge & Question Counter -->
                <div class="col-2 text-center">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-bold fs-6">
                        VS
                    </span>
                    <div class="small text-secondary mt-1">Soal {{ $currentIndex + 1 }}/{{ count($questionList) }}</div>
                </div>

                <!-- Player 2 (Opponent) -->
                <div class="col-5 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-body mb-0 text-truncate">
                                {{ $p2Name }} @if (!$isP1 && $battle->player2_id === Auth::id()) <span class="badge bg-danger-subtle text-danger rounded-pill small">Anda</span> @endif
                            </h6>
                            <small class="text-danger fw-bold">Skor: {{ $player2Score }}</small>
                        </div>
                        <div class="p-2 p-md-3 rounded-circle bg-danger text-white fs-4 fw-bold text-center" style="width: 52px; height: 52px; line-height: 1;">
                            {{ substr($p2Name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Head-to-head Progress Bar -->
            @php
                $totalPts = max(1, $player1Score + $player2Score);
                $p1Pct = round(($player1Score / $totalPts) * 100);
                $p2Pct = 100 - $p1Pct;
            @endphp
            <div class="progress mt-3 rounded-pill" style="height: 10px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $p1Pct }}%"></div>
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $p2Pct }}%"></div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-semibold">
                    Pertanyaan #{{ $currentIndex + 1 }}
                </span>

                <!-- 15s Timer -->
                <div class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-3 py-2 rounded-pill fw-bold fs-6">
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
                    <button type="button" wire:click="nextQuestion" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">
                        Soal Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            @endif
        </div>

    @else
        <!-- Battle Finished Summary -->
        <div class="card border-0 shadow-lg rounded-4 p-5 text-center my-4 overflow-hidden" style="background: linear-gradient(135deg, #0a2647 0%, #144272 100%); color: #ffffff;">
            @php
                $isP1 = Auth::id() === $battle->player1_id;
                $myScore = $isP1 ? $player1Score : $player2Score;
                $oppScore = $isP1 ? $player2Score : $player1Score;
                $iWon = $myScore > $oppScore;
                $isDraw = $myScore === $oppScore;
            @endphp

            <div class="mb-3">
                @if ($iWon)
                    <div class="display-1">🏆</div>
                    <h1 class="display-4 fw-bolder text-warning mb-2">VICTORY!</h1>
                    <p class="fs-5 opacity-75">Selamat! Anda memenangkan duel CAT kali ini!</p>
                @elseif ($isDraw)
                    <div class="display-1">🤝</div>
                    <h1 class="display-4 fw-bolder text-info mb-2">DRAW / SERI</h1>
                    <p class="fs-5 opacity-75">Skor Anda dan lawan seimbang!</p>
                @else
                    <div class="display-1">💥</div>
                    <h1 class="display-4 fw-bolder text-danger mb-2">DEFEAT</h1>
                    <p class="fs-5 opacity-75">Jangan menyerah! Coba lagi dan kalahkan lawanmu!</p>
                @endif
            </div>

            <!-- Final Scores -->
            <div class="row justify-content-center my-4">
                <div class="col-md-8">
                    <div class="card border-0 rounded-4 p-4 text-dark" style="background: rgba(255, 255, 255, 0.95);">
                        <div class="row align-items-center">
                            <div class="col-6 border-end">
                                <h6 class="text-secondary text-uppercase mb-1">Skor Anda</h6>
                                <h2 class="display-5 fw-bolder text-primary mb-0">{{ $myScore }}</h2>
                            </div>
                            <div class="col-6">
                                <h6 class="text-secondary text-uppercase mb-1">Skor Lawan</h6>
                                <h2 class="display-5 fw-bolder text-danger mb-0">{{ $oppScore }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                <a href="{{ route('battle.quick') }}" class="btn btn-warning text-dark btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="bi bi-play-circle-fill me-1"></i> Main Lagi (Cari Lawan Cepat)
                </a>
                <a href="{{ route('battle.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold">
                    Kembali ke Lobby Duel
                </a>
            </div>
        </div>
    @endif
</div>