<?php
namespace App\Actions;
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;

class AddSubscriberAction
{
    public function subscribeEmail(string $endpoint, string $topic)
    {
        $sns = new SnsClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ],
        ]);

        $protocol = 'email';
//        $topic = 'arn:aws:sns:us-east-1:211125694447:WeatherAlert';

        try {
            $result = $sns->subscribe([
               'Protocol' => $protocol,
               'Endpoint' => $endpoint,
               'TopicArn' => $topic,
               'ReturnSubscriptionArn' => true,
            ]);

            return $result;

        } catch (AwsException $e) {
            error_log($e->getMessage());
        }
    }
}
