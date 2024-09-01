<?php

namespace App\Http\Controllers;

use App\Actions\AddSubscriberAction;
use App\Actions\CreateTopicAction;
use App\Actions\GetTopicNameAction;
use App\Actions\ListTopicsAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserCreatedWebhookController extends Controller
{
    public function execute(Request $request){
        $city_name = $request->city;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $email = $request->email;

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
