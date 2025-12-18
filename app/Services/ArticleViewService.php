<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticleViewService
{
    /**
     * Засчитать просмотр с данными от клиента (из Vue)
     * Это ОСНОВНОЙ метод, который вызывается через API
     */
    public function countViewFromClient(int $articleId, array $clientData = []): bool
    {
        try {
            \Log::info('Запись просмотра в БД', [
                'article_id' => $articleId,
                'client_data' => $clientData
            ]);

            // Простые данные для записи
            $data = [
                'article_id' => $articleId,
                'session_id' => session()->getId() ?: 'sess_' . md5(microtime(true)),
                'ip_address' => request()->ip() ?: 'unknown',
                'user_agent' => substr(request()->userAgent() ?? '', 0, 500),
                'yandex_uid' => $clientData['yandex_uid'] ?? null,
                'yandex_client_id' => $clientData['yandex_client_id'] ?? null,
                'referer' => $clientData['referer'] ?? null,
                'viewed_at' => now(),
            ];

            // Удаляем null
            $data = array_filter($data, fn($value) => !is_null($value));

            Log::info('Данные для вставки', $data);

            // Пробуем записать
            $result = DB::connection('pgsql_stats')
                ->table('article_views')
                ->insert($data);

            Log::info('Результат вставки', ['success' => $result]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Ошибка записи просмотра', [
                'article_id' => $articleId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Получить ID сессии (упрощенный)
     */
    private function getSessionId(): string
    {
        // Используем Laravel session ID
        $sessionId = session()->getId();

        if (!$sessionId) {
            // Если сессия не стартовала, стартуем
            session()->start();
            $sessionId = session()->getId();
        }

        return 'laravel_' . $sessionId;
    }

    /**
     * Получить реальный IP (правильный метод)
     */
    private function getRealIp(): string
    {
        $ipSources = [
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR',
            'HTTP_CLIENT_IP',
        ];

        foreach ($ipSources as $source) {
            $ip = $_SERVER[$source] ?? null;
            if ($ip && filter_var($ip, FILTER_VALIDATE_IP)) {
                if ($source === 'HTTP_X_FORWARDED_FOR') {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]); // Берем первый IP в цепочке
                }
                return $ip;
            }
        }

        return request()->ip() ?: 'unknown';
    }

    /**
     * Получить реферер
     */
    private function getReferer(): ?string
    {
        $referer = request()->header('referer');

        if ($referer && strpos($referer, config('app.url')) === false) {
            return substr($referer, 0, 500);
        }

        return null;
    }

    /**
     * Получить UTM параметр из URL
     */
    private function getUtmParam(string $param): ?string
    {
        return request()->input($param);
    }

    /**
     * Определить тип устройства
     */
    private function detectDeviceType(): string
    {
        $userAgent = strtolower(request()->userAgent() ?? '');

        if (strpos($userAgent, 'mobile') !== false) {
            return 'mobile';
        }

        if (strpos($userAgent, 'tablet') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Определить браузер
     */
    private function detectBrowser(): string
    {
        $userAgent = strtolower(request()->userAgent() ?? '');

        $browsers = [
            'yandex' => ['yabrowser', 'yandex'],
            'chrome' => ['chrome', 'crios'],
            'safari' => ['safari'],
            'firefox' => ['firefox', 'fxios'],
            'opera' => ['opera', 'opr'],
            'edge' => ['edg', 'edge'],
            'ie' => ['msie', 'trident'],
        ];

        foreach ($browsers as $browser => $patterns) {
            foreach ($patterns as $pattern) {
                if (strpos($userAgent, $pattern) !== false) {
                    return $browser;
                }
            }
        }

        return 'other';
    }

    /**
     * Определить платформу
     */
    private function detectPlatform(): string
    {
        $userAgent = strtolower(request()->userAgent() ?? '');

        if (strpos($userAgent, 'windows') !== false) {
            return 'windows';
        }

        if (strpos($userAgent, 'mac') !== false) {
            return 'macos';
        }

        if (strpos($userAgent, 'linux') !== false) {
            return 'linux';
        }

        if (strpos($userAgent, 'android') !== false) {
            return 'android';
        }

        if (strpos($userAgent, 'iphone') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'ios';
        }

        return 'unknown';
    }

    /**
     * Получить статистику по статье
     */
    public function getStats(int $articleId): array
    {
        $allViews = DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->get();

        $todayViews = $allViews->filter(function ($view) {
            return \Carbon\Carbon::parse($view->viewed_at)->isToday();
        });

        return [
            'total_views' => $allViews->count(),
            'unique_sessions' => $allViews->unique('session_id')->count(),
            'unique_ips' => $allViews->unique('ip_address')->count(),
            'unique_yandex' => $allViews->whereNotNull('yandex_uid')->unique('yandex_uid')->count(),
            'today_views' => $todayViews->count(),
            'today_unique' => $todayViews->unique('session_id')->count(),
            'devices' => $allViews->groupBy('device_type')->map->count(),
            'browsers' => $allViews->groupBy('browser')->map->count(),
            'platforms' => $allViews->groupBy('platform')->map->count(),
            'utm_sources' => $allViews->whereNotNull('utm_source')->groupBy('utm_source')->map->count(),
            'last_viewed' => $allViews->max('viewed_at'),
            'first_viewed' => $allViews->min('viewed_at'),
        ];
    }

    /**
     * Удалить старые методы, которые не нужны
     */

    // Удаляем старые методы получения Яндекс кук на сервере
    // private function getYandexUid() - НЕ НУЖЕН, получаем с фронта
    // private function getYandexClientId() - НЕ НУЖЕН, получаем с фронта

    // Удаляем старые методы проверки уникальности
    // private function checkExistingView() - НЕ НУЖЕН, считаем все просмотры
    // private function getIpAddress() - заменен на getRealIp()
}
