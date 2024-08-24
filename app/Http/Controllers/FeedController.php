<?php

namespace App\Http\Controllers;

use App\Models\Uslugi;
use App\Models\Offer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class FeedController extends Controller
{

    //семейный юрист Симферополь
    public function feed()
    { 
        $offers = DB::table('uslugis')
        ->join('users', 'uslugis.user_id', 'users.id')        
        ->join('offers', function (JoinClause $join) {
            $join            
            ->on('offers.mainusl_id', '=', 'uslugis.main_usluga_id' )
            ->on('offers.sity', '=', 'uslugis.sity');         
        }              
        )
        ->join('cities', 'offers.sity', '=', 'cities.id')
        ->where('uslugis.is_main', '=', 0)
        ->select('uslugis.id as usluga_id', 'uslugis.main_usluga_id as main_usluga_id', 
        'uslugis.file_path as file_path', 'uslugis.usl_name as usluga_name', 'uslugis.url as usluga_url',
        'offers.id as offer_id',
        'cities.title as city_title',
        'users.*')
        ->get();
        //dd($offers);

     

        $categories = Uslugi::
        with('hasuslugi')->get();
        
        $sets = Offer::all();

        return response()->view('feed/feed', [
            'offers' => $offers,
            'sets' => $sets,
            'categories' => $categories,
        ])->header('Content-Type', 'text/xml');
    }
}