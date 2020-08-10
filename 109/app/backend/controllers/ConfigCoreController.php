<?php

namespace Modules\Backend\Controllers;

use Modules\Models\ConfigCore;
use Modules\Models\ConfigItem;
use Modules\Models\Subdomain;
use Modules\Models\WordCore;
use Modules\Models\WordItem;
use Modules\Forms\ConfigCoreForm;
use Modules\Forms\WordCoreForm;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;

class ConfigCoreController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Cấu hình';
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
        $items = $this->recursive(0);

        $page_current = 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->items = $items;
        $this->view->page_current = $page_current;
    }

    public function listAction()
    {
        $items = $this->recursive(0);

        $page_current = 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->items = $items;
        $this->view->page_current = $page_current;
    }

    public function createAction()
    {
        $form = new ConfigCoreForm();
        $list = $this->recursive(0);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new ConfigCore();

            $data = [
                'config_group_id' => $this->request->getPost('config_group_id'),
                'parent_id' => $this->request->getPost('parent_id'),
                'name' => $this->request->getPost('name', 'trim'),
                'field' => $this->request->getPost('field', 'trim'),
                'value' => $this->request->getPost('value', 'trim'),
                'min_value' => $this->request->getPost('min_value'),
                'max_value' => $this->request->getPost('max_value'),
                'description' => $this->request->getPost('description'),
                'place_holder' => $this->request->getPost('place_holder'),
                'type' => $this->request->getPost('type'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);

            if ($item->save()) {
                
                $id = $item->id;

                /*
                $subdomain = Subdomain::find(['columns' => 'id']);
                foreach ($subdomain as $row) {
                    //insert config item
                    $data_item = [
                        'subdomain_id' => $row->id,
                        'config_group_id' => $this->request->getPost('config_group_id'),
                        'config_core_id' => $id,
                        'name' => $this->request->getPost('name', 'trim'),
                        'field' => $this->request->getPost('field', 'trim'),
                        'value' => $this->request->getPost('value', 'trim'),
                        'min_value' => $this->request->getPost('min_value'),
                        'max_value' => $this->request->getPost('max_value'),
                        'description' => $this->request->getPost('description'),
                        'place_holder' => $this->request->getPost('place_holder'),
                        'type' => $this->request->getPost('type'),
                        'sort' => $this->request->getPost('sort'),
                        'active' => $this->request->getPost('active')
                    ];

                    $config_item = new ConfigItem();
                    $config_item->assign($data_item);
                    $config_item->save();
                }
                */

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
        $this->view->list = $list;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction($id, $page = 1)
    {
        $item = ConfigCore::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new ConfigCoreForm($item, ['edit' => true]);
        $list = $this->recursive(0);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $type = $this->request->getQuery('type');

            $data = [
                'config_group_id' => $this->request->getPost('config_group_id'),
                'parent_id' => $this->request->getPost('parent_id'),
                'name' => $this->request->getPost('name', 'trim'),
                'field' => $this->request->getPost('field', 'trim'),
                'value' => $this->request->getPost('value', 'trim'),
                'min_value' => $this->request->getPost('min_value'),
                'max_value' => $this->request->getPost('max_value'),
                'description' => $this->request->getPost('description'),
                'place_holder' => $this->request->getPost('place_holder'),
                'type' => $this->request->getPost('type'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);

            if ($item->save()) {
                /*
                $subdomains = Subdomain::find();
                foreach ($subdomains as $subdomain) {
                    $config_item = ConfigItem::findFirst([
                        'conditions' => 'subdomain_id = '. $subdomain->id .' AND config_core_id = '. $id .''
                    ]);
                    if ($type == 'new') {
                        if (!$config_item) {
                            $config_item = new ConfigItem();
                            $data_config_item = [
                                'subdomain_id' => $subdomain->id,
                                'config_group_id' => $item->config_group_id,
                                'config_core_id' => $id,
                                'name' => $item->name,
                                'field' => $item->field,
                                'value' => $item->value,
                                'min_value' => $item->min_value,
                                'max_value' => $item->max_value,
                                'description' => $item->description,
                                'place_holder' => $item->place_holder,
                                'type' => $item->type,
                                'sort' => $item->sort,
                                'active' => $item->active
                            ];

                            $config_item->assign($data_config_item);
                            $config_item->save();
                        }
                    } else {
                        if ($config_item) {
                            $data_config_item = [
                                'config_group_id' => $this->request->getPost('config_group_id'),
                                'config_core_id' => $id,
                                'name' => $this->request->getPost('name', 'trim'),
                                'min_value' => $this->request->getPost('min_value'),
                                'max_value' => $this->request->getPost('max_value'),
                                'field' => $this->request->getPost('field', 'trim'),
                                // 'value' => $this->request->getPost('value', 'trim'),
                                'description' => $this->request->getPost('description'),
                                'place_holder' => $this->request->getPost('place_holder'),
                                'type' => $this->request->getPost('type'),
                                'sort' => $this->request->getPost('sort'),
                                'active' => $this->request->getPost('active')
                            ];

                            if (empty($config_item->value)) {
                                $data_config_item['value'] = $this->request->getPost('value', 'trim');
                            }
                        } else {
                            $config_item = new ConfigItem();
                            $data_config_item = [
                                'subdomain_id' => $subdomain->id,
                                'config_group_id' => $item->config_group_id,
                                'config_core_id' => $id,
                                'name' => $item->name,
                                'field' => $item->field,
                                'value' => $item->value,
                                'min_value' => $item->min_value,
                                'max_value' => $item->max_value,
                                'description' => $item->description,
                                'place_holder' => $item->place_holder,
                                'type' => $item->type,
                                'sort' => $item->sort,
                                'active' => $item->active
                            ];
                        }
                        
                        $config_item->assign($data_config_item);
                        $config_item->save();
                    }
                }
                */

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
        $this->view->list = $list;
        $this->view->form = $form;
        $this->view->item = $item;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateGuideAction($id)
    {
        $item = ConfigCore::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new ConfigCoreForm($item, ['edit' => true]);
        $list = $this->recursive(0);
        $folderImg = 'system_file/guide/' . $item->id;
        $dir = DOCUMENT_ROOT . '/public/' . $folderImg;
        $imgUploadPaths = [];
        if (is_dir($dir)) {
            $imgUploads = array_filter(scandir($dir), function ($itemFile) {
                return $itemFile[0] !== '.';
            });

            if (!empty($imgUploads)) {
                foreach ($imgUploads as $img) {
                    if ($img != 'medium') {
                        $imgUploadPaths[] = '/' . $folderImg . '/' . $img;
                    }
                }
            }
        }
        if ($this->request->isPost()) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $data = [
                'guide' => $this->request->getPost('guide'),
            ];

            $item->assign($data);

            if ($item->save()) {
                
                $this->flashSession->success("Cập nhật dữ liệu thành công");
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/list';
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/updateGuide/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->list = $list;
        $this->view->form = $form;
        $this->view->item = $item;
        $this->view->img_upload_paths = $imgUploadPaths;
    }

    public function viewGuideAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->view->setTemplateBefore('viewGuide');
            $id = $this->request->getPost('id');
            $configCore = ConfigCore::findFirstById($id);
            $this->view->configCore = $configCore;
            $this->view->pick($this->_getControllerName() . '/viewGuide');
        }
    }

    public function showAction($id, $page = 1)
    {
        $item = ConfigCore::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            
            $list_config_item = ConfigItem::find([
                'columns' => 'id',
                'conditions' => 'config_core_id = '. $id .''
            ]);
            foreach ($list_config_item as $row) {
                $config_item = ConfigItem::findFirstById($row->id);
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
        $item = ConfigCore::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            
            $list_config_item = ConfigItem::find([
                'columns' => 'id',
                'conditions' => 'config_core_id = '. $id .''
            ]);
            foreach ($list_config_item as $row) {
                $config_item = ConfigItem::findFirstById($row->id);
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
            $item = ConfigCore::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'Y',
                ));
                $item->save();
                $list_config_item = ConfigItem::find([
                    'columns' => 'id',
                    'conditions' => 'config_core_id = '. $id .''
                ]);
                foreach ($list_config_item as $row) {
                    $config_item = ConfigItem::findFirstById($row->id);
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
            $item = ConfigCore::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'N',
                ));
                $item->save();
                $list_config_item = ConfigItem::find([
                    'columns' => 'id',
                    'conditions' => 'config_core_id = '. $id .''
                ]);
                foreach ($list_config_item as $row) {
                    $config_item = ConfigItem::findFirstById($row->id);
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
        $item = ConfigCore::findFirst([
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
            
            $list_config_item = ConfigItem::find([
                'columns' => 'id',
                'conditions' => 'config_core_id = '. $id .''
            ]);
            foreach ($list_config_item as $row) {
                $config_item = ConfigItem::findFirstById($row->id);
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
            $item = ConfigCore::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                $item->assign(array(
                    'deleted' => 'N',
                ));
                $item->save();
                $list_module_item = ConfigItem::find([
                    'columns' => 'id',
                    'conditions' => 'config_core_id = '. $id .''
                ]);
                foreach ($list_module_item as $row) {
                    $config_item = ConfigItem::findFirstById($row->id);
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
        $item = ConfigCore::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        
        $list_config_item = ConfigItem::findByConfigCoreId($id);

        if (count($list_config_item) > 0) {
            foreach ($list_config_item as $row) {
                $row->delete();
            }
        }
        
        if ($item->delete()) {
            
            $this->flashSession->success("Xóa dữ liệu thành công");
        } else {
            foreach ($item->getMessages() as $message) {
                $this->flashSession->error($message);
            }
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
            $item = ConfigCore::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                if (!$item->delete()) {
                    $this->flashSession->error($item->getMessages());
                } else {
                    $list_config_item = ConfigItem::find([
                        'columns' => 'id',
                        'conditions' => 'config_core_id = '. $id .''
                    ]);
                    foreach ($list_config_item as $row) {
                        $config_item = ConfigItem::findFirstById($row->id);
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

    public function recursive($parent_id = 0, $space = "", $trees = array())
    {
        if (!$trees) {
            $trees = [];
        }
        $result = ConfigCore::find(
            [
                "order" => "sort ASC, name ASC, id DESC",
                "conditions" => "parent_id = ". $parent_id ." AND deleted = 'N'"
            ]
        );


        $trees_obj = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $trees[] = [
                    'id' => $row->id,
                    'parent_id' => $row->parent_id,
                    'level' => $row->level,
                    'name' => $space . $row->name,
                    'field' => $row->field,
                    'min_value' => $row->min_value,
                    'max_value' => $row->max_value,
                    'sort' => $row->sort,
                    'active' => $row->active,
                    "count_child" => $this->count_child($row->id)
                ];
                $trees   = $this->recursive($row->id, $space . '|---', $trees);
            }
        }

        if (!empty($trees)) {
            foreach ($trees as $tree) {
                $tree        = (object) $tree;
                $trees_obj[] = $tree;
            }
        }
        return $trees_obj;
    }

    public function count_child(int $id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['n' => '\Modules\Models\ConfigCore'])
            ->where('n.parent_id = '. $id .' AND n.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }
}
