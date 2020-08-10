<?php namespace Modules\Frontend\Controllers;

use Modules\Models\ChannelGroup;
use Modules\Models\Tv;
use Phalcon\Mvc\Model\Query;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class LivetvController extends BaseController
{
    public function indexAction()
    {
        $title_bar = 'Live TV';


        $channel_group = ChannelGroup::find(
            array(
                "columns" => "id, name, slug",
                "conditions" => "active = 'Y'",
                "order" => "sort ASC, id DESC",
            )
        )->toArray();

        for ($i=0; $i<count($channel_group); $i++) {
            $channel_group[$i]['tv'] = Tv::find(
                array(
                    "columns" => "id, name, slug, photo,created_at",
                    "conditions" => "active = 'Y' AND channel_group_id = ".$channel_group[$i]['id']."",
                    "order" => "name ASC, id DESC"
                )
            )->toArray();
        }


        $this->view->title_bar = $title_bar;
        $this->view->channel_group = $channel_group;
        //$this->view->disable();
    }

    public function detailAction($slug)
    {
        $detail = Tv::findFirst(
            array(
                "conditions" => "slug = '".$slug."' AND active = 'Y'",
            )
        );

        if (empty($detail)) {
            return $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
        }

        $id = $detail->id;
        $cate_id = $detail->channel_group_id;
        $hits = $detail->hits + 1;

        $phql   = "UPDATE Modules\Models\Tv SET hits = $hits WHERE id = $id";
        $result = $this->modelsManager->executeQuery($phql);
        if ($result->success() == false) {
            foreach ($result->getMessages() as $message) {
                echo $message->getMessage();
            }
        }
        //echo $hits;die;

        $other_channel = Tv::find(
            array(
                "columns" => "id, name, slug, photo",
                "conditions" => "channel_group_id = $cate_id AND id != $id AND active = 'Y'",
                "order" => "name ASC, id DESC",
            )
        )->toArray();

        $channel_group = ChannelGroup::find(
            array(
                "columns" => "id, name, slug",
                "conditions" => "active = 'Y' AND id != $cate_id",
                "order" => "sort ASC, id DESC",
            )
        )->toArray();

        for ($i=0; $i<count($channel_group); $i++) {
            $channel_group[$i]['tv'] = Tv::find(
                array(
                    "columns" => "id, name, slug, photo,created_at",
                    "conditions" => "active = 'Y' AND channel_group_id = ".$channel_group[$i]['id']."",
                    "order" => "name ASC, id DESC"
                )
            )->toArray();
        }

        $image_meta = _URL . '/' . _upload_tv . $detail->photo;

        $this->view->title = (!empty($detail->title))? $detail->title : $detail->name;
        $this->view->keywords = (!empty($detail->keywords))? $detail->keywords : $detail->name;
        $this->view->description = (!empty($detail->description))? $detail->description : $detail->name;
        $this->view->image_meta = $image_meta;

        $this->view->detail = $detail;
        $this->view->other_channel = $other_channel;
        $this->view->channel_group = $channel_group;
    }
}
