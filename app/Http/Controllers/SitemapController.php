<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Uslugi;
use App\Models\Questions;
use App\Http\Controllers\Controller;

class SitemapController extends Controller
{
    public function sitemap()
    { 
        return response()->view('sitemap/sitemap', [
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
        $uslugi = Uslugi::has('main')->has('second')->has('cities')->get();
  
        return response()->view('sitemap/uslugi', [
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