<?php

namespace App\Http\Controllers;

use App\Actions\AddSubscriberAction;
use App\Actions\CreateTopicAction;
use App\Actions\GetTopicNameAction;
use App\Actions\ListTopicsAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserCreatedWebhookController extends Controller
{
    // Get the lat and long form GeoAPI
    public function getlatlong($city_name)
    {
        // Data needed to get latitude and longitude
//        $city_name = $request->input('city');
        $api_key = env("GEOAPIFY_API_KEY");
        $type = 'city';
        $filter = 'countrycode:nz';

        try {
            $response = Http::get('https://api.geoapify.com/v1/geocode/search', [
                'text' => $city_name,
                'apiKey' => $api_key,
                'type' => $type,
                'filter' => $filter,
            ]);

            if ($response->successful()) {
                 $data = $response->json();

                // Extract lat and lon
                $latitude = $data['features'][0]['properties']['lat'];
                $longitude = $data['features'][0]['properties']['lon'];

                return [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ];

            }
            else{
                throw new \Exception('Failed to fetch latitude and longitude');
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    // Get the data from the POST request body
    public function execute(Request $request){
        $city_name = $request->city;
        $email = $request->email;

        // Call getlatlong to fetch latitude and longitude
        $latlong = $this->getlatlong($city_name);

        if ($latlong){
            $latitude = $latlong['latitude'];
            $longitude = $latlong['longitude'];
        }
        else{
            // Handle the error if latitude and longitude is null
            return response()->json(['error' => 'Unable to fetch latitude and longitude'], 500);
        }

        $topic_name = 'WeatherAlert'.$city_name;

        // Check if the City exists in the DB
        $city = DB::table('topics')
                ->where('city', $city_name)
                ->first();

        if(!$city){
            // City Not Found
            //Create a new Topic
            $new_topic = new CreateTopicAction();
            $topic = $new_topic->createTopic($topic_name);
            $topic_arn = $topic['TopicArn'];

            // Insert new record
            DB::table('topics')->insert([
                'city' => $city_name,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'topicArn' => $topic_arn,
            ]);

        } else {
            $topic_arn = $city->topicArn;
        }

        // Subscribe the user under the topic
        $action = new AddSubscriberAction();
        $response = $action->subscribeEmail($email, $topic_arn);

    }
}
