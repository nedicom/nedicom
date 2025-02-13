<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\CitySet;

class SessionController extends Controller
{
    
    public function City(Request $request)
    {        
        $cityurl = '';
        CitySet::CitySet($request, $cityurl, false, false);
    }
}

