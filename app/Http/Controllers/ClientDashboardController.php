<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return Inertia::render('ClientDashboard', [
                'isAuthenticated' => false,
                'auth' => null,
                'clientData' => [
                    'id' => 100,
                    'name' => 'Иванов Иван',
                    'email' => 'example@yandex.ru',
                    'payments_count' => 4,
                    'tasks_count' => 3,
                    'payments_for_client' => [
                        ['id' => 4, 'summ' => 1000, 'clientid' => 100, 'created_at' => '2025-09-15T08:22:22.000000Z'],
                        ['id' => 3, 'summ' => 8250, 'clientid' => 100, 'created_at' => '2023-09-11T13:57:16.000000Z'],
                        ['id' => 2, 'summ' => 20000, 'clientid' => 100, 'created_at' => '2023-08-31T10:42:29.000000Z'],
                        ['id' => 1, 'summ' => 3000, 'clientid' => 100, 'created_at' => '2023-07-26T12:30:21.000000Z']
                    ],
                    'tasks_for_client' => [
                        ['id' => 3, 'name' => 'Получение ответа', 'status' => 'выполнена', 'clientid' => 502, 'created_at' => '2025-12-10T05:40:15.000000Z', 'donetime' => '2025-12-22 15:56:02', 'hrftodcm' => 'https://disk.yandex.ru/i/8fuQqywzKb9tPw'],
                        ['id' => 2, 'name' => 'Направление заявления', 'status' => 'выполнена', 'clientid' => 502, 'created_at' => '2025-10-20T09:46:04.000000Z', 'donetime' => '2025-10-22 15:56:02', 'hrftodcm' => null],
                        ['id' => 1, 'name' => 'Заявление в администрацию города о предоставлении информации', 'status' => 'выполнена', 'clientid' => 502, 'created_at' => '2025-09-15T07:09:44.000000Z', 'donetime' => '2025-09-19 14:04:22', 'hrftodcm' => null]
                    ]
                ],
                'error' => null,
                'userEmail' => null,
                'publicContent' => [
                    'title' => 'Личный кабинет клиента',
                    'description' => 'Управление задачами и платежами.Отслеживайте статусы,получайте уведомления,работайте с документами онлайн.',
                    'features' => [
                        ['icon' => 'tasks', 'title' => 'Отслеживание задач', 'description' => 'Следите за выполнением заказов в реальном времени'],
                        ['icon' => 'payments', 'title' => 'История платежей', 'description' => 'Вся информация о платежах в одном месте'],
                        ['icon' => 'documents', 'title' => 'Электронные документы', 'description' => 'Скачивайте договоры,акты и отчеты'],
                        ['icon' => 'support', 'title' => 'Поддержка 24/7', 'description' => 'Помощь по любым вопросам работы с системой']
                    ],
                    'benefits' => [
                        'Прозрачность всех процессов',
                        'Экономия времени на согласованиях',
                        'Безопасное хранение документов',
                        'Мобильный доступ с любого устройства'
                    ],
                    'stats' => [
                        ['value' => '1000+', 'label' => 'довольных клиентов'],
                        ['value' => '99%', 'label' => 'задач в срок'],
                        ['value' => '24/7', 'label' => 'техподдержка'],
                        ['value' => '5 лет', 'label' => 'на рынке']
                    ]
                ]
            ]);
        }
        $userEmail = $user->email;
        $error = null;
        $data = null;
        try {
            $response = Http::withHeaders([
                'Authorization' => config('services.crm.token'),
                'Accept' => 'application/json',
            ])
                ->withoutVerifying()
                ->timeout(10)
                ->get(config('services.crm.url') . "/client/" . urlencode($userEmail) . "/summary");
            if ($response->successful()) {
                $json = $response->json();
                if (isset($json['success']) && $json['success'] === true) {
                    $data = $json['data'] ?? null;
                } else {
                    $error = $json['message'] ?? 'Ошибка CRM';
                }
            } else {
                $status = $response->status();
                switch ($status) {
                    case 401:
                        $error = 'Ошибка авторизации: неверный токен API';
                        break;
                    case 403:
                        $error = 'Доступ к данным клиента запрещен. Запросите его у юриста';
                        break;
                    case 404:
                        $error = 'Вы не являетесь клиентом, с доступом, проверьте что Вы указали email при регистрации';
                        break;
                    case 500:
                        $error = 'Ошибка сервера CRM';
                        break;
                    default:
                        $error = "Ошибка CRM (код: {$status})";
                }
            }
        } catch (\Exception $e) {
            $error = 'Ошибка соединения с CRM';
        }
        return Inertia::render('ClientDashboard', [
            'isAuthenticated' => true,
            'auth' => $user,
            'clientData' => $data,
            'error' => $error,
            'userEmail' => $userEmail,
            'publicContent' => null,
            'backendurl' => $request->path(),
        ]);
    }
}
