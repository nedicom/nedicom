<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Helpers\PaginationHelper;
use App\Models\Uslugi;
use App\Models\User;
use App\Models\cities;

use Illuminate\Support\Str;

class GetUslugi
{
    public static function GetUsl($user_id, $city, $main, $second)
    {
        // Определяем, является ли выбранный город регионом или конкретным городом
        $isRegion = self::isRegion($city);
        
        $uslugi = Uslugi::where('is_main', '!=', 1)
            ->where('is_second', null)
            ->where('is_feed', 1)
            ->leftjoin('bundles_socials', function ($join) use ($user_id) {
                $join->on('bundles_socials.uslugis_id', '=', 'uslugis.id')
                    ->where('bundles_socials.users_id', '=', $user_id);
            })
            ->rightjoin('users', 'uslugis.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'uslugis.id',
                'uslugis.file_path',
                'uslugis.usl_name',
                'uslugis.url',
                'uslugis.url as clean_url',
                'uslugis.sity',
                'uslugis.main_usluga_id',
                'uslugis.second_usluga_id',
                'uslugis.usl_desc',
                'uslugis.price',
                'uslugis.likes',
                'uslugis.shares',
                'uslugis.bookmarks',
                'uslugis.views_count',                
                'bundles_socials.likes as user_like',
                'bundles_socials.bookmarks as user_bookmark',
                'bundles_socials.shares as user_share',
            )
            ->selectRaw('IF(uslugis.id, "uslugi", false) AS type')
            ->with('cities')
            ->with('main')
            ->with('second')
            ->with('review')
            ->with('userreview')
            ->when($city->id != 0, function ($query) use ($city, $isRegion) {
                if ($isRegion) {
                    // Если это регион - показываем объявления всего региона
                    $query->whereHas('cities', function ($q) use ($city) {
                        $q->where('regionId', $city->regionId);
                    });
                } else {
                    // Если это город - показываем только объявления этого города
                    $query->where('sity', $city->id);
                }
            })
            ->when($main ? $main->id : null, function ($query, $main_usl) {
                $query->where(function ($query) use ($main_usl) {
                    $query->where('main_usluga_id', $main_usl);
                });
            })
            ->when($second ? $second->id : null, function ($query, $sec_usl) {
                $query->where(function ($query) use ($sec_usl) {
                    $query->where('second_usluga_id', $sec_usl);
                });
            })
            ->withCount('review')
            ->withCount('userreview')
            ->withSum('userreview', 'rating')
            ->withSum('review', 'rating')
            ->orderBy('user_id')
            ->get();

        foreach ($uslugi as $item) {
            if ($item->cities === null) {
                $item->cities = (object) [ // создаем relation
                    'id' => 1,
                    'title' => 'Симферополь',
                    'url' => 'simferopol',
                    'regionId' => 10,
                    'region' => 'Республика Крым',
                ];
            }
            $item->url = $item->cities->url . '/' .
                $item->main->url . '/' .
                $item->second->url . '/' .
                $item->url;
            $item->usl_desc = Str::limit($item->usl_desc, 200);
            $item->total_rating = $item->review_sum_rating + $item->userreview_sum_rating;
            $item->total_count = $item->userreview_count + $item->review_count;
            $item->final_rating = $item->total_count ? $item->total_rating / $item->total_count : 0;
        };

        $users = User::where('lawyer', '=', 1)
            ->select(
                'users.id as user_id',
                'users.id',
                'users.file_path',
                'users.name as usl_name',
                'users.id as url',
                'users.id as clean_url',
                'users.city as sity',
                'users.city_id',
                'users.id as main_usluga_id',
                'users.id as second_usluga_id',
                'users.about as usl_desc',
                'users.id as price',
                'users.likes',
                'users.shares',
                'users.bookmarks',
                'users.id as user_like',
                'users.id as user_bookmark',
                'users.id as user_share',
            )
            ->selectRaw('IF(users.id, "user", false) AS type')
            ->when($city->id != 0, function ($query) use ($city, $isRegion) {
                if ($isRegion) {
                    // Если это регион - показываем юристов всего региона
                    $query->whereHas('cities', function ($q) use ($city) {
                        $q->where('regionId', $city->regionId);
                    });
                } else {
                    // Если это город - показываем только юристов этого города
                    $query->where('city_id', $city->id);
                }
            })
            ->with('cities')
            ->with('reviews')
            ->withCount('reviews')
            ->withSum('reviews', 'rating')
            ->with('main')
            ->with('second')
            ->inRandomOrder()
            ->get();

        foreach ($users as $item) {
            $item->usl_desc = Str::limit($item->usl_desc, 200);
            $item->total_rating = $item->reviews_sum_rating;
            $item->total_count = $item->reviews_count;
            $item->final_rating = $item->total_count ? $item->total_rating / $item->total_count : 0;
        };

        // Если это регион - сортируем объявления по городам с наибольшим количеством
        if ($isRegion && $city->id != 0) {
            $uslugi = self::sortByCityPopularity($uslugi, $city->regionId);
        }

        $grouped = $uslugi->groupBy('user_id');

        $maxvalue = 0;
        $i = 0;
        $a = 0;
        $collection = collect();

        foreach ($grouped as $groupedvalue) {
            $maxvalue = $maxvalue < $groupedvalue->count() ? $groupedvalue->count() : $maxvalue;
        }

        if ($maxvalue > 3) {
            while ($i < $maxvalue) {
                foreach ($grouped as $groupedvalue) {
                    $a++;
                    if (is_int($a / 4) && isset($users[$i])) {
                        $collection->push($users[$i]);
                    }
                    if (isset($groupedvalue[$i])) {
                        $collection->push($groupedvalue[$i]);
                    }
                }
                $i++;
            }
        } else {
            foreach ($uslugi as $usluga) {
                $collection->push($usluga);
            }
            foreach ($users as $user) {
                $collection->push($user);
            }
        }

        // Дополнительные юристы для регионов не нужны, так как мы уже учитываем весь регион
        // Оставляем эту логику только для конкретных городов
        if (!$isRegion && $collection->count() < 10 && $city->id != 0) {
            $old_users = $users->pluck('id')->toArray();
            $array = cities::where('regionId', $city->regionId)->get()->pluck('id')->toArray();
            $dop_users = User::where('lawyer', '=', 1)
                ->where('file_path', '!=', '/storage/images/landing/main/default.webp')
                ->whereIn('city_id',  $array)
                ->whereNotIn('id',  $old_users)
                ->select(
                    'users.id as user_id',
                    'users.id',
                    'users.file_path',
                    'users.name as usl_name',
                    'users.id as url',
                    'users.id as clean_url',
                    'users.city as sity',
                    'users.city_id',
                    'users.id as main_usluga_id',
                    'users.id as second_usluga_id',
                    'users.about as usl_desc',
                    'users.id as price',
                    'users.likes',
                    'users.shares',
                    'users.bookmarks',
                    'users.id as user_like',
                    'users.id as user_bookmark',
                    'users.id as user_share',
                )
                ->selectRaw('IF(users.id, "user", false) AS type')
                ->with('cities')
                ->with('reviews')
                ->withCount('reviews')
                ->withSum('reviews', 'rating')
                ->with('main')
                ->with('second')
                ->inRandomOrder()
                ->get();

            foreach ($dop_users as $user) {
                $collection->push($user);
            }
        }

        $paginated = PaginationHelper::paginate($collection, 10);

        return $paginated;
    }

