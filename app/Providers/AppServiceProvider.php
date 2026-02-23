<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Override Filament LoginResponse agar user biasa diarahkan ke /dashboard
        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            $activity = \App\Models\UserActivity::firstOrNew([
                'user_id' => $event->user->id,
                'action' => 'login',
                'date' => now()->toDateString(),
            ]);

            if ($activity->exists) {
                $activity->increment('count');
            } else {
                $activity->save();
            }
        });
    }
}
