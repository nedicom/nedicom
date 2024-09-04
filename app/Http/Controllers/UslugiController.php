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
use App\Models\Review;
use App\Models\cities;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class UslugiController extends Controller
{

    public function index()
    {
        return Inertia::render('Uslugi/Uslugi', [
            'uslugi' => Uslugi::where('is_main', '=', 1)->paginate(12),
        ]);
    }


    public function show($url,  Request $request)
    {
        $usluga = Uslugi::where('url', '=', $url)->first();
        $id = $usluga->id;
        $mainid = $usluga->main_usluga_id;

        if (!$mainid) {
            $mainid = $id;
        }

        if (Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->count() !== 0) {
            $reviews = Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->orderBy('id', 'desc')->get();
            $reviewscount = Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->count();
            $rating = Review::select('rating')->where('usl_id', $id)->orWhere('usl_id', $mainid)->sum('rating');
        } else {
            $reviews = Review::orderBy('id', 'desc')->get();
            $reviewscount = Review::count();
            $rating = Review::sum('rating');
        }

        $rating =  round($rating / $reviewscount, 1);

        $user_id = Uslugi::where('url', '=', $url)->first()->user_id;

        //views
        if ($usluga->is_main) {
            $view = 'Uslugi/Main';
        } else {
            $view = 'Uslugi/Usluga';
        }

        //data
        return Inertia::render($view, [
            'usluga' => Uslugi::where('url', $url)->with('cities')->first(),
            'secondUslugi' => Uslugi::where('main_usluga_id', $id)->where('is_second', '!=', 0)->get(),
            'uslugi' => Uslugi::where('second_usluga_id', $id)->with('cities')->get(),
            'main_usluga' => Uslugi::where('id', $mainid)->where('is_main', $mainid)->first(['id', 'usl_name', 'url']),
            'user' => Auth::user(),
            'lawyers' => User::where('speciality_one_id', '=', $id)->orderBy('name', 'asc')->get()->take(3),
            'practice' => Article::where('usluga_id', $mainid)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'firstlawyer' => User::where('id', $user_id)->get(),
            'reviews' => $reviews,
            'reviewscount' => $reviewscount,
            'rating' => $rating,
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);
    }


    public function showsecond($main_usluga, $second_usluga, Request $request)
    {
        $usluga = Uslugi::where('url', '=', $second_usluga)->first();
        $id = $usluga->id;
        $mainid = $usluga->main_usluga_id;

        if (!$mainid) {
            $mainid = $id;
        }

        if (Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->count() !== 0) {
            $reviews = Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->orderBy('id', 'desc')->get();
            $reviewscount = Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->count();
            $rating = Review::select('rating')->where('usl_id', $id)->orWhere('usl_id', $mainid)->sum('rating');
        } else {
            $reviews = Review::orderBy('id', 'desc')->get();
            $reviewscount = Review::count();
            $rating = Review::sum('rating');
        }

        $rating =  round($rating / $reviewscount, 1);

        $user_id = Uslugi::where('url', '=', $second_usluga)->first()->user_id;

        //data
        return Inertia::render('Uslugi/Second', [
            'usluga' => Uslugi::where('url', $second_usluga)->with('cities')->with('main')->first(),
            'uslugi' => Uslugi::where('second_usluga_id', $id)->with('cities')->get(),
            'main_usluga' => Uslugi::where('id', $mainid)->where('is_main', $mainid)->first(['id', 'usl_name', 'url']),
            'user' => Auth::user(),
            'lawyers' => User::where('speciality_one_id', '=', $id)->orderBy('name', 'asc')->get()->take(3),
            'practice' => Article::where('usluga_id', $mainid)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'firstlawyer' => User::where('id', $user_id)->get(),
            'reviews' => $reviews,
            'reviewscount' => $reviewscount,
            'rating' => $rating,
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);
    }



    public function showcanonical($main_usluga, $second_usluga, $city, $url,  Request $request)
    {

        $usluga = Uslugi::where('url', '=', $url)->first();

        $id = $usluga->id;
        $mainid = $usluga->main_usluga_id;

        if (!$mainid) {
            $mainid = $id;
        }

        if (Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->count() !== 0) {
            $reviews = Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->orderBy('id', 'desc')->get();
            $reviewscount = Review::where('usl_id', $id)->orWhere('usl_id', $mainid)->count();
            $rating = Review::select('rating')->where('usl_id', $id)->orWhere('usl_id', $mainid)->sum('rating');
        } else {
            $reviews = Review::orderBy('id', 'desc')->get();
            $reviewscount = Review::count();
            $rating = Review::sum('rating');
        }

        $rating =  round($rating / $reviewscount, 1);

        $user_id = Uslugi::where('url', '=', $url)->first()->user_id;
        return Inertia::render('Uslugi/Usluga', [
            'usluga' => Uslugi::where('url', '=', $url)->first(),
            'user' => Auth::user(),
            'lawyers' => User::where('speciality_one_id', '=', $id)->orderBy('name', 'asc')->get()->take(3),
            'practice' => Article::where('usluga_id', $mainid)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'firstlawyer' => User::where('id', $user_id)->get(),
            'reviews' => $reviews,
            'reviewscount' => $reviewscount,
            'rating' => $rating,
            'main_usluga' => Uslugi::where('id', $usluga->main_usluga_id)->first(['id', 'usl_name', 'url']),
            'second_usluga' => Uslugi::where('id', $usluga->second_usluga_id)->first(['id', 'usl_name', 'url']),
            'city' => cities::where('id', $usluga->sity)->first(),
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);
    }

    public function formadd()
    {
        return Inertia::render('Uslugi/Add');
    }

    public function create(Request $request)
    {
        $usluga = new Uslugi;
        $usluga->usl_name = $request->header;
        $usluga->usl_desc = $request->description;
        $usluga->user_id = Auth::id();

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

    public function edit(string $url, Request $request)
    {
        return Inertia::render(
            'Uslugi/Edit',
            [
                'uslugi' => Uslugi::where('id', '=', $url)->first(),
                'all_uslugi' => Uslugi::where('is_main', '=', 1)->select('id', 'usl_name')->get(),
                'second_uslugi' => Uslugi::where('is_second', 1)->select('id', 'usl_name', 'main_usluga_id')
                    ->get()->groupBy('main_usluga_id'),
                'user' => Auth::user(),
                'flash' => ['message' => $request->session()->get(key: 'message')],
                'cities' => cities::all(),
            ],
        );
    }

    public function update(Request $request)
    {
        $id = $request->ids;
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
        $usluga->popular_question = $request->popular;
        $usluga->expirience = $request->expirience;
        $usluga->price = $request->price;


        if ($request->second_usluga_id) {
            $usluga->second_usluga_id = $request->second_usluga_id;
        }


        if ($request->sity) {
            $usluga->sity = $request->sity;
        }

        if ($request->main_usluga_id) {
            $usluga->is_main = false;
            $usluga->main_usluga_id = $request->main_usluga_id;
        }

        if ($request->is_main) {
            $usluga->is_main = $request->is_main;
            $usluga->is_second = null;
            $usluga->main_usluga_id = $id;
        }

        if ($request->is_second) {
            $usluga->is_second = $request->is_second;
            $usluga->is_main = null;
            $usluga->second_usluga_id = null;
        }

        if ($request->is_feed) {
            $usluga->is_feed = $request->is_feed;
        }

        $url = Translate::translit($request->header);
        $checkurl = Checkurl::chkurl($url, 'usluga');

        if ($checkurl != $usluga->url) {
            $usluga->url =  $checkurl;
        }

        $usluga->save();
        return redirect()->route('uslugi.url', $usluga->url)->with('message', 'Обновлено успешно');
    }

    public function useruslugi(Request $request)
    {
        $id = Auth::id();
        $query = Uslugi::query()->where('user_id', '=', $id);

        if ($request->has('search')) {
   
            $query = $query->filter($request->all('search'));
        } else {
            $query = Uslugi::where('user_id', '=', $id);
        }

        $uslugi = $query->orderBy('id')->paginate(12);

        return Inertia::render('Uslugi/Byuser', [
            'filters' => $request->all('search'),
            'uslugi' =>  $uslugi,
        ]);
    }

    public function delete(int $id)
    {
        if ($usluga = Uslugi::find($id)) {
            $usluga->delete();

            return redirect()->route('uslugi.user');
        }

        abort(404);
    }
}
