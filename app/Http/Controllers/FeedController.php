<?php

namespace App\Http\Controllers;

use App\Models\Uslugi;
use App\Models\Offer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Contracts\Database\Eloquent\Builder;

class FeedController extends Controller
{

    public function simferopol()
    {
        $categories = Uslugi::where('is_main', '!=', 0)
            ->where('is_feed', 1)
            ->with('hasuslugi')->get();

        $sets = Uslugi::where('is_main', '!=', 0)
            ->with('hasuslugi')
            ->where('is_feed', 1)
            ->get();

        $offers = Uslugi::where('is_main', 0)
            ->where('is_second', null)
            ->with('mainwithsecond')
            ->where('sity', 1)
            ->where('is_feed', 1)
            ->with('main')
            ->with('second')
            ->with('cities')
            ->withCount('review as count_review')
            ->withAvg('review as avg_review', 'rating')
            ->get();

        return response()->view('feed/simferopol', [
            'categories' => $categories,
            'sets' => $sets,
            'offers' => $offers,
        ])->header('Content-Type', 'text/xml');
    }

    public function moscow()
    {
        $categories = Uslugi::where('is_main', '!=', 0)
            ->with('hasuslugi')->get();

        $sets = Uslugi::where('is_main', '!=', 0)
            ->with('hasuslugi')
            ->where('is_feed', 1)
            ->get();

        $offers = Uslugi::where('is_main', 0)
            ->where('is_second', null)
            ->with('mainwithsecond')
            ->where('sity', 6)
            ->with('main')
            ->with('second')
            ->with('cities')
            ->withCount('review as count_review')
            ->withAvg('review as avg_review', 'rating')
            ->get();

        return response()->view('feed/moscow', [
            'categories' => $categories,
            'sets' => $sets,
            'offers' => $offers,
        ])->header('Content-Type', 'text/xml');
    }

    //старый ушатанный фид с неправильной структурой, но сложными запросами
    public function old()
    {
        $offers = DB::table('uslugis')
            ->join('users', 'uslugis.user_id', 'users.id')
            ->join(
                'offers',
                function (JoinClause $join) {
                    $join
                        ->on('offers.mainusl_id', '=', 'uslugis.main_usluga_id')
                        ->on('offers.sity', '=', 'uslugis.sity');
                }
            )
            ->join('cities', 'offers.sity', '=', 'cities.id')
            ->where('uslugis.is_main', '=', 0)
            ->select(
                'uslugis.id as usluga_id',
                'uslugis.main_usluga_id as main_usluga_id',
                'uslugis.file_path as file_path',
                'uslugis.usl_name as usluga_name',
                'uslugis.url as usluga_url',
                'offers.id as offer_id',
                'cities.title as city_title',
                'users.*'
            )
            ->get();

        $categories = Uslugi::with('hasuslugi')->get();

        $sets = Offer::all();

        return response()->view('feed/feed', [
            'offers' => $offers,
            'sets' => $sets,
            'categories' => $categories,
        ])->header('Content-Type', 'text/xml');
    }
}
