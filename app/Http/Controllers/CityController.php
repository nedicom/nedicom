<?php

namespace App\Http\Controllers;

use App\Models\cities;
use App\Models\Uslugi;

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
        return Inertia::render('Uslugi/MainOffer', [
            'city' => $city,
            'main_usluga' => $main,
            'uslugi' => Uslugi::where('main_usluga_id', $main->id)->where('sity', $city->id)
            ->withCount('review')
            ->withSum( 'review', 'rating')
            ->get()
            ,
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
}
