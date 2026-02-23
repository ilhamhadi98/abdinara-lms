<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ($user->hasRole(['admin', 'super-admin']) || $user->isSubscribed())) {
            return $next($request);
        }

        return redirect()->route('subscription.index')->with('error', 'Silakan berlangganan terlebih dahulu untuk mengakses Tryout simulasi CAT.');
    }
}
