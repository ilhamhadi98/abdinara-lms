<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TryoutSession;
use Illuminate\Support\Facades\DB;

class Leaderboard extends Component
{
    public function render()
    {
        $topUsers = TryoutSession::select('user_id', DB::raw('SUM(score) as total_score'), DB::raw('COUNT(*) as total_sessions'))
            ->where('status', 'finished')
            ->groupBy('user_id')
            ->orderByDesc('total_score')
            ->with('user')
            ->take(5)
            ->get();

        return view('livewire.leaderboard', [
            'topUsers' => $topUsers
        ]);
    }
}
