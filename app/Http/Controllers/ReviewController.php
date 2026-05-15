<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Uslugi;
use App\Casts\humandate;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;

use Carbon\Carbon;

class ReviewController extends Controller
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
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReviewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReviewRequest $request)
    {
        $data = $request->validated();

        if (empty($data['lawyer_id']) && !empty($data['usl_id'])) {
            $data['lawyer_id'] = Uslugi::where('id', $data['usl_id'])->value('user_id');
        }

        Review::create($data);
        return back()->with('message', 'Отзыв опубликован!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReviewRequest  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        abort_unless(auth()->check() && auth()->user()->isadmin, 403);
        $review->update($request->validated());
        return back()->with('message', 'Отзыв обновлён');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        abort_unless(auth()->check() && auth()->user()->isadmin, 403);
        $review->delete();
        return back()->with('message', 'Отзыв удалён');
    }
}
