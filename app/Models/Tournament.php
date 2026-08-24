<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'title',
        'edition_number',
        'tryout_id',
        'start_at',
        'end_at',
        'is_active',
        'prizes_description',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }

    public function participants()
    {
        return $this->hasMany(TournamentParticipant::class);
    }

    public function isOngoing(): bool
    {
        return now()->between($this->start_at, $this->end_at);
    }
}