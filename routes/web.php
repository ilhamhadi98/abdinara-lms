<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TryoutSessionController;
use App\Http\Controllers\SubscriptionController;
use App\Livewire\TryoutEngine;
use App\Livewire\TryoutList;
use App\Livewire\TryoutResult;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Illuminate\Support\Facades\Auth::user();
    
    // Modules Progress
    $totalModules = \App\Models\Module::where('is_active', true)->count();
    $completedModules = \App\Models\ModuleProgress::where('user_id', $user->id)
                            ->where('completed', true)->count();
    $moduleProgressPercent = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;
    
    // Active Modules for main list
    $activeModules = \App\Models\Module::where('is_active', true)->with(['progress' => function($q) use ($user) {
        $q->where('user_id', $user->id);
    }])->take(3)->get();
    
    // Tryout Sessions (Simulasi CAT)
    $tryoutSessions = \App\Models\TryoutSession::where('user_id', $user->id)
                          ->where('status', 'finished')->get();
    $totalSessions = $tryoutSessions->count();
    $avgScore = $totalSessions > 0 ? round($tryoutSessions->avg('score'), 1) : 0;
    
    // Personal Target (UserTargets)
    $userTarget = \App\Models\UserTarget::where('user_id', $user->id)
                      ->where('is_completed', false)
                      ->orderBy('deadline_date', 'asc')
                      ->first();
    
    // Ranking Internal based on total tryout scores
    $usersRanking = \App\Models\TryoutSession::selectRaw('user_id, sum(score) as total_score')
                         ->where('status', 'finished')
                         ->groupBy('user_id')
                         ->orderByDesc('total_score')
                         ->pluck('total_score', 'user_id');
    
    $rank = 0;
    $totalParticipants = \App\Models\User::count();
    $currentRank = 1;
    foreach ($usersRanking as $uid => $ts) {
        if ($uid == $user->id) {
            $rank = $currentRank;
            break;
        }
        $currentRank++;
    }
    if ($rank === 0 && $totalSessions > 0) $rank = $currentRank; // Edge case safety
    
    // Agenda
    $agendas = \App\Models\Agenda::where('is_active', true)->orderBy('time', 'asc')->get();
    
    // Announcements
    $announcements = \App\Models\Announcement::where('is_active', true)->latest()->take(3)->get();

    return view('dashboard', compact(
        'moduleProgressPercent', 'completedModules', 'totalModules',
        'activeModules',
        'totalSessions', 'avgScore',
        'userTarget',
        'rank', 'totalParticipants',
        'agendas', 'announcements'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::get('/subscription/history', [SubscriptionController::class, 'history'])->name('subscription.history');
    Route::post('/subscription/checkout/{package}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/subscription/{transaction}/pay', [SubscriptionController::class, 'pay'])->name('subscription.pay');
    Route::get('/subscription/{transaction}/invoice', [SubscriptionController::class, 'invoice'])->name('subscription.invoice');
});

Route::post('/subscription/notification', [SubscriptionController::class, 'notification'])->name('subscription.notification');


// ============================================================
// Member Routes — Tryout (permission: take tryout + needs subscription)
// Admin panel ditangani oleh Filament di /admin
// ============================================================
Route::middleware(['auth', 'can:take tryout', 'subscribed'])
    ->name('tryout.')
    ->group(function () {
        Route::get('/tryout', TryoutList::class)->name('index');
        Route::get('/tryout/session/{session}', TryoutEngine::class)->name('engine');
        Route::get('/tryout/results', TryoutResult::class)->name('results');
        Route::get('/tryout/results/{session}', [TryoutSessionController::class, 'show'])
             ->name('results.show');
    });

require __DIR__.'/auth.php';
