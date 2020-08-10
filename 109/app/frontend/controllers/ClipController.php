<?php namespace Modules\Frontend\Controllers;

use Modules\Models\Clip;
use Modules\Models\Subdomain;
use Modules\PhalconVn\General;
use Phalcon\Mvc\Model\Query;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class ClipController extends BaseController
{
    public function indexAction($page = 1)
    {
        $clip = Clip::find(
            array(
                    "columns" => "id, name, slug, photo, folder, created_at",
                    "conditions" => "active = 'Y'",
                    "order" => "id DESC"
                )
        );

        $title_bar = 'Clip bóng đá sưu tầm';

        $currentPage =  $page;

        $paginator   = new PaginatorModel(
            array(
                "data"  => $clip,
                "limit" => 24,
                "page"  => $currentPage
            )
        );

        $this->view->title = 'Clip bóng đá sưu tầm';
        $this->view->keywords = 'Clip bóng đá sưu tầm';
        $this->view->description = 'Clip bóng đá sưu tầm';
        $this->view->title_bar = $title_bar;
        $this->view->page = $paginator->getPaginate();
        $this->view->url_page = 'clip-bong-da';
    }

    public function detailAction($slug)
    {
        $detail = Clip::findFirst(
            array(
                "conditions" => "slug = '".$slug."' AND active = 'Y'",
            )
        );

        if (empty($detail)) {
            return $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
        }

        $id = $detail->id;
        $hits = $detail->hits + 1;

        $phql   = "UPDATE Modules\Models\Clip SET hits = $hits WHERE id = $id";
        $result = $this->modelsManager->executeQuery($phql);
        if ($result->success() == false) {
            foreach ($result->getMessages() as $message) {
                echo $message->getMessage();
            }
        }
        //echo $hits;die;

        $other_clip = Clip::find(
            array(
                "columns" => "id, name, slug, photo, folder",
                "conditions" => "id != $id AND active = 'Y'",
                "order" => "id DESC",
                "limit" => 12
            )
        )->toArray();

        

        $image_meta = PROTOCOL . HOST . '/' . _upload_youtube . $detail->folder . '/' . $detail->photo;

        $this->view->title = (!empty($detail->title))? $detail->title : $detail->name;
        $this->view->keywords = (!empty($detail->keywords))? $detail->keywords : $detail->name;
        $this->view->description = (!empty($detail->description))? $detail->description : $detail->name;
        $this->view->image_meta = $image_meta;

        $this->view->detail = $detail;
        $this->view->other_clip = $other_clip;
        $this->view->created_at = date('H:i:s d/m/Y', strtotime($detail->created_at));
    }
}
