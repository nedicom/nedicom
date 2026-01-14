<?php

namespace App\Http\Middleware;

use App\Services\DataTracker;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class TrackYandexData
{
    public function handle(Request $request, Closure $next)
    {
        // Проверяем User-Agent
        $userAgent = $request->userAgent();
        
        $isBot = empty($userAgent) || 
                 str_contains(strtolower($userAgent), 'bot') ||
                 str_contains(strtolower($userAgent), 'crawl') ||
                 str_contains(strtolower($userAgent), 'spider') ||
                 str_contains(strtolower($userAgent), 'scrape') ||
                 str_contains(strtolower($userAgent), 'compatible') ||
                 preg_match('/android\s+\d+\.\d+\);?\s*applewebkit/i', strtolower($userAgent));

        // Только для людей
        if (!$isBot) {
            // Генерируем visit_uuid
            if (!$request->hasCookie('visit_uuid')) {
                $visitUuid = Str::uuid()->toString();
                Cookie::queue('visit_uuid', $visitUuid, 60 * 24 * 360);
            } else {
                $visitUuid = $request->cookie('visit_uuid');
            }

            session()->put('visit_uuid', $visitUuid);
            DataTracker::saveFromRequest($request, $visitUuid);
        }

        return $next($request);
    }
}