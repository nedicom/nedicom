<?php

namespace App\Http\Controllers;

use App\Models\Uslugi;
use App\Models\Article;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Review;

use App\Helpers\CitySet;
use App\Helpers\PaginationHelper;

use Inertia\Inertia;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Casts\humandate;
use Illuminate\Support\Str;

class MainpageController extends Controller
{

    public function main(Request $request)
    {
        $reviewscount = Review::count();
        $rating = Review::sum('rating');
        $rating =  round($rating / $reviewscount, 1);
        $city = CitySet::CityGet($request->cityheader);
        $user_id = Auth::user() ? Auth::user()->id : null;

        $secondoffers = Uslugi::where('is_second', 1)->where('is_feed', 1)->with('main')->select(['id', 'usl_name', 'url', 'file_path', 'main_usluga_id'])->get()
            ->map(function ($item) {
                $item->usl_name = $item->main->name . ' - ' . $item->usl_name;
                $item->url = $item->main->url . '/' . $item->url;
                return $item;
            });


        $query_articles = DB::table('articles')
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
                'articles.description as abody',
                'articles.created_at AS created_at',
                'articles.url',
                'articles.counter',
                'article_comments.body as comment',
                'article_comments.users_id as avatar',
                DB::raw('count("article_comments.body") as comment_count'),
                'bundles_socials.likes as user_like',
                'bundles_socials.bookmarks as user_bookmark',
                'bundles_socials.shares as user_share',
            )
            ->selectRaw('IF(articles.id, "articles", false) AS type')
            ->limit(20);

        $query_questions = DB::table('questions')
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
            ->selectRaw('IF(questions.id, "questions", false) AS type')
            ->limit(20);

        $questions_by_created_at = clone $query_questions;
        $questions_by_counter = clone $query_questions;
        $questions_by_created_at = $questions_by_created_at->orderBy('created_at', 'desc')
            ->groupBy('questions.id')->get();
        $questions_by_counter = $questions_by_counter->orderByDesc('counter')
            ->groupBy('questions.id')->get();

        $articles_by_counter = clone $query_articles;
        $articles_by_created_at = clone $query_articles;
        $articles_by_created_at = $articles_by_created_at->orderBy('created_at', 'desc')
            ->groupBy('articles.id')->get();
        $articles_by_counter = $query_articles->orderByDesc('counter')
            ->groupBy('articles.id')->get();
        $questions_created_counter = 0;
        $questions_count_counter = 0;
        $articles_created_counter = 0;
        $articles_count_counter = 0;

        $number_times_scroll = 2; //bundle quantity for page 

        function checkCollection($collection, $rawbundles, $counter)
        {
            while ($collection->contains('id', $rawbundles[$counter]->user_id)) {   // need filter by type
                $counter++;
            };
            return $counter;
        }

        $collection = collect();
        for ($i = 0; $i < $number_times_scroll; $i++) {
            for ($a = 0; $a < 6; $a++) {
                switch ($a) {
                    case (0):                        
                        $questions_created_counter = checkCollection($collection, $questions_by_created_at, $questions_created_counter); //new question
                        $collection->push($questions_by_created_at[$questions_created_counter]);
                        $questions_created_counter++;
                        break;
                    case (1):
                        $articles_count_counter = checkCollection($collection, $articles_by_counter, $articles_count_counter); //popular article
                        $collection->push($articles_by_counter[$articles_count_counter]);
                        $articles_count_counter++;
                        break;
                    case (2):
                        $articles_created_counter = checkCollection($collection, $articles_by_created_at, $articles_created_counter); //new article
                        $collection->push($articles_by_created_at[$articles_created_counter]);
                        $articles_created_counter++;
                        break;
                    case (3):
                        $questions_created_counter = checkCollection($collection, $questions_by_created_at, $questions_created_counter); //new question
                        $collection->push($questions_by_created_at[$questions_created_counter]);
                        $questions_created_counter++;
                        break;
                    case (4):
                        $articles_created_counter = checkCollection($collection, $articles_by_created_at, $articles_created_counter); //new article
                        $collection->push($articles_by_created_at[$articles_created_counter]);
                        $articles_created_counter++;
                        break;
                    case (5):
                        $questions_count_counter = checkCollection($collection, $questions_by_counter, $questions_count_counter); //popular question
                        $collection->push($questions_by_counter[$questions_count_counter]);
                        $questions_count_counter++;
                        break;
                }
            }
        }

        foreach ($collection as $item) {
            $item->abody = Str::limit($item->abody, 200);
            $item->created_at = humandate::lenta($item->created_at);
            $item->avatar =  $item->avatar ? User::find($item->avatar)->avatar_path : null;
        };

        $bundles = PaginationHelper::paginate($collection, 30);

        return Inertia::render('Welcome', [
            'bundles' => $bundles,
            'city' => $city,
            'mainoffers' => Uslugi::where('is_main', 1)->where('is_feed', 1)->get(['id', 'usl_name', 'url', 'file_path']),
            'secondoffers' => $secondoffers,
            'practice' => Article::where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(10)->get(),
            'reviews' => Review::inRandomOrder()->with('usluga')->get(),
            'reviewscount' => $reviewscount,
            'rating' => $rating,
            'auth' => Auth::user(),
            'user' => User::find(94),
            'usluga' => Uslugi::where('id', 1)->select('uslugis.url', 'uslugis.usl_name')->first(),            
        ]);
    }
}
