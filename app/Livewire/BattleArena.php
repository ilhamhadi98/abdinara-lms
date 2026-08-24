<?php

namespace App\Livewire;

use App\Models\Battle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BattleArena extends Component
{
    public Battle $battle;
    public int $currentIndex = 0;
    public int $player1Score = 0;
    public int $player2Score = 0;
    public int $timeLeft = 15;
    public bool $isFinished = false;
    public ?string $selectedOption = null;
    public bool $isAnswered = false;
    public ?string $lastCorrectAnswer = null;
    public ?string $winnerName = null;
    public array $questionList = [];

    // Waiting Room State
    public bool $isWaitingForOpponent = false;
    public int $waitingSecondsLeft = 300;

    public function mount(Battle $battle)
    {
        $this->battle = $battle;
        $this->player1Score = (int) ($battle->player1_score ?? 0);
        $this->player2Score = (int) ($battle->player2_score ?? 0);

        // Check if waiting for opponent (Player 1 created a room and opponent has not joined)
        if ($battle->status === 'waiting' && !$battle->player2_id) {
            $this->isWaitingForOpponent = true;
            $elapsed = (int) now()->diffInSeconds($battle->created_at);
            $this->waitingSecondsLeft = max(0, 300 - $elapsed);

            if ($this->waitingSecondsLeft <= 0) {
                $this->cancelBattleDueToTimeout();
                return;
            }
        }

        $questions = $battle->questions()->get();
        foreach ($questions as $q) {
            $this->questionList[] = [
                'id' => $q->id,
                'text' => $q->question_text,
                'image' => $q->image,
                'option_a' => $q->option_a,
                'option_b' => $q->option_b,
                'option_c' => $q->option_c,
                'option_d' => $q->option_d,
                'option_e' => $q->option_e,
                'correct' => $q->correct_answer,
                'explanation' => $q->explanation,
            ];
        }

        if ($battle->status === 'finished') {
            $this->isFinished = true;
            $this->winnerName = $battle->winner_name;
        }
    }

    /**
     * Poll every 2s while in waiting room to check if opponent joined or timeout.
     */
    public function checkOpponentJoined()
    {
        if (!$this->isWaitingForOpponent) {
            return;
        }

        $this->battle->refresh();

        if ($this->battle->status === 'cancelled') {
            return redirect()->route('battle.index')->with('error', 'Room duel telah dibatalkan.');
        }

        // Opponent has joined!
        if ($this->battle->player2_id || $this->battle->status === 'active') {
            $this->isWaitingForOpponent = false;
            $this->battle->status = 'active';
            return;
        }

        $elapsed = (int) now()->diffInSeconds($this->battle->created_at);
        $this->waitingSecondsLeft = max(0, 300 - $elapsed);

        if ($this->waitingSecondsLeft <= 0) {
            return $this->cancelBattleDueToTimeout();
        }
    }

    public function cancelRoom()
    {
        $this->battle->update(['status' => 'cancelled']);
        return redirect()->route('battle.index')->with('info', 'Room duel telah dibatalkan.');
    }

    public function cancelBattleDueToTimeout()
    {
        $this->battle->update(['status' => 'cancelled']);
        return redirect()->route('battle.index')->with('error', 'Tidak ada lawan yang bergabung dalam 5 menit. Room telah ditutup otomatis.');
    }

    public function selectAnswer(string $option)
    {
        if ($this->isWaitingForOpponent || $this->isAnswered || $this->isFinished) {
            return;
        }

        $this->selectedOption = $option;
        $this->isAnswered = true;

        $currentQ = $this->questionList[$this->currentIndex] ?? null;
        if (!$currentQ) {
            return;
        }

        $this->lastCorrectAnswer = $currentQ['correct'];
        $isCorrect = strtoupper($option) === strtoupper($currentQ['correct']);

        $isPlayer1 = Auth::id() === $this->battle->player1_id;

        // Award points to answering player
        if ($isCorrect) {
            if ($isPlayer1) {
                $this->player1Score += 100 + ($this->timeLeft * 5); // Speed bonus
            } else {
                $this->player2Score += 100 + ($this->timeLeft * 5);
            }
        }

        // If Opponent is AI Bot, simulate realistic bot answering
        if ($this->battle->is_bot) {
            $botSuccessRate = rand(1, 100);
            if ($botSuccessRate <= 75) { // 75% accuracy
                $botTimeLeft = rand(5, 13);
                $this->player2Score += 100 + ($botTimeLeft * 5);
            }
        }
    }

    public function nextQuestion()
    {
        $this->selectedOption = null;
        $this->isAnswered = false;
        $this->lastCorrectAnswer = null;
        $this->timeLeft = 15;

        if ($this->currentIndex + 1 < count($this->questionList)) {
            $this->currentIndex++;
        } else {
            $this->finalizeBattle();
        }
    }

    public function finalizeBattle()
    {
        $this->isFinished = true;

        $p1 = $this->battle->player1;
        $p2Name = $this->battle->player2_name ?? ($this->battle->player2->name ?? 'Lawan');

        if ($this->player1Score > $this->player2Score) {
            $this->winnerName = $p1->name ?? 'Player 1';
            $winnerId = $p1->id ?? null;
        } elseif ($this->player2Score > $this->player1Score) {
            $this->winnerName = $p2Name;
            $winnerId = $this->battle->player2_id ?? null;
        } else {
            $this->winnerName = 'Seri / Draw';
            $winnerId = null;
        }

        $this->battle->update([
            'status' => 'finished',
            'player1_score' => $this->player1Score,
            'player2_score' => $this->player2Score,
            'winner_id' => $winnerId,
            'winner_name' => $this->winnerName,
        ]);
    }

    public function render()
    {
        $currentQuestion = $this->questionList[$this->currentIndex] ?? null;
        return view('livewire.battle-arena', [
            'currentQuestion' => $currentQuestion,
            'isWaitingForOpponent' => $this->isWaitingForOpponent,
            'waitingSecondsLeft' => $this->waitingSecondsLeft,
            'isFinished' => $this->isFinished,
            'isAnswered' => $this->isAnswered,
            'selectedOption' => $this->selectedOption,
            'lastCorrectAnswer' => $this->lastCorrectAnswer,
            'winnerName' => $this->winnerName,
            'player1Score' => $this->player1Score,
            'player2Score' => $this->player2Score,
            'currentIndex' => $this->currentIndex,
            'questionList' => $this->questionList,
        ]);
    }
}