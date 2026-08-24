<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\Tryout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    /**
     * Display the national weekly tournament page & live leaderboard.
     */
    public function index()
    {
        // Get or trigger latest tournament
        $tournament = Tournament::with('tryout')
            ->latest('id')
            ->first();

        if (!$tournament) {
            Artisan::call('tournament:schedule-weekly');
            $tournament = Tournament::with('tryout')->latest('id')->first();
        }

        $leaderboard = [];
        $myParticipation = null;

        if ($tournament) {
            $leaderboard = TournamentParticipant::where('tournament_id', $tournament->id)
                ->with('user:id,name')
                ->orderByDesc('score')
                ->orderBy('duration_seconds', 'asc')
                ->take(50)
                ->get();

            if (Auth::check()) {
                $myParticipation = TournamentParticipant::where('tournament_id', $tournament->id)
                    ->where('user_id', Auth::id())
                    ->first();
            }
        }

        $pastTournaments = Tournament::where('is_active', false)
            ->withCount('participants')
            ->latest('id')
            ->take(5)
            ->get();

        return view('tournament.index', compact('tournament', 'leaderboard', 'myParticipation', 'pastTournaments'));
    }

    /**
     * Display or download the digital E-Certificate for a passing participant.
     */
    public function certificate(TournamentParticipant $participant)
    {
        abort_if(!Auth::check() || ($participant->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['admin', 'super-admin'])), 403);
        abort_if(!$participant->is_passed, 404);

        $participant->load('tournament.tryout', 'user');

        return view('tournament.certificate', compact('participant'));
    }
}