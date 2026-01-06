<?php

namespace App\Services;

use App\Models\YandexTracking;
use Illuminate\Http\Request;

class DataTracker
{
    // Добавляем параметр $visitUuid
    public static function saveFromRequest(Request $request, string $visitUuid): void
    {
        $utmParams = self::extractUtmParams($request);

        // 1. Проверяем дубликат СТРАНИЦЫ для этого визита
        $existing = YandexTracking::where('visit_uuid', $visitUuid)
            ->where('url', $request->path()) // ← Проверяем URL страницы
            ->where('utm_source', $utmParams['utm_source'] ?? null)
            ->where('utm_medium', $utmParams['utm_medium'] ?? null)
            ->where('utm_campaign', $utmParams['utm_campaign'] ?? null)
            ->where('utm_term', $utmParams['utm_term'] ?? null)
            ->where('utm_content', $utmParams['utm_content'] ?? null)
            ->first();

        if ($existing) {
            return; // Эта страница с этими UTM уже записана для этого визита
        }

        // 2. Создаём запись (новая страница или новые UTM)
        YandexTracking::create([
            'visit_uuid'    => $visitUuid, // Одинаковый для всех записей визита
            '_ym_uid'       => $request->cookie('_ym_uid'),
            'url'           => $request->path(),
            'ip'            => $request->ip(),
            'utm_source'    => $utmParams['utm_source'] ?? null,
            'utm_medium'    => $utmParams['utm_medium'] ?? null,
            'utm_campaign'  => $utmParams['utm_campaign'] ?? null,
            'utm_term'      => $utmParams['utm_term'] ?? null,
            'utm_content'   => $utmParams['utm_content'] ?? null,
            'created_at'    => now(),
        ]);
    }

    private static function extractUtmParams(Request $request): array
    {
        $keys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
        $params = [];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                $params[$key] = $request->get($key);
            }
        }

        return $params;
    }

    /**
     * Для обновления конверсий (клик на телефон, форма и т.д.)
     */
    public static function updateConversion(string $visitUuid, array $data): void
    {
        // Обновляем первую запись визита или все?
        YandexTracking::where('visit_uuid', $visitUuid)
            ->orderBy('created_at', 'asc')
            ->limit(1)
            ->update($data);
    }
}
