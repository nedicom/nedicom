<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class YandexController extends Controller
{
    public function yandexoauth()
    {



        try {
            // 1. Получаем access token по коду
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

            return response(<<<HTML
            <script>
                if (window.opener) {
                    window.opener.postMessage("oauthSuccess", "*");
                    window.close();
                } else {
                    window.location.href = "/";
                }
            </script>
        HTML);

            /*
            return response(<<<HTML
                <script>
                if (window.opener) {
                    // Отправляем сообщение об успехе
                    window.opener.postMessage({
                        type: 'yandex-auth-success',
                        user: {
                            id: "{$user->id}",
                            name: "{$user->name}"
                        }
                    }, window.location.origin);
                    
                    // Закрываем окно
                    window.close();
                } else {
                    // Если нет родительского окна - редирект
                    window.location.href = '/Welcome';
                }
            </script>
            HTML);


            
            if (Cookie::get('last_url')) {
                return redirect()->to(Cookie::get('last_url'));
            } else {
                return redirect()->route('Welcome');
            }*/
        } catch (\Exception $e) {
            return inertia('Auth/Login', [
                'error' => 'Yandex authentication failed: ' . $e->getMessage()
            ]);
        }
    }
}
