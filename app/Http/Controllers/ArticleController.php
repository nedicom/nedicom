<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\draft;
use App\Models\Uslugi;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Models\cities;
use App\Models\Questions;
use App\Models\Article_comment;
use Illuminate\Support\Facades\Storage;

use App\Helpers\OpenAI;
use App\Helpers\Translate;
use App\Helpers\TgSend;
use App\Helpers\CitySet;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{

    protected $viewService;

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
        if (Auth::user()) {
            $draft = draft::where('user_id', Auth::user()->id)->first() ? draft::where('user_id', Auth::user()->id)->first() : ['header' => '', 'description' => '', 'body' => ''];
        } else {
            $draft = ['header' => '', 'description' => '', 'body' => ''];
        }
        return Inertia::render('Articles/Add', [
            'auth' => Auth::user(),
            'draft' => $draft,
        ]);
    }

    public function articleGeneration()
    {
        return Inertia::render('Articles/Generator', [
            'auth' => Auth::user(),
        ]);
    }

    public function generate(Request $request)
    {
        $article = new Article;
        $article->userid = Auth::user()->id;
        $article->username = Auth::user()->name;
        $article->header = $request->header;
        $generation =  OpenAI::ArticleBody($request->header);
        //dd($generation);
        $article->body = $generation[0];
        $article->description = $generation[1];
        $url = Translate::translit($request->header);
        $article->url = $url;

        $article->save();

        return redirect()->route('articles/url', $url);
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
        $article = Article::where('url', '=', $url)->first();

        return Inertia::render(
            'Articles/Edit',
            [
                'article' => $article,
                'uslugi' => Uslugi::where('is_main', '=', 1)->where('is_feed', 1)->get(),
                'auth' => Auth::user(),
                'region' => $unique->values()->all(),
            ],
        );
    }

    public function searchLawyersWeb(Request $request)
{
    // Проверяем авторизацию через сессию
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    // Проверяем права администратора
    if (!Auth::user()->isadmin) {
        return response()->json(['error' => 'Forbidden'], 403);
    }
    
    $search = $request->input('search', '');

    $lawyers = User::where('lawyer', 1)
        ->when($search, function ($query) use ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->orderBy('name')
        ->limit(20)
        ->get(['id', 'name', 'email']);
    
    return response()->json($lawyers);
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
        $article->avito = $request->avito;
        $article->tg = $request->tg;
        $article->tg_description = $request->tg_description;

        if ($request->has('userid') && Auth::user()->isadmin) {
            $newUser = User::find($request->userid);
            if ($newUser) {
                $article->userid = $newUser->id;
                $article->username = $newUser->name;
            }
        }
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
                imagewebp($im, 'storage/' . $filePath . $fileName, 80);
            } else if ($mime == "image/jpeg") {
                $im = imagecreatefromjpeg($request->file('file'));
                imagewebp($im, 'storage/' . $filePath . $fileName, 80);
            } else if ($mime == "image/webp") {
                Storage::putFileAs($filePath, $request->file('file'), $fileName);
            } else {
                return back()->with('message', 'Недопустимое расширение файла');
            }
            imagedestroy($im);
            return back()->with(['message' => '/storage/' . $filePath . $fileName]);
        } else {
            return back()->with('message', 'Что-то пошло не так');
        }
    }

    //article by url
    public function articleURL($url, Request $request)
    {

        // Получаем статью с проверкой существования
        $article = DB::table('articles')->where('url', $url)->first();

        if (!$article) {
            abort(410, 'Статья не найдена');
        }

        $data = $this->prepareArticleData($article, $url, $request);
        // Обновляем счетчик просмотров
        $this->incrementArticleCounter($article, $url);

        return Inertia::render('Articles/Article', $data);
    }

    protected function incrementArticleCounter($article, $url)
    {
        // Увеличиваем счетчик только если пользователь не автор
        if (!Auth::user() || Auth::id() != $article->userid) {
            DB::table('articles')->where('url', $url)->increment('counter');
        }
    }

    protected function prepareArticleData($article, $url, $request)
    {
        $userId = Auth::id();
        $uslugaId = $article->usluga_id ?? 1;

        // Устанавливаем локаль для дат
        DB::statement("SET lc_time_names = 'ru_RU'");

        // Получаем вопросы для слайдера
        $sliderQ = $this->getSliderQuestions($uslugaId);

        $totalComments = Article_comment::where('article_id', $article->id)
            ->withCount('subcomments')
            ->get()
            ->sum(function ($comment) {
                return 1 + $comment->subcomments_count;
            });

        return [
            'article' => $this->getArticleWithDetails($url, $userId),
            'totalComments' => $totalComments,
            'auth' => Auth::user(),
            'region' => Cities::where('regionId', $article->region)->first(),
            'usluga' => Uslugi::where('id', $uslugaId)->select('url', 'usl_name')->first(),
            'question' => Article::with('User')->find($article->id),
            'SliderQ' => $sliderQ,
            'answers' => Article_comment::where('article_id', $article->id)
                ->with(['UserAns', 'subcomments'])
                ->get(),
            'authid' => $userId,
            'cityheader' => CitySet::CityGet(false),
            'backendurl' => $request->path(),
        ];
    }

    protected function getSliderQuestions($uslugaId)
    {
        $query = Questions::limit(20)->orderBy('updated_at', 'desc');

        return $uslugaId != 1
            ? $query->where('usluga', $uslugaId)->get()
            : $query->get();
    }

    protected function getArticleWithDetails($url, $userId)
    {
        return DB::table('articles')
            ->where('articles.url', $url)
            ->leftJoin('users', 'articles.userid', '=', 'users.id')
            ->leftJoin('bundles_socials', function ($join) use ($userId) {
                $join->on('bundles_socials.article_id', '=', 'articles.id')
                    ->where('bundles_socials.users_id', '=', $userId);
            })
            ->select(
                'articles.*',
                'bundles_socials.likes as user_like',
                'bundles_socials.bookmarks as user_bookmark',
                'bundles_socials.shares as user_share',
                'users.id as user_id',
                'users.phone',
                'users.name',
                'users.avatar_path',
                DB::raw("DATE_FORMAT(articles.created_at, '%d-%M-%Y') as created"),
                DB::raw("DATE_FORMAT(articles.updated_at, '%d-%M-%Y') as updated"),
                DB::raw('IF(articles.id, "articles", false) AS type')
            )
            ->first();
    }
    //article by url

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
}
