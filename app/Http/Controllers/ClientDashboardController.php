<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class ClientDashboardController extends Controller
{
    public function dashboard()
    {
        $clientId = auth()->id();

        try {
            $response = Http::withToken(config('services.crm.token'))
                ->timeout(5)
                ->get(config('services.crm.url') . "/client/{$clientId}/summary");

            $data = $response->successful() ? $response->json() : null;

        } catch (\Exception $e) {
            $data = null;            
        }

        return Inertia::render('ClientDashboard', [
            'clientData' => $data
        ]);
    }
}