<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\cities;


class CitySet
{
    public static function CitySet($request, $cityurl)
    {
       
        if (Auth::user() && $request->setcity) { //set city to profile
            $user = User::find(Auth::user()->id);  
            $user->city = $request->city;
            $user->city_id = $request->cityid;    
            $user->save();  
        }
        if ($request->cityid) { //from Cityfilter form or form in header    
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
                    //dd($cityurl);  
                    $city = cities::where('url', $cityurl)->first();
                    session(['cityid' => $city->id, 'citytitle' => $city->title]);
                } else {
                    $city = collect(['id' => 0, 'title' => '', 'url' => 'moscow']);
                }
            }
        }

        return $city;
    }

    public static function CityGet()
    {
        if (session()->get('cityid')) {
            $city = cities::where('id', session()->get('cityid'))->first();
        } else {
            $city = collect(['id' => 0, 'title' => '','url' => 'moscow']);
        }
        return $city;
    }
}
