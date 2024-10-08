<?php

namespace App\Http\Controllers;

use App\Actions\SendNotificationsAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function getWeatherData(){

        $topics = DB::table('topics')->get();
        // Take one entry from the db

        if (!$topics->isEmpty()) {
            foreach($topics as $topic) {
                // Get weather for the city
                $latitude = $topic->latitude;
                $longitude = $topic->longitude;
                $topicArn = $topic->topicArn;
                $city = $topic->city;
                $weatherData = $this->getCurrentWeather($latitude, $longitude);
                $weatherDataString = implode(" ", $weatherData);

                // Create the message
                $message = sprintf('The temperature in %s is currently %s°C, and the wind speed is %skm/h.',
                    $city,
                    $weatherData['temperature'],
                    $weatherData['wind_speed']);

                // Publish the message to the topic relevant tp the city
                $action = new SendNotificationsAction();
                $notification = $action->publishMessage($topicArn, $message);

            }

            return response()->json([
                'message' => 'Message sent to all subscribers',
            ]);
        } else {
            return response()->json([
                'message' => 'No topics in db',
            ]);
        }

    }

    public function getCurrentWeather($latitude, $longitude){
        try {
            $response = Http::get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current_weather' => true,
                'timezone' => 'auto',
                'daily' => 'rain_sum',
            ]);
            if ($response->successful()) {
                 $data = $response->json();
                 return[
                     'temperature' => $data['current_weather']['temperature'],
                     'wind_speed' => $data['current_weather']['windspeed']
                 ];
            }
            else{
                throw new \Exception('Failed to fetch Weather Data');
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
