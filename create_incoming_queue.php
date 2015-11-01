<?php

require_once 'bootstrap.php';

use Rabbitmq as Rabbitmq;

//Get the RabbitMQ instance
$objRabbitMQ = new Rabbitmq\Rabbitmq(RMQ_HOST, RMQ_PORT, RMQ_USERNAME, RMQ_PASSWORD);

//Create the Connection to the RabbitMQ
$objRabbitMQ->connect();

//Declare the Queue
$objRabbitMQ->declareQueue('messages');


/**
 * @param $messageArray
 * @param int $numOfMessage
 */
function addDummyMessageToIncomingQueue($messageArray, $numOfMessage = 10) {

    global $objRabbitMQ;

    $counter = 1;

    while($counter <= $numOfMessage){

        $randomValue = rand(0,1);
        $messageJSON = $messageArray[$randomValue];

        //Publish the message to the defined Queue
        $objRabbitMQ->publish($messageJSON, array('delivery_mode' => 2));

        $counter++;

    }

}


//Create the queue with Dummy incoming values
addDummyMessageToIncomingQueue($messageArray, 10);

