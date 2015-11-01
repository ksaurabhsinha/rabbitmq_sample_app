<?php

//Set the default timezone to UTC - Please change this to UTC on PROD
date_default_timezone_set('Asia/Kolkata');

require_once('app/Autoloader.php');     //This is the Autoloader for the Application level Classes
require_once __DIR__ . '/vendor/autoload.php';      //This is the Autoloader for the Vendor Packages

require_once "settings.php";    //This is the file for the configuration values for the Application
require_once "dummy_message.php";   //This is the file for the dummy message array

/**
 * @param $logArray
 * @param $path
 */
function writeLog($logArray, $path) {

    if(is_array($logArray))
        $logString = json_encode($logArray);
    else
        $logString = $logArray;

    $err = date('Y-m-d H:i:s') . " - " . $logString . PHP_EOL . str_repeat('-', 100) . PHP_EOL . PHP_EOL;
    error_log($err, 3, LOG_PATH . $path);

}

/**
 * @param $startTime
 * @param $endTime
 *
 * @return array
 */
function getPauseTimings($startTime, $endTime) {

    $startTimeArray = explode(":", $startTime);
    $endTimeArray = explode(":", $endTime);

    $pauseStartDatetime = date('Y-m-d ' . PAUSE_START_TIME);

    $isSameDay = true;

    if($startTimeArray[0] == $endTimeArray[0]){

        $isSameDay = true;

    }
    else {
        if($startTimeArray[0] > $endTimeArray[0]) {
            $isSameDay = false;
        }
        else {
            $isSameDay = true;
        }
    }

    if($isSameDay === false) {
        $endDay = date('Y-m-d', strtotime("+1 days"));
    }
    else {
        $endDay = date('Y-m-d');
    }

    $pauseEndDatetime = $endDay . " " . PAUSE_END_TIME;

    $pauseTimeArray = array('pause_start_time' => $pauseStartDatetime,
                            'pause_end_time' => $pauseEndDatetime);

    return $pauseTimeArray;

}