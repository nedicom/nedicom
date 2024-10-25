<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {        
        return Inertia::render('Dashboard', [
            'articles' => Article::where('userid', '=', optional(Auth::user())->id)->paginate(6),
            'auth' => Auth::user(),
        ]);
        
    }
}