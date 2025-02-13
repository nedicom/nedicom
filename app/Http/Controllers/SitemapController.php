<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Uslugi;
use App\Models\Questions;
use App\Models\cities;
use App\Http\Controllers\Controller;

class SitemapController extends Controller
{
    public function sitemap()
    {
        return response()->view('sitemap/sitemap', [])->header('Content-Type', 'text/xml');
    }

    public function articles()
    {
        $articles = Article::all();

        return response()->view('sitemap/articles', [
            'articles' => $articles,
        ])->header('Content-Type', 'text/xml');
    }

    public function lawyers()
    {
        $lawyers = User::where('lawyer', 1)->get();

        return response()->view('sitemap/lawyers', [
            'lawyers' => $lawyers,
        ])->header('Content-Type', 'text/xml');
    }

    public function uslugi_city()
    {
        return response()->view('sitemap/uslugi/uslugi_city', [
            'uslugi' => cities::pluck('url'),
        ])->header('Content-Type', 'text/xml');
    }

    public function uslugi_city_main()
    {
        return response()->view('sitemap/uslugi/uslugi_city_main', [
            'cities' => cities::pluck('url'),
            'mains' => Uslugi::where('is_main', 1)->where('is_feed', 1)->get(['url', 'updated_at']),
        ])->header('Content-Type', 'text/xml');
    }

    public function uslugi_city_main_second()
    {
        return response()->view('sitemap/uslugi/uslugi_city_main_second', [
            'cities' => cities::pluck('url'),
            'mains' => Uslugi::where('is_main', 1)->where('is_feed', 1)->with('mainhassecond')->get(['id', 'url', 'updated_at', 'main_usluga_id']),
        ])->header('Content-Type', 'text/xml');
    }

    public function uslugi_city_main_second_canonical()
    {
        $uslugi = Uslugi::where('is_feed', 1)->where(function ($query) {
            $query->where('is_main', '!=', 1)
                ->orWhereNull('is_main');
        })->whereNull('is_second')->with('main')->with('second')->with('cities')->get();
        //dd($uslugi);
        return response()->view('sitemap/uslugi/uslugi_city_main_canonical', [
            'uslugi' => $uslugi,
        ])->header('Content-Type', 'text/xml');
    }

    public function questions()
    {
        $questions = Questions::all();

        return response()->view('sitemap/questions', [
            'questions' => $questions,
            'main' => Uslugi::where('is_main', 1)->where('is_feed', 1)->select('id', 'usl_name', 'url', 'updated_at')->get(),
        ])->header('Content-Type', 'text/xml');
    }
}
