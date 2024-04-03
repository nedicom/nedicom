<?php

namespace App\Http\Controllers\Admin;

use App\Models\Uslugi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;


class UslugiController extends Controller
{

    public function index(Request $request)
    {  
        $query = Uslugi::query();

        if ($request->has('search')) {
            $query = $query->filter($request->all('search'));
        }

        $uslugi = $query->orderBy('created_at', 'desc')->with('firstlawyer')->paginate(9);

        return Inertia::render('Admin/Uslugi/Index', [
            'filters' => $request->all('search'),
            'uslugi' => $uslugi
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