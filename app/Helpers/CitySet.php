<?php
namespace App\Helpers;

use App\Models\cities;

class CitySet
{
    public static function CitySet($request) {
        if ($request->cityid) {
            $city = cities::where('id', $request->cityid)->first();
            session(['cityid' => $city->id, 'citytitle' => $city->title]);
            $city = $city->url;
        } else {
            $city = null;
        }
        return $city;
    }
}
