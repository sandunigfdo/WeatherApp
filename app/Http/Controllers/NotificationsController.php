<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function createMessage(){
        $weather_data = new WeatherController();
        $message_data = $weather_data->getWeatherData();

    }
    public function sendNotification(Request $request){

    }
}
