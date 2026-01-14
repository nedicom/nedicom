<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TrackingController extends Controller
{
    /**
     * прикручиваем яндекс идентификатор к собственному идентификатор
     * 
     * 
     */
    public function track(Request $request)
    {
        try {
            // Упрощенная валидация
            $validator = Validator::make($request->all(), [
                'visit_uuid' => 'required|string|max:255',
                'url' => 'required|string|max:255',
                '_ym_uid' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                Log::warning('Track validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'data' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $visitUuid = $request->input('visit_uuid');
            $url = $request->input('url');
            $ymUid = $request->input('_ym_uid');

            Log::info('Track processing', [
                'visit_uuid' => $visitUuid,
                'url' => substr($url, 0, 100),
                'has_ym_uid' => !empty($ymUid)
            ]);

            // Ищем существующую запись
            $record = DB::connection('pgsql_stats')
                ->table('yandex_tracking')
                ->where('visit_uuid', $visitUuid)
                ->where('url', $url)
                ->latest('created_at')
                ->first();

            if (!$record) {
                Log::warning('No record found for tracking', [
                    'visit_uuid' => $visitUuid,
                    'url' => $url
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'No tracking record found'
                ], 404);
            }

            // Подготавливаем данные для обновления
            $updateData = [];

            // Добавляем _ym_uid только если он передан и еще не установлен
            if ($ymUid && !$record->_ym_uid) {
                $updateData['_ym_uid'] = $ymUid;
            }

            // Устанавливаем is_engaged в true если еще false
            if (!$record->is_engaged) {
                $updateData['is_engaged'] = true;
            }

            // Если нечего обновлять - возвращаем успех
            if (empty($updateData)) {
                Log::info('Nothing to update', ['record_id' => $record->id]);

                return response()->json([
                    'success' => true,
                    'record_id' => $record->id,
                    'has_ym_uid' => !empty($record->_ym_uid),
                    'is_engaged' => (bool)$record->is_engaged,
                    'message' => 'Already up to date'
                ]);
            }

            // Выполняем обновление
            DB::connection('pgsql_stats')
                ->table('yandex_tracking')
                ->where('id', $record->id)
                ->update($updateData);

            Log::info('Track updated', [
                'record_id' => $record->id,
                'updates' => $updateData
            ]);

            return response()->json([
                'success' => true,
                'record_id' => $record->id,
                'has_ym_uid' => !empty($ymUid) || !empty($record->_ym_uid),
                'is_engaged' => true,
                'updates_applied' => array_keys($updateData)
            ]);
        } catch (\Exception $e) {
            Log::error('Track error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Server error'
            ], 500);
        }
    }

    public function trackPhoneClick(Request $request)
    {
        // Получили собственный идентификатор
        $visitUuid = $request->input('visit_uuid');

        // url взяли из бэкенда(публичные перменные) потому что ssr
        $currentUrl = $request->input('url');

        //чекаем наличие перменных
        if (!$visitUuid) {
            return response()->json(['success' => false, 'error' => 'No visitUuid'], 400);
        }

        if (!$currentUrl) {
            return response()->json(['success' => false, 'error' => 'No currentUrl'], 400);
        }

        //пробуем запись по visit_uuid и url
        try {
            $parsedDate = now();

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

            return response()->json(['success' => true, 'phone_click_at' => $parsedDate]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
