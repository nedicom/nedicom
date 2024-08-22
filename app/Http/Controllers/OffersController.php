<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Offer;
use App\Models\Uslugi;
use App\Models\cities;

class OffersController extends Controller
{
    public function show($url, Request $request)
    {
        $offer = Offer::where('id', $url)->with('city')->first();
        return Inertia::render('Offers/Offer', [
            'offer' => $offer,
            'offers' => Uslugi::where('sity', $offer->id)->paginate(12),
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
