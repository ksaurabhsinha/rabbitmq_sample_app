<?php

//Set the default timezone to UTC
date_default_timezone_set('UTC');

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