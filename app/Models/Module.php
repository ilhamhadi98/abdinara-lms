<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['title', 'description', 'category', 'content_type', 'url', 'is_active'];

    public function progress()
    {
        return $this->hasMany(ModuleProgress::class);
    }
}
