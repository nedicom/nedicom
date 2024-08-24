<?php

namespace App\Helpers;

use App\Models\Uslugi;

class Checkurl{
    public static function chkurl($value, $type)
    {
        if ($type == 'usluga'){
            $uslugi = Uslugi::where('url', '=', $value)->first();
            $counter = 1;
            $newurl = $value;
            while($uslugi !== null)
                {                       
                    $newurl = $value.'-'.$counter;
                    $counter++;
                    $uslugi = Uslugi::where('url', '=', $newurl)->first();
                }
            return $newurl;
        }     
    }
}