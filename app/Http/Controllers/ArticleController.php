<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\draft;
use App\Models\Uslugi;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Models\cities;
use App\Models\Questions;
use App\Models\Article_comment;
use Illuminate\Support\Facades\Storage;

use App\Helpers\Translate;
use App\Helpers\TgSend;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $draft = draft::where('user_id', Auth::user()->id)->first() ? draft::where('user_id', Auth::user()->id)->first() : ['header' => '', 'description' => '', 'body' => ''];
        return Inertia::render('Articles/Add', [
            'auth' => Auth::user(),
            'draft' => $draft,
        ]);
    }

    public function draft(Request $request)
    {
        $draft = draft::where('user_id', Auth::user()->id)->first() ? draft::where('user_id', Auth::user()->id)->first() : new draft;
        $draft->user_id = Auth::user()->id;
        $draft->header = $request->header;
        $draft->description = $request->description;
        $draft->body = $request->body;
        $draft->save();
    }

    public function create(Request $request)
    {
        draft::where('user_id', Auth::user()->id)->first() ? draft::where('user_id', Auth::user()->id)->delete() : null;

        $article = new Article;
        $article->userid = Auth::user()->id;
        $article->username = Auth::user()->name;
        $article->header = $request->header;
        $article->description = $request->description;
        $article->body = $request->body;
        $url = Translate::translit($request->header);
        $article->url = $url;

        //TgSend::SendMess("Добавлена статья пользователем - " . $article->username, $article->header, "https://nedicom.ru/articles/" . $url);

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

    public function autoupdate(Request $request)
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


    public function image(Request $request)
    {

        if ($request->file()) {
            $filePath = 'usr/' . $request->id . '/articleimages/';
            $fileName = Str::random(4) . '.webp';

            $mime = $request->file('file')->getClientMimeType();

            if ($mime == "image/png") {
                $im = imagecreatefrompng($request->file('file'));
                imagewebp($im, 'storage/' .$filePath . $fileName, 80);
            } else if ($mime == "image/jpeg") {
                $im = imagecreatefromjpeg($request->file('file'));
                imagewebp($im, 'storage/' .$filePath . $fileName, 80);
            } else if ($mime == "image/webp") {
                Storage::putFileAs($filePath, $request->file('file'), $fileName);
            } 
            else {
                return back()->with('message', 'Недопустимое расширение файла');
            }
            imagedestroy($im);
            return back()->with(['message' => '/storage/'.$filePath . $fileName]);
        } else {
            return back()->with('message', 'Что-то пошло не так');
        }
    }

    public function articleURL($url)
    {

        $article = DB::table('articles')->where('articles.url', '=', $url)->first();
        if (is_null($article)) {
            abort(410);
        }

        if (Auth::user()) {
            $user_id = Auth::user()->id;
            if (Auth::user()->id != $article->userid) {
                DB::table('articles')->where('articles.url', '=', $url)->increment('counter', 1);
            }
        } else {
            $user_id = null;
            DB::table('articles')->where('articles.url', '=', $url)->increment('counter', 1);
        }


        if ($article->usluga_id == null) {
            $usluga_id_sec = 1;
        } else {
            $usluga_id_sec = $article->usluga_id;
        }

        DB::statement("SET lc_time_names = 'ru_RU'");

        //dd(Uslugi::where('id', $usluga_id_sec)->first());
        return Inertia::render('Articles/Article', [
            'article' => DB::table('articles')
                ->where('articles.url', '=', $url)
                ->leftJoin('users', 'articles.userid', '=', 'users.id')
                ->leftjoin('bundles_socials', function ($join) use ($user_id) {
                    $join->on('bundles_socials.article_id', '=', 'articles.id')
                        ->where('bundles_socials.users_id', '=', $user_id);
                })
                ->select(
                    'articles.*',
                    'bundles_socials.likes as user_like',
                    'bundles_socials.bookmarks as user_bookmark',
                    'bundles_socials.shares as user_share',
                    'users.id as user_id',
                    'users.name',
                    'users.avatar_path',
                    DB::raw("DATE_FORMAT(articles.created_at, '%d-%M-%Y') as created"),
                    DB::raw("DATE_FORMAT(articles.updated_at, '%d-%M-%Y') as updated")
                )
                ->selectRaw('IF(articles.id, "articles", false) AS type')
                ->first(),
            'user' => Auth::user(),
            'auth' => Auth::user(),
            'region' =>  cities::where('regionId', $article->region)->first(),
            'usluga' => Uslugi::where('id', $usluga_id_sec)->select('uslugis.url', 'uslugis.usl_name')->first(),
            'question' => Questions::where('id', $article->id)->withCount('QuantityAns')->with('User')->with('Usluga')->first(),
            'answers' => Article_comment::where('article_id', $article->id)
                ->with('UserAns')
                ->with('subcomments')
                ->get(),
            'authid' => (Auth::user()) ? Auth::user()->id : null,

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
