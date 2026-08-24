<?php

namespace App\Http\Controllers;

use App\Models\Battle;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BattleController extends Controller
{
    /**
     * Display Battle Lobby.
     */
    public function index()
    {
        $myBattles = Battle::where(function ($q) {
                $q->where('player1_id', Auth::id())
                  ->orWhere('player2_id', Auth::id());
            })
            ->where('status', 'finished')
            ->latest('id')
            ->take(10)
            ->get();

        $totalWins = Battle::where('winner_id', Auth::id())->count();
        $totalBattles = Battle::where(function ($q) {
                $q->where('player1_id', Auth::id())
                  ->orWhere('player2_id', Auth::id());
            })
            ->where('status', 'finished')
            ->count();

        $winRate = $totalBattles > 0 ? round(($totalWins / $totalBattles) * 100) : 0;

        return view('battle.index', compact('myBattles', 'totalWins', 'totalBattles', 'winRate'));
    }

    /**
     * Create a new room for challenging friends.
     */
    public function createRoom()
    {
        $roomCode = strtoupper(Str::random(6));

        // Pick 10 questions
        $questions = Question::inRandomOrder()->take(10)->get();

        $battle = Battle::create([
            'room_code' => $roomCode,
            'player1_id' => Auth::id(),
            'status' => 'waiting',
        ]);

        foreach ($questions as $order => $q) {
            $battle->questions()->attach($q->id, ['sort_order' => $order + 1]);
        }

        return redirect()->route('battle.arena', $battle->id);
    }

    /**
     * Join an existing battle via room code.
     */
    public function joinRoom(Request $request)
    {
        $request->validate([
            'room_code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->room_code));
        $battle = Battle::where('room_code', $code)->first();

        if (!$battle) {
            return back()->with('error', 'Kode room duel tidak ditemukan.');
        }

        if ($battle->status === 'finished') {
            return back()->with('error', 'Sesi duel ini sudah selesai.');
        }

        if ($battle->status === 'cancelled') {
            return back()->with('error', 'Room duel ini telah dibatalkan atau kedaluwarsa.');
        }

        if ($battle->player1_id !== Auth::id() && !$battle->player2_id) {
            $battle->update([
                'player2_id' => Auth::id(),
                'status' => 'active',
            ]);
        }

        return redirect()->route('battle.arena', $battle->id);
    }

    /**
     * Direct link join via room code URL.
     */
    public function joinDirect(string $roomCode)
    {
        $battle = Battle::where('room_code', strtoupper($roomCode))->first();

        if (!$battle) {
            return redirect()->route('battle.index')->with('error', 'Kode room duel tidak ditemukan.');
        }

        if ($battle->status === 'finished') {
            return redirect()->route('battle.index')->with('error', 'Sesi duel ini sudah selesai.');
        }

        if ($battle->status === 'cancelled') {
            return redirect()->route('battle.index')->with('error', 'Room duel ini telah dibatalkan atau kedaluwarsa.');
        }

        if ($battle->player1_id !== Auth::id() && !$battle->player2_id && $battle->status === 'waiting') {
            $battle->update([
                'player2_id' => Auth::id(),
                'status' => 'active',
            ]);
        }

        return redirect()->route('battle.arena', $battle->id);
    }

    /**
     * Quick Auto Matchmaking with AI Ghost Opponent fallback.
     */
    public function quickMatch()
    {
        // 1. Look for an open human waiting battle
        $openBattle = Battle::where('status', 'waiting')
            ->where('player1_id', '!=', Auth::id())
            ->whereNull('player2_id')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->latest('id')
            ->first();

        if ($openBattle) {
            $openBattle->update([
                'player2_id' => Auth::id(),
                'status' => 'active',
            ]);
            return redirect()->route('battle.arena', $openBattle->id);
        }

        // 2. Otherwise, create a battle with an AI Ghost Opponent
        $botNames = ['Rian IPDN 👮‍♂️', 'Siti Poltekip 👩‍✈️', 'Adit CASN Kemenkeu 💼', 'Dina STIS 📊', 'Budi Pejuang SKD 🎯'];
        $chosenBot = $botNames[array_rand($botNames)];

        $roomCode = strtoupper(Str::random(6));
        $questions = Question::inRandomOrder()->take(10)->get();

        $battle = Battle::create([
            'room_code' => $roomCode,
            'player1_id' => Auth::id(),
            'player2_name' => $chosenBot,
            'is_bot' => true,
            'status' => 'active',
        ]);

        foreach ($questions as $order => $q) {
            $battle->questions()->attach($q->id, ['sort_order' => $order + 1]);
        }

        return redirect()->route('battle.arena', $battle->id);
    }

    /**
     * Render the Livewire Duel Arena.
     */
    public function arena(Battle $battle)
    {
        if ($battle->status === 'cancelled') {
            return redirect()->route('battle.index')->with('error', 'Room duel ini telah dibatalkan atau kedaluwarsa.');
        }

        // Auto join if waiting and open
        if ($battle->status === 'waiting' && $battle->player1_id !== Auth::id() && !$battle->player2_id) {
            $battle->update([
                'player2_id' => Auth::id(),
                'status' => 'active',
            ]);
            $battle->refresh();
        }

        // Authorization: Player1 or Player2 or Admin
        if ($battle->player1_id !== Auth::id() && $battle->player2_id !== Auth::id() && !Auth::user()->hasAnyRole(['admin', 'super-admin'])) {
            return redirect()->route('battle.index')->with('error', 'Room duel ini sudah penuh atau Anda tidak memiliki akses.');
        }

        $battle->load('player1', 'player2', 'questions');

        return view('battle.arena-wrapper', compact('battle'));
    }
}