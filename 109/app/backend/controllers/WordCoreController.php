<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Subdomain;
use Modules\Models\WordCore;
use Modules\Models\WordItem;
use Modules\Models\Languages;
use Modules\Forms\WordCoreForm;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Modules\Translate\GoogleTranslation;

class WordCoreController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Cấu hình';
        $this->_trans = new GoogleTranslation();
    }


    public function indexAction()
    {
        $list = WordCore::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "deleted = 'N'"
            ]
        );


        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            [
                "data" => $list,
                "limit" => 200,
                "page" => $numberPage
            ]
        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }

    public function createAction()
    {
        $form = new WordCoreForm();
        $subdomain = Subdomain::find();
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new WordCore();

            $data = [
                'name' => $this->request->getPost('name', 'trim'),
                'word_key' => $this->request->getPost('word_key', 'trim'),
                'word_translate' => $this->request->getPost('word_translate', 'trim'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);

            if ($item->save()) {
                $id = $item->id;

                $messageFolder = $this->config->application->messages;
                $languages = Languages::find();
                $langWordValue = [];
                foreach ($languages as $language) {
                    $langCodeFile = $messageFolder . 'core/' . $language->code . '.json';
                    $wordCoreTranslate = json_decode(file_get_contents($langCodeFile), true);
                    $code = $language->code;
                    $key = $item->name;
                    $value = '';
                    if ($language->code == 'vi') {
                        $value = $item->word_translate;
                    } else {
                        $value = $this->_trans->getTranslator('vi', $language->code, $item->word_key);
                        if ($value != '') {
                            $langWordValue[$code] = $value;
                        }
                    }

                    if ($value != '') {
                        $wordCoreTranslate[$key] = $value;
                    }
                    // put again file
                    file_put_contents($langCodeFile, json_encode($wordCoreTranslate, JSON_UNESCAPED_UNICODE));
                }

                foreach ($subdomain as $row) {
                    $langFile = $messageFolder . 'subdomains/' . $row->folder . '/vi.json';
                    if (file_exists($langFile)) {
                        $wordTranslate = json_decode(file_get_contents($langFile), true);
                        $key = $this->request->getPost('name', 'trim');
                        $value = $this->request->getPost('word_translate', 'trim');
                        $wordTranslate[$key] = $value;
                        // put again file
                        file_put_contents($langFile, json_encode($wordTranslate, JSON_UNESCAPED_UNICODE));
                        foreach ($languages as $language) {
                            $code = $language->code;
                            if ($code != 'vi') {
                                $langFile = $messageFolder . 'subdomains/' . $row->folder . '/' . $code . '.json' ;
                                if (file_exists($langFile)) {
                                    $wordTranslate = json_decode(file_get_contents($langFile), true);
                                    $key = $item->name;
                                    $value = isset($langWordValue[$code]) ? $langWordValue[$code] : '';
                                    if ($value != '') {
                                        $wordTranslate[$key] = $value;
                                    }
                                    // put again file
                                    file_put_contents($langFile, json_encode($wordTranslate, JSON_UNESCAPED_UNICODE));
                                }
                            }
                        }
                    } else {
                        //insert word item
                        $data_config_item = [
                            'subdomain_id' => $row->id,
                            'word_core_id' => $id,
                            'name' => $this->request->getPost('name', 'trim'),
                            'word_key' => $this->request->getPost('word_key', 'trim'),
                            'word_translate' => $this->request->getPost('word_translate', 'trim'),
                            'sort' => $this->request->getPost('sort'),
                            'active' => $this->request->getPost('active')
                        ];

                        $config_item = new WordItem();
                        $config_item->assign($data_config_item);
                        $config_item->save();
                    }
                }

                
                $this->flashSession->success("Thêm mới thành công");

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/index';
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction($id, $page = 1)
    {
        $item = WordCore::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new WordCoreForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $data = [
                'name' => $this->request->getPost('name', 'trim'),
                'word_key' => $this->request->getPost('word_key', 'trim'),
                'word_translate' => $this->request->getPost('word_translate', 'trim'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);

            if ($item->save()) {
                $messageFolder = $this->config->application->messages;
                $languages = Languages::find();
                $langWordValue = [];
                foreach ($languages as $language) {
                    $langCodeFile = $messageFolder . 'core/' . $language->code . '.json';
                    $wordCoreTranslate = json_decode(file_get_contents($langCodeFile), true);
                    $code = $language->code;
                    $key = $item->name;
                    $value = '';
                    if ($language->code == 'vi') {
                        $value = $item->word_translate;
                    } else {
                        $value = $this->_trans->getTranslator('vi', $language->code, $item->word_key);
                        if ($value != '') {
                            $langWordValue[$code] = $value;
                        }
                    }

                    if ($value != '') {
                        $wordCoreTranslate[$key] = $value;
                    }
                    // put again file
                    file_put_contents($langCodeFile, json_encode($wordCoreTranslate, JSON_UNESCAPED_UNICODE));
                }
                
                $subdomains = Subdomain::find();
                foreach ($subdomains as $subdomain) {
                    $langFile = $messageFolder . 'subdomains/' . $subdomain->folder . '/vi.json';
                    if (file_exists($langFile)) {
                        $wordTranslate = json_decode(file_get_contents($langFile), true);
                        if (isset($wordTranslate[$item->name])) {
                            unset($wordTranslate[$item->name]);
                        }

                        $wordTranslate[$item->name] = $item->word_translate;
                        // put again file
                        file_put_contents($langFile, json_encode($wordTranslate, JSON_UNESCAPED_UNICODE));
                        foreach ($languages as $language) {
                            $code = $language->code;
                            if ($code != 'vi') {
                                $langFile = $messageFolder . 'subdomains/' . $subdomain->folder . '/' . $code . '.json' ;

                                if (file_exists($langFile)) {
                                    $wordTranslate = json_decode(file_get_contents($langFile), true);
                                    $key = $item->name;
                                    $value = isset($langWordValue[$code]) ? $langWordValue[$code] : '';
                                    if ($value != '') {
                                        $wordTranslate[$key] = $value;
                                    }
                                    // put again file
                                    file_put_contents($langFile, json_encode($wordTranslate, JSON_UNESCAPED_UNICODE));
                                }
                            }
                        }
                    } else {
                        $list_item = WordItem::findFirst([
                            'conditions' => 'subdomain_id = '. $subdomain->id .' AND word_core_id = '. $id .''
                        ]);

                        if ($list_item) {
                            $data_item = [
                                'word_core_id' => $id,
                                'name' => $this->request->getPost('name', 'trim'),
                                'word_key' => $this->request->getPost('word_key', 'trim'),
                                'word_translate' => $this->request->getPost('word_translate', 'trim'),
                                'sort' => $this->request->getPost('sort'),
                                'active' => $this->request->getPost('active')
                            ];
                        } else {
                            $list_item = new WordItem();
                            $data_item = [
                                'subdomain_id' => $subdomain->id,
                                'word_core_id' => $id,
                                'name' => $this->request->getPost('name', 'trim'),
                                'word_key' => $this->request->getPost('word_key', 'trim'),
                                'word_translate' => $this->request->getPost('word_translate', 'trim'),
                                'sort' => $this->request->getPost('sort'),
                                'active' => $this->request->getPost('active')
                            ];
                        }
                        $list_item->assign($data_item);
                        $list_item->save();
                    }
                }
                
                $list_item = WordItem::find([
                    'columns' => 'id',
                    'conditions' => 'word_core_id = '. $id .''
                ]);

                foreach ($list_item as $row) {
                    $data_item = [
                        'word_core_id' => $id,
                        'name' => $this->request->getPost('name', 'trim'),
                        'word_key' => $this->request->getPost('word_key', 'trim'),
                        'word_translate' => $this->request->getPost('word_translate', 'trim'),
                        'sort' => $this->request->getPost('sort'),
                        'active' => $this->request->getPost('active')
                    ];

                    $word_item = WordItem::findFirstById($row->id);
                    $word_item->assign($data_item);
                    $word_item->save();
                }

                
                $this->flashSession->success("Cập nhật dữ liệu thành công");
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id . '/' . $page;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->item = $item;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function showAction($id, $page = 1)
    {
        $item = WordCore::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            
            $list_config_item = WordItem::find([
                'columns' => 'id',
                'conditions' => 'word_core_id = '. $id .''
            ]);
            foreach ($list_config_item as $row) {
                $config_item = WordItem::findFirstById($row->id);
                $config_item->assign(array(
                    'active' => 'Y',
                ));
                $config_item->save();
            }
            $this->flashSession->success("Hiển thị dữ liệu thành công!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function hideAction($id, $page = 1)
    {
        $item = WordCore::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            
            $list_config_item = WordItem::find([
                'columns' => 'id',
                'conditions' => 'word_core_id = '. $id .''
            ]);
            foreach ($list_config_item as $row) {
                $config_item = WordItem::findFirstById($row->id);
                $config_item->assign(array(
                    'active' => 'N',
                ));
                $config_item->save();
            }
            $this->flashSession->success("Ẩn dữ liệu thành công!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function showmultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = WordCore::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'Y',
                ));
                $item->save();
                $list_config_item = WordItem::find([
                    'columns' => 'id',
                    'conditions' => 'word_core_id = '. $id .''
                ]);
                foreach ($list_config_item as $row) {
                    $config_item = WordItem::findFirstById($row->id);
                    $config_item->assign(array(
                        'active' => 'Y',
                    ));
                    $config_item->save();
                }
                $d++;
            }
        }

        if ($d > 0) {
            
            $this->flashSession->success("Thao tác thành công!");
        } else {
            $this->flash->error("Không tìm thấy dữ liệu");
        }
        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;
        $this->response->redirect($url);
    }

    public function hidemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = WordCore::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'N',
                ));
                $item->save();
                $list_config_item = WordItem::find([
                    'columns' => 'id',
                    'conditions' => 'word_core_id = '. $id .''
                ]);
                foreach ($list_config_item as $row) {
                    $config_item = WordItem::findFirstById($row->id);
                    $config_item->assign(array(
                        'active' => 'N',
                    ));
                    $config_item->save();
                }
                $d++;
            }
        }

        if ($d > 0) {
            
            $this->flash->success("Ẩn dữ liệu thành công");
        } else {
            $this->flash->error("Không tìm thấy dữ liệu");
        }
        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;
        $this->response->redirect($url);
    }

    public function deleteAction($id, $page = 1)
    {
        $item = WordCore::findFirst([
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
            
            $list_config_item = WordItem::find([
                'columns' => 'id',
                'conditions' => 'word_core_id = '. $id .''
            ]);
            foreach ($list_config_item as $row) {
                $config_item = WordItem::findFirstById($row->id);
                $config_item->assign(array(
                    'deleted' => 'Y',
                ));
                $config_item->save();
            }
            $this->flashSession->success("Xóa dữ liệu thành công");
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
            $item = WordCore::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                $item->assign(array(
                    'deleted' => 'N',
                ));
                $item->save();
                $list_module_item = WordItem::find([
                    'columns' => 'id',
                    'conditions' => 'word_core_id = '. $id .''
                ]);
                foreach ($list_module_item as $row) {
                    $config_item = WordItem::findFirstById($row->id);
                    $config_item->assign(array(
                        'deleted' => 'Y',
                    ));
                    $config_item->save();
                }
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if ($d > 0) {
            
            $this->flashSession->success("Ẩn dữ liệu thành công");
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction($id, $page = 1)
    {
        $item = WordCore::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $photo = $item->photo;
        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            
            $list_config_item = WordItem::find([
                'columns' => 'id',
                'conditions' => 'word_core_id = '. $id .''
            ]);
            foreach ($list_config_item as $row) {
                $config_item = WordItem::findFirstById($row->id);
                $config_item->delete();
            }

            $this->flashSession->success("Xóa dữ liệu thành công");
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
            $item = WordCore::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                if (!$item->delete()) {
                    $this->flashSession->error($item->getMessages());
                } else {
                    $list_config_item = WordItem::find([
                        'columns' => 'id',
                        'conditions' => 'word_core_id = '. $id .''
                    ]);
                    foreach ($list_config_item as $row) {
                        $config_item = WordItem::findFirstById($row->id);
                        $config_item->delete();
                    }
                }
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if ($d > 0) {
            
            $this->flashSession->success("Xóa dữ liệu thành công");
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }
}
