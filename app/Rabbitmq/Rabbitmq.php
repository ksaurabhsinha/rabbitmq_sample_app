<?php

/**
 * This is the RabbitMQ class which implements the RabbitBridge Interface and provide the basic functionality for the RabbitMQ implementation
 *
 * @package    RabbitMQ Implementation
 * @author     Kumar Saurabh Sinha
 * @version    1.0
 * ...
 */

namespace Rabbitmq;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Rabbitmq implements RabbitBridge{

    private $hostName;
    private $portNum;
    private $userName;
    private $password;
    var $channel;
    var $connection;
    var $queueName;

    /**
     * @param $hostName
     * @param $port
     * @param $username
     * @param $password
     */
    public function __construct($hostName, $port, $username, $password) {

        $this->hostName = $hostName;
        $this->portNum = $port;
        $this->userName = $username;
        $this->password = $password;

    }


    /**
     * @return bool
     */
    public function connect() {

        $this->connection = new AMQPConnection($this->hostName, $this->portNum, $this->userName, $this->password);
        $this->channel = $this->connection->channel();

        return true;

    }

    /**
     * @param $queueName
     *
     * @return bool
     */
    public function declareQueue($queueName){

        $this->queueName = $queueName;

        //Just a fail safe case where we have a blank queue name
        if($queueName == '') {
            $this->queueName = 'sample_queue';
        }

        $this->channel->queue_declare($this->queueName, false, false, false, false);

        return true;

    }

    /**
     * @param $data
     * @param $opsArray
     *
     * @return bool
     */
    public function publish($data, $opsArray) {

        if($data == '') {
            return false;
        }

        $message = new AMQPMessage($data, $opsArray);
        $this->channel->basic_publish($message, '', $this->queueName);

        return true;

    }

}