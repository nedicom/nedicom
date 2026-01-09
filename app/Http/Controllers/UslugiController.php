<?php

namespace App\Http\Controllers;

use App\Models\Uslugi;
use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Helpers\Translate;
use App\Helpers\Checkurl;
use App\Helpers\TgSend;
use App\Helpers\GetUslugi;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Review;
use App\Models\cities;
use App\Models\price;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Helpers\CitySet;
use App\Helpers\UslugaSet;

use Illuminate\Database\Eloquent\Builder;

class UslugiController extends Controller
{

    public function index(Request $request) // http://nedicom.ru/uslugi/
    {
        $cities = [];
        if ($request->city) {
            $cities = cities::when($request->city ?? null, function ($query, $city) {
                return $query->where('title', 'like', '%' . $city . '%')->get();
            });
        }

        $city = CitySet::CityGet(false);


        $category = Uslugi::where('is_main', 1)
            ->where('is_feed', 1)
            ->with(['mainhasoffer' => function ($query) {
                if (session()->get('cityid')) {
                    $query->where('sity', session()->get('cityid'));
                }
            }])
            ->with('mainhassecond')
            ->get();

        $user_id = Auth::user() ? Auth::user()->id : null;

        $uslugi = GetUslugi::GetUsl($user_id, $city, null, null);

        return Inertia::render('Uslugi/Uslugi', [
            'city' => $city,
            'main_usluga' => collect([
                'url' => '0',
                'usl_name' => 'Услуги юристов',
                'usl_desc' => 'Услуги юристов: цены, отзывы, адреса.',
                'file_path' => 'storage/images/landing/main/1280on600.webp',
            ]),
            'uslugi' => $uslugi,
            'count' => $uslugi->count(),
            'max' => $uslugi->max('price'),
            'min' => $uslugi->min('price'),
            'sumrating' => ($uslugi->sum('review_sum_rating') + $uslugi->sum('userreview_sum_rating')),
            'countrating' => ($uslugi->sum('review_count') + $uslugi->sum('userreview_count')),
            'cities' => $cities,
            'allsities' => Uslugi::where('is_main', '!=', 1)
                ->where('is_second', null)
                ->where('is_feed', 1)->get(['id', 'sity'])->unique('cities'),
            'category' => $category,
            'routeurl' => '/uslugi',
            'backendurl' => $request->path(),
            'auth' => Auth::user(),
            'getLawyer' => session()->get('questionTitle') ? session()->get('questionTitle') : '0',
            'active' => true,
        ]);
    }


    public function show($url, Request $request) // http://nedicom.ru/uslugi/city
    {
        //check city in url  
        if (cities::where('url', $url)->first()) {
            $cities = [];
            if ($request->city) {
                $cities = cities::when($request->city ?? null, function ($query, $city) {
                    return $query->where('title', 'like', '%' . $city . '%')->get();
                });
            }

            $city = CitySet::CitySet($request, $url, false);

            $category = Uslugi::where('is_main', 1)
                ->where('is_feed', 1)
                ->with(['mainhasoffer' => function ($query) {
                    if (session()->get('cityid')) {
                        $query->where('sity', session()->get('cityid'));
                    }
                }])
                ->with('mainhassecond')
                ->get();

            $user_id = Auth::user() ? Auth::user()->id : null;

            $uslugi = GetUslugi::GetUsl($user_id, $city, null, null);

            return Inertia::render('Uslugi/Uslugi', [
                'city' => $city,
                'main_usluga' => collect([
                    'url' => '0',
                    'usl_name' => 'Услуги юристов',
                    'usl_desc' => 'Услуги юристов: цены, отзывы, адреса.',
                    'file_path' => 'storage/images/landing/main/1280on600.webp',
                ]),
                'uslugi' => $uslugi,
                'count' => $uslugi->count(),
                'max' => $uslugi->max('price'),
                'min' => $uslugi->min('price'),
                'sumrating' => ($uslugi->sum('review_sum_rating') + $uslugi->sum('userreview_sum_rating')),
                'countrating' => ($uslugi->sum('review_count') + $uslugi->sum('userreview_count')),
                'cities' => $cities,
                'category' => $category,
                'routeurl' => '/uslugi',
                'getLawyer' => session()->get('questionTitle') ? session()->get('questionTitle') : '0',
                'auth' => Auth::user(),
                'cityheader' => CitySet::CityGet($url),
                'backendurl' => $request->path(),
            ]);
        }
        //check city in url


        $usluga = Uslugi::where('url', $url)->first();

        if ($usluga->is_main == 1) {
            return redirect()->route(
                'offer.main',
                [
                    'city' => 'all-cities',
                    'main_usluga' => $usluga->url,
                ]
            );
        };

        if ($usluga) {
            $sity = ($usluga->sity) ? $usluga->sity : 'allcities';
            $main_usluga_id = ($usluga->main_usluga_id) ? $usluga->main_usluga_id : 'main';
            $second_usluga_id = ($usluga->second_usluga_id) ? Uslugi::findOrFail($usluga->second_usluga_id)->url : 'second';
            return redirect()->route(
                'uslugi.canonical.url',
                [
                    'city' => cities::findOrFail($sity)->url,
                    'main_usluga' => Uslugi::findOrFail($main_usluga_id)->url,
                    'second_usluga' => $second_usluga_id,
                    'url' => $usluga->url,
                    'cityheader' => CitySet::CityGet(false),
                ]
            );
        } else {
            abort(404);
        }
        abort(404);
    }


