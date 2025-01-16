<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Questions;
use App\Models\Bundles_social;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function reaction(Request $request)
    {
        //dd($request);
        if (!Auth::user()) {
            return redirect()->back();
        }

        if ($request->property) {
            $article = Article::find($request->id);

            $bundle = Bundles_social::firstOrNew(
                ['users_id' => Auth::user()->id, 'article_id' => $article->id],
            );
            //dd($bundle);
            if (!$bundle) {
                $bundle = new Bundles_social();
                $bundle->users_id = Auth::user()->id;
                $bundle->article_id = $article->id;
            }

            switch ($request->type) {
                case 'bookmarks':

                    if ($request->value ==  "up") {
                        $article->bookmarks = $article->bookmarks + 1;
                        $bundle->bookmarks = 1;
                    }
                    if ($request->value ==  "down") {
                        $article->bookmarks = $article->bookmarks - 1;
                        $bundle->bookmarks = null;
                    }
                    break;
                case 1:
                    echo "Значение переменной \$i равно 1";
                    break;
                case 2:
                    echo "Значение переменной \$i равно 2";
                    break;
            }
            $bundle->save();
            $article->save();
        } else {
            $question = Questions::find($request->id);
        }


        return redirect()->back();
    }
}
