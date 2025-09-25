<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
       
        $user = $request->user();
        if (!$user) {
            return redirect('/');
        }
        if ($user->role !== $role) {
            return redirect('dashboard');
        }
        return $next($request);
    }
}