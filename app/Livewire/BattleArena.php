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

    public function mount(Battle $battle)
    {
        $this->battle = $battle;
        $this->player1Score = (int) ($battle->player1_score ?? 0);
        $this->player2Score = (int) ($battle->player2_score ?? 0);

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

    public function selectAnswer(string $option)
    {
        if ($this->isAnswered || $this->isFinished) {
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
        return view('livewire.battle-arena', compact('currentQuestion'));
    }
}