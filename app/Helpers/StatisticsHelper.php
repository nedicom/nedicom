<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticsHelper
{
    public static function generateLast7DaysViews($item, String $itemType): array
    {
        // Проверяем входные данные
        if (!$item || !isset($item->id) || !isset($item->url)) {
            Log::error('StatisticsHelper: не передан объект статьи или отсутствуют обязательные поля');
            return self::getDefaultData();
        }

        $itemId = $item->id;
        $days = 7;

        // Получаем реальные данные из базы статистики
        $realData = self::getRealViewsData($itemId, $itemType, $days);

        // Если есть реальные данные - используем их, иначе фейковые
        if (!empty($realData['viewsData'])) {
            return $realData;
        }

        Log::info('Реальных данных не найдено, используем фейковые', [
            'item_id' => $item->id
        ]);

        return self::generateFakeData($item, $days);
    }

    /**
     * Получение реальных данных из базы статистики
     */
    private static function getRealViewsData(int $itemId, string $itemType, int $days): array
    { 
        try {
            $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            // Запрос к базе статистики
            $query  = DB::connection('pgsql_stats')
                ->table('yandex_tracking')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as views'),
                    DB::raw('SUM(CASE WHEN is_engaged = true THEN 1 ELSE 0 END) as engaged'),
                    DB::raw('SUM(CASE WHEN phone_click_at IS NOT NULL THEN 1 ELSE 0 END) as conversions')
                )
                ->whereBetween('created_at', [$startDate, $endDate]);

            if ($itemType === 'article') {
                $query->where('article_id', $itemId);
            } elseif ($itemType === 'usluga') {
                $query->where('interests', $itemId);
            } else {
                Log::error('Неизвестный тип контента', ['type' => $itemType]);
            }

            $views = $query->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date', 'ASC')
                ->get()
                ->keyBy('date');

            // Если данных нет, возвращаем пустой массив
            if ($views->isEmpty()) {
                return [
                    'viewsData' => [],
                    'totalViews' => 0,
                    'period' => $days,
                    'is_real_data' => false
                ];
            }

            // Формируем данные для всех дней периода
            $statistics = [];
            $totalViews = 0;
            $totalEngaged = 0;
            $totalConversions = 0;

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateString = $date->format('Y-m-d');

                $dayData = $views->get($dateString);

                $dayViews = $dayData ? (int) $dayData->views : 0;
                $dayEngaged = $dayData ? (int) $dayData->engaged : 0;
                $dayConversions = $dayData ? (int) $dayData->conversions : 0;

                $statistics[] = [
                    'date' => $dateString,
                    'formattedDate' => $date->locale('ru')->translatedFormat('j M'),
                    'dayOfWeek' => $date->locale('ru')->translatedFormat('D'),
                    'fullDate' => $date->format('d.m.Y'),
                    'views' => $dayViews,
                    'engaged' => $dayEngaged,
                    'conversions' => $dayConversions,
                    'dayOfWeekNumber' => $date->dayOfWeek,
                    'engagement_rate' => $dayViews > 0 ? round(($dayEngaged / $dayViews) * 100, 1) : 0,
                    'conversion_rate' => $dayViews > 0 ? round(($dayConversions / $dayViews) * 100, 1) : 0,
                ];

                $totalViews += $dayViews;
                $totalEngaged += $dayEngaged;
                $totalConversions += $dayConversions;
            }

            $result = [
                'viewsData' => $statistics,
                'totalViews' => $totalViews,
                'totalEngaged' => $totalEngaged,
                'totalConversions' => $totalConversions,
                'period' => $days,
                'is_real_data' => true,
                'engagement_rate' => $totalViews > 0 ? round(($totalEngaged / $totalViews) * 100, 1) : 0,
                'conversion_rate' => $totalViews > 0 ? round(($totalConversions / $totalViews) * 100, 1) : 0,
                'data_source' => 'pgsql_stats.yandex_tracking',
            ];

            Log::debug('Сформированы реальные данные:', [
                'total_views' => $totalViews,
                'days_with_data' => count(array_filter($statistics, fn($d) => $d['views'] > 0))
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Ошибка при получении реальных данных статистики:', [
                'error' => $e->getMessage(),
            ]);

            return [
                'viewsData' => [],
                'totalViews' => 0,
                'period' => $days,
                'is_real_data' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Генерация фейковых данных (фолбэк)
     */
    private static function generateFakeData($article, int $days): array
    {
        $articleId = (int) $article->id;
        $seed = crc32($articleId . date('Y-m-d'));
        mt_srand($seed);

        $statistics = [];
        $totalViews = 0;
        $totalEngaged = 0;
        $totalConversions = 0;

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayOfWeek = $date->dayOfWeek;

            // Генерация просмотров
            $views = self::generateDailyViews($articleId, $dayOfWeek);

            // Вовлеченность (20-40% от просмотров)
            $engaged = (int) round($views * (mt_rand(20, 40) / 100));

            // Конверсии (1-3% от просмотров)
            $conversions = (int) round($views * (mt_rand(1, 3) / 100));

            $statistics[] = [
                'date' => $date->format('Y-m-d'),
                'formattedDate' => $date->locale('ru')->translatedFormat('j M'),
                'dayOfWeek' => $date->locale('ru')->translatedFormat('D'),
                'fullDate' => $date->format('d.m.Y'),
                'views' => $views,
                'engaged' => $engaged,
                'conversions' => $conversions,
                'dayOfWeekNumber' => $dayOfWeek,
                'engagement_rate' => round(($engaged / $views) * 100, 1),
                'conversion_rate' => round(($conversions / $views) * 100, 1),
            ];

            $totalViews += $views;
            $totalEngaged += $engaged;
            $totalConversions += $conversions;
        }

        return [
            'viewsData' => $statistics,
            'totalViews' => $totalViews,
            'totalEngaged' => $totalEngaged,
            'totalConversions' => $totalConversions,
            'period' => $days,
            'is_real_data' => false,
            'engagement_rate' => round(($totalEngaged / $totalViews) * 100, 1),
            'conversion_rate' => round(($totalConversions / $totalViews) * 100, 1),
            'data_source' => 'fake_data',
        ];
    }

    private static function generateDailyViews(int $articleId, int $dayOfWeek): int
    {
        $base = match ($dayOfWeek) {
            0 => mt_rand(30, 100),   // Воскресенье
            6 => mt_rand(40, 120),   // Суббота
            5 => mt_rand(180, 320),  // Пятница
            1 => mt_rand(120, 220),  // Понедельник
            default => mt_rand(150, 280),
        };

        $articleFactor = ($articleId % 10 + 1) * 20;
        $views = $base + $articleFactor;

        return round($views, -1);
    }

    private static function getDefaultData(): array
    {
        $days = 7;
        $statistics = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $views = mt_rand(50, 200);
            $engaged = (int) round($views * 0.3);
            $conversions = (int) round($views * 0.03);

            $statistics[] = [
                'date' => $date->format('Y-m-d'),
                'formattedDate' => $date->locale('ru')->translatedFormat('j M'),
                'dayOfWeek' => $date->locale('ru')->translatedFormat('D'),
                'fullDate' => $date->format('d.m.Y'),
                'views' => $views,
                'engaged' => $engaged,
                'conversions' => $conversions,
                'dayOfWeekNumber' => $date->dayOfWeek,
                'engagement_rate' => round(($engaged / $views) * 100, 1),
                'conversion_rate' => round(($conversions / $views) * 100, 1),
            ];
        }

        $totalViews = array_sum(array_column($statistics, 'views'));
        $totalEngaged = array_sum(array_column($statistics, 'engaged'));
        $totalConversions = array_sum(array_column($statistics, 'conversions'));

        return [
            'viewsData' => $statistics,
            'totalViews' => $totalViews,
            'totalEngaged' => $totalEngaged,
            'totalConversions' => $totalConversions,
            'period' => $days,
            'is_real_data' => false,
            'is_fallback' => true,
        ];
    }

    /**
     * Дополнительный метод для получения расширенной статистики
     */
    public static function getExtendedStatistics(string $articleUrl, int $days = 30): array
    {
        try {
            $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $searchUrl = trim($articleUrl, '/');

            $stats = DB::connection('pgsql_stats')
                ->table('yandex_tracking')
                ->select(
                    DB::raw("DATE_TRUNC('day', created_at) as date"),
                    DB::raw('COUNT(*) as total_views'),
                    DB::raw('COUNT(DISTINCT ip_address) as unique_visitors'),
                    DB::raw('SUM(CASE WHEN is_engaged = true THEN 1 ELSE 0 END) as engaged_sessions'),
                    DB::raw('SUM(CASE WHEN hone_click_at IS NOT NULL THEN 1 ELSE 0 END) as conversions'),
                    DB::raw('AVG(CASE WHEN time_on_page IS NOT NULL THEN time_on_page END) as avg_time_on_page'),
                    DB::raw('PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY time_on_page) as median_time_on_page')
                )
                ->where(function ($query) use ($searchUrl) {
                    $query->where('path', 'like', '%' . $searchUrl . '%')
                        ->orWhere('path', 'like', '%' . $searchUrl . '/%');
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw("DATE_TRUNC('day', created_at)"))
                ->orderBy('date', 'ASC')
                ->get();

            return [
                'success' => true,
                'data' => $stats,
                'period' => $days,
                'total_records' => $stats->count()
            ];
        } catch (\Exception $e) {
            Log::error('Ошибка при получении расширенной статистики:', [
                'error' => $e->getMessage(),
                'article_url' => $articleUrl
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'data' => []
            ];
        }
    }
}
