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

use App\Helpers\CitySet;

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

        $cityurl = '';       

        $city = CitySet::CitySet($request, $cityurl);

        $category = Uslugi::where('is_main', 1)
            ->where('is_feed', 1)
            ->with(['mainhasoffer' => function ($query) {
                if (session()->get('cityid')) {
                    $query->where('sity', session()->get('cityid'));
                }
            }])
            ->with('mainhassecond')
            ->get();

        $uslugi = Uslugi::where('is_main', '!=', 1)
            ->where('is_second', null)
            ->where('is_feed', 1)
            ->inRandomOrder()
            ->with('cities')
            ->with('main')
            ->with('second')
            ->when(session()->get('cityid') ?? null, function ($query, $sescity) {
                $query->where(function ($query) use ($sescity) {
                    $query->where('sity', $sescity);
                });
            })
            ->withCount('review')
            ->withSum('review', 'rating')
            ->with('review')
            ->get();

        return Inertia::render('Uslugi/Uslugi', [
            'city' => $city,
            'main_usluga' => collect([
                'url' => 0,
                'usl_name' => 'все услуги',
                'usl_desc' => 'Найдите квалифицированного юриста сейчас. Качество юридических услуг гарантировано.',
                'file_path' => 'storage/images/landing/main/1280on600.webp',
            ]),
            'uslugi' => $uslugi,
            'count' => $uslugi->count(),
            'max' => $uslugi->max('price'),
            'min' => $uslugi->min('price'),
            'sumrating' => $uslugi->sum('review_sum_rating'),
            'countrating' => $uslugi->sum('review_count'),
            'cities' => $cities,
            'allsities' => $uslugi->unique('cities'),
            'category' => $category,
            'routeurl' => '/uslugi',
        ]);
    }


    public function show($url,  Request $request) // http://nedicom.ru/uslugi/city
    {

        //check city in url
        if (cities::where('url', $url)->first()) {
            $cities = [];
            if ($request->city) {
                $cities = cities::when($request->city ?? null, function ($query, $city) {
                    return $query->where('title', 'like', '%' . $city . '%')->get();
                });
            }

            $cityurl = $url;

            $request->session()->forget('cityid');

            $city = CitySet::CitySet($request, $cityurl);

            $category = Uslugi::where('is_main', 1)
                ->where('is_feed', 1)
                ->with(['mainhasoffer' => function ($query) {
                    if (session()->get('cityid')) {
                        $query->where('sity', session()->get('cityid'));
                    }
                }])
                ->with('mainhassecond')
                ->get();

            $uslugi = Uslugi::where('is_main', '!=', 1)
                ->where('is_second', null)
                ->where('is_feed', 1)
                ->with('cities')
                ->with('main')
                ->with('second')
                ->when(session()->get('cityid') ?? null, function ($query, $sescity) {
                    $query->where(function ($query) use ($sescity) {
                        $query->where('sity', $sescity);
                    });
                })
                ->withCount('review')
                ->withSum('review', 'rating')
                ->with('review')
                ->get();

            return Inertia::render('Uslugi/Uslugi', [
                'city' => $city,
                'main_usluga' => collect([
                    'url' => 0,
                    'usl_name' => 'все услуги',
                    'usl_desc' => 'Найдите квалифицированного юриста сейчас. Качество юридических услуг гарантировано.',
                    'file_path' => 'storage/images/landing/main/1280on600.webp',
                ]),
                'uslugi' => $uslugi,
                'count' => $uslugi->count(),
                'max' => $uslugi->max('price'),
                'min' => $uslugi->min('price'),
                'sumrating' => $uslugi->sum('review_sum_rating'),
                'countrating' => $uslugi->sum('review_count'),
                'cities' => $cities,
                'category' => $category,
                'routeurl' => '/uslugi',
                'getLawyer' => (session()->get('questionTitle')) ? session()->get('questionTitle') : '0',
            ]);
        }
        //check city in url

        $usluga = Uslugi::where('url', '=', $url)->first();

        if ($usluga) {
            if ($usluga->sity) {
                return redirect()->route(
                    'uslugi.canonical.url',
                    [
                        cities::findOrFail($usluga->sity)->url,
                        Uslugi::findOrFail($usluga->main_usluga_id)->url,
                        Uslugi::findOrFail($usluga->second_usluga_id)->url,
                        $usluga->url
                    ],
                    301
                );
            }
            else {
                abort(404);
            }
        } else {
            abort(404);
        }


        //del
        if ($usluga->sity) {
            $cityurl = cities::where('id', $usluga->sity)->first()->url;
            $city = CitySet::CitySet($request, $cityurl);
        }

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

        //views
        if ($usluga->is_main) {
            $view = 'Uslugi/Main';
        } else {
            $view = 'Uslugi/Usluga';
        }

        //data
        return Inertia::render($view, [
            'usluga' => Uslugi::where('url', $url)->with('cities')->with('main')->with('second')->first(),
            'city' => $city,
            'secondUslugi' => Uslugi::where('main_usluga_id', $id)->where('is_second', '!=', 0)->get(),
            'uslugi' => Uslugi::where('second_usluga_id', $id)->with('cities')->get(),
            'main_usluga' => Uslugi::where('id', $mainid)->withCount('mainreview')->withAvg('mainreview as avg_review', 'rating')->first(['id', 'usl_name', 'url']),
            'user' => Auth::user(),
            'lawyers' => User::where('speciality_one_id', '=', $id)->orderBy('name', 'asc')->get()->take(3),
            'practice' => Article::where('usluga_id', $mainid)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'lawyer' => User::where('id', $usluga->user_id)->first(),
            'reviews' => $reviews,
            'reviewscount' => $reviewscount,
            'rating' => $rating,
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);

        //del
    }


    public function showOfferByMain(string $city, string $main_usluga,  Request $request) // http://nedicom.ru/uslugi/city/main
    {
        $city = cities::where('url', $city)->with('uslugies')->first();
        $main = Uslugi::where('url', $main_usluga)->with('cities')->first(['id', 'usl_name', 'url', 'usl_desc', 'file_path']);

        $category = Uslugi::where('is_main', 1)
            ->where('is_feed', 1)
            ->with(['mainhasoffer' => function ($query) {
                if (session()->get('cityid')) {
                    $query->where('sity', session()->get('cityid'));
                }
            }])
            ->with('mainhassecond')
            ->get();        

        $city = CitySet::CitySet($request, $city->url);


        $uslugi = Uslugi::where('main_usluga_id', $main->id)
            ->where('is_main', '!=', 1)
            ->where('is_second', null)
            ->where('second_usluga_id', 0)
            ->where('is_feed', 1)
            ->where('sity', $city->id)
            ->with('cities')
            ->with('main')
            ->with('second')
            /*->when(session()->get('cityid') ?? null, function ($query, $sescity) {
                $query->where(function ($query) use ($sescity) {
                    $query->where('sity', $sescity);
                });
            })*/
            ->withCount('review')
            ->withSum('review', 'rating')
            ->get();

        $cities = '';
        if ($request->city) {
            $cities = cities::when($request->city ?? null, function ($query, $city) {
                return $query->where('title', 'like', '%' . $city . '%')->get();
            });
        }

        return Inertia::render('Uslugi/Uslugi', [
            'city' => $city,
            'category' => $category,
            'main_usluga' => $main,
            //'second_usluga' => Uslugi::where('is_main', 1)->first(),
            'uslugi' => $uslugi,
            'count' => $uslugi->count(),
            'max' => $uslugi->max('price'),
            'min' => $uslugi->min('price'),
            'sumrating' => $uslugi->sum('review_sum_rating'),
            'countrating' => $uslugi->sum('review_count'),
            'cities' => $cities,
            'routeurl' => '/uslugi/' . $city->url . '/' . $main_usluga,
        ]);
    }

    public function showOfferBysecond(string $city, string $main_usluga, string $second_usluga, Request $request)
    {
        $city = cities::where('url', $city)->with('uslugies')->first();
        $main = Uslugi::where('url', $main_usluga)->first(['id', 'usl_name', 'usl_desc', 'url']);
        $second = Uslugi::where('url', $second_usluga)->with('cities')->with('main')->first(['id', 'usl_name', 'usl_desc', 'url', 'file_path']);


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

        $city = CitySet::CitySet($request, $city->url);

        $uslugi = Uslugi::where('second_usluga_id', $second->id)
            ->where('is_main', '!=', 1)
            ->where('is_second', null)
            ->where('is_feed', 1)
            ->where('sity', $city->id)
            ->with('cities')
            ->with('main')
            ->with('second')
            ->when(session()->get('cityid') ?? null, function ($query, $sescity) {
                $query->where(function ($query) use ($sescity) {
                    $query->where('sity', $sescity);
                });
            })
            ->withCount('review')
            ->withSum('review', 'rating')
            ->get();


        return Inertia::render('Uslugi/Uslugi', [
            'city' => $city,
            'category' => $category,
            'main_usluga' => $main,
            'cities' => $cities,
            'second_usluga' => $second,
            'uslugi' => $uslugi,
            'count' => $uslugi->count(),
            'max' => $uslugi->max('price'),
            'min' => $uslugi->min('price'),
            'sumrating' => $uslugi->sum('review_sum_rating'),
            'countrating' => $uslugi->sum('review_count'),
            'routeurl' => '/uslugi/' . $city->url . '/' . $main_usluga . '/' . $second_usluga,
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
            'usluga' => Uslugi::where('url', $second_usluga)->with('cities')->with('cities')->with('main')->with('second')->first(),
            'uslugi' => Uslugi::where('second_usluga_id', $id)->with('cities')->get(),
            'main_usluga' => Uslugi::where('id', $mainid)->where('is_main', $mainid)->first(['id', 'usl_name', 'url']),
            'user' => Auth::user(),
            'lawyer' => User::where('id', $usluga->user_id)->first(),
            'lawyers' => User::where('speciality_one_id', '=', $id)->orderBy('name', 'asc')->get()->take(3),
            'practice' => Article::where('usluga_id', $mainid)->where('practice_file_path', '!=', null)->orderBy('updated_at', 'desc')->take(3)->get(),
            'firstlawyer' => User::where('id', $user_id)->get(),
            'reviews' => $reviews,
            'reviewscount' => $reviewscount,
            'rating' => $rating,
            'flash' => ['message' => $request->session()->get(key: 'message')],
        ]);
    }



    public function showcanonical($city, $main_usluga, $second_usluga, $url,  Request $request)
    {
        $usluga = Uslugi::where('url', '=', $url)->first();

        CitySet::CitySet($request, $city);

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
            'usluga' => Uslugi::where('url', $url)->with('cities')->first(),
            'user' => Auth::user(),
            'lawyer' => User::where('id', $usluga->user_id)->first(),
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
        return redirect()->route('uslugi.url', ['url' => $checkurl])->with('message', 'Услуга создана успешно.');
    }

    public function edit(string $url, Request $request)
    {

        return Inertia::render(
            'Uslugi/Edit',
            [
                'uslugi' => Uslugi::where('id', '=', $url)->with('second')->with('main')->first(),
                'main_uslugi' => Uslugi::where('is_main', '=', 1)->select('id', 'usl_name')
                    //->doesntHave('doesntHaveoffersbymain')
                    ->with('zerocategory')
                    ->get(),
                'second_uslugi' => Uslugi::where('is_second', 1)->select('id', 'usl_name', 'main_usluga_id')
                    ->doesntHave('doesntHaveoffersbysecond')->get()->groupBy('main_usluga_id'),
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
