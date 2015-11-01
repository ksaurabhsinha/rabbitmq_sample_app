<?php

/**
 * This is the Interface for the RabbitMQ Implementation
 *
 * @package    RabbitMQ Implementation
 * @author     Kumar Saurabh Sinha
 * @version    1.0
 * ...
 */

namespace Rabbitmq;

interface RabbitBridge {

    /**
     * @return mixed
     */
    function connect();


    /**
     * @param $queueName
     *
     * @return mixed
     */
    function declareQueue($queueName);


    /**
     * @param $data
     * @param $opsArray
     *
     * @return mixed
     */
    function publish($data, $opsArray);


}