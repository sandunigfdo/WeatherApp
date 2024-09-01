<?php

namespace App\Actions;

use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;

class SetSubscriptionAttributes{
    public function setAttributes(string $location, string $subscriptionArn){
        $sns = new SnsClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ],
        ]);

        $AttributeName = 'FilterPolicy';
        $AttributeValue = [
            'location' => $location,
        ];

        try {
            Log::info('I am at set attributes');
            $result = $sns->setSubscriptionAttributes([
                'SubscriptionArn' => $subscriptionArn,
                'AttributeName' => $AttributeName,
                'AttributeValue' => json_encode($AttributeValue),

            ]);
//            return $result;
            dd($result);

        } catch (AwsException $e) {
            error_log( $e->getMessage());
        }



    }
}
