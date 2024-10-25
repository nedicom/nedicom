<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller{
    public function index(){       
        return Inertia::render('Home', [
            'title' => 'hometitle',
            'auth' => Auth::user(),
        ]); 
    }
}
