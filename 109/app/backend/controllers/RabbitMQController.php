<?php 

namespace Modules\Backend\Controllers;

use Phalcon\Text;
use Phalcon\Mvc\Router;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class RabbitMQController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Admin';
        $this->_message = $this->getMessage();
    }

    public function indexAction() 
    {
        $exchange ='subscribes';
        $queue ='test_queue_send_mess';
        $connection = $this->amqconnection;
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
        $exchange ='subscribes';
        $queue ='test_queue_send_mess';
        $connection = $this->amqconnection;
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
                $channel->wait(null, false, 60);
                }
            }
        catch(\PhpAmqpLib\Exception\AMQPTimeoutException $e){
            //catch timeout exception
        }
        $channel->close();
        $connection->close();
    }
    
}
