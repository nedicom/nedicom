<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class YandexController extends Controller
{
    public function yandexoauth(Request $request)
    {
        $user = Socialite::driver('yandex')->user();

        // Ваша логика создания/авторизации пользователя
        $authUser = User::updateOrCreate([
            'email' => $user->email,

        ], [
            'yandex_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make(Str::random(24))
        ]);

        Auth::login($authUser, true);

        return redirect()->route('Welcome');
    }
}