    public function showOfferByMain(string $city, string $main_usluga,  Request $request)
    // http://nedicom.ru/uslugi/city/main
    {
        if (!$city) abort(404);
        UslugaSet::setFromUrl($main_usluga);
        $route = ['name' => 'uslugi.url', 'urls' => [$city, $main_usluga]];
        $city = CitySet::CitySet($request, $city, false, $route);
        $main = Uslugi::where('url', $main_usluga)->with('cities')->first(['id', 'usl_name', 'url', 'usl_desc', 'file_path', 'popular_question']);
        $category = Uslugi::where('is_main', 1)
            ->where('is_feed', 1)
            ->with(['mainhasoffer' => function ($query) {
                if (session()->get('cityid')) {
                    $query->where('sity', session()->get('cityid'));
                }
            }])
            ->with('mainhassecond')
            ->get();

        $user_id = Auth::user() ? Auth::user()->id : null;

        $uslugi = GetUslugi::GetUsl($user_id, $city,  $main, null);

        $cities = '';
        if ($request->city) {
            $cities = cities::when($request->city ?? null, function ($query, $city) {
                return $query->where('title', 'like', '%' . $city . '%')->get();
            });
        }
        //dd($uslugi->sum('review_sum_rating') + $uslugi->sum('userreview_sum_rating'));
        return Inertia::render('Uslugi/Uslugi', [
            'city' => $city,
            'category' => $category,
            'main_usluga' => $main,
            //'second_usluga' => Uslugi::where('is_main', 1)->first(),
            'uslugi' => $uslugi,
            'count' => $uslugi->count(),
            'max' => $uslugi->max('price'),
            'min' => $uslugi->min('price'),
            'sumrating' => ($uslugi->sum('review_sum_rating') + $uslugi->sum('userreview_sum_rating')),
            'countrating' => ($uslugi->sum('review_count') + $uslugi->sum('userreview_count')),
            'cities' => $cities,
            'routeurl' => '/uslugi/' . $city->url . '/' . $main_usluga,
            'auth' => Auth::user(),
            'backendurl' => $request->path(),
        ]);
    }

    public function showOfferBysecond(string $city, string $main_usluga, string $second_usluga, Request $request)
    // http://nedicom.ru/uslugi/city/main/second
    {
        $city = CitySet::CitySet($request, $city, false);

        UslugaSet::setFromUrl($second_usluga);

        $main = Uslugi::where('url', $main_usluga)->first(['id', 'usl_name', 'usl_desc', 'url', 'popular_question']);
        $second = Uslugi::where('url', $second_usluga)->with('cities')->with('main')->first(['id', 'usl_name', 'usl_desc', 'url', 'file_path', 'popular_question']);
        if ($second->url === "second") $second->usl_desc = $main->usl_desc . '. ' . $second->usl_desc;

        $cities = '';
        if ($request->city) {
            $cities = cities::when($request->city ?? null, function ($query, $city) {
                return $query->where('title', 'like', '%' . $city . '%')->get();
            });
        }

        $category = Uslugi::where('is_main', 1)
            ->where('is_feed', 1)
            ->with(['mainhasoffer' => function ($query) {
                if (session()->get('cityid')) {
                    $query->where('sity', session()->get('cityid'));
                }
            }])
            ->with('mainhassecond')
            ->get();

        $user_id = Auth::user() ? Auth::user()->id : null;

        if ($second->id == 0) {
            $uslugi = GetUslugi::GetUsl($user_id, $city,  $main, null);
        } else {
            $uslugi = GetUslugi::GetUsl($user_id, $city,  null, $second);
        }

        $minPrice = $uslugi->min('price') ?? 1;
        $maxPrice = $uslugi->max('price') ?? 1000;

        return Inertia::render('Uslugi/Uslugi', [
            'city' => $city,
            'category' => $category,
            'main_usluga' => $main,
            'cities' => $cities,
            'second_usluga' => $second,
            'uslugi' => $uslugi,
            'count' => $uslugi->count(),
            'max' => $maxPrice,
            'min' => $minPrice,
            'sumrating' => ($uslugi->sum('review_sum_rating') + $uslugi->sum('userreview_sum_rating')),
            'countrating' => ($uslugi->sum('review_count') + $uslugi->sum('userreview_count')),
            'routeurl' => '/uslugi/' . $city->url . '/' . $main_usluga . '/' . $second_usluga,
            'auth' => Auth::user(),
            'backendurl' => $request->path(),
        ]);
    }