    /**
     * Сортирует объявления по городам с наибольшим количеством объявлений
     */
    private static function sortByCityPopularity($uslugi, $regionId)
    {
        // Получаем количество объявлений по городам в регионе
        $cityCounts = Uslugi::where('is_main', '!=', 1)
            ->where('is_second', null)
            ->where('is_feed', 1)
            ->rightjoin('users', 'uslugis.user_id', '=', 'users.id')
            ->join('cities', 'uslugis.sity', '=', 'cities.id')
            ->where('cities.regionId', $regionId)
            ->select('cities.id', 'cities.title')
            ->selectRaw('COUNT(uslugis.id) as uslugi_count')
            ->groupBy('cities.id', 'cities.title')
            ->orderBy('uslugi_count', 'DESC')
            ->get()
            ->pluck('uslugi_count', 'id')
            ->toArray();

        // Если нет данных по городам, возвращаем исходную коллекцию
        if (empty($cityCounts)) {
            return $uslugi;
        }

        // Сортируем объявления: сначала из городов с наибольшим количеством объявлений
        return $uslugi->sortByDesc(function ($item) use ($cityCounts) {
            $cityId = $item->cities->id ?? $item->sity;
            return $cityCounts[$cityId] ?? 0;
        })->values();
    }

    /**
     * Определяет, является ли переданный город регионом
     */
    private static function isRegion($city)
    {
        return $city->id == 10 || // Республика Крым
               $city->regionId === null || 
               $city->id == 0; // "Все регионы"
    }
}