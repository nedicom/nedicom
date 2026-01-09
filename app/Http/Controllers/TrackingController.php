<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrackingController extends Controller
{
    /**
     * прикручиваем яндекс идентификатор к собственному идентификатор
     * 
     * 
     */
    public function track(Request $request)
    {
        // Получили собственный идентификатор
        $visitUuid = $request->input('visit_uuid');

        // Берём _ym_uid из куки запроса
        $ymUid = $request->input('_ym_uid');

        // url взяли из бэкенда(публичные перменные) потому что ssr
        $currentUrl = $request->input('url');

        // isEngaged взяли из фронта (обычно 30 сек на странице)
        $isEngaged = $request->input('is_engaged');

        //чекаем наличие перменных
        if (!$visitUuid) {
            return response()->json(['success' => false, 'error' => 'No visitUuid'], 400);
        }

        if (!$ymUid) {
            return response()->json(['success' => false, 'error' => 'No _ym_uid cookie'], 400);
        }

        if (!$currentUrl) {
            return response()->json(['success' => false, 'error' => 'No currentUrl'], 400);
        }

        //пробуем запись по visit_uuid и url
        try {
            $record = DB::connection('pgsql_stats')
                ->table('yandex_tracking')
                ->where('visit_uuid', $visitUuid)
                ->where('url', $currentUrl)
                ->latest('created_at')
                ->first();

            if ($record) {
                // дописываем is_engaged
                if ($isEngaged) {
                    DB::connection('pgsql_stats')
                        ->table('yandex_tracking')
                        ->where('id', $record->id)
                        ->update(['is_engaged' => true]);
                    return response()->json(['success' => true, 'isEngaged' => $isEngaged]);
                }
                // дописываем _ym_uid
                DB::connection('pgsql_stats')
                    ->table('yandex_tracking')
                    ->where('id', $record->id)
                    ->update(['_ym_uid' => $ymUid]);
            }

            return response()->json(['success' => true, 'visit_uuid' => $visitUuid]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function trackPhoneClick(Request $request)
    {
        // Получили собственный идентификатор
        $visitUuid = $request->input('visit_uuid');

        // url взяли из бэкенда(публичные перменные) потому что ssr
        $currentUrl = $request->input('url');

        // получили phone_click_at
        $phoneClickAt = $request->input('phone_click_at');

        //чекаем наличие перменных
        if (!$visitUuid) {
            return response()->json(['success' => false, 'error' => 'No visitUuid'], 400);
        }

        if (!$currentUrl) {
            return response()->json(['success' => false, 'error' => 'No currentUrl'], 400);
        }

        if (!$phoneClickAt) {
            return response()->json(['success' => false, 'error' => 'No phoneClick'], 400);
        }

        //пробуем запись по visit_uuid и url
        try {

            $parsedDate = Carbon::parse($phoneClickAt);

            // Если парсинг не удался, используем текущее время
            if (!$parsedDate) {
                $parsedDate = now();
            }

            $record = DB::connection('pgsql_stats')
                ->table('yandex_tracking')
                ->where('visit_uuid', $visitUuid)
                ->where('url', $currentUrl)
                ->latest('created_at')
                ->first();

            if ($record) {
                if (!$record->phone_click_at) {
                    // дописываем phone_click_at 
                    DB::connection('pgsql_stats')
                        ->table('yandex_tracking')
                        ->where('id', $record->id)
                        ->update([
                            'phone_click_at' => $parsedDate->format('Y-m-d H:i:s')
                        ]);
                }
            }

            return response()->json(['success' => true, 'phone_click_at' => $phoneClickAt]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
