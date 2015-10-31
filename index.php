<?php

require_once('app/Autoloader.php');
require_once __DIR__ . '/vendor/autoload.php';

require "settings.php";

use Rabbitmq as Rabbitmq;

$objRabbitMQ = new Rabbitmq\Rabbitmq(HOST_NAME, PORT, USER_NAME, PASSWORD);

$objRabbitMQ->connect();
$objRabbitMQ->declareQueue('messages');

$messageJSON = "{
  'type': 'email',
  'from': 'no-reply@company.com',
  'recipients': ['test1@test.com', 'test2@test.com'],
  'html': 'This is a test email. It can <i>also</i> contain HTML code'
  'text': 'This is a test email. It is text only'
}";

$objRabbitMQ->publish($messageJSON, array('delivery_mode' => 2));

var_dump($objRabbitMQ);
