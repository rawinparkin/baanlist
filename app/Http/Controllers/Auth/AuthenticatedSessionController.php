<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt to log in manually instead of using $request->authenticate()
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
            ])->withInput();
        }
        $request->authenticate();

        $id = Auth::user()->id;
        $adminData = User::find($id);
        $username = $adminData->name;
        $request->session()->regenerate();

        $url = '';
        if ($request->user()->role === 'admin') {
            $url = 'admin/dashboard';
        } else if ($request->user()->role === 'agent') {
            $url = 'agent/dashboard';
        } else if ($request->user()->role === 'user') {
            $url = '/dashboard';
        }

        //return redirect()->intended($url);
        return redirect()->back();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
