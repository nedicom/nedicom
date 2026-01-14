<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\CitySet;

use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{

    public function City(Request $request)
    {
        try {
            // Устанавливаем город (пустая строка для $cityurl = город по умолчанию)
           CitySet::CitySet($request, '', false);
            
        } catch (\Exception $e) {
            Log::error('Error in City method: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при установке города'
            ], 500);
        }
    }
}
