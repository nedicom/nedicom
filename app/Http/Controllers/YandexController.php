<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;

class YandexController extends Controller
{
    public function yandexoauth(Request $request)
    {
        $user = Socialite::driver('yandex')->user();
        dd($user);

        // Ваша логика создания/авторизации пользователя
        /*$authUser = User::updateOrCreate([
            'yandex_id' => $user->id,
        ], [
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make(Str::random(24))
        ]);

        Auth::login($authUser, true);
        */

        return redirect()->route('dashboard');
    }
}
