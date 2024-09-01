<?php
namespace App\Actions;
use App\Actions\AddSubscriberAction;
use App\Http\Controllers\WeatherController;
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use App\Actions\GetSubscribersAction;

class SendNotificationsAction
{
    public function publishMessage(string $topic, string $message){
        // Create the SnsClient instance class to configure necessary credentials
        $sns = new SnsClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ],
        ]);

        try {
            // Publish method of the SnsClient
            $response = $sns->publish([
                'Message' => $message,
                'TargetArn' => $topic,
            ]);

            var_dump($response);

        } catch(AwsException $e) {
            error_log("Aws Exception: ".$e->getMessage());
        }

    }

}
