<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    protected $fillable = [
        'room_code',
        'player1_id',
        'player2_id',
        'player2_name',
        'is_bot',
        'status',
        'winner_id',
        'winner_name',
        'player1_score',
        'player2_score',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
    ];

    public function player1()
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'battle_questions')
            ->withPivot('sort_order')
            ->orderBy('battle_questions.sort_order');
    }
}