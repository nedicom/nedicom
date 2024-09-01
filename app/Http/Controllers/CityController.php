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
        return Inertia::render('Uslugi/City', [
            'uslugi' => Uslugi::where('sity', 1)->paginate(12),
            'city' => cities::where('url', $city)->with('uslugies')->first(),
            'main_usluga' => Uslugi::where('url', $main_usluga)->first(['usl_name', 'url']),
            'second_usluga' => Uslugi::where('url', $second_usluga)->first(['usl_name', 'url']),
        ]);
    }
}
