<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsOwner
{
    public function handle(Request $request, Closure $next)
    {
        if ((int) $request->route('id') !== Auth::id()) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้');
        }

        return $next($request);
    }
}
