<?php

require_once 'bootstrap.php';

use Rabbitmq as Rabbitmq;

//Get the RabbitMQ instance
$objRabbitMQ = new Rabbitmq\Rabbitmq(RMQ_HOST, RMQ_PORT, RMQ_USERNAME, RMQ_PASSWORD);

//Create the Connection to the RabbitMQ
$objRabbitMQ->connect();

//Declare the Queue
$channel = $objRabbitMQ->declareQueue('messages');

$messageCounter = 1;

$objSwiftTransport = Swift_SmtpTransport::newInstance(MAILTRAP_HOST, MAILTRAP_PORT)
                                        ->setUsername(MAILTRAP_USERNAME)
                                        ->setPassword(MAILTRAP_PASSWORD);
$objSwiftMailer = Swift_Mailer::newInstance($objSwiftTransport);

function processMessage($msg){

    global $messageCounter, $objSwiftMailer;

    echo "Processing Message Number = $messageCounter. Press CTRL+C to stop processing\n";

    $messageCounter++;

    if(isset($msg)) {
        $dataArray = json_decode($msg->body, true);

        if(is_array($dataArray)) {

            if($dataArray['type'] == 'email'){

                // Create a message
                $message = Swift_Message::newInstance('Sample Subject')
                                            ->setFrom(array($dataArray['from']))
                                            ->setTo($dataArray['recipients'])
                                            ->setBody($dataArray['html'], 'multipart/alternative')
                                            ->addPart($dataArray['html'], 'text/html')
                                            ->addPart($dataArray['text'], 'text/plain');
                ;

                // Send the message
                $objSwiftMailer->send($message);

                //writeLog($result, LOG_FOR_EMAIL_SENT);

            }
            else if ($dataArray['type'] = 'sms') {

                $pauseTimeArray = getPauseTimings(PAUSE_START_TIME, PAUSE_END_TIME);

                if(is_array($pauseTimeArray)) {

                    $pauseStartTime = strtotime($pauseTimeArray['pause_start_time']);
                    $pauseEndTime = strtotime($pauseTimeArray['pause_end_time']);

                    $presentTime = strtotime(date('Y-m-d H:i:s'));

                    if($presentTime > $pauseStartTime && $presentTime < $pauseEndTime) {

                        global $objRabbitMQ;

                        //Add this SMS to the custom SMS queue, to be processed at the perfect time
                        $objRabbitMQ->declareQueue('sms_queue');
                        $objRabbitMQ->publish($msg->body, array('delivery_mode' => 2));

                    }
                    else {

                        writeLog($dataArray, LOG_SMS_FOR_NON_PAUSE);

                    }

                }
                else {

                    writeLog('Invalid Pause timings', LOG_PROCESS_INCOMING);

                }

            }

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
$channel->basic_consume('messages', '', false, false, false, false, 'processMessage');

while(count($channel->callbacks)) {

    $channel->wait();
}