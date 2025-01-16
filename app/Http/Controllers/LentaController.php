<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Questions;
use App\Models\Article;
use App\Casts\humandate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LentaController extends Controller
{

    public function popular()
    {
        $articles = DB::table('articles')
            ->leftjoin('users', 'articles.userid', '=', 'users.id')
            ->leftjoin('article_comments', 'articles.id', '=', 'article_comments.article_id')
            ->select(
                'users.id',
                'users.name',
                'users.avatar_path as apath',
                'users.lawyer',
                'articles.id',
                'articles.header',
                'articles.description',
                'articles.created_at',
                'articles.url',
                'articles.counter',
                'article_comments.body as article_comment',
                'article_comments.users_id as avatar',
                DB::raw('count("article_comments.body") as comment_count'),
                'articles.id',
            )
            ->groupBy('articles.id')
            ;

        $bundles = DB::table('questions')
            ->leftjoin('users', 'questions.user_id', '=', 'users.id')
            ->leftjoin('answers', 'questions.id', '=', 'answers.questions_id')
            ->select(
                'users.id',
                'users.name',
                'users.avatar_path as apath',
                'users.lawyer',
                'questions.id AS qid',
                'questions.title AS aheader',
                'questions.body AS abody',
                'questions.created_at AS created_at',
                'questions.url AS url',
                'questions.counter AS counter',
                'answers.body as comment',
                'answers.users_id as avatar',
                DB::raw('count(*) as comment_count'),
            )
            ->selectRaw('questions.url * ? AS type', [''])
            ->groupBy('qid')
            ->union($articles)
            ->orderByDesc('counter')
            ->paginate(20);

            foreach ($bundles as $item) {
                $item->abody = Str::limit($item->abody, 200);
                $item->created_at = humandate::lenta($item->created_at);
                $item->avatar =  $item->avatar ? User::find($item->avatar)->avatar_path : null;
            }

        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
            'auth' => Auth::user(),
            'h1' => 'Популярное',
        ]);
    }

    public function new()
    {
        $articles = DB::table('articles')
            ->join('users', 'articles.userid', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.avatar_path',
                'users.lawyer',
                'articles.id',
                'articles.header',
                'articles.description',
                'articles.created_at',
                'articles.counter',
                'articles.url',
                'articles.description'
            );

        $bundles = DB::table('questions')
            ->join('users', 'questions.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.avatar_path',
                'users.lawyer',
                'questions.id AS qid',
                'questions.title AS aheader',
                'questions.body AS abody',
                'questions.created_at AS created_at',
                'questions.counter',
                'questions.url AS url'
            )
            ->selectRaw('questions.url * ? AS type', [''])
            ->union($articles)
            ->orderByDesc('created_at')
            ->paginate(20);

        foreach ($bundles as $item) {
            $item->abody = Str::limit($item->abody, 100);
            $item->created_at = humandate::lenta($item->created_at);
        }

        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
            'auth' => Auth::user(),
            'h1' => 'Свежее',
        ]);
    }

    public function articles()
    {
        $bundles = DB::table('articles')
            ->join('users', 'articles.userid', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.avatar_path',
                'users.lawyer',
                'articles.id AS qid',
                'articles.header AS aheader',
                'articles.description AS abody',
                'articles.created_at',
                'articles.counter',
                'articles.url'
            )
            ->selectRaw('articles.description AS type')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        foreach ($bundles as $item) {
            $item->abody = Str::limit($item->abody, 100);
            $item->created_at = humandate::lenta($item->created_at);
        }

        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
            'auth' => Auth::user(),
            'h1' => 'Статьи',
        ]);
    }

    public function questions()
    {
        $bundles = DB::table('questions')
            ->join('users', 'questions.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.avatar_path',
                'users.lawyer',
                'questions.id AS qid',
                'questions.title AS aheader',
                'questions.body AS abody',
                'questions.created_at AS created_at',
                'questions.counter',
                'questions.url AS url'
            )
            ->selectRaw('questions.url * :5 AS type', [1])
            ->orderByDesc('created_at')
            ->paginate(20);

        foreach ($bundles as $item) {
            $item->abody = Str::limit($item->abody, 100);
            $item->created_at = humandate::lenta($item->created_at);
        }

        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
            'auth' => Auth::user(),
            'h1' => 'Вопросы',
        ]);
    }
}
