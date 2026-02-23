<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['admin', 'super-admin']);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'subscription_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'subscription_expires_at' => 'datetime',
        ];
    }

    public function tryoutSessions()
    {
        return $this->hasMany(TryoutSession::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isSubscribed(): bool
    {
        return $this->subscription_expires_at && $this->subscription_expires_at->isFuture();
    }
}
