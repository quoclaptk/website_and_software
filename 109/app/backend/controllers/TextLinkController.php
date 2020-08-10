<?php namespace Modules\Backend\Controllers;

use Modules\Models\TextLink;
use Modules\Forms\TextLinkForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Text;
use Phalcon\Image\Adapter\GD;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;

/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class TextLinkController extends BaseController
{
    public function initialize()
    {
        $this->view->setTemplateBefore('private');
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction($type)
    {

        //$numberPage = 1;


        $textlink = TextLink::find(
            array(
                "conditions" => "type = $type",
                "order" => "sort ASC, id DESC"
            )
        );
        //echo '<pre>'; print_r($textLink); echo '</pre>';die;

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
                "data" => $textlink,
                "limit" => 10,
                "page" => $numberPage
            )

        );

        $this->view->type = $type;
        $this->view->page = $paginator->getPaginate();
        $this->view->pick('textlink/index');
    }


    /**
     * Creates a User
     *
     */
    public function createAction($type)
    {
        $form = new TextLinkForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $textLink = new TextLink();
            $general = new General();
            $slug = $general->create_slug($this->request->getPost('name'));

            $textLink->assign(array(
                'name' => $this->request->getPost('name'),
                'link' => $this->request->getPost('link'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
                'type' => $type,
            ));



            if ($textLink->save()) {
                $this->flashSession->success("Thêm mớithành công");

                return $this->response->redirect('acp/text_link/index/' . $type);
            } else {
                $this->flash->error($textLink->getMessages());
            }
        }

        $this->view->type = $type;
        $this->view->form = $form;
        $this->view->pick('textlink/form');
    }

    /**
     * Saves the user from the 'edit' action
     *
     */
    public function updateAction($type, $id)
    {
        //echo '//'.$_SERVER["SERVER_NAME"].'/bongdaviet/home/files/';
        $textLink = TextLink::findFirstById($id);
        if (!$textLink) {
            $this->flash->error("Không tìm thấy bài viết");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new TextLinkForm($textLink, array('edit' => true));
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $general = new General();
            $textLink->assign(array(
                'name' => $this->request->getPost('name'),
                'link' => $this->request->getPost('link'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
                'type' => $type,
            ));

            
            if ($textLink->save()) {
                $this->flash->success("Cập nhật thành công");
            } else {
                $this->flash->error($textLink->getMessages());
            }
        }

        $this->view->type = $type;
        $this->view->textLink = $textLink;
        $this->view->form = $form;
        $this->view->pick('textlink/form');
    }


    public function showAction($type, $id)
    {
        $textLink = TextLink::findFirstById($id);
        if (!$textLink) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index', 'type => ' . $type));
        }

        $textLink->assign(array(
            'active' => 'Y',
        ));

        if ($textLink->save()) {
            $this->flash->success("Hiển thị thành công");
        }

        return $this->dispatcher->forward(array('action' => 'index', 'type =>' . $type));
    }

    public function showmultyAction($type)
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $textLink = TextLink::findFirstById($id);
            if ($textLink) {
                $textLink->assign(array(
                    'active' => 'Y',
                ));
                $textLink->save();
                $d++;
            }
        }

        if ($d > 0) {
            $this->flash->success("Hiển thị thành công");
        } else {
            $this->flash->error("Không tìm thấy dữ liệu");
        }
        return $this->dispatcher->forward(array('action' => 'index', 'type => ' . $type));
    }

    public function hideAction($type, $id)
    {
        $textLink = TextLink::findFirstById($id);
        if (!$textLink) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index', 'type =>' . $type));
        }

        $textLink->assign(array(
            'active' => 'N',
        ));

        if ($textLink->save()) {
            $this->flash->success("Ẩn thành công");
        }

        return $this->dispatcher->forward(array('action' => 'index', 'type =>' . $type));
    }

    public function hidemultyAction($type)
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $textLink = TextLink::findFirstById($id);
            if ($textLink) {
                $textLink->assign(array(
                    'active' => 'N',
                ));
                $textLink->save();
                $d++;
            }
        }

        if ($d > 0) {
            $this->flash->success("Ẩn thành công");
        } else {
            $this->flash->error("Không tìm thấy dữ liệu");
        }
        return $this->dispatcher->forward(array('action' => 'index', 'type =>' . $type));
    }


    /**
     * Deletes a News
     *
     * @param int $id
     */
    public function deleteAction($type, $id)
    {
        $textLink = TextLink::findFirstById($id);
        $photo = $textLink->photo;
        if (!$textLink) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$textLink->delete()) {
            $this->flash->error($textLink->getMessages());
        } else {
            $this->flash->success("Bài viết đã được xóa");
        }

        return $this->dispatcher->forward(array('action' => 'index', 'type' => $type));
    }

    public function deletemultyAction($type)
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $textLink = TextLink::findFirstById($id);
            if ($textLink) {
                $photo = $textLink->photo;
                $d++;
            }
        }
        //echo $d;die;
        if ($d > 0) {
            $this->flash->success("Xóa thành công");
        } else {
            $this->flash->error("Không tìm thấy dữ liệu");
        }
        return $this->dispatcher->forward(array('action' => 'index', 'type' => $type));
    }
}
