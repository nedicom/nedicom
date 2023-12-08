<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Questions;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Helpers\Translate;
use App\Helpers\OpenAI;
use App\Models\User;

class QuestionsController extends Controller
{
    public function index()
    {
        //dd(Article::paginate(3)->toArray());
        return Inertia::render('Questions/Questions', [
            'questions' => Questions::paginate(9),
        ]);
    }

    public function myQuestions()
    {
        //dd(Article::paginate(3)->toArray());
        return Inertia::render('Questions/MyQuestions', [
            'questions' => Questions::paginate(9),
        ]);
    }

    public function questionsURL($url){ 
        $id = Questions::where('url', '=', $url)->pluck('id')->first();  
        return Inertia::render('Questions/Question', [
            'question' => Questions::where('id', '=', $id)->first(),
            'answers' => Answer::where('questions_id', '=', $id)->with('UserAns')->get(),
            /*'ownercookie' => [
                'questionTitle' => session()->get(key: 'questionTitle'), 
                'questionBody' => session()->get(key: 'questionBody'),
                'aianswer' => session()->get(key: 'aianswer'),
            ], */
        ]);
    }

    public function questionsNonAuth(){  
        return Inertia::render('Questions/QuestionNA', [
            'ownercookie' => [
                'questionTitle' => session()->get(key: 'questionTitle'), 
                'questionBody' => session()->get(key: 'questionBody'),
                'aianswer' => session()->get(key: 'aianswer'),                
            ], 
            'test' => 'ии отвечает',
        ]);
    }
    

    public function questionAdd(){  
        return Inertia::render('Questions/Add', [
            'lawyers' => User::where('lawyer', 1)->inRandomOrder()->limit(5)->get(),
        ]);
    }

    public function post(Request $request){
        $Question = new Questions;     
        $Question->title = $request->header;
        $Question->body = $request->body;
        $url = Translate::translit($request->header);        
        $Question->url = $url;
            if(Auth::user()){
                $Question->user_id = Auth::user()->id;
                $Question->save();
                return redirect()->route('questions.url', $url);
            }
            
            //$generated_text= OpenAI::Answer($request->body); 

            $generated_text= 'тест'; 

            session(['questionTitle' => $Question->title, 'questionBody' => $request->body, 'aianswer' => $generated_text]);
                return redirect()->route('questions.nonauth');   
    }

    public function ai(){
        return Inertia::render('Questions/QuestionNA', [
            'test' => 'test'
        ]);
       //return OpenAI::Answer('привет, расскажи кто ты?'); 
    }

    

    public function delete(int $id)
    {
        if(Auth::user()->id == Questions::find($id)->user_id){
            Questions::find($id)->delete();
            return redirect()->back()->with('success', 'Все в порядке, вопрос удален');
        }
        else{
            return redirect()->back()->with('success', 'Удалять вопросы могут только собственники или админ.');
        }     
    }
}
