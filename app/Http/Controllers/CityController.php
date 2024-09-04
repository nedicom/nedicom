<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\cities;
use App\Models\Uslugi;
use App\Models\Review;
use App\Models\User;
use App\Models\Article;


use Illuminate\Http\Request;

use Inertia\Inertia;

class CityController extends Controller
{
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
        $main = Uslugi::where('url', $main_usluga)->first(['id', 'usl_name', 'url', 'usl_desc']);
        $uslugi = Uslugi::where('main_usluga_id', $main->id)->where('sity', $city->id)
        ->withCount('review')
        ->withSum( 'review', 'rating')
        ->get();
        
        return Inertia::render('Uslugi/MainOffer', [
            'city' => $city,
            'main_usluga' => $main,
            'uslugi' => $uslugi,
            'flash' => ['message' => $request->session()->get(key: 'message')],          
        ]);
    }

    public function showOfferBysecond(string $city, string $main_usluga, string $second_usluga, Request $request)
    {
        $city = cities::where('url', $city)->with('uslugies')->first();
        $main = Uslugi::where('url', $main_usluga)->first(['id', 'usl_name', 'url']);
        $second = Uslugi::where('url', $second_usluga)->first(['id', 'usl_name', 'url']);
        return Inertia::render('Uslugi/SecondOffer', [
            'city' => $city,
            'main_usluga' => $main,
            'second_usluga' => $second,
            'uslugi' => Uslugi::where('second_usluga_id', $second->id)->where('sity', $city->id)
            ->withCount('review')
            ->withSum( 'review', 'rating')
            ->get()
            ,
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
            'usluga' => Uslugi::where('url', $url)->with('cities')->first(),
            'secondUslugi' => Uslugi::where('main_usluga_id', $id)->where('is_second', '!=', 0)->get(),
            'uslugi' => Uslugi::where('second_usluga_id', $id)->with('cities')->get(),
            'main_usluga' => Uslugi::where('id', $mainid)->where('is_main', $mainid)->first(['id', 'usl_name', 'url']),
            'user' => Auth::user(),
            'lawyers' => User::where('speciality_one_id', '=', $id)->orderBy('name', 'asc')->get()->take(3),
            'practice' => Article::where('usluga_id', $mainid)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'firstlawyer' => User::where('id', $user_id)->get(),
            'reviews' => $reviews,
            'reviewscount' => $reviewscount,
            'rating' => $rating,
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);
    }
}
