<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class ArticleViewService
{
    /**
     * Засчитать просмотр статьи
     */
    public function countView(int $articleId): bool
    {
        $sessionId = $this->getSessionId();
        
        // Проверяем, не смотрел ли пользователь статью в последние 24 часа
        // По комбинации: сессия ИЛИ IP+UserAgent ИЛИ Яндекс UID
        $alreadyViewed = $this->checkExistingView($articleId, $sessionId);
        
        if ($alreadyViewed) {
            return false;
        }
        
        // Собираем данные для записи
        $data = [
            'article_id' => $articleId,
            'session_id' => $sessionId,
            'ip_address' => $this->getIpAddress(),
            'user_agent' => $this->getUserAgent(),
            'yandex_uid' => $this->getYandexUid(),
            'yandex_client_id' => $this->getYandexClientId(),
            'device_type' => $this->detectDeviceType(),
            'browser' => $this->detectBrowser(),
            'platform' => $this->detectPlatform(),
            'referer' => $this->getReferer(),
            'utm_source' => $this->getUtmParam('utm_source'),
            'utm_medium' => $this->getUtmParam('utm_medium'),
            'utm_campaign' => $this->getUtmParam('utm_campaign'),
            'utm_content' => $this->getUtmParam('utm_content'),
            'utm_term' => $this->getUtmParam('utm_term'),
            'viewed_at' => now(),
        ];
        
        DB::connection('pgsql_stats')
            ->table('article_views')
            ->insert($data);
            
        return true;
    }
    
    /**
     * Проверить существующий просмотр
     */
    private function checkExistingView(int $articleId, string $sessionId): bool
    {
        $query = DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->where('viewed_at', '>=', now()->subHours(24));
        
        // Проверяем по разным идентификаторам
        return $query->where(function($q) use ($sessionId) {
            $q->where('session_id', $sessionId)
              ->orWhere(function($q2) {
                  $q2->where('ip_address', $this->getIpAddress())
                     ->where('user_agent', $this->getUserAgent());
              })
              ->orWhere('yandex_uid', $this->getYandexUid());
        })->exists();
    }
    
    /**
     * Получить IP адрес
     */
    private function getIpAddress(): string
    {
        $ip = request()->ip();
        
        // Для локальной разработки можно добавить тестовые IP
        if (app()->environment('local')) {
            // Генерируем фиктивный IP на основе сессии для тестов
            $sessionId = session()->getId();
            return '127.0.0.' . (crc32($sessionId) % 254 + 1);
        }
        
        return $ip ?: 'unknown';
    }
    
    /**
     * Получить User Agent
     */
    private function getUserAgent(): ?string
    {
        $ua = request()->userAgent();
        
        // Ограничиваем длину, чтобы не перегружать БД
        return $ua ? substr($ua, 0, 500) : null;
    }
    
    /**
     * Получить Яндекс UID из куки _ym_uid
     */
    private function getYandexUid(): ?string
    {
        // Яндекс хранит UID в куке _ym_uid
        $ymUid = request()->cookie('_ym_uid');
        
        if ($ymUid) {
            return $ymUid;
        }
        
        // Также проверяем _ym_d (дата первой метрики)
        $ymD = request()->cookie('_ym_d');
        if ($ymD) {
            return 'ym_d_' . $ymD;
        }
        
        return null;
    }
    
    /**
     * Получить Яндекс Client ID
     */
    private function getYandexClientId(): ?string
    {
        // Яндекс Метрика Client ID (ycid)
        $ycid = request()->cookie('ycid');
        if ($ycid) {
            return $ycid;
        }
        
        // Яндекс ID
        $yandexuid = request()->cookie('yandexuid');
        if ($yandexuid) {
            return $yandexuid;
        }
        
        return null;
    }
    
    /**
     * Получить реферер
     */
    private function getReferer(): ?string
    {
        $referer = request()->header('referer');
        
        // Не сохраняем ссылки с нашего же сайта
        if ($referer && strpos($referer, config('app.url')) === false) {
            return substr($referer, 0, 500);
        }
        
        return null;
    }
    
    /**
     * Получить UTM параметр
     */
    private function getUtmParam(string $param): ?string
    {
        $value = request()->input($param);
        
        return $value ? substr($value, 0, 255) : null;
    }
    
    /**
     * Обновленный метод определения устройства
     */
    private function detectDeviceType(): string
    {
        $userAgent = strtolower($this->getUserAgent() ?? '');
        
        if (strpos($userAgent, 'mobile') !== false) {
            return 'mobile';
        }
        
        if (
            strpos($userAgent, 'tablet') !== false ||
            strpos($userAgent, 'ipad') !== false
        ) {
            return 'tablet';
        }
        
        return 'desktop';
    }
    
    /**
     * Определить браузер
     */
    private function detectBrowser(): string
    {
        $userAgent = $this->getUserAgent() ?? '';
        $userAgent = strtolower($userAgent);
        
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
     * Определить платформу/ОС
     */
    private function detectPlatform(): string
    {
        $userAgent = $this->getUserAgent() ?? '';
        $userAgent = strtolower($userAgent);
        
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
     * Улучшенный метод получения ID сессии
     */
    private function getSessionId(): string
    {
        // Пробуем получить из куки
        $cookieName = 'article_sid';
        $sessionId = request()->cookie($cookieName);
        
        if ($sessionId) {
            return $sessionId;
        }
        
        // Генерируем новый ID
        $sessionId = $this->generateSessionId();
        
        // Сохраняем в куки
        Cookie::queue(
            $cookieName,
            $sessionId,
            30 * 24 * 60, // 30 дней
            '/',
            null, // текущий домен
            null, // secure
            true   // httpOnly
        );
        
        return $sessionId;
    }
    
    /**
     * Генерация уникального ID сессии
     */
    private function generateSessionId(): string
    {
        $parts = [
            'ip' => $this->getIpAddress(),
            'ua' => substr(md5($this->getUserAgent() ?? ''), 0, 8),
            'ym' => $this->getYandexUid() ? substr(md5($this->getYandexUid()), 0, 8) : 'no_yandex',
            'time' => time(),
            'rand' => bin2hex(random_bytes(8)),
        ];
        
        return 'sid_' . md5(implode('|', $parts));
    }
    
    /**
     * Получить статистику (обновленная версия)
     */
    public function getStats(int $articleId): array
    {
        $total = DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->count();
            
        // Уникальные просмотры по разным критериям
        $uniqueSessions = DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->distinct('session_id')
            ->count('session_id');
            
        $uniqueIPs = DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->distinct('ip_address')
            ->count('ip_address');
            
        $uniqueYandex = DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->whereNotNull('yandex_uid')
            ->distinct('yandex_uid')
            ->count('yandex_uid');
            
        $today = DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->whereDate('viewed_at', today())
            ->count();
            
        // География (первые 3 октета IP для анонимности)
        $geoData = DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->select(
                DB::raw("CASE 
                    WHEN ip_address LIKE '127.%' THEN 'localhost'
                    WHEN ip_address LIKE '10.%' THEN 'локальная сеть'
                    WHEN ip_address LIKE '192.168.%' THEN 'локальная сеть'
                    ELSE substring(ip_address from '^([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')
                END as ip_prefix"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('ip_prefix')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
            
        return [
            'total_views' => $total,
            'unique_by_session' => $uniqueSessions,
            'unique_by_ip' => $uniqueIPs,
            'unique_by_yandex' => $uniqueYandex,
            'today_views' => $today,
            'devices' => $this->getDeviceStats($articleId),
            'browsers' => $this->getBrowserStats($articleId),
            'platforms' => $this->getPlatformStats($articleId),
            'utm_sources' => $this->getUtmStats($articleId),
            'geo_prefixes' => $geoData,
            'last_viewed' => DB::connection('pgsql_stats')
                ->table('article_views')
                ->where('article_id', $articleId)
                ->latest('viewed_at')
                ->value('viewed_at'),
        ];
    }
    
    private function getDeviceStats(int $articleId)
    {
        return DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->groupBy('device_type')
            ->orderBy('count', 'desc')
            ->get();
    }
    
    private function getBrowserStats(int $articleId)
    {
        return DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->select('browser', DB::raw('COUNT(*) as count'))
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->get();
    }
    
    private function getPlatformStats(int $articleId)
    {
        return DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->select('platform', DB::raw('COUNT(*) as count'))
            ->groupBy('platform')
            ->orderBy('count', 'desc')
            ->get();
    }
    
    private function getUtmStats(int $articleId)
    {
        return DB::connection('pgsql_stats')
            ->table('article_views')
            ->where('article_id', $articleId)
            ->whereNotNull('utm_source')
            ->select('utm_source', 'utm_medium', 'utm_campaign', DB::raw('COUNT(*) as count'))
            ->groupBy('utm_source', 'utm_medium', 'utm_campaign')
            ->orderBy('count', 'desc')
            ->get();
    }
}