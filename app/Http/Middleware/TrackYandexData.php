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
     * Обработка входящего запроса.
     * Сохраняет метрики и генерирует visit_uuid для сессии.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // Исключаем определённые маршруты (опционально)
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        // Генерируем visit_uuid для новой Cookie
        if (!$request->hasCookie('visit_uuid')) {
            $visitUuid = Str::uuid()->toString();
            Cookie::queue('visit_uuid', $visitUuid, 60 * 24 * 360); // 30 дней
        } else {
            $visitUuid = $request->cookie('visit_uuid');
        }

        // кладем то что было в куки в сессию
        session()->put('visit_uuid', $visitUuid);

        // Передаём запрос и visit_uuid в сервис
        DataTracker::saveFromRequest($request, $visitUuid);

        return $next($request);
    }

    /**
     * Определяем, нужно ли пропустить обработку.
     */
    private function shouldSkip(Request $request): bool
    {
        // Исключаем API-маршруты, админку, статические файлы
        if (
            $request->is('api/*') ||
            $request->is('admin/*') ||
            $request->is('storage/*') ||
            $request->is('health-check')
        ) {
            return true;
        }

        return false;
    }
}
