<?php

namespace App\Services;

use App\Models\YandexTracking;
use App\Models\Article;
use App\Models\Uslugi;
use App\Models\Questions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DataTracker
{
    // Добавляем параметр $visitUuid
    public static function saveFromRequest(Request $request, string $visitUuid): void
    {
        // 1. Проверяем дубликат СТРАНИЦЫ для этого визита
        $existing = YandexTracking::where('visit_uuid', $visitUuid)
            ->where('url', $request->path())
            ->latest('created_at')
            ->first();

        if ($existing) {
            $minutesDiff  = Carbon::parse($existing->created_at)->diffInMinutes(now());

            if ($minutesDiff < 10) { // ← МЕНЬШЕ 10 мин!
                return;
            }
        }

        $userAgent = $request->header('User-Agent');
        if ($userAgent && mb_strlen($userAgent) > 1000) {
            $userAgent = mb_substr($userAgent, 0, 1000) . '... [truncated]';
        }

        $utmParams = self::extractUtmParams($request);

        // 2. Создаём запись
        $trackingData = [
            'visit_uuid'    => $visitUuid, // Одинаковый для всех записей визита
            'url'           => $request->path(),
            'ip'            => $request->ip(),
            'utm_source'    => $utmParams['utm_source'] ?? null,
            'utm_medium'    => $utmParams['utm_medium'] ?? null,
            'utm_campaign'  => $utmParams['utm_campaign'] ?? null,
            'utm_term'      => $utmParams['utm_term'] ?? null,
            'utm_content'   => $utmParams['utm_content'] ?? null,
            'created_at'    => now(),
            'user_agent'    => $userAgent,
        ];

        self::extractContentIds($request, $trackingData);

        // Город если есть в сессии
        if (session()->has('cityid')) {
            $trackingData['city'] = is_numeric(session('cityid'))
                ? (int) session('cityid')
                : null;
        }

        // пользователь если авторизован
        $trackingData['user_id'] = Auth::id();

        YandexTracking::create($trackingData);
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

    //получить тип страницы (статья/юрист) и id
    public  static function extractContentIds(Request $request, array &$trackingData): void
    {
        $routeName = $request->route()->getName();
        $slug = $request->route('url');

        match ($routeName) {
            'articles/url' => $trackingData['article_id'] = $slug ? Article::where('url', $slug)->value('id') : null,
            'questions.url' => $trackingData['question_id'] = $slug ? Questions::where('url', $slug)->value('id') : null,
            'uslugi.url' => $trackingData['interests'] = $slug ? Uslugi::where('url', $slug)->value('id') : null,
            'offer.main' => $trackingData['interests'] = Uslugi::where('url', $request->route('main_usluga'))->value('id'),
            'offer.second' => $trackingData['interests'] = Uslugi::where('url', $request->route('second_usluga'))->value('id'),
            'uslugi.canonical.url' => $trackingData['interests'] = Uslugi::where('url', $request->route('url'))->value('id'),
            'lawyer' => $trackingData['lawyer_id'] = is_numeric($request->route('id'))
                ? (int) $request->route('id')
                : null,
            default => null,
        };
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
