<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Questions;
use App\Models\User;
use App\Helpers\OpenAI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;


class AnswerController extends Controller
{
    public function post(Request $request){
        $Answer = new Answer;
        $Answer->body = $request->body;
        $Answer->questions_id = $request->questions_id;
        $Answer->parent_comment_id = $request->answer_id;
        $Answer->users_id = Auth::user()->id;
        $Answer->save();

        $question = Questions::where('id', '=', $request->questions_id)->first();
        $user = User::where('id', '=', $question->user_id)->first();
        
        if($user->id !== $Answer->users_id){
            $mailData = [
                "url" => "https://nedicom.ru/questions/".$question->url,
                "question" => $question->title,
            ];
            Mail::to($user->email)->send(new TestEmail($mailData));
        }
    }

    public function aiComment(Request $request){
        $Answer = new Answer;
        $Answer->body = OpenAI::Answer($request->body);
        $Answer->questions_id = $request->id;
        $Answer->users_id = 87;
        $Answer->save();
        //$url = $request->url;
        //return redirect()->route('questions.url', $url);
    }

    public function my()
    {
        return Inertia::render('Answers/MyAnswers', [
            'answers' => Answer::where('users_id', '=', Auth::user()->id)
            ->with('Question')
            ->paginate(9),
            'auth' => Auth::user(), 
        ]);
    }
}
