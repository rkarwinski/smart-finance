<?php

namespace App\Http\Controllers;

use App\Services\WelcomeService;
use Illuminate\Http\JsonResponse;

class WelcomeController extends Controller
{
    public function index(): JsonResponse
    {
        $service = WelcomeService::getInstance();
        return response()->json([
            'message' => $service->getWelcomeMessage()
        ]);
    }
}
