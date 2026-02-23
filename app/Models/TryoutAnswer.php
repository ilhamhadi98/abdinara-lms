<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutAnswer extends Model
{
    protected $fillable = [
        'session_id',
        'question_id',
        'selected_answer',
        'is_flagged',
        'time_spent',
    ];

    protected function casts(): array
    {
        return [
            'is_flagged' => 'boolean',
        ];
    }

    public function session()
    {
        return $this->belongsTo(TryoutSession::class, 'session_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
