<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class YandexController extends Controller
{
    public function yandexoauth()
    {

        $response = Http::asForm()->post(config('services.yandex.token_url'), [
            'grant_type' => 'authorization_code',
            'code' => request('code'),
            'client_id' => config('services.yandex.client_id'),
            'client_secret' => config('services.yandex.client_secret'),
        ]);


        if (!$response->ok()) {
            throw new \Exception('Failed to get access token');
        }

        $accessToken = $response->json()['access_token'];

        dd($accessToken);

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
