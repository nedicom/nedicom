<?php

namespace App\Helpers\Rating;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Article;
use App\Models\Questions;
use App\Models\Answer;
use App\Helpers\Rating\RatingCounter;
use Illuminate\Support\Facades\DB;

class LawyerRating
{
    public static function PostRating()
    {
        $array = [];

        if (Auth::user()) {
            $array[] = Auth::user()->id;
        } else {
            $array = User::has('HasArticles')->where('lawyer', 1)->pluck('id');
        }

        foreach ($array as $User) {
            $Ucur = Article::where('userid', '=', $User)->count();  //publication quantity of current user

            $Umax = DB::table('articles') //publication quantity of user with max pub
                ->select('userid', DB::raw('count(userid) as total'))
                ->groupBy('userid')
                ->orderBy('total', 'desc')
                ->limit(1)
                ->get();

            $Umax = $Umax[0]->total;

                if(Article::where('userid', '=',$User)->first()){

                    $Lastpub = Article::where('userid', '=',$User)->latest('created_at')->first()->created_at; //day when last buplication were created
            
                    $Daytotal = RatingCounter::DayConter($Ucur, $Umax); //total discount days

                    $Qpub = RatingCounter::Rating($Daytotal, $Lastpub);

                    RatingCounter::DBPostRecord($Daytotal, $Qpub, $User);    
                }
                else{
                    return 1;
                }
        }
    }

    public static function PracticeRating()
    {
        $array = [];

        if (Auth::user()) {
            $array[] = Auth::user()->id;
        } else {
            $array = User::has('HasPractice')->where('lawyer', 1)->pluck('id');
        }

        foreach ($array as $User) {
            $Ucur = Article::where('userid', '=', $User)->where('practice_file_path', '!=', null)->count();  //publication quantity of current user

            $Umax = DB::table('articles') //publication quantity of user with max pub
                ->select('userid', DB::raw('count(userid) as total'))
                ->where('practice_file_path', '!=', null)
                ->groupBy('userid')
                ->orderBy('total', 'desc')
                ->limit(1)
                ->get();

            $Umax = $Umax[0]->total;

            if(Article::where('userid', '=',$User)->first()){

            $Lastpub = Article::where('userid', '=',$User)->where('practice_file_path', '!=', null)->latest('created_at')->first()->created_at; //day when last buplication were created
       
            $Daytotal = RatingCounter::DayConter($Ucur, $Umax); //total discount days

            $Qpub = RatingCounter::Rating($Daytotal, $Lastpub);

            RatingCounter::DBPracticeRecord($Daytotal, $Qpub, $User);  
            }
            else{
                return 1;
            }      
        }
    }

    public static function QuestionRating()
    {
        $array = [];

        if (Auth::user()) {
            $array[] = Auth::user()->id;
        } else {
            $array = User::has('HasQuestion')->where('lawyer', 1)->pluck('id');
        }

        foreach ($array as $User) {
            $Ucur = Questions::where('user_id', '=', $User)->count();  //publication quantity of current user

            $Umax = DB::table('questions') //publication quantity of user with max pub
                ->select('user_id', DB::raw('count(user_id) as total'))
                ->groupBy('user_id')
                ->orderBy('total', 'desc')
                ->limit(1)
                ->get();

            $Umax = $Umax[0]->total;

            if(Questions::where('user_id', '=',$User)->first()){

            $Lastpub = Questions::where('user_id', '=',$User)->latest('created_at')->first()->updated_at; //day when last buplication were created

            $Daytotal = RatingCounter::DayConter($Ucur, $Umax); //total discount days

            $Qpub = RatingCounter::Rating($Daytotal, $Lastpub);

            RatingCounter::DBQuestionRecord($Daytotal, $Qpub, $User);
            }
            else{
                return 1;
            }        
        }
    }

    public static function AnswerRating()
    {
        $array = [];

        if (Auth::user()) {
            $array[] = Auth::user()->id;
        } else {
            $array = User::has('HasAnwers')->where('lawyer', 1)->pluck('id');
        }

        foreach ($array as $User) {
            $Ucur = Answer::where('users_id', '=', $User)->count();  //publication quantity of current user

            $Umax = DB::table('answers') //publication quantity of user with max pub
                ->select('users_id', DB::raw('count(users_id) as total'))
                ->groupBy('users_id')
                ->orderBy('total', 'desc')
                ->limit(1)
                ->get();

            $Umax = $Umax[0]->total;

            if(Answer::where('users_id', '=',$User)->first()){

            $Lastpub = Answer::where('users_id', '=',$User)->latest('created_at')->first()->updated_at; //day when last buplication were created

            $Daytotal = RatingCounter::DayConter($Ucur, $Umax); //total discount days

            $Qpub = RatingCounter::Rating($Daytotal, $Lastpub);

            RatingCounter::DBAnswernRecord($Daytotal, $Qpub, $User);        
        }
        else{
            return 1;
        }
    }
    }
}
