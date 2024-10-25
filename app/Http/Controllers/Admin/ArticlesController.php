<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Uslugi;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\Translate;

class ArticlesController extends Controller
{

    public function index(Request $request)
    {  
        $query = Article::query();

        if ($request->has('search')) {
            $query = $query->filter($request->all('search'));
        }

        $articles = $query->orderBy('id')->paginate(9);

        return Inertia::render('Admin/Articles/Index', [
            'filters' => $request->all('search'),
            'articles' => $articles,
            'auth' => Auth::user(), 
        ]);
    }

    public function formadd()
    {
        return Inertia::render('Articles/Add');    
    }

    public function create(Request $request){
        $article = new Article;
        $article->userid = Auth::user()->id;
        $article->username = Auth::user()->name;
        $article->header = $request->header;
        $article->description = $request->description;
        $article->body = $request->body;
        $url = Translate::translit($request->header);        
        $article->url =  $url;
        $article->save();
        return redirect()->route('articles/url', $url);
    }

    public function edit(string $url)
    {
        return Inertia::render('Articles/Edit', [
            'article' => Article::where('url', '=', $url)->first(),
            'uslugi' => Uslugi::where('is_main', '=', 1)->get(),
            'auth' => Auth::user(), 
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
        $url = Translate::translit($request->header);        
        $article->url =  $url;
        $article->save();
        return redirect()->route('articles/url', $url);     
    }    

    public function store(StoreArticleRequest $request)
    {
        //
    }

    public function articleURL($url){ 
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
        if ($article = Article::find($id)) {
            $article->delete();

            return redirect()->route('admin.articles.list');
        }

        abort(404);
    }

}