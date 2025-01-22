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

        $user_id = Auth::user() ? Auth::user()->id : null;

        $articles = DB::table('articles')
            ->leftjoin('users', 'articles.userid', '=', 'users.id')
            ->leftjoin('article_comments', 'articles.id', '=', 'article_comments.article_id')
            ->leftjoin('bundles_socials', function ($join) use ($user_id) {
                $join->on('bundles_socials.article_id', '=', 'articles.id')
                    ->where('bundles_socials.users_id', '=', $user_id);
            })
            ->select(
                'users.id as user_id',
                'users.name',
                'users.avatar_path as avatar_path',
                'users.lawyer',
                'articles.likes',
                'articles.shares',
                'articles.bookmarks',
                'articles.comments',
                'articles.id',
                'articles.header',
                'articles.description',
                'articles.created_at',
                'articles.url',
                'articles.counter',
                'article_comments.body as article_comment',
                'article_comments.users_id as avatar',
                DB::raw('count("article_comments.body") as comment_count'),
                'bundles_socials.likes as user_like',
                'bundles_socials.bookmarks as user_bookmark',
                'bundles_socials.shares as user_share',
                'articles.id',
            )
            ->groupBy('articles.id');

        $bundles = DB::table('questions')
            ->leftjoin('users', 'questions.user_id', '=', 'users.id')
            ->leftjoin('answers', 'questions.id', '=', 'answers.questions_id')
            ->leftjoin('bundles_socials', function ($join) use ($user_id) {
                $join->on('bundles_socials.question_id', '=', 'questions.id')
                    ->where('bundles_socials.users_id', '=', $user_id);
            })
            ->select(
                'users.id as user_id',
                'users.name',
                'users.avatar_path as avatar_path',
                'users.lawyer',
                'questions.likes',
                'questions.shares',
                'questions.bookmarks',
                'questions.comments',
                'questions.id',
                'questions.title AS header',
                'questions.body AS abody',
                'questions.created_at AS created_at',
                'questions.url AS url',
                'questions.counter AS counter',
                'answers.body as comment',
                'answers.users_id as avatar',
                DB::raw('count(*) as comment_count'),
                'bundles_socials.likes as user_like',
                'bundles_socials.bookmarks as user_bookmark',
                'bundles_socials.shares as user_share',
            )
            ->selectRaw('questions.url * ? AS type', [''])
            ->groupBy('id')
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
        $user_id = Auth::user() ? Auth::user()->id : null;

        $articles = DB::table('articles')
            ->leftjoin('users', 'articles.userid', '=', 'users.id')
            ->leftjoin('article_comments', 'articles.id', '=', 'article_comments.article_id')
            ->leftjoin('bundles_socials', function ($join) use ($user_id) {
                $join->on('bundles_socials.article_id', '=', 'articles.id')
                    ->where('bundles_socials.users_id', '=', $user_id);
            })
            ->select(
                'users.id as user_id',
                'users.name',
                'users.avatar_path as avatar_path',
                'users.lawyer',
                'articles.likes',
                'articles.shares',
                'articles.bookmarks',
                'articles.comments',
                'articles.id',
                'articles.header',
                'articles.description',
                'articles.created_at',
                'articles.url',
                'articles.counter',
                'article_comments.body as article_comment',
                'article_comments.users_id as avatar',
                DB::raw('count("article_comments.body") as comment_count'),
                'bundles_socials.likes as user_like',
                'bundles_socials.bookmarks as user_bookmark',
                'bundles_socials.shares as user_share',
                'articles.id',
            )
            ->groupBy('articles.id');

        $bundles = DB::table('questions')
            ->leftjoin('users', 'questions.user_id', '=', 'users.id')
            ->leftjoin('answers', 'questions.id', '=', 'answers.questions_id')
            ->leftjoin('bundles_socials', function ($join) use ($user_id) {
                $join->on('bundles_socials.question_id', '=', 'questions.id')
                    ->where('bundles_socials.users_id', '=', $user_id);
            })
            ->select(
                'users.id as user_id',
                'users.name',
                'users.avatar_path as avatar_path',
                'users.lawyer',
                'questions.likes',
                'questions.shares',
                'questions.bookmarks',
                'questions.comments',
                'questions.id',
                'questions.title AS header',
                'questions.body AS abody',
                'questions.created_at AS created_at',
                'questions.url AS url',
                'questions.counter AS counter',
                'answers.body as comment',
                'answers.users_id as avatar',
                DB::raw('count(*) as comment_count'),
                'bundles_socials.likes as user_like',
                'bundles_socials.bookmarks as user_bookmark',
                'bundles_socials.shares as user_share',
            )
            ->selectRaw('questions.url * ? AS type', [''])
            ->groupBy('id')
            ->union($articles)
            ->orderByDesc('created_at')
            ->paginate(20);

        foreach ($bundles as $item) {
            $item->abody = Str::limit($item->abody, 200);
            $item->created_at = humandate::lenta($item->created_at);
            $item->avatar =  $item->avatar ? User::find($item->avatar)->avatar_path : null;
        }

        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
            'auth' => Auth::user(),
            'h1' => 'Свежее',
        ]);
    }

    public function articles()
    {
        $user_id = Auth::user() ? Auth::user()->id : null;

        $bundles = DB::table('articles')
            ->leftjoin('users', 'articles.userid', '=', 'users.id')
            ->leftjoin('article_comments', 'articles.id', '=', 'article_comments.article_id')
            ->leftjoin('bundles_socials', function ($join) use ($user_id) {
                $join->on('bundles_socials.article_id', '=', 'articles.id')
                    ->where('bundles_socials.users_id', '=', $user_id);
            })
            ->select(
                'users.id as user_id',
                'users.name',
                'users.avatar_path as avatar_path',
                'users.lawyer',
                'articles.likes',
                'articles.shares',
                'articles.bookmarks',
                'articles.comments',
                'articles.id',
                'articles.header',
                'articles.description AS abody',
                'articles.created_at',
                'articles.url',
                'articles.counter',
                'article_comments.body as article_comment',
                'article_comments.users_id as avatar',
                DB::raw('count("article_comments.body") as comment_count'),
                'bundles_socials.likes as user_like',
                'bundles_socials.bookmarks as user_bookmark',
                'bundles_socials.shares as user_share',
                'articles.id as type',
            )
            ->groupBy('articles.id')
            ->orderByDesc('created_at')
            ->paginate(20);

        foreach ($bundles as $item) {
            $item->abody = Str::limit($item->abody, 200);
            $item->created_at = humandate::lenta($item->created_at);
            $item->avatar =  $item->avatar ? User::find($item->avatar)->avatar_path : null;
        }

        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
            'auth' => Auth::user(),
            'h1' => 'Статьи',
        ]);
    }

    public function questions()
    {
        $user_id = Auth::user() ? Auth::user()->id : null;

        $bundles = DB::table('questions')
            ->leftjoin('users', 'questions.user_id', '=', 'users.id')
            ->leftjoin('answers', 'questions.id', '=', 'answers.questions_id')
            ->leftjoin('bundles_socials', function ($join) use ($user_id) {
                $join->on('bundles_socials.question_id', '=', 'questions.id')
                    ->where('bundles_socials.users_id', '=', $user_id);
            })
            ->select(
                'users.id as user_id',
                'users.name',
                'users.avatar_path as avatar_path',
                'users.lawyer',
                'questions.likes',
                'questions.shares',
                'questions.bookmarks',
                'questions.comments',
                'questions.id',
                'questions.title AS header',
                'questions.body AS abody',
                'questions.created_at AS created_at',
                'questions.url AS url',
                'questions.counter AS counter',
                'answers.body as comment',
                'answers.users_id as avatar',
                DB::raw('count(*) as comment_count'),
                'bundles_socials.likes as user_like',
                'bundles_socials.bookmarks as user_bookmark',
                'bundles_socials.shares as user_share',
            )
            ->selectRaw('questions.url * ? AS type', [''])
            ->groupBy('id')
            ->orderByDesc('created_at')
            ->paginate(20);

        foreach ($bundles as $item) {
            $item->abody = Str::limit($item->abody, 200);
            $item->created_at = humandate::lenta($item->created_at);
            $item->avatar =  $item->avatar ? User::find($item->avatar)->avatar_path : null;
        }

        return Inertia::render('Lenta/Lenta', [
            'bundles' => $bundles,
            'auth' => Auth::user(),
            'h1' => 'Вопросы',
        ]);
    }
}
