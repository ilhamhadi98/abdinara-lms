<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutSession extends Model
{
    protected $fillable = [
        'user_id',
        'tryout_id',
        'started_at',
        'finished_at',
        'expired_at',
        'score',
        'duration_seconds',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at'  => 'datetime',
            'finished_at' => 'datetime',
            'expired_at'  => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }

    public function answers()
    {
        return $this->hasMany(TryoutAnswer::class, 'session_id');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeFinished($query)
    {
        return $query->where('status', 'finished');
    }

    public function isExpired(): bool
    {
        return $this->expired_at && now()->greaterThan($this->expired_at);
    }
}
