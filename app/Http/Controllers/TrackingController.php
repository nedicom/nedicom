<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function track(Request $request)
    {
        // Сохраняем в базу
        DB::connection('pgsql_stats')->table('yandex_tracking')->insert([
            '_ym_uid' => $request->input('_ym_uid'),
            'yandexuid' => $request->input('yandexuid'),
            'yandex_login' => $request->input('yandex_login'),
            'url' => $request->input('url'),
            'ip' => $request->ip(),
            'created_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    }
}