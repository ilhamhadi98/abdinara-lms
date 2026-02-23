<?php

namespace App\Livewire;

use App\Models\TryoutAnswer;
use App\Models\TryoutSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TryoutEngine extends Component
{
    // Locked: cannot be modified from frontend
    #[Locked]
    public int $sessionId;

    public int $currentIndex = 0;

    // Sparse: [questionId => answer] - only answered questions stored
    public array $answers = [];

    // Sparse: [questionId => bool]
    public array $flagged = [];

    // -------------------------------------------------------------------------
    // Lifecycle
    // -------------------------------------------------------------------------

    public function mount(TryoutSession $session): void
    {
        // Validate session ownership
        abort_if($session->user_id !== Auth::id(), 403);
        abort_if($session->status === 'finished', 403, 'Tryout sudah selesai.');

        $this->sessionId = $session->id;

        // Pre-load persisted answers from DB (only selected_answer + is_flagged)
        $existing = TryoutAnswer::where('session_id', $this->sessionId)
            ->select('question_id', 'selected_answer', 'is_flagged')
            ->get();

        foreach ($existing as $row) {
            if ($row->selected_answer) {
                $this->answers[$row->question_id] = $row->selected_answer;
            }
            if ($row->is_flagged) {
                $this->flagged[$row->question_id] = true;
            }
        }
    }

    // -------------------------------------------------------------------------
    // Computed helpers (not stored as properties)
    // -------------------------------------------------------------------------

    protected function getSession(): TryoutSession
    {
        return TryoutSession::with('tryout')->findOrFail($this->sessionId);
    }

    /** Get ordered question IDs from cache (avoids re-querying pivot per request) */
    protected function getQuestionIds(): array
    {
        return Cache::remember(
            "tryout_session_{$this->sessionId}_qids",
            now()->addHours(3),
            fn () => DB::table('tryout_questions')
                ->where('tryout_id', $this->getSession()->tryout_id)
                ->orderBy('sort_order')
                ->pluck('question_id')
                ->toArray()
        );
    }

    // -------------------------------------------------------------------------
    // Navigation actions
    // -------------------------------------------------------------------------

    public function nextQuestion(): void
    {
        $ids = $this->getQuestionIds();
        if ($this->currentIndex < count($ids) - 1) {
            $this->currentIndex++;
        }
    }

    public function prevQuestion(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function goToQuestion(int $index): void
    {
        $ids = $this->getQuestionIds();
        if ($index >= 0 && $index < count($ids)) {
            $this->currentIndex = $index;
        }
    }

    // -------------------------------------------------------------------------
    // Answer & flag actions (saved lazily via defer)
    // -------------------------------------------------------------------------

    public function selectAnswer(string $answer): void
    {
        $ids = $this->getQuestionIds();
        $questionId = $ids[$this->currentIndex] ?? null;
        if (! $questionId) {
            return;
        }

        // Validate answer value
        if (! in_array($answer, ['A', 'B', 'C', 'D', 'E'])) {
            return;
        }

        $this->answers[$questionId] = $answer;

        // Upsert – single query, no N+1
        TryoutAnswer::updateOrCreate(
            ['session_id' => $this->sessionId, 'question_id' => $questionId],
            ['selected_answer' => $answer]
        );
    }

    public function toggleFlag(): void
    {
        $ids = $this->getQuestionIds();
        $questionId = $ids[$this->currentIndex] ?? null;
        if (! $questionId) {
            return;
        }

        $isFlagged = ! ($this->flagged[$questionId] ?? false);
        $this->flagged[$questionId] = $isFlagged;

        TryoutAnswer::updateOrCreate(
            ['session_id' => $this->sessionId, 'question_id' => $questionId],
            ['is_flagged' => $isFlagged]
        );
    }

    // -------------------------------------------------------------------------
    // Submit
    // -------------------------------------------------------------------------

    public function submitTryout(): void
    {
        $session = $this->getSession();

        // Validate session still belongs to user and is ongoing
        abort_if($session->user_id !== Auth::id(), 403);
        if ($session->status === 'finished') {
            $this->redirect(route('tryout.results.show', $session->id));

            return;
        }

        // Calculate score via SQL JOIN COUNT – no PHP loop
        $score = DB::table('tryout_answers as ta')
            ->join('questions as q', 'ta.question_id', '=', 'q.id')
            ->where('ta.session_id', $this->sessionId)
            ->whereRaw('ta.selected_answer = q.correct_answer')
            ->count();

        $durationSeconds = $session->started_at 
            ? (int) abs(now()->timestamp - $session->started_at->timestamp) 
            : 0;

        $session->update([
            'status' => 'finished',
            'finished_at' => now(),
            'score' => $score,
            'duration_seconds' => $durationSeconds,
        ]);

        $activity = \App\Models\UserActivity::firstOrNew([
            'user_id' => $session->user_id,
            'action' => 'tryout',
            'date' => now()->toDateString(),
        ]);

        if ($activity->exists) {
            $activity->increment('count');
        } else {
            $activity->save();
        }

        // Clear cache
        Cache::forget("tryout_session_{$this->sessionId}_qids");

        $this->redirect(route('tryout.results.show', $session->id));
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    public function render()
    {
        $ids = $this->getQuestionIds();
        $totalCount = count($ids);
        $questionId = $ids[$this->currentIndex] ?? null;

        $question = $questionId
            ? \App\Models\Question::select(
                'id', 'question_text', 'image',
                'option_a', 'option_b', 'option_c', 'option_d', 'option_e',
                'subtopic_id'
            )
                ->with('subtopic:id,name')
                ->find($questionId)
            : null;

        $session = $this->getSession();

        return view('livewire.tryout-engine', [
            'question' => $question,
            'questionIds' => $ids,
            'totalCount' => $totalCount,
            'currentIndex' => $this->currentIndex,
            'answers' => $this->answers,
            'flagged' => $this->flagged,
            'session' => $session,
            'expiredAt' => $session->expired_at?->toIso8601String(),
        ])->layout('layouts.tryout');
    }
}
