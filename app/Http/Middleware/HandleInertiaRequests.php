<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use App\Models\Dialogue;
use App\Models\cities;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' =>  ($request->url()) ? $request->url() : 'errorpage',
                ]);
            },
            'flash' => [
                'message' => fn () => $request->session()->get('message')
            ],
            'dialogue' => [
                'message'  =>  fn () => Dialogue::find($request->session()->get('dialogue')),
            ],
            'usercity' => [
                'title'  =>  fn () => session()->get('citytitle')
                ? session()->get('citytitle')
                : null,
            ],
            'cookie' => fn () => session()->get('cookie')
                ? session()->get('cookie')
                : false,            
        ]);
    }
}
