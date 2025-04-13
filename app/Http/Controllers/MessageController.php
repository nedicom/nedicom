<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dialogue;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\OpenAIDialogue;
use App\Helpers\PostDataOpenAI;
use Inertia\Inertia;

class MessageController extends Controller
{


    public function messages()
    {
        return Inertia::render('Admin/Messages/Messages', [
            'messages' => Dialogue::orderBy('created_at', 'desc')->paginate(99),
            'auth' => Auth::user(), 
        ]);
    }

    //dialogue page with user
    public function lawyer_mess($id)
    {
        $array = [];
        $dialogue_id = null;
        if (Auth::user()) {
            $current_dialogue = Dialogue::where('user_id', Auth::user()->id)->where('lawyer_id', $id)->first();
        } else if (session()->get('dialogue') && !Auth::user()) {            
            $current_dialogue = Dialogue::where('id', session()->get('dialogue'))->where('lawyer_id', $id)->first();
        } else {
            $current_dialogue = false;
        }
        if ($current_dialogue) {
            $array = json_decode($current_dialogue->json, JSON_FORCE_OBJECT);
            $dialogue_id = $current_dialogue->id;
        }

        return Inertia::render('Message/Message', [
            'messages' => $array,
            'dialogue_id' => $dialogue_id,
            'lawyer' => User::find($id),
        ]);
    }

    public function lawyer_send(Request $request)
    {
        if ($request->dlg_id) {
            $message = Dialogue::where('id', $request->dlg_id)->first();
            $array = json_decode($message->json, JSON_FORCE_OBJECT);
            $array[] = ['user_message' => $request->mess];
        } 
        else if (session()->get('dialogue') && !Auth::user()){
            $message = Dialogue::where('id', session()->get('dialogue'))
            ->where('lawyer_id', $request->lawyer_id)
            ->first();
            $array = json_decode($message->json, JSON_FORCE_OBJECT);
            $array[] = ['user_message' => $request->mess];
        }
        else {
            $message = new Dialogue;
            $array = array(array('user_message' => $request->mess));
        }
        $message->json = json_encode($array);
        Auth::user() ? $message->user_id = Auth::user()->id : null;
        $message->lawyer_id = $request->lawyer_id;
        $message->location = $request->location;
        $message->location_header = $request->location_header;
        $message->save();
        session(['dialogue' => $message->id]);
        return ($array);
    }


    public function lawyer_sent(Request $request)
    {
        //sleep(1);
        if ($request->dlg_id) { //user auth
            $message = Dialogue::find($request->dlg_id);
        } else { //anonim
            $message = Dialogue::find(session()->get('dialogue'));
        }
        $array = json_decode($message->json, JSON_FORCE_OBJECT);
        $openAi = OpenAIDialogue::Answer($request->mess, $array);
        $gotit = strripos($openAi, 'challenge');
        if ($gotit !== false) {
            $array[] = ['ai_message' => "Спасибо, я свяжусь с Вами, как освобожусь. Запишите номер по которому можно связаться со мной 89788838978"];
            PostDataOpenAI::PostData($message->json, $request->mess);
        } else {
            $array[] = ['ai_message' => $openAi];
        }
        $message->json = json_encode($array);
        $message->save();
        return ($array);
    }


    public function lawyer_get($id)
    {
        if (Dialogue::where('user_id', Auth::user()->id)->where('lawyer_id', $id)->first()) {
            $message = Dialogue::where('user_id', Auth::user()->id)->where('lawyer_id', $id)->first();
            $array = json_decode($message->json, JSON_FORCE_OBJECT);
        }
        return ($array);
    }


    public function getdata(Request $request)
    {
        if ($request->session()->get('dialogue')) {
            $message = Dialogue::find($request->session()->get('dialogue'));
            $array = json_decode($message->json, JSON_FORCE_OBJECT);
        }
        return ($array);
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
        return ($array);
    }

    public function sent(Request $request)
    {
        sleep(3);
        $message = Dialogue::find($request->session()->get('dialogue'));
        $array = json_decode($message->json, JSON_FORCE_OBJECT);
        $openAi = OpenAIDialogue::Answer($request->mess, $array);
        //$openAi == 'Ok.' ? $array[] = ['ai_message' => "Спасибо, я свяжусь с Вами немного позже, как освобожусь."] : $array[] = ['ai_message' => $openAi];
        $gotit = strripos($openAi, 'challenge');
        if ($gotit !== false) {
            $array[] = ['ai_message' => "Спасибо, я свяжусь с Вами немного позже, как освобожусь. Запишите и мой номер тоже 89788838978"];
            PostDataOpenAI::PostData($message->json, $request->mess);
        } else {
            $array[] = ['ai_message' => $openAi];
        }
        $message->json = json_encode($array);
        $message->save();
        return ($array);
    }
}
