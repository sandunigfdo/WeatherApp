<?php

use App\Http\Controllers\DBAccessController;
use App\Http\Controllers\UserCreatedWebhookController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/weather', [WeatherController::class, 'getWeatherData'])->name('weather');

Route::post('/user_created', [UserCreatedWebhookController::class, 'execute'])->name('user_created');

Route::get('/topics', [DBAccessController::class, 'getDBData'])->name('topics');
Route::delete('/topics/{topic}', [DBAccessController::class, 'destroyTopic'])->name('topics.destroy');

