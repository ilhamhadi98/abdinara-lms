<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\LoginResponse as FilamentLoginResponse;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends FilamentLoginResponse
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = auth()->user();

        // Jika user bisa akses panel Filament → redirect ke panel
        if ($user && method_exists($user, 'canAccessPanel')) {
            $panel = Filament::getCurrentPanel();
            if ($panel && $user->canAccessPanel($panel)) {
                return parent::toResponse($request);
            }
        }

        // User biasa (member) → redirect ke intended URL atau dashboard
        return redirect()->intended(route('dashboard'));
    }
}

