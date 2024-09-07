<?php

use App\Actions\SendNotificationsAction;
use App\Http\Controllers\DBAccessController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\UserCreatedWebhookController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/weather', [WeatherController::class, 'getWeatherData'])->name('weather');

Route::get('/test', [SendNotificationsAction::class, 'execute'])->name('test');

Route::post('/user_created', [UserCreatedWebhookController::class, 'execute'])->name('user_created');

Route::post('/send_message', [NotificationsController::class, 'sendNotification'])->name('send_message');

Route::get('/hello', function () {
    return response()->json('Hi, there!');
});

Route::get('/topics', [DBAccessController::class, 'getDBData'])->name('db');

