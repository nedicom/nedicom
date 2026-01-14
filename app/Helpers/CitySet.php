<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\cities;
use Eseath\SxGeo\SxGeo;
use App\Helpers\GetIp;
use App\Helpers\UslugaSet;
use Illuminate\Support\Facades\Log;

class CitySet
{
    public static function CitySet($request, $cityurl, $profile)
    {
        $city = null;
        
        if (Auth::user() && $profile) { //set city from profile form            
            $user = User::find(Auth::user()->id);
            $user->city = $request->city ?? '';
            $user->city_id = $request->cityid ?? null;
            $user->save();
            
            if ($user->city_id) {
                $city = cities::where('id', $user->city_id)->first();
                if ($city) {
                    session(['cityid' => $city->id, 'citytitle' => $city->title]);
                }
            }
        } else {
            if ($request->cityid) { //set city from popup not profile                             
                $city = cities::where('id', $request->cityid)->first();
                
                if ($city) {
                    session(['cityid' => $city->id, 'citytitle' => $city->title]);
                    
                    if (Auth::user()) {
                        if (!Auth::user()->city_id) { //set city if user hasnt city
                            $user = User::find(Auth::user()->id);
                            $user->city = $request->city ?? '';
                            $user->city_id = $request->cityid;
                            $user->save();
                        }
                    }
                }
            } else {  //url has city url; 
                if ($cityurl != '') {
                    $city = cities::where('url', $cityurl)->first();
                    
                    if ($city) {
                        session(['cityid' => $city->id, 'citytitle' => $city->title]);
                    } else {
                        // Если город по URL не найден, логируем и используем дефолтный
                        Log::warning("City not found by URL: " . $cityurl);
                        $city = self::getDefaultCity();
                    }
                } else { //set all Russia by default
                    $city = self::getDefaultCity();
                }
            }
        }
        
        // Гарантируем, что всегда возвращаем объект города
        if (!$city) {
            $city = self::getDefaultCity();
        }
        
        return $city;
    }

    public static function CityGet($cityid)
    {
        $city = null;

        // &cityheader=id for force checked city
        if ($cityid) {
            $city = cities::where('id', $cityid)->first();
            if ($city) {
                session(['cityid' => $city->id, 'citytitle' => $city->title]);
                return $city;
            }
        }

        // &cityheader=null check city from session
        if (session()->get('cityid')) {
            $city = cities::where('id', session()->get('cityid'))->first();
            if ($city) {
                return $city;
            }
        }

        // check city from user data
        if (Auth::user()) {
            if (Auth::user()->city_id) {
                $city = cities::where('id', Auth::user()->city_id)->first();
                if ($city) {
                    return $city;
                }
            }
        }

        // &cityheader=null city from ip
        if (env('APP_ENV') != 'local' && !$city) {
            try {
                $ip = GetIp::get_ip();
                $token = env('DADATA_TOKEN');
                $secret = env('DADATA_SECRET');
                
                if ($token && $secret) {
                    $dadata = new \Dadata\DadataClient($token, $secret);
                    $result = $dadata->iplocate($ip);
                    
                    if ($result && isset($result['data']['postal_code'])) {
                        $city = cities::where('postalcode', $result['data']['postal_code'])->first();
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error getting city from IP: ' . $e->getMessage());
            }
        }

        // all=null city from ip or default
        if (!$city) {
            $city = self::getDefaultCity();
        }

        // Устанавливаем сессию, если город найден
        if ($city) {
            session(['cityid' => $city->id, 'citytitle' => $city->title]);
        }

        return $city;
    }
    
    /**
     * Получить город по умолчанию
     * 
     * @return \App\Models\cities
     */
    private static function getDefaultCity()
    {
        // Пытаемся найти город с id=0
        $city = cities::find(0);
        
        // Если не найдено, берем первый город из БД
        if (!$city) {
            $city = cities::orderBy('id')->first();
        }
        
        // Если в БД вообще нет городов, создаем заглушку
        if (!$city) {
            $city = new \stdClass();
            $city->id = 0;
            $city->title = 'Все города';
            $city->url = '';
            $city->postalcode = '';
            // Добавьте другие необходимые свойства
        }
        
        return $city;
    }
}