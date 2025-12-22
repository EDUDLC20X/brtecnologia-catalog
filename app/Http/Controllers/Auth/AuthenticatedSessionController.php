<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        $request->authenticate();

        $request->session()->regenerate();

        // Role-based redirect: admin -> admin dashboard, client -> catalog
        $user = $request->user();

        // If an intended URL exists (was attempting to access protected route), prefer it
        $intended = redirect()->intended()->getTargetUrl();

        // If intended URL is the default HOME, ignore and use role-based
        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // For clients, send to catalog
        return redirect()->intended(route('catalog.index'));
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
