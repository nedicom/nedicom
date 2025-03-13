<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\cities;
use Eseath\SxGeo\SxGeo;
use App\Helpers\GetIp;


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

        $ip = GetIp::get_ip();
        //dd($ip);
        $token = "4667f5f8016f7069789e94041a49052dd82414a3";
        $secret = "9d6b5f1aabde129507b11950dbdf272716443d54";
        $dadata = new \Dadata\DadataClient($token, $secret);
        $result = $dadata->iplocate('qwery');
        if ($result) {
            dd($result['data']['postal_code']);
        }


        $city = cities::find(0);

        return $city;



        if ($cityid) {
            $city = cities::where('id', $cityid)->first();
            session(['cityid' => $city->id, 'citytitle' => $city->title]);
            return $city;
        }

        if (session()->get('cityid')) {
            $city = cities::where('id', session()->get('cityid'))->first();
            return $city;
        }


        /* sypexGeo 
        
        $sxGeo = new SxGeo('../database/GeoIP.dat');
        
        if ($ip) {
            $fullInfo  = $sxGeo->getCityFull('188.191.20.139');
            if ($fullInfo) {
                dd($fullInfo['city']);
            }
        }
*/
    }
}
