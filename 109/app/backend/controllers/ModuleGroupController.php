<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Layout;
use Modules\Models\ModuleGroup;
use Modules\Models\ModuleItem;
use Modules\Models\Position;
use Modules\Models\Subdomain;
use Modules\Models\TmpLayoutModule;
use Modules\Models\TmpPositionModuleGroup;
use Modules\Models\TmpPositionModuleItem;
use Modules\Models\TmpModuleGroupLayout;
use Modules\Models\Posts;
use Modules\Forms\ModuleGroupForm;
use Modules\PhalconVn\General;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;

class ModuleGroupController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Module';
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

    public function settingAction()
    {
        $items = ModuleGroup::find(
            [
                "order" => "sort ASC, name ASC, id DESC",
                "conditions" => "parent_id = 0 AND deleted = 'N'"
            ]
        );
        $layout = Layout::find(
            array(
                "order" => "sort ASC, id DESC",
            )
        );
        $page_current = 1;

        $tmpModuleGroupLayout = TmpModuleGroupLayout::find();
        $arrayTmp = [];
        if (count($tmpModuleGroupLayout) > 0) {
            foreach ($tmpModuleGroupLayout as $row) {
                $arrayTmp[$row->module_group_id][] = $row->layout_id;
            }
        }

        if ($this->request->isPost()) {
            
            foreach ($items as $row) {
                TmpModuleGroupLayout::deleteByRawSql('module_group_id ='. $row->id .'');
                $moduleLayout = $this->request->getPost('module_' . $row->id);
                if (!empty($moduleLayout)) {
                    foreach ($moduleLayout as $rowLayout) {
                        $tmp = new TmpModuleGroupLayout();
                        $tmp->module_group_id = $row->id;
                        $tmp->layout_id = $rowLayout;
                        $tmp->save();
                    }
                }
            }
            $this->flashSession->success($this->_message["edit"]);
            $url = ACP_NAME . '/' . $this->_getControllerName() . '/setting';
            $this->response->redirect($url);
        }

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->items = $items;
        $this->view->array_tmp = $arrayTmp;
        $this->view->layout = $layout;
        $this->view->page_current = $page_current;
    }

    /**
     * Create new module
     * 
     * @return Phalcon\Http\Response
     */
    public function createAction()
    {
        $form = new ModuleGroupForm();
        $subdomain = Subdomain::find(['columns' => 'id']);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new ModuleGroup();
            $general = new General();

            $data = [
                'parent_id' => $this->request->getPost('parent_id'),
                'name' => $this->request->getPost('name'),
                'type' => $this->request->getPost('type'),
                'link' => $this->request->getPost('link'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            if ($data['parent_id'] != 0) {
                $item_parent = ModuleGroup::findFirst(
                    [
                        'columns' => 'level',
                        'conditions' => 'id = '. $data['parent_id'] .''
                    ]
                );
                $data['level'] = $item_parent->level + 1;
            }

            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                if (!empty($files[0]->getType())) {
                    $ext = $files[0]->getType();
                    if ($this->extFileCheck($ext)) {
                        $fileName = explode('.', $files[0]->getName());
                        $fileName = $fileName[0];
                        $fileName = $general->create_slug($fileName);
                        $subCode = Text::random(Text::RANDOM_ALNUM);
                        $fileFullName = $fileName . '_' . $subCode . '.' . $files[0]->getExtension();

                        $data['photo'] = $fileFullName;

                        $files[0]->moveTo("files/module/" . $fileFullName);
                    } else {
                        $this->flashSession->error('Định dạng file không cho phép. Hãy upload một hình ảnh.');
                        return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/create');
                    }
                }
            }

            $item->assign($data);

            if ($item->save()) {
                
                $id = $item->id;
                $position_id = $this->request->getPost('position');

                $layout = Layout::find([
                   'conditions' => 'active = "Y"'
                ]);

                if (!empty($position_id)) {
                    foreach ($position_id as $row) {
                        $tmp_position_module_group = new TmpPositionModuleGroup();
                        $tmp_position_module_group->assign([
                            'module_group_id' => $id,
                            'position_id' => $row
                        ]);

                        $tmp_position_module_group->save();
                    }
                }

                //set layout show this module
                foreach ($layout as $rowLayout) {
                    $tmp = new TmpModuleGroupLayout();
                    $tmp->module_group_id = $id;
                    $tmp->layout_id = $rowLayout->id;
                    $tmp->save();
                }

                foreach ($subdomain as $row) {
                    if ($item->type == 'footer_total_posts') {
                        $module_item_parent = ($item->parent_id != 0) ? ModuleItem::findFirst(['columns' => 'id', 'conditions' => 'subdomain_id = '. $row->id .' AND module_group_id = '. $item->parent_id .''])->id : 0;
                       
                        $posts = Posts::find([
                            'conditions' => 'subdomain_id = '. $row->id .'',
                            'order' => 'sort ASC, id DESC'
                        ]);

                        if ($posts->count() > 0) {
                            $i = 8;
                            $j = 1;
                            foreach ($posts as $post) {
                                $data_module_item = [
                                    'subdomain_id' => $row->id,
                                    'module_group_id' => $id,
                                    'parent_id' => $module_item_parent,
                                    'name' => !empty($post->name) ? $post->name : 'Tự soạn thảo ' . $k,
                                    'type' => 'post',
                                    'type_id' => $post->id,
                                    'sort' => $i,
                                    'active' => $item->active
                                ];

                                $module_item = new ModuleItem();
                                $module_item->assign($data_module_item);
                                $module_item->save();
                                $i++;
                                $j++;
                            }
                        }
                    } else {
                        $module_item_parent = ($item->parent_id != 0) ? ModuleItem::findFirst(['columns' => 'id', 'conditions' => 'subdomain_id = '. $row->id .' AND module_group_id = '. $item->parent_id .''])->id : 0;
                        //insert module item
                        $data_module_item = [
                            'subdomain_id' => $row->id,
                            'module_group_id' => $id,
                            'parent_id' => $module_item_parent,
                            'name' => $item->name,
                            'type' => $item->type,
                            'photo' => $item->photo,
                            'sort' => $item->sort,
                            'active' => $item->active
                        ];

                        $module_item = new ModuleItem();
                        $module_item->assign($data_module_item);
                        $module_item->save();
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

        $list = $this->recursive(0);
        $position = Position::find(
            [
                'columns' => 'id, name',
                "conditions" => "active = 'Y'",
                'order' => 'sort ASC, id DESC'
            ]
        );

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->list = $list;
        $this->view->position = $position;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    /**
     * Update module
     * 
     * @param  integer  $id  
     * @param  integer $page
     * @return Phalcon\Http\Response       
     */
    public function updateAction($id, $page = 1)
    {
        $item = ModuleGroup::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $subdomain = Subdomain::find(['columns' => 'id']);

        $tmp_position_module_group_c = TmpPositionModuleGroup::findByModuleGroupId($item->id);

        $tmp_position_module_group_arr = array();
        if (!empty($tmp_position_module_group_c)) {
            foreach ($tmp_position_module_group_c as $row) {
                $tmp_position_module_group_arr[] = $row->position_id;
            }
        }

        $photo = $item->photo;

        $form = new ModuleGroupForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $type = $this->request->getQuery('type');
            $general = new General();

            $data = [
                'parent_id' => $this->request->getPost('parent_id'),
                'name' => $this->request->getPost('name'),
                'link' => $this->request->getPost('link'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
                'enable_delete' => $this->request->getPost('enable_delete'),
            ];

            $data['level'] = 0;
            if ($data['parent_id'] != 0) {
                $item_parent = ModuleGroup::findFirst(
                    [
                        'columns' => 'level',
                        'conditions' => 'id = '. $data['parent_id'] .''
                    ]
                );
                $data['level'] = $item_parent->level + 1;
            }

            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                if (!empty($files[0]->getType())) {
                    $ext = $files[0]->getType();
                    if ($this->extFileCheck($ext)) {
                        $fileName = explode('.', $files[0]->getName());
                        $fileName = $fileName[0];
                        $fileName = $general->create_slug($fileName);
                        $subCode = Text::random(Text::RANDOM_ALNUM);
                        $fileFullName = $fileName . '_' . $subCode . '.' . $files[0]->getExtension();
                        $data['photo'] = $fileFullName;

                        if ($files[0]->moveTo("files/module/" . $fileFullName)) {
                            @unlink("files/module/". $photo);
                        }
                    } else {
                        $this->flashSession->error('Định dạng file không cho phép. Hãy upload một hình ảnh.');
                        return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/create');
                    }
                }
            }

            $item->assign($data);

            if ($item->save()) {
                
                $position_id = $this->request->getPost('position');
                $layout = Layout::find([
                    'conditions' => 'active = "Y"'
                ]);

                if (!empty($position_id)) {
                    TmpPositionModuleGroup::deleteByRawSql('module_group_id ='. $id .'');

                    $tmp_position_module_group = new TmpPositionModuleGroup();
                    foreach ($position_id as $row) {
                        $tmp_position_module_group->assign([
                            'module_group_id' => $id,
                            'position_id' => $row
                        ]);

                        $tmp_position_module_group->save();
                    }
                }

                foreach ($subdomain as $rowSub) {
                    if ($item->type == 'footer_total_posts') {
                        $moduleItems = ModuleItem::find([
                            'columns' => 'id',
                            'conditions' => 'module_group_id = '. $item->id .' AND subdomain_id = '. $rowSub->id .' AND type = "post"'
                        ]);

                        if ($moduleItems->count() == 0) {
                            $module_item_parent = ($item->parent_id != 0) ? ModuleItem::findFirst(['columns' => 'id', 'conditions' => 'subdomain_id = '. $rowSub->id .' AND module_group_id = '. $item->parent_id .''])->id : 0;
                       
                            $posts = Posts::find([
                                'conditions' => 'subdomain_id = '. $rowSub->id .'',
                                'order' => 'sort ASC, id DESC'
                            ]);

                            if ($posts->count() > 0) {
                                $i = 8;
                                $k = 1;
                                foreach ($posts as $post) {
                                    $data_module_item = [
                                        'subdomain_id' => $rowSub->id,
                                        'module_group_id' => $id,
                                        'parent_id' => $module_item_parent,
                                        'name' => !empty($post->name) ? $post->name : 'Tự soạn thảo ' . $k,
                                        'type' => 'post',
                                        'type_id' => $post->id,
                                        'sort' => $i,
                                        'active' => $item->active
                                    ];

                                    $module_item = new ModuleItem();
                                    $module_item->assign($data_module_item);
                                    $module_item->save();
                                    $i++;
                                    $k++;
                                }
                            }
                        }
                    } else {
                        $moduleItem = ModuleItem::findFirst([
                            'conditions' => 'module_group_id = '. $id .' AND subdomain_id = '. $rowSub->id .''
                        ]);

                        // check if type new create new module for subdomain not have this module
                        if ($type == 'new') {
                            if (!$moduleItem) {
                                $module_item_parent = ($item->parent_id != 0) ? ModuleItem::findFirst(['columns' => 'id', 'conditions' => 'subdomain_id = '. $rowSub->id .' AND module_group_id = '. $item->parent_id .''])->id : 0;
                                //insert module item
                                $data_module_item = [
                                    'subdomain_id' => $rowSub->id,
                                    'module_group_id' => $id,
                                    'parent_id' => $module_item_parent,
                                    'name' => $item->name,
                                    'type' => $item->type,
                                    'photo' => $item->photo,
                                    'sort' => $item->sort,
                                    'active' => $item->active
                                ];

                                $module_item = new ModuleItem();
                                $module_item->assign($data_module_item);
                                $module_item->save();
                            }
                        } else {
                            if ($moduleItem) {
                                $module_item_parent = ($item->parent_id != 0) ? ModuleItem::findFirst(['columns' => 'id', 'conditions' => 'subdomain_id = '. $rowSub->id .' AND module_group_id = '. $item->parent_id .''])->id : 0;
                                $data_module_item = [
                                    'parent_id' => $module_item_parent,
                                    'name' => $item->name,
                                    'type' => $item->type,
                                    'photo' => $item->photo,
                                    'sort' => $item->sort,
                                    'active' => $item->active
                                ];

                                $moduleItem->assign($data_module_item);
                                if ($moduleItem->save()) {
                                    //update tmp layout module
                                    
                                    if (!empty($position_id)) {
                                        TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $moduleItem->id .'');
                                    }
                                }
                            } else {
                                $module_item_parent = ($item->parent_id != 0) ? ModuleItem::findFirst(['columns' => 'id', 'conditions' => 'subdomain_id = '. $rowSub->id .' AND module_group_id = '. $item->parent_id .''])->id : 0;
                                //insert module item
                                if ($module_item_parent) {
                                    $data_module_item = [
                                        'subdomain_id' => $rowSub->id,
                                        'module_group_id' => $id,
                                        'parent_id' => $module_item_parent,
                                        'name' => $item->name,
                                        'type' => $item->type,
                                        'photo' => $item->photo,
                                        'sort' => $item->sort,
                                        'active' => $item->active
                                    ];

                                    $module_item = new ModuleItem();
                                    $module_item->assign($data_module_item);
                                    $module_item->save();
                                }
                            }
                        }
                    }
                }

                $this->flashSession->success("Cập nhật dữ liệu thành công");
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

        $list = $this->recursive(0);
        $position = Position::find(
            [
                'columns' => 'id, name',
                "conditions" => "active = 'Y'",
                'order' => 'sort ASC, id DESC'
            ]
        );

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->list = $list;
        $this->view->position = $position;
        $this->view->tmp_position_module_group_c = $tmp_position_module_group_c;
        $this->view->tmp_position_module_group_arr = $tmp_position_module_group_arr;
        $this->view->item = $item;
        $this->view->pick($this->_getControllerName() . '/form');
    }


    public function showAction($id, $page = 1)
    {
        $item = ModuleGroup::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            
            $list_module_item = ModuleItem::find([
                'columns' => 'id',
                'conditions' => 'module_group_id = '. $id .''
            ]);
            foreach ($list_module_item as $row) {
                $module_item = ModuleItem::findFirstById($row->id);
                $module_item->assign(array(
                    'active' => 'Y',
                ));
                $module_item->save();
            }
            $this->flashSession->success("Hiển thị dữ liệu thành công!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function hideAction($id, $page = 1)
    {
        $item = ModuleGroup::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            
            $list_module_item = ModuleItem::find([
                'columns' => 'id',
                'conditions' => 'module_group_id = '. $id .''
            ]);

            foreach ($list_module_item as $row) {
                $module_item = ModuleItem::findFirstById($row->id);
                $module_item->assign(array(
                    'active' => 'N',
                ));
                $module_item->save();
            }
            $this->flashSession->success("Ẩn dữ liệu thành công");
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
            $item = ModuleGroup::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'Y',
                ));
                $item->save();
                $list_module_item = ModuleItem::find([
                    'columns' => 'id',
                    'conditions' => 'module_group_id = '. $id .''
                ]);
                foreach ($list_module_item as $row) {
                    $module_item = ModuleItem::findFirstById($row->id);
                    $module_item->assign(array(
                        'active' => 'Y',
                    ));
                    $module_item->save();
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
            $item = ModuleGroup::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'N',
                ));
                $item->save();
                $list_module_item = ModuleItem::find([
                    'columns' => 'id',
                    'conditions' => 'module_group_id = '. $id .''
                ]);
                foreach ($list_module_item as $row) {
                    $module_item = ModuleItem::findFirstById($row->id);
                    $module_item->assign(array(
                        'active' => 'Y',
                    ));
                    $module_item->save();
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
        $item = ModuleGroup::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $subdomain = Subdomain::find(['columns' => 'id']);
        $position = Position::find(['columns' => 'id']);

        TmpModuleGroupLayout::deleteByRawSql('module_group_id ='. $id .'');
        TmpPositionModuleGroup::deleteByRawSql('module_group_id ='. $id .'');
        $list_module_item = ModuleItem::find([
            'columns' => 'id',
            'conditions' => 'module_group_id = '. $id .''
        ]);
        foreach ($list_module_item as $row) {
            TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $row->id .'');
            TmpLayoutModule::deleteByRawSql('module_item_id ='. $row->id .'');
            $module_item = ModuleItem::findFirstById($row->id);
            $module_item->delete();
        }

        if ($item->delete()) {
            
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
            $item = ModuleGroup::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                TmpModuleGroupLayout::deleteByRawSql('module_group_id ='. $id .'');
                TmpPositionModuleGroup::deleteByRawSql('module_group_id ='. $id .'');
                foreach ($list_module_item as $row) {
                    $module_item = ModuleItem::findFirstById($row->id);
                    TmpLayoutModule::deleteByRawSql('module_item_id ='. $row->id .'');
                    TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $row->id .'');
                    $module_item->delete();
                }

                $item->delete();
                $list_module_item = ModuleItem::find([
                    'columns' => 'id',
                    'conditions' => 'module_group_id = '. $id .''
                ]);
                
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
        $item = ModuleGroup::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $photo = $item->photo;
        if (!$item->delete()) {
            foreach ($item->getMessages() as $message) {
                $this->flashSession->error($message);
            }
        } else {
            
            TmpPositionModuleGroup::deleteByRawSql('module_group_id ='. $id .'');
            $list_module_item = ModuleItem::find([
                'columns' => 'id',
                'conditions' => 'module_group_id = '. $id .''
            ]);
            foreach ($list_module_item as $row) {
                $module_item = ModuleItem::findFirstById($row->id);
                TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $module_item->id .'');
                $module_item->delete();
            }

            @unlink("files/module/" . $photo);
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
            $item = ModuleGroup::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $photo = $item->photo;

                if (!$item->delete()) {
                    $this->flashSession->error($item->getMessages());
                } else {
                    TmpPositionModuleGroup::deleteByRawSql('module_group_id ='. $id .'');
                    $list_module_item = ModuleItem::find([
                        'columns' => 'id',
                        'conditions' => 'module_group_id = '. $id .''
                    ]);
                    foreach ($list_module_item as $row) {
                        $module_item = ModuleItem::findFirstById($row->id);
                        TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $module_item->id .'');
                        $module_item->delete();
                    }
                    @unlink("files/module/" . $photo);
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
        $result = ModuleGroup::find(
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
            ->from(['n' => '\Modules\Models\ModuleGroup'])
            ->where('n.parent_id = '. $id .' AND n.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }
}
