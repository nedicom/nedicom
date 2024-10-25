<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Uslugi;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\Translate;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query = $query->filter($request->all('search'));
        }

        $users = $query->orderBy('id')->paginate(9);

        return Inertia::render('Admin/Users/Index', [
            'filters' => $request->all('search'),
            'users' => $users,
            'auth' => Auth::user(), 
        ]);
    }

    // public function formadd()
    // {
    //     return Inertia::render('Articles/Add');    
    // }

    public function create(Request $request)
    {
        $article = new User;
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

    public function edit(string $id)
    {
        if ($user = User::find($id)) {
            return Inertia::render('Admin/Users/Edit', [
                'user' => $user,
                'imgurl' => $user->file_path,
                'islawyer' => $user->lawyer,
                'status' => session('status'),
                'test' =>  Uslugi::orderBy('usl_name', 'desc')
                    ->select('id', 'usl_name')
                    ->get(),
                'auth' => Auth::user(),
                //'specializationOne' => $user->lawyerSpecOne,
                //'specializationTwo' => $user->lawyerSpecTwo,
                //'specializationThree' => $user->lawyerSpecThree,
            ]);
        }

        abort(404);
    }

    public function update(ProfileUpdateRequest $request, $id)
    {
        if ($user = User::find($id)) {
            $user->fill($request->validated());

            if ($request->lawyer == true) {
                $user->lawyer = 1;
            } else {
                $user->lawyer = 0;
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return Redirect::route('admin.users.list');
        }

        abort(404);
    }

    public function store(StoreArticleRequest $request)
    {
        //
    }

    public function delete(int $id)
    {
        if ($user = User::find($id)) {
            $user->delete();

            return redirect()->route('admin.users.list');
        }

        abort(404);
    }
}
