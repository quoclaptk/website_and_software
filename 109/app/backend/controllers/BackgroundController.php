<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Background;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Image\Adapter\GD;

class BackgroundController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Background';
    }

    /**
     *
     * Delete Background Image
     *
     * @return bolean
     */
    public function deletePhotoAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $id = $this->request->getPost('id');
            $item = Background::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $photo = $item->photo;
                $item->assign([
                    'photo' => ''
                ]);
                if ($item->save()) {
                    @unlink("files/default/" . $this->_get_subdomainFolder() . "/" . $photo);
                    
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        }

        $this->view->disable();
    }
}
