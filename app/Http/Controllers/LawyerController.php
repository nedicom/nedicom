<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Helpers\Rating\LawyerRating;

class LawyerController extends Controller
{

    public function index()
    {
        return Inertia::render('Lawyers/Lawyers', [
            'lawyers' => User::where('lawyer', 1)->select('id', 'name', 'about', 'avatar_path')-> paginate(9),
        ]);
    }

     public function lawyer($id){ 
        LawyerRating::PostRating();
        LawyerRating::PracticeRating();
        LawyerRating::QuestionRating();
        LawyerRating::AnswerRating();
        //dd(Article::where('userid', $id)->orderBy('updated_at', 'desc')->pluck('header', 'description')->take(3));
        return Inertia::render('Lawyers/Lawyer', [
            'lawyer' => User::where('id', '=', $id)->first(),
            'specializationOne' => User::find($id)->lawyerSpecOne,
            'specializationTwo' => User::find($id)->lawyerSpecTwo,
            'specializationThree' => User::find($id)->lawyerSpecThree,
            'specialization' => User::find($id)->arrayspec,
            'articles' => Article::where('userid', $id)->where('practice_file_path', '=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'practice' => Article::where('userid', $id)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'countarticles' => Article::where('userid', $id)->count(), 
        ]);
    }

}