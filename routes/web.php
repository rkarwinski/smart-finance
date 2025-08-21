<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WelcomeController;

Route::get('/api/welcome', [WelcomeController::class, 'index']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
