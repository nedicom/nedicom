<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Helpers\Rating\LawyerRating;
use Illuminate\Support\Facades\Auth;

class LawyerController extends Controller
{

    public function index()
    {
        return Inertia::render('Lawyers/Lawyers', [
            'lawyers' => User::where('lawyer', 1)->select('id', 'name', 'about', 'avatar_path')-> paginate(9),
            'auth' => Auth::user(), 
        ]);
    }

     public function lawyer($id){ 
        LawyerRating::PostRating();
        LawyerRating::PracticeRating();
        LawyerRating::QuestionRating();
        LawyerRating::AnswerRating();

        $user = User::where('id', '=', $id)->first();

        if(!$user->lawyer){
            return redirect()->route('Welcome');
        }

        return Inertia::render('Lawyers/Lawyer', [
            'lawyer' => $user,
            'specializationOne' => User::find($id)->lawyerSpecOne,
            'specializationTwo' => User::find($id)->lawyerSpecTwo,
            'specializationThree' => User::find($id)->lawyerSpecThree,
            'specialization' => User::find($id)->arrayspec,
            'articles' => Article::where('userid', $id)->where('practice_file_path', '=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'practice' => Article::where('userid', $id)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'countarticles' => Article::where('userid', $id)->count(), 
            'auth' => Auth::user(), 
        ]);
    }

}