<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\cities;


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
                    //dd($city);
                    session(['cityid' => $city->id, 'citytitle' => $city->title]);
                } else { //set moscow by default
                    $city = collect(['id' => 0, 'title' => 'Россия', 'url' => 'all-cities']);
                }
            }
        }
        return $city;
    }

    public static function CityGet($cityurl)
    {
        if (session()->get('cityid')) {  
                    
            $city = cities::where('id', session()->get('cityid'))->first();
        } elseif (Auth::user()) {
            $user = User::find(Auth::user()->id);
            session(['cityid' => $user->city_id, 'citytitle' => $user->city]);
        } elseif ($cityurl != '') {
            $city = cities::where('url', $cityurl)->first();
            if ($city) {
                session(['cityid' => $city->id, 'citytitle' => $city->title]);
            }
        } else {
            $city = collect(['id' => 0, 'title' => 'Россия', 'url' => 'all-cities']);
        }
        return $city;
    }
}
