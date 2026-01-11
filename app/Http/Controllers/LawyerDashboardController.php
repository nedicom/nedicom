<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Uslugi;
use App\Models\cities;
use Carbon\Carbon;

class LawyerDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        // Получаем параметры из URL
        $activeTab = $request->query('tab', 'articles');
        $search = $request->query('q', '');
        $sortField = $request->query('sort', 'date');
        $sortOrder = $request->query('order', 'desc');
        $page = (int) $request->query('page', 1);
        $statusFilter = $request->query('status', '');
        
        // Валидация параметров
        $validTabs = ['articles', 'ads', 'stats'];
        $activeTab = in_array($activeTab, $validTabs) ? $activeTab : 'articles';
        
        $validSortFields = ['date', 'views', 'title', 'conversions'];
        $sortField = in_array($sortField, $validSortFields) ? $sortField : 'date';
        
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';
        $page = max(1, $page);

        if (!$user) {
            return $this->renderDemoData($activeTab, $search, $sortField, $sortOrder, $page, $statusFilter);
        }

        return $this->renderUserData($user, $activeTab, $search, $sortField, $sortOrder, $page, $statusFilter);
    }
    
    private function renderDemoData($activeTab, $search, $sortField, $sortOrder, $page, $statusFilter)
    {
        $perPage = 10;
        
        $demoStats = [
            'totalViews' => 1248,
            'todayViews' => 42,
            'totalMaterials' => 18,
            'conversionRate' => 3.2,
            'last7DaysViews' => [65, 59, 80, 81, 56, 55, 40]
        ];

        $demoArticles = [
            [
                'id' => 1,
                'title' => 'Как составить исковое заявление',
                'views' => 156,
                'url' => 'kak-sostavit-iskovoe-zayavlenie',
                'date' => '2024-01-15',
                'status' => 'active',
                'category' => 'Гражданское право',
                'comments_count' => 12,
                'conversions' => [
                    'total_visits' => 45,
                    'engaged' => 12,
                    'phone_clicks' => 8,
                    'rate' => 66.7
                ]
            ],
            [
                'id' => 3,
                'title' => 'Налоговые вычеты для ИП в 2026 году',
                'views' => 203,
                'url' => 'nalogovye-vychety-dlya-ip-v-2026-godu',
                'date' => '2026-01-05',
                'status' => 'active',
                'category' => 'Налоговое право',
                'comments_count' => 8,
                'conversions' => [
                    'total_visits' => 78,
                    'engaged' => 23,
                    'phone_clicks' => 15,
                    'rate' => 65.2
                ]
            ],
        ];

        $demoUslugi = [
            [
                'id' => 2,
                'title' => 'Услуги по банкротству физических лиц',
                'views' => 89,
                'url' => 'uslugi-po-bankrotstvu-fizicheskih-lic',
                'date' => '2026-01-10',
                'status' => 'moderation',
                'price' => 'от 15 000 ₽',
                'city' => 'Москва',
                'conversions' => [
                    'total_visits' => 67,
                    'engaged' => 18,
                    'phone_clicks' => 12,
                    'rate' => 66.7
                ]
            ],
            [
                'id' => 4,
                'title' => 'Юридическое сопровождение бизнеса',
                'views' => 67,
                'url' => 'yuridicheskoe-soprovozhdenie-biznesa',
                'date' => '2026-01-02',
                'status' => 'active',
                'price' => 'от 30 000 ₽',
                'city' => 'Санкт-Петербург',
                'conversions' => [
                    'total_visits' => 42,
                    'engaged' => 11,
                    'phone_clicks' => 9,
                    'rate' => 81.8
                ]
            ],
        ];

        // Фильтруем демо-данные
        $filteredDemoArticles = $this->filterMaterials($demoArticles, $search, $statusFilter);
        $filteredDemoUslugi = $this->filterMaterials($demoUslugi, $search, $statusFilter);
        
        // Сортируем демо-данные
        $sortedDemoArticles = $this->sortMaterials($filteredDemoArticles, $sortField, $sortOrder);
        $sortedDemoUslugi = $this->sortMaterials($filteredDemoUslugi, $sortField, $sortOrder);
        
        // Применяем пагинацию
        $articlesTotalCount = count($sortedDemoArticles);
        $uslugiTotalCount = count($sortedDemoUslugi);
        
        $articlesData = array_slice($sortedDemoArticles, ($page - 1) * $perPage, $perPage);
        $uslugiData = array_slice($sortedDemoUslugi, ($page - 1) * $perPage, $perPage);
        
        // Рассчитываем общую статистику для демо
        $articlesTotalVisits = array_sum(array_column($demoArticles, 'conversions.total_visits'));
        $articlesTotalEngaged = array_sum(array_column($demoArticles, 'conversions.engaged'));
        $articlesTotalPhoneClicks = array_sum(array_column($demoArticles, 'conversions.phone_clicks'));
        
        $articlesConversionRate = $articlesTotalEngaged > 0
            ? round(($articlesTotalPhoneClicks / $articlesTotalEngaged) * 100, 2)
            : 0;
        
        $uslugiTotalVisits = array_sum(array_column($demoUslugi, 'conversions.total_visits'));
        $uslugiTotalEngaged = array_sum(array_column($demoUslugi, 'conversions.engaged'));
        $uslugiTotalPhoneClicks = array_sum(array_column($demoUslugi, 'conversions.phone_clicks'));
        
        $uslugiConversionRate = $uslugiTotalEngaged > 0
            ? round(($uslugiTotalPhoneClicks / $uslugiTotalEngaged) * 100, 2)
            : 0;

        // Для демо используем те же данные что и для userData, но с флагом isDemo
        $demoUserData = [
            'stats' => $demoStats,
            'isDemo' => true,
            'articles' => [
                'data' => $articlesData,
                'count' => $articlesTotalCount,
                'totalViews' => array_sum(array_column($demoArticles, 'views')),
                'last7DaysViews' => [65, 59, 80, 81, 56, 55, 40],
                'conversions' => [
                    'total_visits' => $articlesTotalVisits,
                    'engaged' => $articlesTotalEngaged,
                    'phone_clicks' => $articlesTotalPhoneClicks,
                    'rate' => $articlesConversionRate,
                ],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $articlesTotalCount,
                    'total_pages' => ceil($articlesTotalCount / $perPage)
                ]
            ],
            'uslugi' => [
                'data' => $uslugiData,
                'count' => $uslugiTotalCount,
                'totalViews' => array_sum(array_column($demoUslugi, 'views')),
                'last7DaysViews' => [20, 25, 30, 35, 40, 45, 50],
                'conversions' => [
                    'total_visits' => $uslugiTotalVisits,
                    'engaged' => $uslugiTotalEngaged,
                    'phone_clicks' => $uslugiTotalPhoneClicks,
                    'rate' => $uslugiConversionRate,
                ],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $uslugiTotalCount,
                    'total_pages' => ceil($uslugiTotalCount / $perPage)
                ]
            ],
            'filters' => [
                'activeTab' => $activeTab,
                'search' => $search,
                'sortField' => $sortField,
                'sortOrder' => $sortOrder,
                'page' => $page,
                'status' => $statusFilter,
                'perPage' => $perPage
            ]
        ];

        return Inertia::render('LawyerDashboard', [
            'auth' => null,
            'demoData' => $demoUserData, // Для обратной совместимости
            'userData' => $demoUserData  // Основные данные в том же формате
        ]);
    }
    
    private function renderUserData($user, $activeTab, $search, $sortField, $sortOrder, $page, $statusFilter)
    {
        $perPage = 10;
        
        // Получаем статистику посещений из yandex_tracking
        $articlesTrackingData = $this->getArticlesTrackingData($user->id);
        $uslugiTrackingData = $this->getUslugiTrackingData($user->id);
        
        // Получаем все услуги для маппинга (для статей)
        $allUslugi = Uslugi::where('user_id', $user->id)
            ->pluck('usl_name', 'id')
            ->toArray();
        
        // Получаем все города для маппинга
        $allCities = cities::pluck('title', 'id')->toArray();
        
        // Сначала получаем все данные для сортировки по конверсии
        $articlesQuery = Article::where('userid', $user->id);
        
        if (!empty($search)) {
            $articlesQuery->where('header', 'like', '%' . $search . '%');
        }
        
        $articlesAll = $articlesQuery
            ->select(['id', 'header', 'counter', 'created_at', 'is_published', 'usluga_id', 'url', 'comments'])
            ->get()
            ->map(function ($article) use ($articlesTrackingData, $allUslugi) {
                // Получаем данные отслеживания для этой статьи
                $articleTracking = $articlesTrackingData->get($article->id, [
                    'total_visits' => 0,
                    'engaged' => 0,
                    'phone_clicks' => 0
                ]);
                
                $conversionRate = $articleTracking['engaged'] > 0
                    ? round(($articleTracking['phone_clicks'] / $articleTracking['engaged']) * 100, 2)
                    : 0;
                
                // Получаем название услуги
                $category = 'Без категории';
                if ($article->usluga_id && isset($allUslugi[$article->usluga_id])) {
                    $category = $allUslugi[$article->usluga_id];
                }

                return [
                    'id' => $article->id,
                    'title' => $article->header,
                    'views' => $article->counter,
                    'url' => $article->url,
                    'date' => $article->created_at->format('Y-m-d'),
                    'status' => $article->is_published ? 'inactive' : 'active',
                    'category' => $category,
                    'comments_count' => $article->comments ?? 0,
                    'conversions' => [
                        'total_visits' => $articleTracking['total_visits'],
                        'engaged' => $articleTracking['engaged'],
                        'phone_clicks' => $articleTracking['phone_clicks'],
                        'rate' => $conversionRate,
                    ]
                ];
            });

        // Фильтруем по статусу (если указан)
        if (!empty($statusFilter)) {
            $articlesAll = $articlesAll->filter(function ($article) use ($statusFilter) {
                return $article['status'] === $statusFilter;
            });
        }
        
        // Сортируем статьи
        $articlesAll = $this->sortCollection($articlesAll, $sortField, $sortOrder);
        
        $articlesTotalCount = $articlesAll->count();
        
        // Применяем пагинацию к статьям
        $articlesData = $articlesAll->slice(($page - 1) * $perPage, $perPage)->values();

        // Получаем данные услуг
        $uslugiQuery = Uslugi::where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('is_main', '!=', 1)
                    ->orWhereNull('is_main');
            })
            ->where(function ($query) {
                $query->where('is_second', '!=', 1)
                    ->orWhereNull('is_second');
            });
        
        if (!empty($search)) {
            $uslugiQuery->where('usl_name', 'like', '%' . $search . '%');
        }
        
        $uslugiAll = $uslugiQuery
            ->select(['id', 'usl_name as title', 'counter as views', 'created_at', 'is_feed', 'price', 'sity', 'url'])
            ->get()
            ->map(function ($usluga) use ($uslugiTrackingData, $allCities) {
                // Получаем данные отслеживания для этой услуги
                $uslugaTracking = $uslugiTrackingData->get($usluga->id, [
                    'total_visits' => 0,
                    'engaged' => 0,
                    'phone_clicks' => 0
                ]);
                
                $conversionRate = $uslugaTracking['engaged'] > 0
                    ? round(($uslugaTracking['phone_clicks'] / $uslugaTracking['engaged']) * 100, 2)
                    : 0;
                
                // Получаем название города
                $cityName = 'не указан';
                if ($usluga->sity && isset($allCities[$usluga->sity])) {
                    $cityName = $allCities[$usluga->sity];
                }

                return [
                    'id' => $usluga->id,
                    'title' => $usluga->title,
                    'views' => $usluga->views,
                    'url' => $usluga->url,                    
                    'date' => $usluga->created_at->format('Y-m-d'),
                    'status' => $usluga->is_feed ? 'active' : 'inactive',
                    'price' => $usluga->price ?? 'не указана',
                    'city' => $cityName,
                    'conversions' => [
                        'total_visits' => $uslugaTracking['total_visits'],
                        'engaged' => $uslugaTracking['engaged'],
                        'phone_clicks' => $uslugaTracking['phone_clicks'],
                        'rate' => $conversionRate,
                    ]
                ];
            });

        // Фильтруем по статусу (если указан)
        if (!empty($statusFilter)) {
            $uslugiAll = $uslugiAll->filter(function ($usluga) use ($statusFilter) {
                return $usluga['status'] === $statusFilter;
            });
        }
        
        // Сортируем услуги
        $uslugiAll = $this->sortCollection($uslugiAll, $sortField, $sortOrder);
        
        $uslugiTotalCount = $uslugiAll->count();
        
        // Применяем пагинацию к услугам
        $uslugiData = $uslugiAll->slice(($page - 1) * $perPage, $perPage)->values();

        // Общая статистика для статей из yandex_tracking
        $articlesTotalVisits = $articlesTrackingData->sum(function ($item) {
            return $item['total_visits'];
        });
        $articlesTotalEngaged = $articlesTrackingData->sum(function ($item) {
            return $item['engaged'];
        });
        $articlesTotalPhoneClicks = $articlesTrackingData->sum(function ($item) {
            return $item['phone_clicks'];
        });
        
        $articlesConversionRate = $articlesTotalEngaged > 0
            ? round(($articlesTotalPhoneClicks / $articlesTotalEngaged) * 100, 2)
            : 0;

        // Общая статистика для услуг из yandex_tracking
        $uslugiTotalVisits = $uslugiTrackingData->sum(function ($item) {
            return $item['total_visits'];
        });
        $uslugiTotalEngaged = $uslugiTrackingData->sum(function ($item) {
            return $item['engaged'];
        });
        $uslugiTotalPhoneClicks = $uslugiTrackingData->sum(function ($item) {
            return $item['phone_clicks'];
        });
        
        $uslugiConversionRate = $uslugiTotalEngaged > 0
            ? round(($uslugiTotalPhoneClicks / $uslugiTotalEngaged) * 100, 2)
            : 0;

        // Общая статистика просмотров (из counter в таблицах)
        $articlesTotalViews = Article::where('userid', $user->id)->sum('counter');
        $uslugiTotalViews = Uslugi::where('user_id', $user->id)->sum('counter');
        
        $totalViews = $articlesTotalViews + $uslugiTotalViews;
        $totalMaterials = $articlesTotalCount + $uslugiTotalCount;

        // Просмотры за сегодня (из counter)
        $todayArticlesViews = Article::where('userid', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->sum('counter');
        $todayUslugiViews = Uslugi::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->sum('counter');
        $todayViews = $todayArticlesViews + $todayUslugiViews;

        // Статистика за последние 7 дней для графика (из counter)
        $last7Days = collect();
        $articlesLast7Days = collect();
        $uslugiLast7Days = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $dayArticlesViews = Article::where('userid', $user->id)
                ->whereDate('created_at', $date)
                ->sum('counter');
            $dayUslugiViews = Uslugi::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->sum('counter');

            $articlesLast7Days->push($dayArticlesViews);
            $uslugiLast7Days->push($dayUslugiViews);
            $last7Days->push($dayArticlesViews + $dayUslugiViews);
        }

        // Общая статистика конверсий
        $totalVisits = $articlesTotalVisits + $uslugiTotalVisits;
        $totalEngaged = $articlesTotalEngaged + $uslugiTotalEngaged;
        $totalPhoneClicks = $articlesTotalPhoneClicks + $uslugiTotalPhoneClicks;
        
        $overallConversionRate = $totalEngaged > 0
            ? round(($totalPhoneClicks / $totalEngaged) * 100, 2)
            : 0;

        $userStats = [
            'totalViews' => $totalViews,
            'todayViews' => $todayViews,
            'totalMaterials' => $totalMaterials,
            'conversionRate' => $overallConversionRate,
            'last7DaysViews' => $last7Days->toArray(),
            'conversions' => [
                'total_visits' => $totalVisits,
                'engaged' => $totalEngaged,
                'phone_clicks' => $totalPhoneClicks,
                'rate' => $overallConversionRate,
            ]
        ];

        return Inertia::render('LawyerDashboard', [
            'auth' => $user,
            'demoData' => null, // Для авторизованных - демо нет
            'userData' => [
                'stats' => $userStats,
                'isDemo' => false,
                'articles' => [
                    'data' => $articlesData->toArray(),
                    'count' => $articlesTotalCount,
                    'totalViews' => $articlesTotalViews,
                    'last7DaysViews' => $articlesLast7Days->toArray(),
                    'conversions' => [
                        'total_visits' => $articlesTotalVisits,
                        'engaged' => $articlesTotalEngaged,
                        'phone_clicks' => $articlesTotalPhoneClicks,
                        'rate' => $articlesConversionRate,
                    ],
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $perPage,
                        'total' => $articlesTotalCount,
                        'total_pages' => ceil($articlesTotalCount / $perPage)
                    ]
                ],
                'uslugi' => [
                    'data' => $uslugiData->toArray(),
                    'count' => $uslugiTotalCount,
                    'totalViews' => $uslugiTotalViews,
                    'last7DaysViews' => $uslugiLast7Days->toArray(),
                    'conversions' => [
                        'total_visits' => $uslugiTotalVisits,
                        'engaged' => $uslugiTotalEngaged,
                        'phone_clicks' => $uslugiTotalPhoneClicks,
                        'rate' => $uslugiConversionRate,
                    ],
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $perPage,
                        'total' => $uslugiTotalCount,
                        'total_pages' => ceil($uslugiTotalCount / $perPage)
                    ]
                ],
                'filters' => [
                    'activeTab' => $activeTab,
                    'search' => $search,
                    'sortField' => $sortField,
                    'sortOrder' => $sortOrder,
                    'page' => $page,
                    'status' => $statusFilter,
                    'perPage' => $perPage
                ]
            ]
        ]);
    }
    
    /**
     * Сортировка коллекции
     */
    private function sortCollection($collection, $field, $order)
    {
        return $collection->sortBy(function ($item) use ($field) {
            switch ($field) {
                case 'date':
                    return strtotime($item['date'] ?? '1970-01-01');
                case 'views':
                    return $item['views'] ?? 0;
                case 'title':
                    return $item['title'] ?? '';
                case 'conversions':
                    return $item['conversions']['rate'] ?? 0;
                default:
                    return strtotime($item['date'] ?? '1970-01-01');
            }
        }, SORT_REGULAR, $order === 'desc');
    }
    
    /**
     * Получаем данные отслеживания для статей
     */
    private function getArticlesTrackingData($userId)
    {
        return DB::connection('pgsql_stats')
            ->table('yandex_tracking')
            ->where('user_id', $userId)
            ->whereNotNull('article_id')
            ->selectRaw('
                article_id as material_id,
                COUNT(*) as total_visits,
                COALESCE(SUM(CASE WHEN is_engaged = true THEN 1 ELSE 0 END), 0) as engaged,
                COALESCE(SUM(CASE WHEN phone_click_at IS NOT NULL THEN 1 ELSE 0 END), 0) as phone_clicks
            ')
            ->groupBy('article_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->material_id => [
                        'total_visits' => (int) $item->total_visits,
                        'engaged' => (int) $item->engaged,
                        'phone_clicks' => (int) $item->phone_clicks,
                    ]
                ];
            });
    }
    
    /**
     * Получаем данные отслеживания для услуг
     */
    private function getUslugiTrackingData($userId)
    {
        return DB::connection('pgsql_stats')
            ->table('yandex_tracking')
            ->where('user_id', $userId)
            ->whereNotNull('interests')
            ->selectRaw('
                interests as material_id,
                COUNT(*) as total_visits,
                COALESCE(SUM(CASE WHEN is_engaged = true THEN 1 ELSE 0 END), 0) as engaged,
                COALESCE(SUM(CASE WHEN phone_click_at IS NOT NULL THEN 1 ELSE 0 END), 0) as phone_clicks
            ')
            ->groupBy('interests')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->material_id => [
                        'total_visits' => (int) $item->total_visits,
                        'engaged' => (int) $item->engaged,
                        'phone_clicks' => (int) $item->phone_clicks,
                    ]
                ];
            });
    }
    
    /**
     * Фильтрация демо-материалов
     */
    private function filterMaterials(array $materials, string $search, string $status): array
    {
        return array_filter($materials, function ($material) use ($search, $status) {
            if (!empty($search) && stripos($material['title'], $search) === false) {
                return false;
            }
            
            if (!empty($status) && $material['status'] !== $status) {
                return false;
            }
            
            return true;
        });
    }
    
    /**
     * Сортировка демо-материалов
     */
    private function sortMaterials(array $materials, string $field, string $order): array
    {
        usort($materials, function ($a, $b) use ($field, $order) {
            $valueA = $this->getSortValue($a, $field);
            $valueB = $this->getSortValue($b, $field);
            
            if ($order === 'asc') {
                return $valueA <=> $valueB;
            } else {
                return $valueB <=> $valueA;
            }
        });
        
        return $materials;
    }
    
    /**
     * Получение значения для сортировки
     */
    private function getSortValue(array $material, string $field)
    {
        switch ($field) {
            case 'views':
                return $material['views'] ?? 0;
            case 'title':
                return $material['title'] ?? '';
            case 'conversions':
                return $material['conversions']['rate'] ?? 0;
            case 'date':
            default:
                return strtotime($material['date'] ?? '1970-01-01');
        }
    }
}