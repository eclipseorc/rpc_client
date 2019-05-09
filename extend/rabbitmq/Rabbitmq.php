<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/4/1
 * Time: 19:03
 */
namespace rabbitmq;

use http\Env\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Rabbitmq
{
    private $host		= '127.0.0.1';
    private $vhost		= '/';
    private $port		= 5672;
    private $login		= 'bbc123456';
    private $password	= 'abc123456';

    const   AMQP_EXCHANGE_TYPE_DIRECT   = 'direct';
    const   AMQP_EXCHANGE_TYPE_FANOUT   = 'fanout';
    const   AMQP_EXCHANGE_TYPE_HEADER   = 'header';
    const   AMQP_EXCHANGE_TYPE_TOPIC    = 'topic';

    public function publish($msg)
    {
        $connection = new AMQPStreamConnection($this->host, $this->port, $this->login, $this->password);
        $channel	= $connection->channel();
        $channel->set_ack_handler(function (AMQPMessage $message) {
            echo "Message acked with content: " . $message->body;
        });

        $channel->set_nack_handler(function (AMQPMessage $message) {
            echo "Message nacked with content: " .  $message->body;
        });

        $exchange   = 'exchange';
        $queueName  = QueueName::BAIDU;
        $routingKey = 'hot';

        $channel->confirm_select();
        $channel->exchange_declare($exchange, 'fanout', false, false, false);
        $channel->queue_declare($queueName, false, true, false, false);
        $channel->queue_bind($queueName, $exchange, $routingKey);

        $message = new AMQPMessage($msg, ['content_type' => 'text/plain']);
        $channel->basic_publish($message, $exchange, $routingKey);
        $channel->wait_for_pending_acks();
        $channel->close();
        $connection->close();
    }


    public function msgWrap($msg)
    {
        return [
            'timestamp' => date('Y-m-d H:i:s'),
            'method'    => Request::action(),
        ];
    }
}