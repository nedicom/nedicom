<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Inertia\Inertia;

class AiController extends Controller
{
    
    public function ai()
    {
        return Inertia::render('Event', ['event' => $event])
    ->withViewData(['meta' => $event->meta]);
        //$generated_text= OpenAI::Answer($request->body); 
        //session(['aianswer' => $generated_text]);
    }
}