<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

use App\Models\Questions;
use App\Models\Article;
use App\Casts\humandate;

class LentaController extends Controller
{

    public function popular()
    {
        //$articles = Article::with('user')->get();
        //$questions = Questions::with('User')->get();        
        //$bundles = $articles->concat($questions)->sortBy("created_at");

        $articles = DB::table('articles')
        ->join('users', 'articles.userid', '=', 'users.id')
        ->select('users.id', 'users.name', 'users.avatar_path', 'articles.id AS aid', 'articles.header AS aheader', 
        'articles.body AS abody', 'articles.created_at AS created_at', 'articles.url AS url')
        ;
 
        $bundles = DB::table('questions')
            ->join('users', 'questions.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.avatar_path', 'questions.id AS qid', 
            'questions.title AS aheader', 'questions.body AS abody',
            'questions.created_at AS created_at', 'questions.url AS url')
            ->union($articles)
            ->orderByDesc('created_at')
        ->get();
            
        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
        ]);
    }

    public function new()
    {
        $articles = DB::table('articles')
        ->join('users', 'articles.userid', '=', 'users.id')
        ->select('users.id', 'users.name', 'users.avatar_path', 'articles.id AS aid', 'articles.header AS aheader', 
        'articles.body AS abody', 'articles.created_at AS created_at', 'articles.url AS url')
        ;
 
        $bundles = DB::table('questions')
            ->join('users', 'questions.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.avatar_path', 'questions.id AS qid', 
            'questions.title AS aheader', 'questions.body AS abody',
            'questions.created_at AS created_at', 'questions.url AS url')
            ->union($articles)
            ->orderByDesc('created_at')
        ->get();
            
        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
        ]);
    }

    public function articles()
    {
        $bundles = Article::with('User')->orderBy('created_at', 'desc')->get();
        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
        ]);
    }

    public function questions()
    {
        $bundles = Questions::with('User')->orderBy('created_at', 'desc')->get();
        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
        ]);
    }
}