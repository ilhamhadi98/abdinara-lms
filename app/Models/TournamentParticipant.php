<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentParticipant extends Model
{
    protected $fillable = [
        'tournament_id',
        'user_id',
        'tryout_session_id',
        'score',
        'duration_seconds',
        'rank_position',
        'is_passed',
    ];

    protected $casts = [
        'is_passed' => 'boolean',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tryoutSession()
    {
        return $this->belongsTo(TryoutSession::class);
    }
}