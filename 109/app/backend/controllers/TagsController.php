<?php namespace Modules\Backend\Controllers;

use Modules\Models\Tags;
use Modules\Forms\TagsForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use halcon\Mvc\Model\Resultset\Simple;

/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class TagsController extends BaseController
{
    public function onConstruct()
    {
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction()
    {
        $tags = Tags::find(
            array(
                "order" => "sort ASC, id DESC",
            )
        );

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
                    "data" => $tags,
                    "limit" => 10,
                    "page" => $numberPage
                )

        );

        $this->view->page = $paginator->getPaginate();
        //echo '<pre>'; print_r($tags); echo '</pre>';die;
    }


    /**
     * Creates a User
     *
     */
    public function createAction()
    {
        $form = new TagsForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $tag = new Tags();
            $general = new General();
            $slug = $general->create_slug($this->request->getPost('name'));

            $tag->assign(array(
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
            ));

            if ($tag->save()) {
                $this->flashSession->success("Tags was created successfully");

                return $this->response->redirect('acp/tags');
            } else {
                $this->flash->error($tag->getMessages());
            }
        }
                
        $this->view->pick('tags/form');
        $this->view->title_bar = 'Thêm mới Tag';
        $this->view->form = $form;
    }

    /**
     * Saves the user from the 'edit' action
     *
     */
    public function updateAction($id)
    {
        $tag = Tags::findFirstById($id);
        if (!$tag) {
            $this->flash->error("Tags was not found");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $form = new TagsForm($tag, array('edit' => true));
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $general = new General();
            $slug = $general->create_slug($this->request->getPost('name'));
            $tag->assign(array(
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
            ));
            if ($tag->save()) {
                $this->flash->success("Tag was update successfully");

                return $this->response->redirect('acp/tags');
            } else {
                $this->flash->error($tag->getMessages());
            }
        }
                
                
        $this->view->pick('tags/form');
        $this->view->title_bar = 'Cập nhật Tag';
        $this->view->form = $form;
    }

    

    public function showAction($id)
    {
        $tag = Tags::findFirstById($id);
        if (!$tag) {
            $this->flash->error("Tags was not found");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $tag->assign(array(
            'active' => 'Y',
        ));

        if ($tag->save()) {
            $this->flash->success("Tags was show successfully");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function showmultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $tag = Tags::findFirstById($id);
            if ($tag) {
                $tag->assign(array(
                    'active' => 'Y',
                ));
                $tag->save();
                $d++;
            }
        }

        if ($d > 0) {
            $this->flash->success("Tags was show successfully");
        } else {
            $this->flash->error("Tags was not found");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function hideAction($id)
    {
        $tag = Tags::findFirstById($id);
        if (!$tag) {
            $this->flash->error("Tags was not found");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $tag->assign(array(
            'active' => 'N',
        ));

        if ($tag->save()) {
            $this->flash->success("Tags was hide successfully");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function hidemultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $tag = Tags::findFirstById($id);
            if ($tag) {
                $tag->assign(array(
                    'active' => 'N',
                ));
                $tag->save();
                $d++;
            }
        }

        if ($d > 0) {
            $this->flash->success("Tags was hide successfully");
        } else {
            $this->flash->error("Tags was not found");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }


    /**
     * Deletes a Tags
     *
     * @param int $id
     */
    public function deleteAction($id)
    {
        $tag = Tags::findFirstById($id);
        if (!$tag) {
            $this->flash->error("Tags was not found");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$tag->delete()) {
            $this->flash->error($tag->getMessages());
        } else {
            $this->flash->success("Tags was deleted");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function deletemultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $tag = Tags::findFirstById($id);
            if ($tag) {
                $tag->delete();
                $d++;
            }
        }
        //echo $d;die;
        if ($d > 0) {
            $this->flash->success("Tags was deleted");
        } else {
            $this->flash->error("Tags was not found");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }
}
