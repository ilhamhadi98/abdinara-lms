<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'subtopic_id',
        'question_text',
        'image',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'option_e',
        'correct_answer',
        'explanation',
        'difficulty',
    ];

    // Never expose the correct answer to the frontend
    protected $hidden = ['correct_answer'];

    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }

    public function tryouts()
    {
        return $this->belongsToMany(Tryout::class, 'tryout_questions')
                    ->withPivot('sort_order');
    }
}
