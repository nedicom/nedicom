<?php

namespace App\Http\Controllers\Admin;

use App\Models\Uslugi;
use App\Models\User;
use App\Models\cities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;


class UslugiController extends Controller
{

    public function index(Request $request)
    {

        $query = Uslugi::query();

        if ($request->author || $request->search || $request->city || $request->main || $request->second || $request->feed) {
            $query = $query->uslfilter($request->all());
        }
        
        $uslugi = $query->with('firstlawyer')->with('cities')->paginate(50);

        return Inertia::render('Admin/Uslugi/Index', [
            'filters' => $request->all(),
            'uslugi' => $uslugi,
            'lawyers' => User::Has('HasUslugi')->get(),
            'cities' => cities::get(),
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
        if ($usluga = Uslugi::find($id)) {
            $usluga->delete();

            return redirect()->route('admin.uslugi.list');
        }

        abort(404);
    }
}
