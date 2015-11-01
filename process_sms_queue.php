<?php

require_once 'bootstrap.php';

use Rabbitmq as Rabbitmq;

//Get the RabbitMQ instance
$objRabbitMQ = new Rabbitmq\Rabbitmq(RMQ_HOST, RMQ_PORT, RMQ_USERNAME, RMQ_PASSWORD);

//Create the Connection to the RabbitMQ
$objRabbitMQ->connect();

//Declare the Queue
$channel = $objRabbitMQ->declareQueue('sms_queue');

$messageCounter = 1;

function processMessage($msg){

    global $messageCounter;
    echo "Processing Message Number = $messageCounter. Press CTRL+C to stop processing\n";

    $messageCounter++;

    if(isset($msg)) {

        $dataArray = json_decode($msg->body, true);

        if(is_array($dataArray)) {

            writeLog($dataArray, LOG_SMS_FOR_NON_PAUSE);

            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

        }
        else{
            writeLog('Invalid JSON', LOG_PROCESS_INCOMING);
        }
    }
    else{
        writeLog('Invalid Message Parameter in Callback', LOG_PROCESS_INCOMING);
    }
}

$channel->basic_qos(null, 1, null);
$channel->basic_consume('sms_queue', '', false, false, false, false, 'processMessage');

while(count($channel->callbacks)) {

    $channel->wait();
}