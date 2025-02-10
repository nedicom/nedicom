<?php

namespace App\Http\Controllers\Questions;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Casts\humandate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Uslugi;
use App\Helpers\CitySet;

class QuestionuslugaController
{
    public static function Questioncity($usluga)
    {
        $city = CitySet::CityGet(false);

        $user_id = Auth::user() ? Auth::user()->id : null;

        $uslugi = Uslugi::where('is_main', 1)->where('is_feed', 1)->select('id', 'usl_name', 'url')->get();

        $bundles = DB::table('questions')
            ->where('usluga', $usluga->id)
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
            'h1' => 'Вопросы в категории - "' . $usluga->usl_name . '"',
            'city' => $city,
            'usluga' => $usluga,
            'uslugi' => $uslugi,
        ]);
    }
}
