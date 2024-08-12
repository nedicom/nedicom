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
        $articles = DB::table('articles')
        ->join('users', 'articles.userid', '=', 'users.id')
        ->select('users.id', 'users.name', 'users.avatar_path', 'articles.id', 'articles.header', 
        'articles.body', 'articles.created_at', 'articles.url', 'articles.counter', 'articles.description'
       );
 
        $bundles = DB::table('questions')
            ->join('users', 'questions.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.avatar_path', 'questions.id AS qid', 
            'questions.title AS aheader', 'questions.body AS abody',
            'questions.created_at AS created_at', 'questions.url AS url', 'questions.counter AS counter'
            )
            ->selectRaw('questions.url * ? AS type', [''])
            ->union($articles)
            ->orderByDesc('counter')
        ->get();
       
        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
        ]);
    }

    public function new()
    {
        $articles = DB::table('articles')
        ->join('users', 'articles.userid', '=', 'users.id')
        ->select('users.id', 'users.name', 'users.avatar_path', 'articles.id', 'articles.header', 
        'articles.body', 'articles.created_at', 'articles.url', 'articles.description'
       );
 
        $bundles = DB::table('questions')
            ->join('users', 'questions.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.avatar_path', 'questions.id AS qid', 
            'questions.title AS aheader', 'questions.body AS abody',
            'questions.created_at AS created_at', 'questions.url AS url'
            )
            ->selectRaw('questions.url * ? AS type', [''])
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