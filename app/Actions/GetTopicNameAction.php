<?php
namespace App\Actions;
class GetTopicNameAction
{
    public function getTopicName(string $topicArn){
        // Split the ARN bby the colon
        $arn_parts = explode(':', $topicArn);

        // Extract the topic name
        $topicName = end($arn_parts);
        return $topicName;
    }

}
