<?php
namespace App\Actions;
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;

class ListTopicsAction
{
    public function listTopics(){
        $sns = new SnsClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ],
        ]);

        try {
            $result = $sns->listTopics();
            return $result;
        }
        catch (AwsException $e) {
            error_log($e->getMessage());
        }
    }

}
