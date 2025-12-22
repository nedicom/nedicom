<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {

        $previousUrl = back()->getTargetUrl();

        if ($previousUrl != url('/login') && $previousUrl != url('/register')) {
            Cookie::queue('last_url', $previousUrl, 60);
        } else {
            Cookie::queue('last_url', url('/profile'), 60);
        }

        $red = back()->getTargetUrl();
        return Inertia::render('Auth/Login', [
            'redirect' => $red,
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($request->filled('_ym_uid') && empty($user->_ym_uid)) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['_ym_uid' => $request->input('_ym_uid')]);
        }

        if ($request->filled('_ga') && empty($user->_ga)) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['_ga' => $request->input('_ga')]);
        }

        if ($request->filled('_nedicoo') && empty($user->_nedicoo)) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['_nedicoo' => $request->input('_nedicoo')]);
        }

        return redirect($request->redirect);
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
