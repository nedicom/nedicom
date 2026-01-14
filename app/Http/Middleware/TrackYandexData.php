<?php

namespace App\Http\Middleware;

use App\Services\DataTracker;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class TrackYandexData
{
    /**
     * Пути, которые НЕ нужно трекать
     */
    protected $excludedPaths = [
        'storage/',
        'images/',
        'css/',
        'js/',
        'fonts/',
        'favicon.',
        'robots.txt',
        'sitemap.xml',
        'api/',
        '_debugbar/',
        'horizon/',
        'telescope/',
        'health',
    ];
    
    /**
     * Расширения файлов, которые НЕ нужно трекать
     */
    protected $excludedExtensions = [
        '.jpg', '.jpeg', '.png', '.gif', '.svg', '.webp', '.ico',
        '.css', '.js', '.map',
        '.woff', '.woff2', '.ttf', '.eot',
        '.pdf', '.doc', '.docx', '.xls', '.xlsx',
        '.mp4', '.mp3', '.avi', '.mov',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Пропускаем статические файлы и API
        if ($this->shouldExclude($request)) {
            return $next($request);
        }
        
        // Проверяем User-Agent
        $userAgent = $request->userAgent();
        $isBot = $this->isBot($userAgent);

        // Только для людей
        if (!$isBot) {
            // Генерируем или получаем visit_uuid
            $visitUuid = $this->getOrCreateVisitUuid($request);
            
            // Сохраняем в request attributes
            $request->attributes->set('visit_uuid', $visitUuid);
            $request->attributes->set('is_bot', false);
            
            // Сохраняем в сессию если доступна
            if (session()->getId()) {
                session()->put('visit_uuid', $visitUuid);
            }
            
            // Сохраняем данные в БД (только для HTML страниц)
            if ($this->isHtmlRequest($request)) {
                DataTracker::saveFromRequest($request, $visitUuid);
            }
            
            // Устанавливаем куку
            if (!$request->hasCookie('visit_uuid')) {
                $response = $next($request);
                return $response->cookie(
                    'visit_uuid',
                    $visitUuid,
                    60 * 24 * 360,
                    '/',
                    null,
                    config('app.env') === 'production',
                    false,
                    false,
                    'lax'
                );
            }
        } else {
            $request->attributes->set('is_bot', true);
        }
        
        return $next($request);
    }
    
    /**
     * Проверяем, нужно ли исключить запрос из трекинга
     */
    private function shouldExclude(Request $request): bool
    {
        $path = $request->path();
        
        // Проверяем пути
        foreach ($this->excludedPaths as $excludedPath) {
            if (str_starts_with($path, $excludedPath) || 
                str_contains($path, $excludedPath)) {
                return true;
            }
        }
        
        // Проверяем расширения файлов
        foreach ($this->excludedExtensions as $extension) {
            if (str_ends_with($path, $extension) || 
                str_ends_with($path, $extension . '?') ||
                str_contains($path, $extension . '?')) {
                return true;
            }
        }
        
        // Исключаем AJAX/API запросы
        if ($request->ajax() || $request->isJson()) {
            return true;
        }
        
        // Исключаем не-GET запросы (POST, PUT, DELETE)
        if (!$request->isMethod('GET')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Проверяем, является ли запрос HTML страницей
     */
    private function isHtmlRequest(Request $request): bool
    {
        $accept = $request->header('Accept', '');
        return str_contains($accept, 'text/html') || 
               str_contains($accept, 'application/xhtml+xml');
    }
    
    /**
     * Определяем бота по User-Agent
     */
    private function isBot(?string $userAgent): bool
    {
        if (empty($userAgent) || strlen($userAgent) < 40) {
            return true;
        }
        
        $botPatterns = [
            'bot', 'crawl', 'spider', 'scrape', 
            'curl', 'wget', 'python', 'java',
            'phantom', 'headless', 'selenium',
            'googlebot', 'bingbot', 'yandexbot',
        ];
        
        $uaLower = strtolower($userAgent);
        
        foreach ($botPatterns as $pattern) {
            if (str_contains($uaLower, $pattern)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Получаем или создаем visit_uuid
     */
    private function getOrCreateVisitUuid(Request $request): string
    {
        // 1. Из куки
        if ($request->hasCookie('visit_uuid')) {
            return $request->cookie('visit_uuid');
        }
        
        // 2. Из сессии
        if (session()->getId() && session()->has('visit_uuid')) {
            $visitUuid = session('visit_uuid');
            if (!$request->hasCookie('visit_uuid')) {
                Cookie::queue('visit_uuid', $visitUuid, 60 * 24 * 360);
            }
            return $visitUuid;
        }
        
        // 3. Генерируем новый
        $visitUuid = Str::uuid()->toString();
        
        return $visitUuid;
    }
}