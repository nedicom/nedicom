<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Questions;
use App\Models\Uslugi;
use App\Models\User;
use App\Models\Bundles_social;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function reaction(Request $request)
    {
        if (!Auth::user()) {
            return redirect()->back();
        }
        $bundle = false;
        /*check question or article */
        if ($request->property == "articles") {
            $bundle = Article::find($request->id);
            $reaction = Bundles_social::firstOrNew(
                ['users_id' => Auth::user()->id, 'article_id' => $bundle->id],
            );
        }
        if ($request->property == "questions") {
            $bundle = Questions::find($request->id);
            $reaction = Bundles_social::firstOrNew(
                ['users_id' => Auth::user()->id, 'question_id' => $bundle->id],
            );
        }

        if ($request->property == "uslugi") {
            $bundle = Uslugi::find($request->id);
            $reaction = Bundles_social::firstOrNew(
                ['users_id' => Auth::user()->id, 'uslugis_id' => $bundle->id],
            );
        }

        if ($request->property == "user") {
            $bundle = User::find($request->id);
            $reaction = Bundles_social::firstOrNew(
                ['users_id' => Auth::user()->id, 'lawyer_id' => $bundle->id],
            );
        }

        if (!$bundle) {
            dd($request);
        }

        switch ($request->type) {
            case 'bookmarks': //reaction type == bookmarks
                if ($request->value ==  "up") {
                    $bundle->bookmarks = $bundle->bookmarks + 1;
                    $reaction->bookmarks = 1;
                }
                if ($request->value ==  "down") {
                    $bundle->bookmarks = $bundle->bookmarks - 1;
                    $reaction->bookmarks = null;
                }
                break;
            case 'likes':
                if ($request->value ==  "up") {
                    $bundle->likes = $bundle->likes + 1;
                    $reaction->likes = 1;
                }
                if ($request->value ==  "down") {
                    $bundle->likes = $bundle->likes - 1;
                    $reaction->likes = null;
                }
                break;
            case 'shares':
                if ($request->value ==  "up") {
                    $bundle->shares = $bundle->shares + 1;
                    $reaction->shares = 1;
                }
                break;
        }
        $reaction->save();
        $bundle->save();

        return redirect()->back();
    }
}
