<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Uslugi;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Models\cities;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\Translate;


class ArticleController extends Controller
{

    public function index()
    {
        return Inertia::render('Articles/Articles', [
            'articles' => DB::table('articles')
                ->leftJoin('users', 'articles.userid', '=', 'users.id')
                ->paginate(9),
            'auth' => Auth::user(),
        ]);
    }

    public function my()
    {
        return Inertia::render('Articles/MyArticles', [
            'articles' => Article::where('userid', '=', Auth::user()->id)
                ->select(['id', 'header', 'description', 'url'])
                ->paginate(100),
            'auth' => Auth::user(),
        ]);
    }

    public function formadd()
    {
        return Inertia::render('Articles/Add', [
            'articles' => Article::where('userid', '=', Auth::user()->id)
                ->select(['id', 'header', 'description', 'url'])
                ->paginate(100),
            'auth' => Auth::user(),
        ]);
    }

    public function create(Request $request)
    {
        $article = new Article;
        $article->userid = Auth::user()->id;
        $article->username = Auth::user()->name;
        $article->header = $request->header;
        $article->description = $request->description;
        $article->body = $request->body;
        $url = Translate::translit($request->header);
        $article->url = $url;
        $article->save();
        return redirect()->route('articles/url', $url);
    }

    public function edit(string $url)
    {

        $unique = cities::select('region', 'regionid')->get()->unique('regionid');
        return Inertia::render(
            'Articles/Edit',
            [
                'article' => Article::where('url', '=', $url)->first(),
                'uslugi' => Uslugi::where('is_main', '=', 1)->where('is_feed', 1)->get(),
                'auth' => Auth::user(),
                'region' => $unique->values()->all(),
            ],
        );
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $article = Article::find($id);
        $article->header = $request->header;
        $article->description = $request->description;
        $article->body = $request->body;
        $article->usluga_id = $request->usluga_id;
        $article->youtube_file_path = $request->youtube;
        $article->region = $request->region;
        $article->save();
        return redirect()->route('articles/url', $article->url);
    }

    public function store(StoreArticleRequest $request)
    {
        //
    }

    /*public function articleId($id){ //for SEO the HFU were created
        return Inertia::render('Articles/Article', [
            'article' => Article::find($id),
        ]);
    }*/

    public function articleURL($url)
    {

        $article = DB::table('articles')->where('articles.url', '=', $url)->first();

        if (Auth::user()) {
            if (Auth::user()->id != $article->userid) {
                DB::table('articles')->where('articles.url', '=', $url)->increment('counter', 1);
            }
        } else {
            DB::table('articles')->where('articles.url', '=', $url)->increment('counter', 1);
        }
        if ($article->usluga_id == null) {
            $usluga_id_sec = 15;
        } else {
            $usluga_id_sec = $article->usluga_id;
        }
        DB::statement("SET lc_time_names = 'ru_RU'");

        //dd(Uslugi::where('id', $usluga_id_sec)->first());
        return Inertia::render('Articles/Article', [
            'article' => DB::table('articles')
                ->where('articles.url', '=', $url)
                ->leftJoin('users', 'articles.userid', '=', 'users.id')
                ->select(
                    'articles.*',
                    'users.id',
                    'users.name',
                    'users.avatar_path',
                    DB::raw("DATE_FORMAT(articles.created_at, '%d-%M-%Y') as created"),
                    DB::raw("DATE_FORMAT(articles.updated_at, '%d-%M-%Y') as updated")
                )
                ->first(),
            'user' => Auth::user(),
            'auth' => Auth::user(),
            'region' =>  cities::where('regionId', $article->region)->first(),
            'usluga' => Uslugi::where('id', $usluga_id_sec)->select('uslugis.url', 'uslugis.usl_name')->first(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
        Article::find($id)->delete();
        return redirect()->back()->with('success', 'Все в порядке, статья удалена');
    }

    //import

}
