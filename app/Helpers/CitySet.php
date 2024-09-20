<?php

namespace App\Helpers;

use App\Models\cities;

class CitySet
{
    public static function CitySet($request)
    {
        if ($request->cityid) {
            $city = cities::where('id', $request->cityid)->first();
            session(['cityid' => $city->id, 'citytitle' => $city->title]);
            $city = $city->url;
        } else {
            if (session()->get('cityid')) {
                $city = cities::where('id', session()->get('cityid'))->first()->url;
            } else {
                $city = null;
            }
        }
        return $city;
    }
}
