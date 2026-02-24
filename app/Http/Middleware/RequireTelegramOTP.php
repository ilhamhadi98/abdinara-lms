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

        // Jika tidak login, atau bukan route admin, biarkan lewat (sudah ditangani middleware auth)
        if (!$user) {
            return $next($request);
        }

        // Cek apakah user adalah admin dan punya telegram_chat_id
        if ($user->telegram_chat_id && !$request->session()->has('otp_verified_at')) {
            // Abaikan pengecekan jika sedang berada di halaman livewire OTP itu sendiri
            if ($request->routeIs('*telegram-otp*') || $request->routeIs('livewire.update')) {
                return $next($request);
            }

            return redirect()->to('/admin/auth/telegram-otp');
        }

        return $next($request);
    }
}
