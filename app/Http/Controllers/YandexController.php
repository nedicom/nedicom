<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;


class YandexController extends Controller
{
    public function yandexoauth(Request $request)
    {

        try {
            if (empty($request->code)) {
                return inertia('Auth/Login', [
                    'error' => 'Сервис Яндекса прямо сейчас не доступен. Войдите через форму'
                ]);
            }
            // 1. Получаем access token по коду
            $response = Http::asForm()->post(config('services.yandex.token_url'), [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'client_id' => config('services.yandex.client_id'),
                'client_secret' => config('services.yandex.client_secret'),
            ]);

            if (!$response->ok()) {
                return inertia('Auth/Login', [
                    'error' => 'Сервис Яндекса прямо сейчас не доступен. Войдите через форму'
                ]);
            }

            $data = $response->json();

            $accessToken = $data['access_token'];

            // 2. Получаем информацию о пользователе
            $userInfo = Http::withHeaders([
                'Authorization' => 'OAuth ' . $accessToken,
            ])->get(config('services.yandex.user_info_url'), [
                'format' => 'json',
            ])->json();
            // проверяем
            if (empty($userInfo['id'])) {
                return inertia('Auth/Login', [
                    'error' => 'Не получили от Яндекса подтверждение. Войдите через форму'
                ]);
            }

            // 3. Создаем или обновляем пользователя
            // Если пришел email от yandex
            if (!empty($userInfo['default_email'])) {
                $existingUserByEmail = User::where('email', $userInfo['default_email'])->first();
            }

            // Если пользователь найден по email, но у него нет yandex_id — обновляем
            if (isset($existingUserByEmail) && empty($existingUserByEmail->yandex_id)) {
                $user = $existingUserByEmail->update([
                    'yandex_id' => $userInfo['id'],
                    'client_id' => $userInfo['client_id'],
                    'default_avatar_id' => $userInfo['default_avatar_id'],
                ]);
            }
            // Иначе создаем/обновляем по yandex_id
            else {
                dd(2);
                $user = User::updateOrCreate(
                    ['yandex_id' => $userInfo['id']], // Главный ключ
                    [
                        'name' => $userInfo['real_name'] ?? $userInfo['login'],
                        'client_id' => $userInfo['client_id'],
                        'default_avatar_id' => $userInfo['default_avatar_id'],
                    ]
                );
            }
            dd(3);
            if (!$user) {
                return inertia('Auth/Login', [
                    'error' => 'Что-то не получилось сверить с данными Яндекса. Войдите через форму'
                ]);
            }

            // 4. Авторизуем пользователя
            Auth::login($user);

            $lastUrl = Cookie::get('last_url');
            return Redirect::to($lastUrl ?: '/profile');
        } catch (\Exception $e) {
            return inertia('Auth/Login', [
                'error' => 'Что-то пошло не так. Войдите через форму' . $e->getMessage(),
            ]);
        }
    }
}