    public function showcanonical($city, $main_usluga, $second_usluga, $url,  Request $request)
    {
        UslugaSet::setFromUrl($url);
        // http://nedicom.ru/uslugi/city/main/second/usl-name
        $usluga = Uslugi::where('url', '=', $url)->first();

        $id = $usluga->id;
        $mainid = $usluga->main_usluga_id;

        if (!$mainid) {
            $mainid = $id;
        }

        $user_id = Uslugi::where('url', '=', $url)->first()->user_id;

        $practice = DB::table('uslugis_practice')
            ->leftJoin('articles', 'uslugis_practice.article_id', '=', 'articles.id')
            ->select(
                'uslugis_practice.*',
                'articles.id as id',
                'articles.created_at as created_at',
                'articles.description as description',
                'articles.header as header',
                'articles.url as url',
                'articles.practice_file_path as practice_file_path',
                'articles.region as region',
            )
            ->where('uslugis_practice.usluga_id', $usluga->id)
            ->get();

        $practice->map(function ($practice) {
            $practice->year =  Carbon::parse($practice->created_at)->format("Y");
            return $practice;
        });

        $auth = Auth::user();

        $lawyer = User::where('id', $usluga->user_id)->first();

        $main = Uslugi::where('id', $usluga->main_usluga_id)->first(['id', 'usl_name', 'url',]);

        $reviews = Review::where('lawyer_id', $lawyer->id)
            ->orWhere('usl_id', $usluga->id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($auth && $reviews) {
            $auth->has_comment = ($reviews->where('user_id', $auth->id)->first()) ? true : false;
        }

        if ($auth) {
            if ($auth->id != $usluga->user_id) {
                DB::table('uslugis')->where('uslugis.url', '=', $url)->increment('counter', 1);
            }
        } else {
            DB::table('uslugis')->where('uslugis.url', '=', $url)->increment('counter', 1);
        }

        $usluga = Uslugi::where('url', $url)->with('cities')->first();

        $usluga->incrementViews();

        return Inertia::render('Uslugi/Usluga', [
            'auth' => $auth,
            'usluga' => $usluga,
            'userprices' => DB::table('uslugis_prices')
                ->where('users_id', $usluga->user_id)
                ->where('uslugis_id', $usluga->id)
                ->leftJoin('prices', 'uslugis_prices.prices_id', '=', 'prices.id')
                ->select(
                    'uslugis_prices.*',
                    'prices.name as name',
                    'prices.price as common_price',
                )
                ->get(),
            'lawyer' => $lawyer,
            'reviews' => $reviews,
            'lawyers' => User::where('speciality_one_id', '=', $id)->orderBy('name', 'asc')->get()->take(3),
            'practice' => $practice->groupBy('year'),
            'firstlawyer' => User::where('id', $user_id)->get(),
            'reviews' => $reviews,
            'reviewscount' => $reviews->count(),
            'rating' => $reviews->count() ? round($reviews->sum('rating') / $reviews->count(), 1) : null,
            'main_usluga' =>  $main,
            'second_usluga' => Uslugi::where('id', $usluga->second_usluga_id)->first(['id', 'usl_name', 'url']),
            'city' => is_null(cities::find($usluga->sity)) ? cities::find(0) : cities::find($usluga->sity),
            'cityheader' => CitySet::CitySet($request, $city, false, 'offer.second'),
            'url' => $city . '/' . $main_usluga . '/' . $second_usluga . '/' . $url,
            'flash' => ['message' => $request->session()->get(key: 'message')],
            'backendurl' => $request->path(),
        ]);
    }

    public function formadd()
    {
        return Inertia::render(
            'Uslugi/Add',
            [
                'all_uslugi' => Uslugi::where('is_main', '=', 1)->select('id', 'usl_name')
                    //->doesntHave('doesntHaveoffersbymain')
                    ->with('zerocategory')
                    ->get(),
                'second_uslugi' => Uslugi::where('is_second', 1)->select('id', 'usl_name', 'main_usluga_id')
                    ->doesntHave('doesntHaveoffersbysecond')->get()->groupBy('main_usluga_id'),
                'user' => Auth::user(),
                'cities' => cities::all(),
                'auth' => Auth::user(),
            ],
        );
    }

    public function create(Request $request)
    {
        $usluga = new Uslugi;
        $usluga->usl_name = $request->header;
        $usluga->usl_desc = $request->description;

        $usluga->user_id = Auth::id();

        $usluga->preimushestvo1 = '600+ дел';
        $usluga->preimushestvo2 = 'Более 10 лет практики';
        $usluga->preimushestvo3 = 'Аналитический подход к решению задачи';

        $usluga->longdescription = 'Это детальное описание услуги';
        $usluga->preimushestvo1 = '600+ дел';
        $usluga->preimushestvo2 = 'Более 10 лет практики';
        $usluga->preimushestvo3 = 'Аналитический подход к решению задачи';
        $usluga->address = 'Россия';
        $usluga->phone = '+7000 000 0000';

        if ($request->main_usluga_id) {
            $usluga->is_main = false;
            $usluga->main_usluga_id = $request->main_usluga_id;
        }

        if ($request->second_usluga_id) {
            $usluga->second_usluga_id = $request->second_usluga_id;
        }

        if ($request->second_usluga_id == 0) {
            $usluga->second_usluga_id = 0;
        }

        if ($request->sity) {
            $usluga->sity = $request->sity;
        }

        if ($request->is_main) {
            $usluga->is_main = $request->is_main;
            $usluga->is_second = null;
        }

        if ($request->is_second) {
            $usluga->is_second = $request->is_second;
            $usluga->is_main = null;
            $usluga->second_usluga_id = null;
        }

        if ($request->is_feed) {
            $usluga->is_feed = $request->is_feed;
        } else {
            $usluga->is_feed = 0;
        }

        $url = Translate::translit($request->header);
        $checkurl = Checkurl::chkurl($url, 'usluga');
        $usluga->url =  $checkurl;

        $usluga->save();

        TgSend::SendMess("Добавлена услуга", $usluga->usl_name, "https://nedicom.ru/uslugi/" . $usluga->url);

        return redirect()->route('uslugi.url', ['url' => $checkurl])->with('message', 'Услуга создана успешно.');
    }

    public function edit(string $url, Request $request)
    {
        $usluga = Uslugi::where('id', '=', $url)->with('second')->with('main')->first();
        return Inertia::render(
            'Uslugi/Edit',
            [
                'uslugi' => $usluga,
                'main_uslugi' => Uslugi::where('is_main', '=', 1)->select('id', 'usl_name')
                    //->doesntHave('doesntHaveoffersbymain')
                    ->with('zerocategory')
                    ->get(),
                'second_uslugi' => Uslugi::where('is_second', 1)->select('id', 'usl_name', 'main_usluga_id')
                    ->doesntHave('doesntHaveoffersbysecond')->get()->groupBy('main_usluga_id'),
                'user' => User::where('id', $usluga->user_id)->first(),
                'lawyers' => User::where('lawyer', 1)->get(['id', 'name']),
                'flash' => ['message' => $request->session()->get(key: 'message')],
                'cities' => cities::all(),
                'practice' => DB::table('articles')->where('userid', $usluga->user_id)
                    ->leftJoin('uslugis', 'articles.usluga_id', '=', 'uslugis.id')
                    ->select(
                        'articles.id as id',
                        'articles.header as header',
                        'articles.usluga_id as usluga_id',
                        'articles.practice_file_path as practice_file_path',
                        'uslugis.id as uslugis_id',
                        'uslugis.usl_name as usl_name',
                    )
                    ->whereNotNull('articles.practice_file_path')
                    ->get()->groupBy('usl_name'),
                'userpractice' => DB::table('uslugis_practice')
                    ->leftJoin('articles', 'uslugis_practice.article_id', '=', 'articles.id')
                    ->select(
                        'uslugis_practice.*',
                        'articles.id as id',
                        'articles.header as header',
                        'articles.usluga_id as article_usluga_id',
                    )
                    ->where('uslugis_practice.usluga_id', $usluga->id)
                    ->get(),
                'prices' => price::all(),
                'userprices' => DB::table('uslugis_prices')
                    ->where('users_id', $usluga->user_id)
                    ->where('uslugis_id', $usluga->id)
                    ->leftJoin('prices', 'uslugis_prices.prices_id', '=', 'prices.id')
                    ->get(),
                'auth' => Auth::user(),
            ],
        );
    }

    public function update(Request $request)
    {

        if ($request->updtprice) {
            if ($request->updtprice == 1) {
                DB::table('uslugis_prices')->insert([
                    'users_id' => $request->users_id,
                    'uslugis_id' => $request->uslugis_id,
                    'prices_id' => $request->prices_id,
                    'value' => $request->value,
                    'price' => $request->price,
                ]);
            }
            if ($request->updtprice == 2) {
                DB::table('uslugis_prices')
                    ->where('users_id', $request->users_id)
                    ->where('prices_id', $request->prices_id)
                    ->delete();
            }
            return back();
        }

        if ($request->updtpractice) {
            if ($request->updtpractice == 1) {
                DB::table('uslugis_practice')->insert([
                    'usluga_id' => $request->usluga_id,
                    'article_id' => $request->article_id,
                ]);
            }
            if ($request->updtpractice == 2) {
                DB::table('uslugis_practice')
                    ->where('usluga_id', $request->usluga_id)
                    ->where('article_id', $request->article_id)
                    ->delete();
            }
            return back();
        }

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
        $usluga->dopadress = $request->dopadress;
        $usluga->maps = $request->maps;
        $usluga->popular_question = $request->popular;
        //dd($request->ok);
        $usluga->vk = $request->vk;
        $usluga->ok = $request->ok;

        $usluga->expirience = $request->expirience;
        $usluga->price = $request->price;


        $arr = $request->video_links;
        foreach ($arr as $index => $entry) {
            foreach ($entry as $data) {
                if (str_contains($data, "\" width=")) $data = substr($data, 0, strpos($data, "\" width="));
                if (str_contains($data, "https")) $data = strstr($data, "https");
                if ($data) $arr[$index]['videolink'] =  $data;
            }
        }
        $usluga->video = $arr;

        if ($request->sity) {
            $usluga->sity = $request->sity;
        }

        if ($request->main_usluga_id) {
            $usluga->is_main = false;
            $usluga->main_usluga_id = $request->main_usluga_id;
        }

        if ($request->second_usluga_id) {
            $usluga->second_usluga_id = $request->second_usluga_id;
        } else if ($request->second_usluga_id == 0) {
            $usluga->second_usluga_id = 0;
        } else {
            $usluga->second_usluga_id = null;
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
        } else {
            $usluga->is_feed = 0;
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

        $uslugi = $query->orderBy('id')->with('cities')->paginate(12);

        return Inertia::render('Uslugi/Byuser', [
            'filters' => $request->all('search'),
            'uslugi' =>  $uslugi,
            'auth' => Auth::user(),
        ]);
    }

    public function searchAuthor(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:50'
        ]);

        $query = $request->get('q');

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                $email = $user->email;
                $parts = explode('@', $email);
                if (count($parts) === 2) {
                    $username = $parts[0];
                    $domain = $parts[1];
                    $maskedUsername = substr($username, 0, 2) . '***';
                    $user->email = $maskedUsername . '@' . $domain;
                }
                return $user;
            });

        // Возвращаем данные для Inertia
        return response()->json($users);


        // return Inertia::render('YourComponent', [
        //     'authors' => $users
        // ]);
    }

    public function changeAuthor(Request $request)
    {
        $request->validate([
            'author_id' => 'required|integer|exists:users,id',
            'usluga_id' => 'required|integer|exists:uslugis,id',
        ]);

        $usluga = Uslugi::findOrFail($request->usluga_id);
        $usluga->user_id = $request->author_id;
        $usluga->save();

        return redirect()->back()->with('success', 'Автор успешно изменен');
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
