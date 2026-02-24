<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTelegramOTP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        \Illuminate\Support\Facades\Log::info('RequireTelegramOTP Hit', ['has_user' => (bool)$user, 'route' => $request->route()->getName()]);

        // Cek apakah user adalah admin dan punya telegram_chat_id
        if ($user && $user->telegram_chat_id && !$request->session()->has('otp_verified_at')) {
            // Abaikan pengecekan jika sedang berada di halaman livewire OTP itu sendiri
            if ($request->routeIs('*telegram-otp*') || $request->routeIs('livewire.update')) {
                return $next($request);
            }

            return redirect()->to('/admin/auth/telegram-otp');
        }

        return $next($request);
    }
}
