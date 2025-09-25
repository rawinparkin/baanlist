<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserCredit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // If user has no credit, redirect to buy package
            if ((int) $user->credit <= 0) {
                return redirect()->route('user.package.plan')->with([
                    'notification' => 'คุณไม่มีเครดิตเพียงพอ กรุณาซื้อแพ็คเกจ!',
                    'notification_class' => 'warning'
                ]);
            }
        }
        return $next($request);
    }
}
