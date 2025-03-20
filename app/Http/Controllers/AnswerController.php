<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Article_comment;

use App\Models\Article;
use App\Models\Questions;
use App\Models\User;
use App\Helpers\OpenAI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;


class AnswerController extends Controller
{
    public function post(Request $request)
    {
        if ($request->type == "question") {
            $Answer = new Answer;
            $Answer->body = $request->body;
            $Answer->questions_id = $request->questions_id;
            $Answer->parent_comment_id = $request->answer_id;
            $Answer->users_id = Auth::user()->id;
            $Answer->save();

            $question = Questions::where('id', '=', $request->questions_id)->first();
            $user = User::where('id', '=', $question->user_id)->first();

            if ($user->id !== $Answer->users_id) {
                $mailData = [
                    "url" => "https://nedicom.ru/questions/" . $question->url,
                    "question" => $question->title,
                ];
                try{
                    Mail::to($user->email)->send(new TestEmail($mailData));
                }
                catch(\Exception $e){
                }                
            }
        }

        if ($request->type == "article") {
            $Answer = new Article_comment;
            $Answer->body = $request->body;
            $Answer->article_id = $request->article_id;
            $Answer->parent_comment_id = $request->answer_id;
            $Answer->users_id = Auth::user()->id;
            $Answer->save();
        }
    }

    public function aiComment(Request $request)
    {
        $Answer = new Answer;
        $Answer->body = OpenAI::Answer($request->body);
        $Answer->questions_id = $request->id;       
        $input = [1, 67, 68, 69, 87, 94, 95, 109, 150, 262];
        $rand_keys = array_rand($input);
        $Answer->users_id = $input[$rand_keys];
        $Answer->save();
        //$url = $request->url;
        //return redirect()->route('questions.url', $url);
    }

    public function ArticleComment(Request $request)
    {
        //dd($request);
        $article = Article_comment::where('article_id', $request->form['article_id'])->select('users_id')
        ->pluck('users_id')->toArray();
        $input = [1, 67, 68, 69, 87, 94, 95, 109, 150, 262];
        foreach($article as $key => $users_id){
            if(($key = array_search($users_id, $input)) !== false){
                unset($input[$key]);
            };
        };               
        $rand_keys = array_rand($input);
        $Answer = new Article_comment; 
        $Answer->users_id = $input[$rand_keys];     
        $Answer->article_id = $request->form['article_id'];           
        $Answer->body = OpenAI::Comment($request->form['article_body'], $request->form['comment_type']); 
        $Answer->save();
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

    public function ArticleCommentDelete(int $id)
    {
        $article_comment = Article_comment::where('id', $id)->first();
        if (Auth::user()->isadmin == 1) {
            try{
                $article_comment->delete();  
                return redirect()->back()->with('success', 'Комментарий удален');
            }
            catch(\Exception $e){
                return redirect()->back()->with('success', 'Комментарий не был удален');
            } 
        } else {
            return redirect()->back()->with('success', 'Удалять вопросы могут только собственники или админ.');
        }
    }

    public function AnswerDelete(int $id)
    {
        $answer = Answer::where('id', $id)->first();        
        if (Auth::user()->isadmin == 1) {
            try{
                $answer->delete();
                return redirect()->back()->with('success', 'Комментарий удален');
            }
            catch(\Exception $e){
                return redirect()->back()->with('success', 'Комментарий не был удален');
            } 
        } else {
            return redirect()->back()->with('success', 'Удалять вопросы могут только собственники или админ.');
        }
    }   
}
