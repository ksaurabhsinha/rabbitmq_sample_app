<?php

require_once 'bootstrap.php';

use Rabbitmq as Rabbitmq;

$objRabbitMQ = new Rabbitmq\Rabbitmq(RMQ_HOST, RMQ_PORT, RMQ_USERNAME, RMQ_PASSWORD);

$objRabbitMQ->connect();
$channel = $objRabbitMQ->declareQueue('messages');

$messageCounter = 1;

function processMessage($msg){

    global $messageCounter;
    echo "Processing Message Number = $messageCounter. Press CTRL+C to stop processing\n";

    $messageCounter++;

    if(isset($msg)) {
        $dataArray = json_decode($msg->body, true);

        if(is_array($dataArray)) {

            if($dataArray['type'] == 'email'){

                //Send the email to the specific email address
                writeLog($dataArray, 'processed_email_at_valid_time.log');


            }
            else if ($dataArray['type'] = 'sms') {

                $pauseTimeArray = getPauseTimings(PAUSE_START_TIME, PAUSE_END_TIME);

                if(is_array($pauseTimeArray)) {

                    $pauseStartTime = $pauseTimeArray['pause_start_time'];
                    $pauseEndTime = $pauseTimeArray['pause_end_time'];

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