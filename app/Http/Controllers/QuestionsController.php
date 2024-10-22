<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Questions;
use App\Models\Answer;
use App\Models\Uslugi;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Translate;
use App\Helpers\OpenAI;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class QuestionsController extends Controller
{
    public function index()
    {
        return Inertia::render('Questions/Questions', [
            'questions' => Questions::orderBy('created_at', 'desc')->paginate(9),
        ]);
    }

    public function myQuestions()
    {
        return Inertia::render('Questions/MyQuestions', [
            'questions' => Questions::where('user_id', '=', Auth::user()->id)->select('id', 'title', 'body', 'url')->withCount('QuantityAns')->orderBy('updated_at', 'desc')->paginate(9),
        ]);
    }

    public function questionsURL($url)
    {
        if (!app()->environment('local')) {
            $question = Questions::where('url', $url)->firstOrFail();
        }
        else{
            $question = Questions::where('url', $url)->first();
        }

        $authid = null;
        if (Auth::user()) {
            $authid = Auth::user()->id;
        }
        return Inertia::render('Questions/Question', [
            'question' => Questions::where('id', $question->id)->withCount('QuantityAns')->with('User')->with('Usluga')->first(),
            'uslugi' => Uslugi::where('is_main', 1)->where('is_feed', 1)->select(['id', 'usl_name'])->get(),
            'answers' => Answer::where('questions_id', $question->id)
                ->with('UserAns')
                ->with('subcomments')
                ->get(),
            'aianswer' => Inertia::lazy(fn() => OpenAI::Answer($question->body)),
            'authid' => $authid,
            //'countanswer' => Article::where('userid', $id)->count(), 
        ]);
    }

    public function getAIAnswer($url)
    {
        //$id = Questions::where('url', '=', $url)->pluck('id')->first();  
        Inertia::share('appName', 'this testus');
    }

    public function setUsl(Request $request)
    {
        $Question = Questions::findOrFail($request->id);
        $Question->usluga = $request->usluga;
        $Question->save();
    }

    public function getLawyer(Request $request)
    {
        session(['getLawyer' => 1]);
    }


    public function questionsNonAuth()
    {
        return Inertia::render('Questions/QuestionNA', [
            'ownercookie' => [
                'questionTitle' => session()->get(key: 'questionTitle'),
                'questionBody' => session()->get(key: 'questionBody'),
            ]
        ]);
    }


    public function questionAdd()
    {
        return Inertia::render('Questions/Add', [
            'lawyers' => User::where('lawyer', 1)->inRandomOrder()->limit(5)->get(),
            'SliderQ' => Questions::limit(20)->withCount('QuantityAns')->orderBy('updated_at', 'desc')->get(),
        ]);
    }

    public function post(Request $request)
    {
        $Question = new Questions;
        $Question->title = $request->header;
        $Question->body = $request->body;
        $url = Translate::translit($request->header);
        $Question->url = $url;
        if (Auth::user()) {
            $Question->user_id = Auth::user()->id;
            $Question->save();
            return redirect()->route('questions.url', $url);
        }

        //$generated_text= OpenAI::Answer($request->body); 

        $generated_text = 'тест';

        session(['questionTitle' => $Question->title, 'questionBody' => $request->body, 'aianswer' => $generated_text]);
        return redirect()->route('questions.nonauth');
    }

    public function ai()
    {
        return Inertia::render('Questions/QuestionNA', [
            'test' => 'test'
        ]);
        //return OpenAI::Answer('привет, расскажи кто ты?'); 
    }



    public function delete(int $id)
    {
        if (Auth::user()->id == Questions::find($id)->user_id) {
            Questions::find($id)->delete();
            return redirect()->back()->with('success', 'Все в порядке, вопрос удален');
        } else {
            return redirect()->back()->with('success', 'Удалять вопросы могут только собственники или админ.');
        }
    }
}
