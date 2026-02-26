<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tryout extends Model
{
    protected $fillable = [
        'title',
        'duration_minutes',
        'total_questions',
        'is_active',
        'category_id',
        'subtopic_id',
        'difficulty',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'tryout_questions')
                    ->withPivot('sort_order')
                    ->orderBy('tryout_questions.sort_order');
    }

    public function sessions()
    {
        return $this->hasMany(TryoutSession::class);
    }
}
