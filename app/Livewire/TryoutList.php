<?php

namespace App\Livewire;

use App\Models\Tryout;
use App\Models\TryoutSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TryoutList extends Component
{
    public function startTryout(int $tryoutId): void
    {
        $tryout = Tryout::where('id', $tryoutId)->where('is_active', true)->firstOrFail();
        $user   = Auth::user();

        // Check if there's an ongoing session that hasn't expired
        $existing = TryoutSession::where('user_id', $user->id)
            ->where('tryout_id', $tryout->id)
            ->where('status', 'ongoing')
            ->first();

        if ($existing && !$existing->isExpired()) {
            $this->redirect(route('tryout.engine', $existing->id));
            return;
        }

        // If expired, mark as finished
        if ($existing && $existing->isExpired()) {
            $existing->update(['status' => 'finished', 'finished_at' => now()]);
        }

        // Create new session
        $session = TryoutSession::create([
            'user_id'    => $user->id,
            'tryout_id'  => $tryout->id,
            'started_at' => now(),
            'expired_at' => now()->addMinutes($tryout->duration_minutes),
            'status'     => 'ongoing',
        ]);

        $this->redirect(route('tryout.engine', $session->id));
    }

    public function render()
    {
        $tryouts = Tryout::where('is_active', true)
            ->withCount('questions')
            ->latest()
            ->paginate(12);

        // Get user's ongoing/finished sessions for this page of tryouts
        $userId     = Auth::id();
        $tryoutIds  = $tryouts->pluck('id');
        $mySessions = TryoutSession::where('user_id', $userId)
            ->whereIn('tryout_id', $tryoutIds)
            ->latest()
            ->get(['id', 'tryout_id', 'status', 'score', 'finished_at', 'expired_at'])
            ->keyBy('tryout_id');

        return view('livewire.tryout-list', compact('tryouts', 'mySessions'))
            ->layout('layouts.app');
    }
}
