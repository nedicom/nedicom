<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Helpers\PaginationHelper;
use App\Models\Uslugi;
use App\Models\User;

use Illuminate\Support\Str;

class GetUslugi
{
    public static function GetUsl($user_id, $city, $main, $second)
    {

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
            ->when($city->id == 0 ? 0 : $city->id, function ($query, $sescity) {
                $query->where(function ($query) use ($sescity) {
                    $query->where('sity', $sescity);
                });
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
            $item->url = $item->cities->url . '/' .
                $item->main->url . '/' .
                $item->second->url . '/' .
                $item->url;
            $item->usl_desc = Str::limit($item->usl_desc, 200);
            $item->total_rating = $item->review_sum_rating + $item->userreview_sum_rating;
            $item->total_count = $item->userreview_count + $item->review_count;
            $item->final_rating = $item->total_count ? $item->total_rating / $item->total_count : 0;
            //$item->final_rating = number_format( $item->final_rating, 2);
        };


        $users = User::where('lawyer', '=', 1)
            ->where('file_path', '!=', '/storage/images/landing/main/default.webp')
            ->select(
                'users.id as user_id',
                'users.id as id',
                'users.file_path',
                'users.name as usl_name',
                'users.id as url',
                'users.id as clean_url',
                'users.city as sity',
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
            ->withCount('review')
            ->withSum('review', 'rating')
            ->with('review')
            ->with('main')
            ->with('second')
            ->inRandomOrder ()
            ->get();

        foreach ($users as $item) {
            $item->usl_desc = Str::limit($item->usl_desc, 200);
        };

        $grouped = $uslugi->groupBy('user_id');

        $maxvalue = 0;
        $i = 0;
        $a = 0;
        $collection = collect();

        foreach ($grouped as $groupedvalue) {
            $maxvalue = $maxvalue < $groupedvalue->count() ? $groupedvalue->count() : $maxvalue;
        }

        while ($i <= $maxvalue) {
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

        while ($i <= $users->count()) {
            if (isset($users[$i])) {
                $collection->push($users[$i]);
            }
            $i++;
        }

        $paginated = PaginationHelper::paginate($collection, 10);

        return $paginated;
    }
}
