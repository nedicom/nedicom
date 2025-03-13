<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\cities;
use Eseath\SxGeo\SxGeo;


class CitySet
{
    public static function CitySet($request, $cityurl, $profile)
    {
        if (Auth::user() && $profile) { //set city from profile form            
            $user = User::find(Auth::user()->id);
            $user->city = $request->city;
            $user->city_id = $request->cityid;
            $user->save();
            session(['cityid' => $user->city_id, 'citytitle' => $user->city]);
            $city = cities::where('id', $user->city_id)->first();
        } else {
            if ($request->cityid) { //set city from popup not profile                             
                $city = cities::where('id', $request->cityid)->first();
                session(['cityid' => $city->id, 'citytitle' => $city->title]);
                if (Auth::user()) {
                    if (!Auth::user()->city_id) { //set city if user hasnt city
                        $user = User::find(Auth::user()->id);
                        $user->city = $request->city;
                        $user->city_id = $request->cityid;
                        $user->save();
                    }
                }
            } else {  //url has city url; 
                if ($cityurl != '') {
                    $city = cities::where('url', $cityurl)->first();
                    session(['cityid' => $city->id, 'citytitle' => $city->title]);
                } else { //set all Russia by default
                    $city = cities::find(0);
                }
            }
        }
        return $city;
    }

    public static function CityGet()
    {

        $sxGeo = new SxGeo('../database/GeoIP.dat');
        $fullInfo  = $sxGeo->getCityFull('232.223.11.11');
        //dd($fullInfo);

        if (session()->get('cityid')) {
            $city = cities::where('id', session()->get('cityid'))->first();
        } else {
            $city = cities::find(0);
        }
        return $city;
    }
}
