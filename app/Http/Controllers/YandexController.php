<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class YandexController extends Controller
{
    public function yandexoauth()
    {
        $user = Socialite::driver('yandex')->user();

        // Ваша логика создания/авторизации пользователя
        $authUser = User::updateOrCreate([
            'email' => $user->email,

        ], [
            'yandex_id' => $user->id,
            'name' => $user->name,
        ]);

        Auth::login($authUser, true);

        return redirect()->route('Welcome');
    }
}
