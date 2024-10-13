<?php

namespace App\Helpers;

use App\Models\cities;

class CitySet
{
    public static function CitySet($request, $cityurl)
    {
        if ($request->cityid) { //form from Cityfilter
            if ($request->cityid == 'zero') {
                $city = collect(['id' => 0, 'title' => '']);
                session(['cityid' => 0, 'citytitle' => '']);
            } else {
                $city = cities::where('id', $request->cityid)->first();
                session(['cityid' => $city->id, 'citytitle' => $city->title]);
            }
            //$city = $city;          
        } else { //session has cityid;
            if (session()->get('cityid')) {
                $city = cities::where('id', session()->get('cityid'))->first();
            } else { //url has city url;                
                if ($cityurl != '') {
                    $city = cities::where('url', $cityurl)->first();
                    session(['cityid' => $city->id, 'citytitle' => $city->title]);
                } else {
                    $city = collect(['id' => 0, 'title' => '']);
                }
            }
        }
        return $city;
    }
}
