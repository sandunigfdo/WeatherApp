<?php
namespace App\Actions;
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;

class GetSubscribersAction
{
    public function getSubscriberList()
    {
        $sns = new SnsClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ],
        ]);

        $topic = 'arn:aws:sns:us-east-1:211125694447:WeatherAlert';
        $allSubscribers = [];
        $nextToken = null;

        try {
            do{
                $result = $sns->listSubscriptionsByTopic([
                    'TopicArn' => $topic,
                    'NextToken' => $nextToken,
                ]);

                // Merge the current batch of subscriptions with the overall list
                $allSubscribers = array_merge($allSubscribers, $result->get('Subscriptions'));

                // Get the next token if present
                $nextToken = $result->get('NextToken');
            } while ($nextToken);

            return $allSubscribers;

        } catch (AwsException $e) {
            error_log($e->getMessage());
        }
    }
}
