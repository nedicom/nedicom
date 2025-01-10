<?php

namespace App\Http\Controllers;

use App\Models\Uslugi;
use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\CitySet;



class MainpageController extends Controller
{

    public function main(Request $request)
    {        
        $reviewscount = Review::count();
        $rating = Review::sum('rating');
        $rating =  round($rating / $reviewscount , 1);
        $city = CitySet::CitySet($request, 0);

        return Inertia::render('Welcome', [
            'city' => $city,
            'mainoffers' => Uslugi::where('is_main', 1)->where('is_feed', 1)->get(['id', 'usl_name', 'url', 'file_path']),
            'secondoffers' => Uslugi::where('is_second', 1)->where('is_feed', 1)->get(['id', 'usl_name', 'url', 'file_path']),
            'practice' => Article::where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(10)->get(),
            'reviews' => Review::inRandomOrder()->with('usluga')->get(),
            'reviewscount' => $reviewscount,
            'rating' => $rating,
            'auth' => Auth::user(), 
        ]);
    }
    
}