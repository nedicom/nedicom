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

use App\Casts\humandate;

class QuestionsController extends Controller
{
    public function index()
    {
        return Inertia::render('Questions/Questions', [
            'questions' => Questions::orderBy('created_at', 'desc')->paginate(9),
            'auth' => Auth::user(),
        ]);
    }

    public function myQuestions()
    {
        return Inertia::render('Questions/MyQuestions', [
            'questions' => Questions::where('user_id', '=', Auth::user()->id)->select('id', 'title', 'body', 'url')->withCount('QuantityAns')->orderBy('updated_at', 'desc')->paginate(9),
            'auth' => Auth::user(),
        ]);
    }

    public function questionsURL($url)
    {
        $question = Questions::where('url', $url)->firstOrFail();

        DB::table('questions')->where('questions.url', $url)->increment('counter', 1);


        $user_id = Auth::user() ? Auth::user()->id : null;

        $question = DB::table('questions')
            ->leftjoin('users', 'questions.user_id', '=', 'users.id')
            ->leftjoin('answers', 'questions.id', '=', 'answers.questions_id')
            ->leftjoin('uslugis', 'questions.usluga', '=', 'uslugis.id')
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
                'uslugis.usl_name as uslugis_usl_name',
                'uslugis.url as uslugis_url',
                DB::raw('count(*) as comment_count'),
                'bundles_socials.likes as user_like',
                'bundles_socials.bookmarks as user_bookmark',
                'bundles_socials.shares as user_share',
            )
            ->selectRaw('questions.url * ? AS type', [''])
            ->groupBy('id')
            ->orderByDesc('created_at')
            ->first();

        $question->created_at = humandate::lenta($question->created_at);
        $question->avatar =  $question->avatar ? User::find($question->avatar)->avatar_path : null;


        return Inertia::render('Questions/Question', [
            'question' => $question,
            'uslugi' => Uslugi::where('is_main', 1)->where('is_feed', 1)->select(['id', 'usl_name'])->get(),
            'answers' => Answer::where('questions_id', $question->id)
                ->with('UserAns')
                ->with('subcomments')
                ->get(),
            'aianswer' => Inertia::lazy(fn() => OpenAI::Answer($question->body)),
            'authid' => (Auth::user()) ? Auth::user()->id : null,
            'auth' => (Auth::user()) ? Auth::user() : null,
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
            ],
            'auth' => Auth::user(),
        ]);
    }

    public function similar($url)
    {
        $url = preg_split('/\,/', $url);
        if (count($url) == 2) {
            $questions = Questions::limit(20)->where('title', 'like', '%' . $url[0] . '%')
                ->orwhere('title', 'like', '%' . $url[1] . '%')
                ->orwhere('body', 'like', '%' . $url[0] . '%' . $url[1])
                ->withCount('QuantityAns')->orderBy('updated_at', 'desc')
                ->get();
            $questions->map(function ($item) {
                $item['status'] = 'new';
                return $item;
            });
            if (count($questions) == 0) {
                $questions = Questions::limit(20)->withCount('QuantityAns')->orderBy('updated_at', 'desc')->get();
            }
        } else {
            $questions = Questions::limit(20)->withCount('QuantityAns')->orderBy('updated_at', 'desc')->get();
        }
        return $questions;
    }

    public function questionAdd(Request $request)
    {
        $questions = Questions::limit(20)->withCount('QuantityAns')->orderBy('updated_at', 'desc')->get();

        return Inertia::render('Questions/Add', [
            'lawyers' => User::where('lawyer', 1)->where('avatar_path', '!=', '/storage/default/avatar.webp')->inRandomOrder()->limit(5)->get(),
            'SliderQ' => $questions,
            'auth' => Auth::user(),
            'filters' => $request->all(),
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
            'test' => 'test',
            'auth' => Auth::user(),
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
