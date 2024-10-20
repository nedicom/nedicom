<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Uslugi;
use App\Http\Controllers\Controller;

class SitemapController extends Controller
{
    public function sitemap()
    { 
        return response()->view('sitemap/sitemap', [
        ])->header('Content-Type', 'text/xml');
    }

    public function sets()
    {
        $sets = Uslugi::where('is_main', 1)
        ->where('is_feed', 1)
        ->with('mainhassecond')
        ->get();

        return response()->view('sitemap/sets', [
            'sets' => $sets,
        ])->header('Content-Type', 'text/xml');
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

    public function uslugi()
    {
        $uslugi = Uslugi::all()->with('main')->with('second')->with('city');
  
        return response()->view('sitemap/uslugi', [
            'uslugi' => $uslugi,
        ])->header('Content-Type', 'text/xml');
    }

}