<?php

namespace App\Http\Middleware;

use App\Services\DataTracker;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class TrackYandexData
{
    /**
     * Список user agent ботов для фильтрации трекинга
     */
    protected $botPatterns = [
        'ahrefsbot',
        'semrushbot',
        'dotbot',
        'mj12bot',
        'claudebot',
        'anthropic',
        'gptbot',
        'facebookexternalhit',
        'twitterbot',
        'linkedinbot',
        'telegrambot',
        'gigaexplorator',
        'scrapy',
        'httrack',
        'uptimerobot',
        'pingdom',
        'moz.com',
        'ccbot',
        'coccoc',
        'seznam',
        'baiduspider',
        'sogou',
        'exabot',
        'gigablast',
        'googlebot',
        'bingbot',
        'yandexbot',
        'yandexmetrika',
        'mail.ru_bot',
        'aport',
        'yahoo! slurp',
        'duckduckbot',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Проверяем, не бот ли это (только для трекинга)
        $isBot = $this->isBot($request);

        if (!$isBot) {
            // Генерируем visit_uuid для новой Cookie (даже для ботов)
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

    protected function isBot(Request $request): bool
    {
        $userAgent = $request->userAgent();

        if (empty($userAgent)) {
            return true;
        }

        $userAgent = strtolower($userAgent);

        // Проверяем блокируемых ботов
        foreach ($this->botPatterns as $bot) {
            if (str_contains($userAgent, strtolower($bot))) {
                return true;
            }
        }

        return false;
    }
}
