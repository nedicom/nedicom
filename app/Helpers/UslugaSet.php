<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Models\Uslugi;

class UslugaSet
{
    public static function setFromRequest(Request $request)
    {
        if ($request->uslugaurl) {
            $usluga = Uslugi::where('url', $request->uslugaurl)->first();
            if ($usluga) {
                session(['usluga_id' => $usluga->id, 'usl_name' => $usluga->usl_name, 'usl_desc' => $usluga->usl_desc]);
            }
        } else {
            if (session()->has('usluga_id')) {
                $usluga = Uslugi::where('id', session('usluga_id'))->first();
            } else {
                $usluga = null;
            }
        }
        return $usluga;
    }

    public static function setFromUrl($url)
    {
        if ($url) {
            $usluga = Uslugi::where('url', $url)->first();
            if ($usluga) {
                session(['usluga_id' => $usluga->id, 'usl_name' => $usluga->usl_name, 'usl_desc' => $usluga->usl_desc]);
            }
        } else {
            $usluga = null;
        }
        return $usluga;
    }
}
