<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Answer;
use App\Models\User;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Helpers\Rating\LawyerRating;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class LawyerController extends Controller
{

    public function index()
    {
        return Inertia::render('Lawyers/Lawyers', [
            'lawyers' => User::where('lawyer', 1)->select('id', 'name', 'about', 'avatar_path')->paginate(9),
            'auth' => Auth::user(),
        ]);
    }

    public function lawyer($id)
    {
        LawyerRating::PostRating();
        LawyerRating::PracticeRating();
        LawyerRating::QuestionRating();
        LawyerRating::AnswerRating();

        $user = User::where('id', $id)->with('reviews')->with('cities')->withCount('reviews')
            ->withSum('reviews', 'rating')->first();

        if (!$user->lawyer) {
            return redirect()->route('Welcome');
        }

        $auth = Auth::user();

        if ($auth) {
            $auth->has_comment = ($user->reviews->where('user_id', $auth->id)->first()) ? true : false;
        }

        return Inertia::render('Lawyers/Lawyer', [
            'lawyer' => $user,            'articles' => Article::where('userid', $id)->where('practice_file_path', '=', null)->orderBy('updated_at', 'desc')->get(),
            'practice' => Article::where('userid', $id)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->get(),
            'anwswers' => Answer::where('users_id', $id)->orderBy('updated_at', 'desc')->with('Question')->get(),
            'countarticles' => Article::where('userid', $id)->count(),
            'auth' => $auth,
        ]);
    }
}
