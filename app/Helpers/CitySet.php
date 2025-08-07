<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\cities;
use Eseath\SxGeo\SxGeo;
use App\Helpers\GetIp;
use App\Helpers\UslugaSet;


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

    public static function CityGet($cityid)
    {

        $city = false;
/*
        // &cityheader=id for force checked city
        if ($cityid) {
            $city = cities::where('id', $cityid)->first();
            session(['cityid' => $city->id, 'citytitle' => $city->title]);
            return $city;
        }

        // &cityheader=null check city from session
        if (session()->get('cityid')) {
            $city = cities::where('id', session()->get('cityid'))->first();
            return $city;
        }

        // check city from user dta
        if (Auth::user()) {
            if(Auth::user()->city_id){
                $city = cities::where('id', Auth::user()->city_id)->first();
                return $city;
            } 
        }

        // &cityheader=null city from ip
        if (env('APP_ENV') != 'local') {
            $ip = GetIp::get_ip();
            $token = env('DADATA_TOKEN');
            $secret = env('DADATA_SECRET');
            $dadata = new \Dadata\DadataClient($token, $secret);
            $result = $dadata->iplocate($ip);

            if ($result) {
                $city = cities::where('postalcode', $result['data']['postal_code'])->first();
            }
        }

        // all=null city from ip
        if (!$city) {
            $city = cities::find(0);
        }
            */
        return $city;
    }
}
