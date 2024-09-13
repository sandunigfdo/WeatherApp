<?php
namespace App\Actions;
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;

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

        } catch(AwsException $e) {
            error_log("Aws Exception: ".$e->getMessage());
        }

    }

}
