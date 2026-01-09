<?php

namespace App\Http\Controllers;

use App\Models\Pension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StorePensionRequest;
use Illuminate\Support\Facades\Cookie;

class PensionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $years = [['year' => 2024, 'stipk' => 133.05, 'fvipl' => 8134.88], ['year' => 2025, 'stipk' => 145.69, 'fvipl' => 8907.70]];

        if (Cookie::get('pension_data')) {
            $pension = json_decode(Cookie::get('pension_data'), true);
        } else {
            $pension = ['gender' => 20, 'stagh2002' => 15, 'stagh1991' => 3, 'zp' => 201, 'szp' => 212, 'pktwo' => 550000, 'ipkn' => 20.1, 'year' => 2025];
        }

        return Inertia::render('Pension/Create', [
            'pensionData' =>  $pension,
            'years' => $years,
            'auth' => Auth::user(),
            'backendurl' => $request->path(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePensionRequest $request)
    {

        // Валидация входящих данных
        $validated = $request->validated();
        //dd($validated);
        $cookieValue = json_encode($validated);
        //dd($cookieValue);
        Cookie::queue('pension_data',  $cookieValue, 120);
        // Добавляем user_id текущего пользователя
        //$validated['user_id'] = Auth::id();

        // Создаем запись
        //$pension = Pension::create($validated);

        return back();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pension  $Pension
     * @return \Illuminate\Http\Response
     */
    public function show(Pension $Pension)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pension  $Pension
     * @return \Illuminate\Http\Response
     */
    public function edit(Pension $Pension)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pension  $Pension
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pension $Pension)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pension  $Pension
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pension $Pension)
    {
        //
    }
}
