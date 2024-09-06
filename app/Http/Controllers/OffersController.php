<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Offer;
use App\Models\Uslugi;
use App\Models\cities;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;

class OffersController extends Controller
{
    public function all(Request $request)
    {
  /*      $offers = DB::table('offers')
                ->join('cities', 'offers.sity', '=', 'cities.id')
                ->join('uslugis', function (JoinClause $join) {
                    $join
                    
                    ->on('offers.mainusl_id', '=', 'uslugis.main_usluga_id' )
                    ->on('offers.sity', '=', 'uslugis.sity')
                    ;
                 
                }              
                )
                ->where('uslugis.is_main', '=', 0)
                ->select('cities.id as city_id', 'cities.title as city_title', 
                'offers.id as offer_id', 'offers.title as offer_title', 'offers.mainusl_id as offer_mainusl_id',
                'offers.sity as offer_sity', 
                'uslugis.id as uslugis_id', 'uslugis.usl_name as uslugis_usl_name', 'uslugis.url as uslugis_url', 
                'uslugis.main_usluga_id as uslugis_main_usluga_id', 'uslugis.is_main as uslugis_is_main', 
                'uslugis.is_feed as uslugis_is_feed', 'uslugis.sity as uslugis_sity', 
                'uslugis.file_path as uslugis_file_path'
                )
                ->orderBy('city_title', 'asc')
                ->get();
*/
                $cities=cities::all();

        return Inertia::render('Offers/Offers', [
            //'offers' => $offers,
            'cities' => $cities,
            //'user' => Auth::user(),
        ]);
    }

    public function show($url, Request $request)
    {
        $offer = Offer::where('id', $url)->with('city')->first();
        return Inertia::render('Offers/Offer', [
            'offer' => $offer,
            'offers' => Uslugi::where('sity', $offer->city->id)
            ->where('is_main', 0)
            ->where('main_usluga_id', $offer->mainusl_id)->paginate(12),
                    ]);
    }

    public function formadd()
    {       
        return Inertia::render('Offers/Add', [
            'mainusl_id' => Uslugi::where('is_main', "!=", 0)->get(),
            'cities' => cities::all(),
        ]); 
    }

    public function create(Request $request){
        $offer = new Offer;
            $offer->title = $request->title;
            $offer->mainusl_id = $request->mainusl_id;
            $offer->sity = $request->sity;
        $offer->save();
        return redirect()->route('offer.url', ['url' => $offer->id])->with('message', 'Страница услуг (offers) создана успешно');
    }
}
