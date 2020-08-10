<?php 

namespace Modules\Backend\Controllers;

use Phalcon\Text;
use Phalcon\Mvc\Router;
use Modules\Forms\QueueForm;
use Phalcon\Mailer\Manager;
use Phalcon\Queue\Beanstalk As Beanstalk;

class QueueController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Admin';
        $this->_message = $this->getMessage();
    }

    public function beanStackAction() {
        while (($job = $this->queue->peekReady()) !== false) {
            $message = $job->getBody();

            var_dump($message);exit;

            $job->delete();
        }
    }

    public function indexAction()
    {
        $form = new QueueForm();
        $this->view->setVar('form', $form);
    }

    public function sendAction()
    {

        $config = [
            'fromName' => '110.vn',
            'fromEmail' => '110',
            'smtp' => array(
            'server' => '45.117.169.19',
            'port' => 587,
            'secure' => 'tls',
            'username' => 'noreply@110.vn',
            'password' => 'IiUKlVVa',
        )];
        
        $mailer = new \Phalcon\Ext\Mailer\Manager($config);
        $queue = new Beanstalk(
            array(
                'host' => '127.0.0.1',
                'port' => '80'
            )
        );
        $queue->put(
            array(
                'sendEmail' => rand()
            )
        );
            var_dump( $queue);exit;
        while (($job = $queue->peekReady()) !== false) {
            $to = $this->request->getPost('to');
            $subject = $this->request->getPost('subject');
            $content = $this->request->getPost('content');
            $message = $mailer->createMessage()
                ->to($to)
                ->subject($subject)
                ->content($content);
            $message->send();
            $job->delete();
        }
        return $this->flash->success("Email has been put to the queue!");
        }

}
