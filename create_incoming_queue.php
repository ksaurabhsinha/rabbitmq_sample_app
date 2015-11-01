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















//var_dump($objRabbitMQ);
/*
$transport = Swift_SmtpTransport::newInstance('mailtrap.io', 25)
    ->setUsername('48812e3a42fcc714d')
    ->setPassword('4b2647cb61303f')
;

$mailer = Swift_Mailer::newInstance($transport);

// Create a message
$message = Swift_Message::newInstance('Wonderful Subject')
    ->setFrom(array('saurabh@saurabhsinha.in' => 'Kumar Saurabh Sinha'))
    ->setTo(array('sinha.ksaurabh@gmail.com' => 'Saurabh Sinha'))
    ->setBody('Here is the message itself')
;

// Send the message
$result = $mailer->send($message);

*/
