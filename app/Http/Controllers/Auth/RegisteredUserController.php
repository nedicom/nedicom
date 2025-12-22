<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        //$red = back()->getTargetUrl();

        $redirect = back()->getTargetUrl() ?? 
                $request->session()->get('url.intended') ?? 
                route('dashboard');

        return Inertia::render('Auth/Register',[
            'redirect' => $redirect,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            '_ym_uid' => 'nullable|string|max:255',
            '_ga' => 'nullable|string|max:255',
            '_nedicoo' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'lawyer' => $request->lawyer,
            'name' => $request->name,
            'email' => $request->email,
            '_ym_uid' => $request->input('_ym_uid'),
            '_ga' => $request->input('_ga'),
            '_nedicoo' => $request->input('_nedicoo'),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect($request->redirect);
    }
}
