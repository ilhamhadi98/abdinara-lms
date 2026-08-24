<?php

namespace App\Livewire;

use App\Models\Battle;
use App\Models\TournamentParticipant;
use App\Models\TryoutSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class TryoutResult extends Component
{
    use WithPagination;

    public function render()
    {
        $userId = Auth::id();

        // 1. Paginated Sessions for History Cards
        $sessions = TryoutSession::where('user_id', $userId)
            ->where('status', 'finished')
            ->with('tryout:id,title,total_questions,duration_minutes')
            ->latest('finished_at')
            ->paginate(8);

        // 2. Aggregate Tryout Stats
        $totalTryouts = TryoutSession::where('user_id', $userId)->where('status', 'finished')->count();
        $avgScore = $totalTryouts > 0 ? round(TryoutSession::where('user_id', $userId)->where('status', 'finished')->avg('score')) : 0;
        $highestScore = TryoutSession::where('user_id', $userId)->where('status', 'finished')->max('score') ?? 0;

        // 3. Aggregate Game / Battle Stats
        $totalBattles = Battle::where(function ($q) use ($userId) {
                $q->where('player1_id', $userId)->orWhere('player2_id', $userId);
            })
            ->where('status', 'finished')
            ->count();

        $totalBattleWins = Battle::where('winner_id', $userId)
            ->where('status', 'finished')
            ->count();

        $battleWinRate = $totalBattles > 0 ? round(($totalBattleWins / $totalBattles) * 100) : 0;

        // 4. Aggregate Tournament Stats
        $totalTournaments = TournamentParticipant::where('user_id', $userId)->count();
        $bestRank = TournamentParticipant::where('user_id', $userId)->whereNotNull('rank_position')->min('rank_position');

        // 5. Gamer EXP & Level System
        $totalExp = ($totalTryouts * 500) + ($totalBattles * 150) + ($totalBattleWins * 200) + ($totalTournaments * 1000);
        $level = max(1, floor($totalExp / 1000) + 1);
        $levelProgress = ($totalExp % 1000) / 10;

        $gamerTitle = match(true) {
            $level >= 15 => 'Komandan CASN 👑',
            $level >= 10 => 'Ksatria Kedinasan 🎖️',
            $level >= 6 => 'Pejuang Tangguh ⭐⭐⭐',
            $level >= 3 => 'Taruna Muda ⭐⭐',
            default => 'Kadet Pemula ⭐',
        };

        // 6. Subtopic Accuracy All-Time Aggregation (from all finished tryouts)
        $allAnswers = DB::table('tryout_answers as ta')
            ->join('tryout_sessions as ts', 'ta.session_id', '=', 'ts.id')
            ->join('questions as q', 'ta.question_id', '=', 'q.id')
            ->leftJoin('subtopics as s', 'q.subtopic_id', '=', 's.id')
            ->leftJoin('categories as c', 's.category_id', '=', 'c.id')
            ->where('ts.user_id', $userId)
            ->where('ts.status', 'finished')
            ->select(
                'ta.selected_answer',
                'q.correct_answer',
                's.name as subtopic_name',
                'c.name as category_name'
            )
            ->get();

        $subtopicStats = [];
        $totalAnswered = $allAnswers->count();
        $totalCorrect = 0;

        foreach ($allAnswers as $row) {
            $isCorrect = $row->selected_answer && strtoupper($row->selected_answer) === strtoupper($row->correct_answer);
            if ($isCorrect) {
                $totalCorrect++;
            }

            $subName = $row->subtopic_name ?? ($row->category_name ?? 'Umum');

            if (!isset($subtopicStats[$subName])) {
                $subtopicStats[$subName] = [
                    'name' => $subName,
                    'category' => $row->category_name ?? 'SKD',
                    'category_slug' => Str::slug($row->category_name ?? 'twk'),
                    'subtopic_slug' => Str::slug($subName),
                    'total' => 0,
                    'correct' => 0,
                    'accuracy' => 0,
                ];
            }

            $subtopicStats[$subName]['total']++;
            if ($isCorrect) {
                $subtopicStats[$subName]['correct']++;
            }
        }

        foreach ($subtopicStats as &$st) {
            $st['accuracy'] = $st['total'] > 0 ? round(($st['correct'] / $st['total']) * 100) : 0;
        }
        unset($st);

        // Overall Accuracy
        $overallAccuracy = $totalAnswered > 0 ? round(($totalCorrect / $totalAnswered) * 100) : 0;

        // Top Strengths (Kelebihan) and Top Weaknesses (Kekurangan)
        $sortedStrengths = collect($subtopicStats)->sortByDesc('accuracy')->values();
        $topStrengths = $sortedStrengths->take(3);

        $sortedWeaknesses = collect($subtopicStats)->sortBy('accuracy')->values();
        $topWeaknesses = $sortedWeaknesses->take(3);

        // Radar chart datasets
        $radarLabels = !empty($subtopicStats) ? array_keys($subtopicStats) : ['TWK', 'TIU', 'TKP', 'Integritas', 'Nasionalisme', 'Deret'];
        $radarData = !empty($subtopicStats) ? array_column($subtopicStats, 'accuracy') : [0, 0, 0, 0, 0, 0];

        // Estimated passing probability
        $passingProb = match(true) {
            $overallAccuracy >= 80 => 95,
            $overallAccuracy >= 65 => 78,
            $overallAccuracy >= 50 => 55,
            $overallAccuracy > 0 => 35,
            default => 10,
        };

        // Achievement Badges
        $badges = [
            [
                'name' => 'First Step',
                'desc' => 'Menyelesaikan tryout pertama',
                'icon' => 'bi-flag-fill',
                'color' => 'primary',
                'unlocked' => $totalTryouts >= 1,
            ],
            [
                'name' => 'Duel Warrior',
                'desc' => 'Memenangkan duel 1v1',
                'icon' => 'bi-swords',
                'color' => 'danger',
                'unlocked' => $totalBattleWins >= 1,
            ],
            [
                'name' => 'Sharp Mind',
                'desc' => 'Akurasi all-time di atas 70%',
                'icon' => 'bi-bullseye',
                'color' => 'warning',
                'unlocked' => $overallAccuracy >= 70,
            ],
            [
                'name' => 'National Contender',
                'desc' => 'Berpartisipasi di Liga Nasional',
                'icon' => 'bi-trophy-fill',
                'color' => 'success',
                'unlocked' => $totalTournaments >= 1,
            ],
            [
                'name' => 'Grind Master',
                'desc' => 'Mencapai Level 5 Pejuang',
                'icon' => 'bi-shield-shaded',
                'color' => 'info',
                'unlocked' => $level >= 5,
            ],
        ];

        return view('livewire.tryout-result', compact(
            'sessions',
            'totalTryouts',
            'avgScore',
            'highestScore',
            'totalBattles',
            'totalBattleWins',
            'battleWinRate',
            'totalTournaments',
            'bestRank',
            'totalExp',
            'level',
            'levelProgress',
            'gamerTitle',
            'overallAccuracy',
            'totalAnswered',
            'topStrengths',
            'topWeaknesses',
            'radarLabels',
            'radarData',
            'passingProb',
            'badges'
        ))->layout('layouts.app');
    }
}