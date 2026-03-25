<?php

namespace App\Http\Controllers;

use App\Models\Uslugi;
use App\Models\Offer;
use App\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

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
            ->get()
            ->map(function ($offer) {
                $expirience = DB::table('users')->where('id', $offer->user_id)->value('expirience');
                $offer->expirience = $expirience ?? 1;

                $userReviews = Review::where('lawyer_id', $offer->user_id)->get();
                $uslugaReviews = Review::where('usl_id', $offer->id)->get();
                $allReviews = $uslugaReviews->concat($userReviews)->unique('id');
                $offer->count_review = $allReviews->count();
                $offer->avg_review = $offer->count_review > 0
                    ? round($allReviews->avg('rating'), 1)
                    : null;

                $prices = DB::table('uslugis_prices')
                    ->join('prices', 'uslugis_prices.prices_id', '=', 'prices.id')
                    ->where('uslugis_prices.uslugis_id', $offer->id)
                    ->whereNotNull('uslugis_prices.price')
                    ->where('uslugis_prices.price', '>', 0)
                    ->orderBy('uslugis_prices.price', 'asc')
                    ->select('uslugis_prices.price', 'prices.name') // берем цену и название
                    ->first(); // берем первый (самый дешевый)

                if ($prices) {
                    $offer->price = $prices->price;
                    $offer->sales_notes = $prices->name; // название из таблицы prices
                } else {
                    $offer->price = 0;
                    $offer->sales_notes = 'цена договорная';
                }

                $phone = DB::table('users')->where('id', $offer->user_id)->value('phone');
                $defaultPhone = '+7000 000 0000';
                $realPhone = '+79788838978';

                // Убираем все пробелы
                $cleanPhone = $phone ? preg_replace('/[^\d+]/', '', $phone) : null;
                $cleanDefault = str_replace(' ', '', $defaultPhone);

                $offer->phone = ($cleanPhone && $cleanPhone !== $cleanDefault)
                    ? $cleanPhone
                    : $realPhone;

                return $offer;
            })
            ->sortByDesc('count_review')
            ->unique(function ($item) {
                $setId = $item->second_usluga_id ? 's' . $item->second_usluga_id : 's' . $item->main_usluga_id;
                return $item->user->name . '|' . $setId;
            });

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
