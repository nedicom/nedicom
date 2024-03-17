<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dialogue;
use Illuminate\Support\Facades\Auth;
use App\Helpers\OpenAIDialogue;
use App\Helpers\PostDataOpenAI;
use Inertia\Inertia;

class MessageController extends Controller
{


    public function messages()
    {
        return Inertia::render('Admin/Messages/Messages', [
            'messages' => Dialogue::orderBy('created_at', 'desc')->paginate(99), ]);
    }

    public function getdata(Request $request)
    {
       if ($request->session()->get('dialogue')) {
            $message = Dialogue::find($request->session()->get('dialogue'));
            $array = json_decode($message->json, JSON_FORCE_OBJECT);
        }
        return($array);
    }


    public function send(Request $request)
    {
       if ($request->session()->get('dialogue')) {
            $message = Dialogue::find($request->session()->get('dialogue'));
            $array = json_decode($message->json, JSON_FORCE_OBJECT);
            $array[] = ['user_message' => $request->mess];
        } else {
            $message = new Dialogue;
            $array = array(array('user_message' => $request->mess));
        }
        $message->json = json_encode($array);
        Auth::user() ? $message->user_id = Auth::user()->id : null;
        $message->location = $request->location;
        $message->location_header = $request->location_header;
        $message->save();
        session(['dialogue' => $message->id]);
        return($array);
    }

    public function sent(Request $request)
    {
        sleep(1);
        $message = Dialogue::find($request->session()->get('dialogue'));
        $array = json_decode($message->json, JSON_FORCE_OBJECT); 
        $openAi = OpenAIDialogue::Answer($request->mess, $array);
        //$openAi == 'Ok.' ? $array[] = ['ai_message' => "Спасибо, я свяжусь с Вами немного позже, как освобожусь."] : $array[] = ['ai_message' => $openAi];
        $gotit = strripos($openAi, 'challenge');
        if($gotit !== false){
            $array[] = ['ai_message' => "Спасибо, я свяжусь с Вами немного позже, как освобожусь. Запишите и мой номер тоже 89788838978"];            
            PostDataOpenAI::PostData($message->json, $request->mess);
        } 
        else{
            $array[] = ['ai_message' => $openAi];
        } 
        $message->json = json_encode($array);
        $message->save();
        return($array);
    }


}