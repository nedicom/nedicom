<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\cities;
use App\Models\Uslugi;
use App\Models\Review;
use App\Models\User;
use App\Models\Article;

use Illuminate\Http\Request;

use App\Helpers\CitySet;

use Inertia\Inertia;

class CityController extends Controller
{

    public function showCities($city,  Request $request)
    {
        $city = cities::where('url', $city)->with('uslugies')->first();

        $mainuslugi = Uslugi::where('sity', $city->id)
            ->whereNotNull('main_usluga_id')
            ->with('main')
            ->get(['id', 'main_usluga_id', 'url'])
            ->groupBy('main.name');

        $uslugi = Uslugi::where('sity', $city->id)
            ->withCount('review')
            ->withSum('review', 'rating')
            ->get();

        return Inertia::render('Offers/City', [
            'city' => $city,
            'uslugi' => $uslugi,
            'mainuslugi' => $mainuslugi,
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);
    }


    public function showCityFromUslugi($main_usluga, $second_usluga, $city,  Request $request)
    {
        $city = cities::where('url', $city)->with('uslugies')->first();
        return Inertia::render('Uslugi/City', [
            'uslugi' => Uslugi::where('sity', $city->id)->paginate(12),
            'city' => $city,
            'main_usluga' => Uslugi::where('url', $main_usluga)->first(['usl_name', 'url']),
            'second_usluga' => Uslugi::where('url', $second_usluga)->first(['usl_name', 'url']),
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);
    }

    public function showOfferByMain(string $city, string $main_usluga,  Request $request)
    {
        $city = cities::where('url', $city)->with('uslugies')->first();
        $main = Uslugi::where('url', $main_usluga)->with('cities')->first(['id', 'usl_name', 'url', 'usl_desc']);

        $category = Uslugi::where('is_main', 1)
        ->where('is_feed', 1)
        ->with(['mainhasoffer' => function ($query) {
            if (session()->get('cityid')) {
                $query->where('sity', session()->get('cityid'));
            }
        }])
        ->with('mainhassecond')
        ->get();

        CitySet::CitySet($request);

        $uslugi = Uslugi::where('main_usluga_id', $main->id)
            ->where('is_feed', 1)
            ->where('is_second', null)
            ->with('cities')
            ->when(session()->get('cityid') ?? null, function ($query, $sescity) {
                $query->where(function ($query) use ($sescity) {
                    $query->where('sity', $sescity);
                });
            })
            ->withCount('review')
            ->withSum('review', 'rating')
            ->get();

        $cities = '';
        if ($request->city) {
            $cities = cities::when($request->city ?? null, function ($query, $city) {
                return $query->where('title', 'like', '%' . $city . '%')->get();
            });
        }

        return Inertia::render('Offers/MainOffer', [
            'city' => $city,
            'category' => $category,
            'main_usluga' => $main,
            'uslugi' => $uslugi,
            'cities' => $cities,
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);
    }

    public function showOfferBysecond(string $city, string $main_usluga, string $second_usluga, Request $request)
    {
        $city = cities::where('url', $city)->with('uslugies')->first();
        $main = Uslugi::where('url', $main_usluga)->first(['id', 'usl_name', 'usl_desc', 'url']);
        $second = Uslugi::where('url', $second_usluga)->with('cities')->with('main')->first(['id', 'usl_name', 'url']);


        $cities = '';
        if ($request->city) {
            $cities = cities::when($request->city ?? null, function ($query, $city) {
                return $query->where('title', 'like', '%' . $city . '%')->get();
            });
        }

        $category = Uslugi::where('is_main', 1)
        ->where('is_feed', 1)
        ->with(['mainhasoffer' => function ($query) {
            if (session()->get('cityid')) {
                $query->where('sity', session()->get('cityid'));
            }
        }])
        ->with('mainhassecond')
        ->get();

        CitySet::CitySet($request);

        return Inertia::render('Offers/SecondOffer', [
            'city' => $city,
            'category' => $category,
            'main_usluga' => $main,
            'cities' => $cities,
            'second_usluga' => $second,
            'uslugi' => Uslugi::where('second_usluga_id', $second->id)->where('sity', $city->id)
                ->withCount('review')
                ->withSum('review', 'rating')
                ->with('cities')
                ->get(),
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);
    }



    public function showOffer($city, $main, $second, $url,  Request $request)
    {
        $usluga = Uslugi::where('url', '=', $url)->first();
        $id = $usluga->id;
        $mainid = $usluga->main_usluga_id;

        if (!$mainid) {
            $mainid = $id;
        }

        if (Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->count() !== 0) {
            $reviews = Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->orderBy('id', 'desc')->get();
            $reviewscount = Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->count();
            $rating = Review::select('rating')->where('usl_id', $id)->orWhere('usl_id', $mainid)->sum('rating');
        } else {
            $reviews = Review::orderBy('id', 'desc')->get();
            $reviewscount = Review::count();
            $rating = Review::sum('rating');
        }

        $rating =  round($rating / $reviewscount, 1);

        $user_id = Uslugi::where('url', '=', $url)->first()->user_id;

        //views
        if ($usluga->is_main) {
            $view = 'Uslugi/Main';
        } else {
            $view = 'Uslugi/Usluga';
        }

        //data
        return Inertia::render($view, [
            'usluga' => Uslugi::where('url', $url)->with('cities')->with('main')->with('second')->first(),
            'secondUslugi' => Uslugi::where('main_usluga_id', $id)->where('is_second', '!=', 0)->get(),
            'uslugi' => Uslugi::where('second_usluga_id', $id)->with('cities')->get(),
            'main_usluga' => Uslugi::where('id', $mainid)->withCount('mainreview')->withAvg('mainreview as avg_review', 'rating')->first(['id', 'usl_name', 'url']),
            'user' => Auth::user(),
            'lawyers' => User::where('speciality_one_id', '=', $id)->orderBy('name', 'asc')->get()->take(3),
            'practice' => Article::where('usluga_id', $mainid)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'lawyer' => User::where('id', $user_id)->first(),
            'reviews' => $reviews,
            'reviewscount' => $reviewscount,
            'rating' => $rating,
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);
    }
}
