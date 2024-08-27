<?php

namespace App\Http\Controllers;

use App\Models\Objavleniya;
use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Helpers\Translate;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Uslugi;


class ObjavleniyaController extends Controller
{

    public function index()
    {
        return Inertia::render('Uslugi/Uslugi', [
            'uslugi' => Objavleniya::paginate(18),
        ]);
    }

    public function show($url){ 
        $id = Objavleniya::where('url', '=', $url)->first()->id;
        return Inertia::render('Objavleniya/Objavleniye', [
            'usluga' => Objavleniya::where('url', '=', $url)->first(),
            'user' => Auth::user(),
            'lawyers' => User::where('speciality_one_id', '=', $id)->orderBy('name', 'asc')->get()->take(3),
            'practice' => Article::where('usluga_id', $id)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'firstlawyer' => Objavleniya::where('url', '=', $url)->first()->firstlawyer,
        ]);
    }

    public function formadd()
    {       
        return Inertia::render('Uslugi/Add'); 
    }

    public function create(Request $request){
        $usluga = new Uslugi;
        $usluga->usl_name = $request->header;
        $usluga->usl_desc = $request->description;

            $usluga->longdescription = 'Это детальное описание услуги';
            $usluga->preimushestvo1 = '600+ дел';
            $usluga->preimushestvo2 = 'Более 10 лет практики';
            $usluga->preimushestvo3 = 'Аналитический подход к решению задачи';
            $usluga->address = 'Респ. Крым, г. Симферополь, ул. Долгоруковская, 5';
            $usluga->phone = '+79788838978';
            $usluga->maps = 'https://yandex.ru/map-widget/v1/?um=constructor%3Af656829d54430f3c7f23ae0313146d2525f75dfbaffa3e083f51d06d98a33614&amp;source=constructor';

        $url = Translate::translit($request->header);        
        $usluga->url =  $url;
        $usluga->save();
        return redirect()->route('uslugi');
    }

    public function edit(string $url)
    {
        return Inertia::render('Uslugi/Edit', [
            'uslugi' => Uslugi::where('id', '=', $url)->first(),
        ],  
    );
    }

    public function update(Request $request)
    {   
        $id = $request->id;
        $usluga = Uslugi::find($id);
            $usluga->usl_name = $request->header;
            $usluga->usl_desc = $request->description;
            $usluga->longdescription = $request->longdescription;
            $usluga->preimushestvo1 = $request->preimushestvo1;
            $usluga->preimushestvo2 = $request->preimushestvo2;
            $usluga->preimushestvo3 = $request->preimushestvo3;
            $usluga->phone = $request->phone;
            $usluga->address = $request->address;
            $usluga->maps = $request->maps;
        $url = Translate::translit($request->header);        
        $usluga->url =  $url;
        $usluga->save();
        return redirect()->route('uslugi.url', $url);     
    }    
}