<?php namespace Modules\Backend\Controllers;

use Modules\Models\Languages;
use Modules\Models\WordCore;
use Modules\Forms\LanguagesForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Text as TextRandom;
use Phalcon\Image\Adapter\GD;
use Phalcon\Mvc\Dispatcher;

/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class LanguagesController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Đa ngôn ngữ';
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $identity = $this->auth->getIdentity();
        if ($identity['role'] != 1) {
            $dispatcher->forward(array(
                'module' => 'backend',
                'controller' => 'index',
                'action' => 'noPermission'
            ));

            return false;
        }
    }

    public function indexAction()
    {
        $list = Languages::find(
            array(
                "order" => "sort ASC, id DESC",
                "conditions" => "deleted = 'N'"
            )
        );

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
                "data" => $list,
                "limit" => 20,
                "page" => $numberPage
            )

        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        if ($this->request->isPost()) {
            foreach ($paginator->getPaginate()->items as $language) {
                //save sort
                $sortValue = $this->request->getPost('sort_' . $language->id);
                if (!empty($sortValue)) {
                    $language->sort = $sortValue;
                } else {
                    $language->sort = 1;
                }

                $language->save();
                
            }
            
            $this->flashSession->success($this->_message["edit"]);
            $url = ($numberPage > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $numberPage :  ACP_NAME . '/' . $this->_getControllerName();
            $this->response->redirect($url);
        }

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }

    public function createAction()
    {
        $form = new LanguagesForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new Languages();

            $data = array(
                'name' => $this->request->getPost('name'),
                'code' => $this->request->getPost('code'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
            );

            $item->assign($data);

            if ($item->save()) {
                $messageFolder = $this->config->application->messages;
                if (!is_dir($messageFolder . 'core')) {
                    mkdir($messageFolder . 'core', 0777);
                }

                if ($item->code == 'vi') {
                    $wordCores = WordCore::find([
                        'order' => 'name ASC, id DESC'
                    ]);
                    $words = [];
                    foreach ($wordCores as $wordCore) {
                        $words[$wordCore->name] = $wordCore->word_translate;
                    }

                    file_put_contents($messageFolder . 'core/' . $item->code . '.json', json_encode($words, JSON_UNESCAPED_UNICODE));
                }

                if (getenv('REMOTE_ADDR') == '127.0.0.1') {
                    if ($item->code == 'en') {
                        $wordCores = json_decode(file_get_contents($messageFolder . 'core/vi.json'));
                        $words = [];
                        foreach ($wordCores as $key => $wordCore) {
                            $words[$key] = $this->_trans->getTranslator('vi', 'en', $wordCore);
                        }

                        file_put_contents($messageFolder . 'core/' . $item->code . '.json', json_encode($words, JSON_UNESCAPED_UNICODE));
                    }

                    if ($item->code != 'vi' && $item->code != 'en') {
                        if (!file_exists('messages/' . $item->code . '.json')) {
                            $messages = json_decode(file_get_contents('messages/en.json'));
                            $langMessages = [];
                            foreach ($messages as $key => $message) {
                                $langMessages[$key] = $this->_trans->getTranslator('en', $item->code, $message);
                            }

                            file_put_contents('messages/' . $item->code . '.json', json_encode($langMessages, JSON_UNESCAPED_UNICODE));
                        }

                        // word
                        if (!file_exists($messageFolder . 'core/' . $item->code . '.json')) {
                            $wordCores = json_decode(file_get_contents($messageFolder . 'core/en.json'));
                            $words = [];
                            foreach ($wordCores as $key => $wordCore) {
                                $words[$key] = $this->_trans->getTranslator('en', $item->code, $wordCore);
                            }

                            file_put_contents($messageFolder . 'core/' . $item->code . '.json', json_encode($words, JSON_UNESCAPED_UNICODE));
                        }
                    }
                }

                
                $id = $item->id;
                $this->flashSession->success($this->_message["add"]);

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName();
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction($id, $page = 1)
    {
        $item = Languages::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }


        $form = new LanguagesForm($item, array('edit' => true));
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $data = array(
                'name' => $this->request->getPost('name'),
                'code' => $this->request->getPost('code'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
            );

            $item->assign($data);

            if ($item->save()) {
                $messageFolder = $this->config->application->messages;
                if (!is_dir($messageFolder . 'core')) {
                    mkdir($messageFolder . 'core', 0777);
                }

                if ($item->code == 'vi') {
                    $wordCores = WordCore::find([
                        'order' => 'name ASC, id DESC'
                    ]);
                    $words = [];
                    foreach ($wordCores as $wordCore) {
                        $words[$wordCore->name] = $wordCore->word_translate;
                    }

                    file_put_contents($messageFolder . 'core/' . $item->code . '.json', json_encode($words, JSON_UNESCAPED_UNICODE));
                }

                if (getenv('REMOTE_ADDR') == '127.0.0.1') {
                    if ($item->code == 'en') {
                        if (file_exists($messageFolder . 'core/' . $item->code . '.json')) {
                            $wordCores = json_decode(file_get_contents($messageFolder . 'core/' . $item->code . '.json'), true);
                            ksort($wordCores);

                            file_put_contents($messageFolder . 'core/' . $item->code . '.json', json_encode($wordCores, JSON_UNESCAPED_UNICODE));
                        } else {
                            $wordCores = json_decode(file_get_contents($messageFolder . 'core/vi.json'));
                            $words = [];
                            foreach ($wordCores as $key => $wordCore) {
                                $words[$key] = $this->_trans->getTranslator('vi', 'en', $wordCore);
                            }

                            file_put_contents($messageFolder . 'core/' . $item->code . '.json', json_encode($words, JSON_UNESCAPED_UNICODE));
                        }
                    }

                    if ($item->code != 'vi' && $item->code != 'en') {
                        if (file_exists($messageFolder . 'core/' . $item->code . '.json')) {
                            $wordCores = json_decode(file_get_contents($messageFolder . 'core/' . $item->code . '.json'), true);
                            ksort($wordCores);

                            file_put_contents($messageFolder . 'core/' . $item->code . '.json', json_encode($wordCores, JSON_UNESCAPED_UNICODE));
                        }

                        // message
                        if (!file_exists('messages/' . $item->code . '.json')) {
                            $messages = json_decode(file_get_contents('messages/en.json'));
                            $langMessages = [];
                            foreach ($messages as $key => $message) {
                                $langMessages[$key] = $this->_trans->getTranslator('en', $item->code, $message);
                            }

                            file_put_contents('messages/' . $item->code . '.json', json_encode($langMessages, JSON_UNESCAPED_UNICODE));
                        }

                        // word
                        if (!file_exists($messageFolder . 'core/' . $item->code . '.json')) {
                            $wordCores = json_decode(file_get_contents($messageFolder . 'core/en.json'));
                            $words = [];
                            foreach ($wordCores as $key => $wordCore) {
                                $words[$key] = $this->_trans->getTranslator('en', $item->code, $wordCore);
                            }

                            file_put_contents($messageFolder . 'core/' . $item->code . '.json', json_encode($words, JSON_UNESCAPED_UNICODE));
                        }
                    }
                }

                
                $this->flashSession->success($this->_message["edit"]);
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id . '/' . $page;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }


    public function showAction($id, $page = 1)
    {
        $item = Languages::findFirst([
            "conditions" => "id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction($id, $page = 1)
    {
        $item = Languages::findFirst([
            "conditions" => "id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showmultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Languages::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                $item->assign(array(
                    'active' => 'Y',
                ));
                $item->save();
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function hidemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Languages::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                $item->assign(array(
                    'active' => 'N',
                ));
                $item->save();
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }


    public function deleteAction($id, $page = 1)
    {
        $item = Languages::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'deleted' => 'Y',
        ));

        if ($item->save()) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Languages::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'deleted' => 'N',
                ));
                $item->save();
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if ($d > 0) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction($id, $page = 1)
    {
        $item = Languages::findFirst([
            "conditions" => "id = $id"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            $this->flashSession->success($this->_message["delete"]);
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function _deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Languages::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                $item->delete();
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if ($d > 0) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }
}
