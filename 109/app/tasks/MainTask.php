<?php

define('PROJECT_PATH', realpath(''));
define('BASE_PATH', dirname(__DIR__));
require_once PROJECT_PATH . '/vendor/autoload.php';
use Phalcon\Cli\Task;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class MainTask extends Task
{
    public function mainAction()
    {
        echo 'This is the default task and the default action' . PHP_EOL;
    }

    public function indexAction() 
    {
        $host ='woodpecker-01.rmq.cloudamqp.com';
		$user ='tiwwbrhz';
		$pass ='ydjYZon51In91Pyutk168u06LnTNNBmO';
		$vhost ='tiwwbrhz';
		$exchange ='subscribes';
		$queue ='test_queue_send_mess';

		$connection = new AMQPStreamConnection($host, 5672, $user, $pass, $vhost);
		$channel = $connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange,'direct',false, true,false);
        $channel->queue_bind($queue, $exchange);
        // tại chổ này email đả được lên db rabbit
        $mess = json_encode([
            "email" => "test_rabbit1MQ@gmail.com",
            "subscribed" => true
        ]);

        $message = new AMQPMessage($mess,[
            "content_type" => "text/plain", 
            "devivery_mode" => AMQPMessage::DELIVERY_MODE_NON_PERSISTENT
        ]);

        $channel->basic_publish($message, $exchange);

        $channel->close();
        $connection->close();
        // var_dump(json_decode($message->body));exit;
    }

    public function receiveAction()
    {
       	$host ='llama-01.rmq.cloudamqp.com';
		$user ='avevegll';
		$pass ='Y5iKnxqEanxWa2JK5tDY8tVQD82jSn6r';
		$vhost ='avevegll';
		$exchange ='subscribes';
		$queue ='test_queue_send_mess';
		$connection = new AMQPStreamConnection($host, 5672, $user, $pass, $vhost);
        $channel = $connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange,'direct',false, true,false);
        $channel->queue_bind($queue, $exchange);
        $callback = function (AMQPMessage $message) {
            $mess = json_decode($message->body);
            $email = $mess->email;
            // var_dump($message->body);exit;
            file_put_contents(BASE_PATH. '/data/'.$email. '.json', $message->body);
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        };

        $consumerTag = 'local.window.consummer';
        $channel->basic_consume($queue, $consumerTag, false, false, false, false, $callback);
        
        try {
            while (count($channel->callbacks)) {
                //5sec timeout
                $channel->wait(null, false, 300);
            }
        } catch(\PhpAmqpLib\Exception\AMQPTimeoutException $e){
                //catch timeout exception
        }

        $channel->close();
        $connection->close();
    }
}