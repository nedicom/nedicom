<?php

namespace App\Http\Controllers;

use App\Models\Uslugi;
use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Helpers\Translate;
use App\Helpers\Checkurl;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UslugiController extends Controller
{

    public function index()
    {
        return Inertia::render('Uslugi/Uslugi', [
            'uslugi' => Uslugi::where('is_main', '=', 1)->paginate(12),
        ]);
    }

    public function show($url, Request $request){ 
        //dd($request);
        $id = Uslugi::where('url', '=', $url)->first()->id;
            $main_usluga_id = Uslugi::where('url', '=', $url)->first()->main_usluga_id;
            if(!$main_usluga_id){
                $main_usluga_id = $id;
            }
        // dd($main_usluga_id);
        return Inertia::render('Uslugi/Usluga', [
            'usluga' => Uslugi::where('url', '=', $url)->first(),
            'user' => Auth::user(),
            'lawyers' => User::where('speciality_one_id', '=', $id)->orderBy('name', 'asc')->get()->take(3),
            'practice' => Article::where('usluga_id', $main_usluga_id)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'firstlawyer' => Uslugi::where('url', '=', $url)->first()->firstlawyer,
            'flash' => ['message' => $request->session()->get(key: 'message')], 
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

        $url = Translate::translit($request->header);
        $checkurl = Checkurl::chkurl($url, 'usluga');       
        $usluga->url =  $checkurl;
        $usluga->save();
        return redirect()->route('uslugi.url', ['url' => $checkurl])->with('message', 'Услуга создана успешно.');
    }

    public function edit(string $url)
    {
        return Inertia::render('Uslugi/Edit', [
            'uslugi' => Uslugi::where('id', '=', $url)->first(),
            'all_uslugi' => Uslugi::where('is_main', '=', 1)->get(),
            'user' => Auth::user(),
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

                if($request->main_usluga_id){  
                    $usluga->is_main = false;                  
                    $usluga->main_usluga_id = $request->main_usluga_id;                    
                }

                if($request->is_main){
                    $usluga->is_main = $request->is_main;
                    $usluga->main_usluga_id = $id;
                }

        $usluga->save();
        return redirect()->route('uslugi.url', $usluga->url)->with('message', 'Обновлено успешно');  
    }    
}