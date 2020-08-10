<?php namespace Modules\Frontend\Controllers;

use Modules\Models\Channel;
use Modules\Models\Streaming;
use Modules\PhalconVn\General;
use Phalcon\Mvc\Model\Query;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class ChannelController extends BaseController
{
    public function viewAction($id)
    {
        $detail = Channel::findFirst(
            array(
                "conditions" => "id = '".$id."' AND active = 'Y'",
            )
        );

        if (empty($detail)) {
            return $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
        }

        $hits = $detail->hits + 1;

        $phql   = "UPDATE Modules\Models\Channel SET hits = $hits WHERE id = $id";
        $result = $this->modelsManager->executeQuery($phql);
        if ($result->success() == false) {
            foreach ($result->getMessages() as $message) {
                echo $message->getMessage();
            }
        }
        
        $this->view->detail = $detail;
    }
}
