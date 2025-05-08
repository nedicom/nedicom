<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


class YandexController extends Controller
{
    public function yandexoauth(Request $request)
    {

        try {

            // 1. Получаем access token по коду
            $response = Http::asForm()->post(config('services.yandex.token_url'), [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                //'code' => request('code'),
                'client_id' => config('services.yandex.client_id'),
                'client_secret' => config('services.yandex.client_secret'),
            ]);

            if (!$response->ok()) {
                throw new \Exception('Failed to get access token');
            }

            $data = $response->json();

            $accessToken = $data['access_token'];

            // 2. Получаем информацию о пользователе
            $userInfo = Http::withHeaders([
                'Authorization' => 'OAuth ' . $accessToken,
            ])->get(config('services.yandex.user_info_url'), [
                'format' => 'json',
            ])->json();

            // 3. Создаем или обновляем пользователя
            $user = User::updateOrCreate([
                'email' => $userInfo['default_email'],
            ], [
                'name' => $userInfo['real_name'] ?? $userInfo['login'],
                'yandex_id' => $userInfo['id'],
                //'password' => bcrypt(Str::random(16)), // Генерируем случайный пароль
            ]);

            // 4. Авторизуем пользователя
            Auth::login($user);
            
        } catch (\Exception $e) {
            return inertia('Auth/Login', [
                'error' => 'Yandex authentication failed: ' . $e->getMessage()
            ]);
        }
    }
}
