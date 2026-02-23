<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTarget extends Model
{
    protected $fillable = ['user_id', 'target_type', 'target_value', 'current_value', 'deadline_date', 'is_completed'];

    protected $casts = [
        'deadline_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
