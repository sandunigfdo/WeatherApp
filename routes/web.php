<?php

use App\Http\Controllers\DBAccessController;
use App\Http\Controllers\UserCreatedWebhookController;
use App\Http\Controllers\WeatherController;
use App\Http\Middleware\EnsureRequestIsAuthorized;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/user-created-webhook', [UserCreatedWebhookController::class, 'execute'])
    ->middleware(EnsureRequestIsAuthorized::class)
    ->name('user_created');

Route::get('/send-notifications', [WeatherController::class, 'getWeatherData'])->name('weather');

if (env('APP_ENV') === 'production') {
    Route::get('/index.php/topics', [DBAccessController::class, 'getDBData'])->name('topics');
    Route::delete('/index.php/topics/{topic}', [DBAccessController::class, 'destroyTopic'])->name('topics.destroy');
} else {
    Route::get('/topics', [DBAccessController::class, 'getDBData'])->name('topics');
    Route::delete('/topics/{topic}', [DBAccessController::class, 'destroyTopic'])->name('topics.destroy');
}

