<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Layout;
use Modules\Models\ModuleItem;
use Modules\Models\Position;
use Modules\Models\Posts;
use Modules\Models\TmpLayoutModule;
use Modules\Models\TmpPositionModuleItem;
use Modules\Forms\PostsForm;
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

class PostsController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Trình soạn thảo';
    }

    public function indexAction()
    {
        $list = Posts::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND deleted = 'N'"
            ]
        );

        if (count($this->_tmpSubdomainLanguages) > 0) {
            $listLang = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langId = $tmp->language->id;
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $postLang = Posts::find(
                        [
                            "order" => "sort ASC, id DESC",
                            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND deleted = 'N'"
                        ]
                    );
                    if ($postLang) {
                        $listLang[$langCode] = $postLang;
                    }
                }
            }

            $this->view->listLang = $listLang;
        }

        $row_id = 'post' . $this->_get_subdomainID();

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/post/'. $row_id;
        $dir = DOCUMENT_ROOT . '/public/' . $folderImg;

        $imgUploadPaths = [];
        if (is_dir($dir)) {
            $imgUploads = array_filter(scandir($dir), function ($item) {
                return $item[0] !== '.';
            });

            if (!empty($imgUploads)) {
                foreach ($imgUploads as $img) {
                    if ($img != 'medium') {
                        $imgUploadPaths[] = '/' . $folderImg . '/' . $img;
                    }
                }
            }
        }

        $result = [];
        if (count($list) > 0) {
            foreach ($list->toArray() as $key => $value) {
                $result[] = $value;
                $tmp_position_module_item_c = TmpPositionModuleItem::findByModuleItemId($value['module_item_id']);
                if (!empty($tmp_position_module_item_c)) {
                    foreach ($tmp_position_module_item_c as $row) {
                        $result[$key]['tmp_position_module_item_arr'][] = $row->position_id;
                    }
                }
            }
        }

        $numberPage = $this->request->getQuery("page", "int");
        $paginator = new Paginator(
            [
                "data" => $list,
                "limit" => 30,
                "page" => $numberPage
            ]
        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;
        $position = Position::find(
            [
                'columns' => 'id, name',
                "conditions" => "active = 'Y'",
                'order' => 'sort ASC, id DESC'
            ]
        );

        if ($this->request->isPost()) {
            $request = $this->request->getPost();
            $subFolder = $this->_get_subdomainFolder();
            $messengerFormValue = [];
            foreach ($list as $item) {
                $id = $item->id;
                $messengerForm = isset($request['messenger_form'][$id]) ? 'Y' : 'N';
                $micSupportHead = isset($request['mic_support_head'][$id]) ? 'Y' : 'N';
                $micSupportFoot = isset($request['mic_support_foot'][$id]) ? 'Y' : 'N';
                $messengerFormValue[] = $messengerForm;
                
                $data = [
                    'row_id' => $row_id,
                    'name' => $request['name'][$id],
                    'messenger_form' => $messengerForm,
                    'mic_support_head' => $micSupportHead,
                    'mic_support_foot' => $micSupportFoot,
                    'content' => $request['content'][$id],
                ];

                $item->assign($data);

                if ($item->save()) {
                    
                    // $position_id = $this->request->getPost('position_' . $id);
                    $module_item = ModuleItem::findFirstById($item->module_item_id);
                    $dataItem = [
                        'type_id' => $item->id,
                        'active' => 'Y'
                    ];

                    if (!empty($item->name)) {
                        $dataItem['name'] = $item->name;
                    }

                    $module_item->assign($dataItem);
                    $module_item->save();
                }
            }

            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        if (!empty($listLang[$langCode])) {
                            foreach ($listLang[$langCode] as $keyLang => $itemLang) {
                                $dataLang = [
                                    'row_id' => $row_id,
                                    'name' => $request['name'][$langCode][$itemLang->id],
                                    'messenger_form' => $messengerFormValue[$keyLang],
                                    'content' => $request['content'][$langCode][$itemLang->id],
                                ];

                                $itemLang->assign($dataLang);
                                $itemLang->save();
                            }
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["edit"]);
            $url = ACP_NAME . '/' . $this->_getControllerName();
            $this->response->redirect($url);
        }

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
        $this->view->position = $position;
        $this->view->list = $list;
        $this->view->result = $result;
        $this->view->row_id = $row_id;
        $this->view->img_upload_paths = $imgUploadPaths;
    }

    public function createAction()
    {
        $form = new PostsForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $item = new Posts();
            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'module_item_id' => 0,
                'name' => $this->request->getPost('name'),
                'content' => str_replace("public/files/", "files/", $this->request->getPost('content')),
                'sort' => $this->request->getPost('sort'),
                'active' => 'Y'
            ];

            $item->assign($data);

            if ($item->save()) {
                
                $id = $item->id;
                $position_id = $this->request->getPost('position');
                $module_item = new ModuleItem();
                $module_item->assign(
                    [
                        'module_group_id' => 0,
                        'subdomain_id' => $this->_get_subdomainID(),
                        'parent_id' => 0,
                        'name' => $this->request->getPost('name'),
                        'sort' => $this->request->getPost('sort'),
                        'type' => 'post',
                        'active' => 'Y'
                    ]
                );

                if ($module_item->save()) {
                    $module_item_id = $module_item->id;
                    $item = Posts::findFirst([
                        "conditions" => "id = $id"
                    ]);
                    $item->assign(['module_item_id' => $module_item_id]);
                    $item->save();

                    if (!empty($position_id)) {
                        foreach ($position_id as $row) {
                            //insert tmp postiion module item
                            $tmp_position_module_item = new TmpPositionModuleItem();
                            $tmp_position_module_item->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'module_item_id' => $module_item_id,
                                'position_id' => $row
                            ]);

                            $tmp_position_module_item->save();

                            $layout = Layout::find([
                                'conditions' => 'active = "Y"'
                            ]);
                            $layout_module = TmpLayoutModule::findFirst([
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND position_id = $row",
                                "order" => "sort DESC"
                            ]);
                            $sort = (count($layout_module) > 0) ? $layout_module->sort + 1 : 1;

                            foreach ($layout as $rowLayout) {
                                //insert tmp layout module
                                $tmp_layout_module = new TmpLayoutModule();
                                $tmp_layout_module->assign([
                                    'subdomain_id' => $module_item->subdomain_id,
                                    'layout_id' => $rowLayout->id,
                                    'module_item_id' => $module_item->id,
                                    'position_id' => $row,
                                    'sort' => $sort
                                ]);
                                $tmp_layout_module->save();
                            }
                        }
                    }
                }

                $this->flashSession->success($this->_message["add"]);

                $url = ACP_NAME . '/' . $this->_getControllerName();

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $position = Position::find(
            [
                'columns' => 'id, name',
                "conditions" => "active = 'Y'",
                'order' => 'sort ASC, id DESC'
            ]
        );

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->position = $position;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction(int $id, int $page = 1)
    {
        $item = Posts::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $tmp_position_module_item_c = TmpPositionModuleItem::findByModuleItemId($item->module_item_id);
        $tmp_position_module_item_arr = array();
        if (!empty($tmp_position_module_item_c)) {
            foreach ($tmp_position_module_item_c as $row) {
                $tmp_position_module_item_arr[] = $row->position_id;
            }
        }

        $form = new PostsForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $data = [
                'name' => $this->request->getPost('name'),
                'content' => str_replace("public/files/", "files/", $this->request->getPost('content')),
                'sort' => $this->request->getPost('sort')
            ];

            $item->assign($data);

            if ($item->save()) {
                
                $position_id = $this->request->getPost('position');
                $module_item = ModuleItem::findFirstById($item->module_item_id);
                $module_item->assign([
                    'sort' => $this->request->getPost('sort'),
                    'active' => 'Y'
                ]);

                if ($module_item->save()) {
                    $module_item_id = $module_item->id;
                    $layout = Layout::find([
                        'conditions' => 'active = "Y"'
                    ]);

                    if (!empty($position_id)) {
                        //delete tmp position module item and tmp layout module
                        TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $module_item_id .'');
                        TmpLayoutModule::deleteByRawSql('module_item_id ='. $module_item->id .' AND subdomain_id = '. $module_item->subdomain_id .'');
                        $tmp_position_module_item = new TmpPositionModuleItem();
                        foreach ($position_id as $row) {
                            //insert tmp position module item
                            $tmp_position_module_item->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'module_item_id' => $module_item->id,
                                'position_id' => $row
                            ]);

                            $tmp_position_module_item->save();

                            foreach ($layout as $rowLayout) {
                                //insert tmp layout module
                                $tmp_layout_module = new TmpLayoutModule();
                                $tmp_layout_module->assign([
                                    'position_id' => $row,
                                    'subdomain_id' => $module_item->subdomain_id,
                                    'layout_id' => $rowLayout->id,
                                    'module_item_id' => $module_item->id,
                                ]);
                                $tmp_layout_module->save();
                            }
                        }
                    }
                }

                $this->flashSession->success($this->_message["edit"]);
                $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id . '/' . $page;

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $position = Position::find(
            [
                'columns' => 'id, name',
                "conditions" => "active = 'Y'",
                'order' => 'sort ASC, id DESC'
            ]
        );


        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->position = $position;
        $this->view->tmp_position_module_item_c = $tmp_position_module_item_c;
        $this->view->tmp_position_module_item_arr = $tmp_position_module_item_arr;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updatePageAction($id)
    {
        $general = new General();
        $item = Posts::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if ($this->request->isPost()) {
            $subFolder = $this->_get_subdomainFolder();
            /*$folderImg = 'uploads/' . $subFolder. '/post/'. $id;
            $dir = DOCUMENT_ROOT . '/public/' . $folderImg;

            if (is_dir($dir)) {
                $imgUploads = array_filter(scandir($dir), function($item) {
                    return $item[0] !== '.';
                });

                $imgUploadUrls = [];
                if (!empty($imgUploads)) {
                    foreach ($imgUploads as $img) {
                        $imgUploadUrls[] = HTTP_HOST . '/' . $folderImg . '/' . $img;
                    }
                }

                if (!empty($imgUploadUrls)) {
                    $contentHtml = $this->request->getPost('content_' . $id);
                    $general->deleteImgInFolder($imgUploadUrls, $contentHtml);
                }
            }*/

            $messengerForm = ($this->request->getPost('messenger_form_' . $id) == 'Y') ? 'Y' : 'N';
            
            $data = [
                'row_id' => $id,
                'name' => $this->request->getPost('name_' . $id),
                'messenger_form' => $messengerForm,
                'content' => $this->request->getPost('content_' . $id),
            ];

            $item->assign($data);

            if ($item->save()) {
                
                // $position_id = $this->request->getPost('position_' . $id);
                $module_item = ModuleItem::findFirstById($item->module_item_id);
                $module_item->assign([
                    'name' => $this->request->getPost('name_' . $id),
                    'active' => 'Y'
                ]);

                $module_item->save();

                $this->flashSession->success($this->_message["edit"]);
                $url = ACP_NAME . '/' . $this->_getControllerName();

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }
        $this->view->disable();
    }

    public function showAction(int $id, int $page = 1)
    {
        $item = Posts::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'Y',
        ]);

        if ($item->save()) {
            $module_item = ModuleItem::findFirstById($item->module_item_id);
            $module_item->assign([
                'active' => 'Y',
            ]);
            $module_item->save();

            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction(int $id, int $page = 1)
    {
        $item = Posts::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        ;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'N'
        ]);

        if ($item->save()) {
            
            $module_item = ModuleItem::findFirstById($item->module_item_id);
            $module_item->assign([
                'active' => 'N',
            ]);
            $module_item->save();
            $$this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showmultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Posts::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'Y'
                ]);
                if ($item->save()) {
                    $module_item = ModuleItem::findFirstById($item->module_item_id);
                    $module_item->assign([
                        'active' => 'Y',
                    ]);
                    $module_item->save();
                }
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["show"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function hidemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Posts::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'N'
                ]);
                if ($item->save()) {
                    $module_item = ModuleItem::findFirstById($item->module_item_id);
                    $module_item->assign([
                        'active' => 'N',
                    ]);
                    $module_item->save();
                }
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

    public function deleteAction(int $id, $page = 1)
    {
        $item = Posts::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        $item->assign([
            'deleted' => 'Y'
        ]);

        if ($item->save()) {
            
            $module_item = ModuleItem::findFirstById($item->module_item_id);
            $module_item->assign([
                'deleted' => 'Y',
            ]);
            $module_item->save();
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }


        $this->response->redirect($url);
    }

    public function deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        foreach ($listid as $id) {
            $item = Posts::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);

            if ($item) {
                $item->assign([
                    'deleted' => 'N'
                ]);
                if ($item->save()) {
                    
                    $module_item = ModuleItem::findFirstById($item->module_item_id);
                    $module_item->assign([
                        'deleted' => 'Y',
                    ]);
                    $module_item->save();
                }
                $d++;
            }
        }


        if ($d > 0) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction(int $id, $page = 1)
    {
        $item = Posts::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            
            $module_item = ModuleItem::findFirstById($item->module_item_id);
            $module_item->delete();
            TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $item->module_item_id .'');
            $this->flashSession->success($this->_message["delete"]);
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function _deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Posts::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                if ($item->delete()) {
                    $module_item = ModuleItem::findFirstById($item->module_item_id);
                    $module_item->delete();
                    TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $item->module_item_id .'');
                }
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
