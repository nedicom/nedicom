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

        if ($request->hasAny(['search', 'lawyer'])) {
            $query = $query->filter($request->all());
        }

        $users = $query->orderBy('id')->paginate(9)->appends($request->except(['page','_token']));;

        return Inertia::render('Admin/Users/Index', [
            'filters' => $request->all(),
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
        $user = new User;
        $user->userid = Auth::user()->id;
        $user->username = Auth::user()->name;
        $user->header = $request->header;
        $user->description = $request->description;
        $user->body = $request->body;
        $user->body = $request->body;
        $user->body = $request->body;
        $user->body = $request->body;
        $url = Translate::translit($request->header);
        $user->url =  $url;
        $user->save();
        return redirect()->route('admin.users.edit', $url);
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
