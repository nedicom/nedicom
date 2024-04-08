<?php

namespace App\Http\Controllers;

use App\Models\Uslugi;
use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\User;



class MainpageController extends Controller
{

    public function main()
    {        
        $reviews = Review::orderBy('id', 'desc')->get();
        $reviewscount = Review::count();
        $rating = Review::sum('rating');
        $rating =  round($rating / $reviewscount , 1);

        return Inertia::render('Welcome', [
            'uslugislider' => Uslugi::with('firstlawyer')->where('is_main', '=', 1)->get(),
            'practice' => Article::where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(10)->get(),
            'reviews' => $reviews,
            'reviewscount' => $reviewscount,
            'rating' => $rating,
        ]);
    }
    
}