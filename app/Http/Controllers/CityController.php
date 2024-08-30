<?php

namespace App\Http\Controllers;

use App\Models\cities;
use App\Models\Uslugi;

use Illuminate\Http\Request;

use Inertia\Inertia;

class CityController extends Controller
{
    public function showUslugiCity($url,  Request $request)
    {
        return Inertia::render('Uslugi/City', [
            'uslugi' => Uslugi::where('sity', 1)->paginate(12),
        ]);
    }
}
