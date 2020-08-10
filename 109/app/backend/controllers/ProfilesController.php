<?php namespace Modules\Backend\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Modules\Forms\ProfilesForm;
use Modules\Models\Profiles;

/**
 * Modules\Backend\Controllers\ProfilesController
 *
 * CRUD to manage profiles
 */
class ProfilesController extends BaseController
{
    public function initialize()
    {
        $this->view->setTemplateBefore('private');
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction()
    {
        //$this->persistent->conditions = null;
        //$this->view->form = new ProfilesForm();
        $profiles = Profiles::find(
                array(
                            "conditions" => "id != 1",
                            "order" => "sort ASC, id DESC",
                    )
            );
            
        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
                array(
                            "data" => $profiles,
                            "limit" => 10,
                            "page" => $numberPage
                    )

            );

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Searches for profiles
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Modules\Models\Profiles', $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $profiles = Profiles::find($parameters);
        if (count($profiles) == 0) {
            $this->flash->notice("The search did not find any profiles");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $profiles,
            "limit" => 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Creates a new Profile
     *
     */
    public function createAction()
    {
        if ($this->request->isPost()) {
            $profile = new Profiles();

            $profile->assign(array(
                            'name' => $this->request->getPost('name', 'striptags'),
                            'active' => $this->request->getPost('active'),
                            'sort' => $this->request->getPost('sort')
            ));

            if (!$profile->save()) {
                $this->flash->error($profile->getMessages());
            } else {
                $this->flash->success("Profile was created successfully");
                return $this->response->redirect('acp/profiles');
            }

            Tag::resetInput();
        }

        $this->view->form = new ProfilesForm(null);
    }

    /**
     * Edits an existing Profile
     *
     * @param int $id
     */
    public function editAction($id)
    {
        $profile = Profiles::findFirstById($id);
        if (!$profile) {
            $this->flash->error("Profile was not found");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if ($this->request->isPost()) {
            $profile->assign(array(
                            'name' => $this->request->getPost('name', 'striptags'),
                            'active' => $this->request->getPost('active'),
                            'sort' => $this->request->getPost('sort')
            ));

            if (!$profile->save()) {
                $this->flash->error($profile->getMessages());
            } else {
                $this->flash->success("Profile was updated successfully");
            }

            Tag::resetInput();
        }

        $this->view->form = new ProfilesForm($profile, array('edit' => true));

        $this->view->profile = $profile;
    }

    /**
     * Deletes a Profile
     *
     * @param int $id
     */
    public function deleteAction($id)
    {
        $profile = Profiles::findFirstById($id);
        if (!$profile) {
            $this->flash->error("Profile was not found");

            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$profile->delete()) {
            $this->flash->error($profile->getMessages());
        } else {
            $this->flash->success("Profile was deleted");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }
        
    public function deletemultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $profile = Profiles::findFirstById($id);
            if ($profile) {
                $profile->delete();
                $d++;
            }
        }
        //echo $d;die;
        if ($d > 0) {
            $this->flash->success("Profile was deleted");
        } else {
            $this->flash->error("Profile was not found");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }
        
    public function showAction($id)
    {
        $profile = Profiles::findFirstById($id);
        if (!$profile) {
            $this->flash->error("Profile was not found");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $profile->assign(array(
            'active' => 'Y',
        ));

        if ($profile->save()) {
            $this->flash->success("Profile was show successfully");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function showmultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $profile = Profiles::findFirstById($id);
            if ($profile) {
                $profile->assign(array(
                    'active' => 'Y',
                ));
                $profile->save();
                $d++;
            }
        }

        if ($d > 0) {
            $this->flash->success("Profile was show successfully");
        } else {
            $this->flash->error("Category was not found");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function hideAction($id)
    {
        $profile = Profiles::findFirstById($id);
        if (!$profile) {
            $this->flash->error("Profile was not found");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $profile->assign(array(
            'active' => 'N',
        ));

        if ($profile->save()) {
            $this->flash->success("Profile was hide successfully");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function hidemultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $profile = Profiles::findFirstById($id);
            if ($profile) {
                $profile->assign(array(
                    'active' => 'N',
                ));
                $profile->save();
                $d++;
            }
        }

        if ($d > 0) {
            $this->flash->success("Category was hide successfully");
        } else {
            $this->flash->error("Category was not found");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }
}
