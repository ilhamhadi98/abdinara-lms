<?php

namespace App\Livewire;

use App\Models\TryoutSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TryoutResult extends Component
{
    use WithPagination;

    public function render()
    {
        // Only load finished sessions – no eager load berlebihan
        $sessions = TryoutSession::where('user_id', Auth::id())
            ->where('status', 'finished')
            ->with('tryout:id,title,total_questions,duration_minutes')
            ->latest('finished_at')
            ->paginate(10);

        return view('livewire.tryout-result', compact('sessions'))
            ->layout('layouts.app');
    }
}
