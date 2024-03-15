<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Uslugi;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
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
        ]);
    }

    public function my()
    {
        return Inertia::render('Articles/MyArticles', [
            'articles' => Article::where('userid', '=', Auth::user()->id)
            ->select(['id', 'header', 'description', 'url'])
            ->paginate(100),
        ]);
    }

    public function formadd()
    {
        return Inertia::render('Articles/Add');
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
        return Inertia::render('Articles/Edit', [
            'article' => Article::where('url', '=', $url)->first(),
            'uslugi' => Uslugi::where('is_main', '=', 1)->get(),
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
        $url = Translate::translit($request->header);
        $article->url = $url;
        $article->save();
        return redirect()->route('articles/url', $url);
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
        return Inertia::render('Articles/Article', [
            'article' => DB::table('articles')
                ->where('url', '=', $url)
                ->leftJoin('users', 'articles.userid', '=', 'users.id')
                ->first(),
            'user' => Auth::user(),
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