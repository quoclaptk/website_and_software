<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Banner;
use Modules\Models\BannerType;
use Modules\Models\Layout;
use Modules\Models\ModuleItem;
use Modules\Models\Position;
use Modules\Models\TmpBannerBannerType;
use Modules\Models\TmpLayoutModule;
use Modules\Models\TmpPositionModuleItem;
use Modules\Forms\BannerTypeForm;
use Modules\Forms\BannerForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Text as TextRandom;
use Phalcon\Image\Adapter\GD;

class BannerTypeController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Loại banner';
    }

    public function indexAction($type = 1)
    {
        $list = BannerType::find([
            "order" => "sort ASC, id DESC",
            "conditions" => "type = $type AND subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
        ]);

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
                $listBanner = Banner::query()
                            ->columns(["id", "name", "link", "photo", "active", "sort"])
                            ->join("Modules\Models\TmpBannerBannerType", "tmp.banner_id = Modules\Models\Banner.id", "tmp")
                            ->where("banner_type_id = :banner_type_id:")
                            ->andWhere("deleted = :deleted:")
                            ->bind(["banner_type_id" => $value['id'], "deleted" => "N"])
                            ->orderBy("sort ASC, id DESC")
                            ->groupBy("id")
                            ->execute();
                $result[$key]['list_banner'] = (count($listBanner) > 0) ? $listBanner->toArray() : [];
            }
        }

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator([
            "data" => $list,
            "limit" => 50,
            "page" => $numberPage
        ]);

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        $position = Position::find(
            [
                'columns' => 'id, name',
                "conditions" => "active = 'Y'",
                'order' => 'sort ASC, id DESC'
            ]
        );

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->type = $type;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
        $this->view->result = $result;
        $this->view->position = $position;
    }

    public function createAction($type = 1)
    {
        $sub_folder = $this->_get_subdomainFolder();
        $form = new BannerTypeForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $item = new BannerType();

            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'module_item_id' => 0,
                'name' => $this->request->getPost('name'),
                'sort' => $this->request->getPost('sort'),
                'type' => $type,
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
                        'type' => 'banner',
                        'active' => 'Y'
                    ]
                );

                if ($module_item->save()) {
                    
                    $module_item_id = $module_item->id;
                    $item = BannerType::findFirst([
                        "conditions" => "id = $id"
                    ]);
                    $item->assign(['module_item_id' => $module_item_id]);
                    $item->save();

                    if (!empty($position_id)) {
                        foreach ($position_id as $row) {
                            $tmp_position_module_item = new TmpPositionModuleItem();
                            $tmp_position_module_item->assign([
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

                    if ($this->request->hasFiles() == true) {
                        $general = new General();
                        $files = $this->request->getUploadedFiles();
                        foreach ($files as $file) {
                            if (!empty($file->getType())) {
                                $fileName = basename($file->getName(), "." . $file->getExtension());
                                $fileName = $general->create_slug($fileName);
                                $subCode = Text::random(Text::RANDOM_ALNUM);
                                $fileFullName = $fileName . '_' . $subCode . '.' . $file->getExtension();
                                $banner = new Banner();
                                $banner->assign(array(
                                    'subdomain_id' => $this->_get_subdomainID(),
                                    'photo' => $fileFullName
                                ));
                                $banner->save();

                                $tmp_banner_banner_type = new TmpBannerBannerType();
                                $tmp_banner_banner_type->assign([
                                    'banner_id' => $banner->id,
                                    'banner_type_id' => $id
                                ]);
                                $tmp_banner_banner_type->save();

                                $file->moveTo('files/ads/' . $sub_folder . '/' . $fileFullName);
                            }
                        }
                    }
                }

                $this->flashSession->success($this->_message["add"]);

                $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $position = Position::find([
            'columns' => 'id, name',
            "conditions" => "active = 'Y'",
            'order' => 'sort ASC, id DESC'
        ]);


        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '/index/' . $type .'">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->type = $type;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->position = $position;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction($type = 1, $id, $page = 1)
    {
        $sub_folder = $this->_get_subdomainFolder();
        $item = BannerType::findFirst([
            "conditions" => "type = $type AND subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
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

        $form = new BannerTypeForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $data = [
                'name' => $this->request->getPost('name'),
                'sort' => $this->request->getPost('sort')
            ];

            $item->assign($data);

            if ($item->save()) {
                $position_id = $this->request->getPost('position');
                $module_item = ModuleItem::findFirstById($item->module_item_id);
                $module_item->assign(
                    [
                        'name' => $this->request->getPost('name'),
                        'sort' => $this->request->getPost('sort'),
                        'active' => 'Y'
                    ]
                );

                if ($module_item->save()) {
                    
                    $module_item_id = $module_item->id;

                    $layout = Layout::find([
                        'conditions' => 'active = "Y"'
                    ]);

                    if (!empty($position_id)) {
                        TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $module_item_id .'');
                        TmpLayoutModule::deleteByRawSql('module_item_id ='. $module_item->id .' AND subdomain_id = '. $module_item->subdomain_id .'');
                        $tmp_position_module_item = new TmpPositionModuleItem();
                        foreach ($position_id as $row) {
                            $tmp_position_module_item->assign([
                                'module_item_id' => $module_item->id,
                                'position_id' => $row
                            ]);

                            $tmp_position_module_item->save();

                            foreach ($layout as $rowLayout) {
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

                    if ($this->request->hasFiles() == true) {
                        $general = new General();
                        $banner = new Banner();
                        $files = $this->request->getUploadedFiles();
                        foreach ($files as $file) {
                            if (!empty($file->getType())) {
                                $fileName = basename($file->getName(), "." . $file->getExtension());
                                $fileName = $general->create_slug($fileName);
                                $subCode = Text::random(Text::RANDOM_ALNUM);
                                $fileFullName = $fileName . '_' . $subCode . '.' . $file->getExtension();
                                $banner = new Banner();
                                $banner->assign(array(
                                    'subdomain_id' => $this->_get_subdomainID(),
                                    'photo' => $fileFullName
                                ));
                                $banner->save();

                                $tmp_banner_banner_type = new TmpBannerBannerType();
                                $tmp_banner_banner_type->assign([
                                    'banner_id' => $banner->id,
                                    'banner_type_id' => $id
                                ]);
                                $tmp_banner_banner_type->save();

                                $file->moveTo('files/ads/' . $sub_folder . '/' . $fileFullName);
                            }
                        }
                    }
                }

                $this->flashSession->success($this->_message["edit"]);
                $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $type . '/' . $id . '/' . $page;

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $position = Position::find([
            'columns' => 'id, name',
            "conditions" => "active = 'Y'",
            'order' => 'sort ASC, id DESC'
        ]);

        $listBanner = Banner::query()
            ->columns(["id", "name", "link", "photo", "active"])
            ->join("Modules\Models\TmpBannerBannerType", "tmp.banner_id = Modules\Models\Banner.id", "tmp")
            ->where("banner_type_id = :banner_type_id:")
            ->andWhere("deleted = :deleted:")
            ->bind(["banner_type_id" => $id, "deleted" => "N"])
            ->orderBy("sort ASC, id DESC")
            ->groupBy("id")
            ->execute();

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '/index/' . $type .'">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->item = $item;
        $this->view->type = $type;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->position = $position;
        $this->view->list_banner = $listBanner;
        $this->view->tmp_position_module_item_c = $tmp_position_module_item_c;
        $this->view->tmp_position_module_item_arr = $tmp_position_module_item_arr;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updatePageAction($type = 1, $id)
    {
        $sub_folder = $this->_get_subdomainFolder();
        $item = BannerType::findFirst([
            "conditions" => "type = $type AND subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        if ($this->request->isPost()) {
            $module_item = ModuleItem::findFirstById($item->module_item_id);

            
            $module_item_id = $module_item->id;

            $layout = Layout::find([
                'conditions' => 'active = "Y"'
            ]);

            if ($this->request->hasFiles() == true) {
                $general = new General();
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    if (!empty($file->getType())) {
                        $fileName = basename($file->getName(), "." . $file->getExtension());
                        $fileName = $general->create_slug($fileName);
                        $subCode = Text::random(Text::RANDOM_ALNUM);
                        $fileFullName = $fileName . '_' . $subCode . '.' . $file->getExtension();
                        $banner = new Banner();
                        $banner->assign(array(
                            'subdomain_id' => $this->_get_subdomainID(),
                            'photo' => $fileFullName
                        ));
                        $banner->save();

                        $tmp_banner_banner_type = new TmpBannerBannerType();
                        $tmp_banner_banner_type->assign([
                            'banner_id' => $banner->id,
                            'banner_type_id' => $id
                        ]);
                        $tmp_banner_banner_type->save();

                        $file->moveTo('files/ads/' . $sub_folder . '/' . $fileFullName);
                    }
                }
            }
  

            $this->flashSession->success($this->_message["edit"]);
            $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

            $this->response->redirect($url);
        }
    }

    public function showAction($type = 1, $id, $page = 1)
    {
        $item = BannerType::findFirst([
            "conditions" => "type = $type AND subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
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

    public function hideAction($type = 1, $id, $page = 1)
    {
        $item = BannerType::findFirst([
            "conditions" => "type = $type AND subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        ;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
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
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showSliderAction(int $id, int $page = 1)
    {
        $item = BannerType::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'slider' => 'Y',
        ]);

        if ($item->save()) {
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function hideSliderAction(int $id, int $page = 1)
    {
        $item = BannerType::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        ;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'slider' => 'N'
        ]);

        if ($item->save()) {
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function showPartnerAction(int $id, int $page = 1)
    {
        $item = BannerType::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'partner' => 'Y',
        ]);

        if ($item->save()) {
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function hidePartnerAction(int $id, int $page = 1)
    {
        $item = BannerType::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        ;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'partner' => 'N'
        ]);

        if ($item->save()) {
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function showmultyAction($type = 1, $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = BannerType::findFirst([
                "conditions" => "type = $type AND subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
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

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

        if ($d > 0) {
            $this->flashSession->success($this->_message["show"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function hidemultyAction($type = 1, $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = BannerType::findFirst([
                "conditions" => "type = $type AND subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
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

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/index/' . $this->_getControllerName();

        if ($d > 0) {
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function deleteAction($type = 1, $id, $page = 1)
    {
        $item = BannerType::findFirst([
            "conditions" => "type = $type AND subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $count_banner = $this->count_banner($id);
        if ($count_banner > 0) {
            $this->flash->error("Không thể xóa vì chứa ràng buộc dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        // $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/index/' . $this->_getControllerName();

        $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

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

    public function deletemultyAction($type = 1, $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        // $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

        $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        foreach ($listid as $id) {
            $item = BannerType::findFirst([
                "conditions" => "type = $type AND subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);

            $count_banner = $this->count_banner($id);
            if ($count_banner > 0) {
                $this->flash->error("Không thể xóa vì chứa ràng buộc dữ liệu");
                return $this->dispatcher->forward(array('action' => 'index'));
            }

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

    public function updateBannerAction($type, $banner_type_id, $id, $page = 1)
    {
        $sub_folder = $this->_get_subdomainFolder();
        $item = Banner::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $photo = $item->photo;
        $form = new BannerForm($item, array('edit' => true));
        $general = new General();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $item->assign(array(
                'link' => $this->request->getPost('link'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
            ));

            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                if (!empty($files[0]->getType())) {
                    $ext = $files[0]->getType();
                    if ($this->extFileCheck($ext)) {
                        $fileName = basename($files[0]->getName(), "." . $files[0]->getExtension());
                        $fileName = $general->create_slug($fileName);
                        $subCode = Text::random(Text::RANDOM_ALNUM);
                        $fileFullName = $fileName . '_' . $subCode . '.' . $files[0]->getExtension();
                        $item->assign(array(
                            'photo' => $fileFullName
                        ));

                        if ($files[0]->moveTo('files/ads/' . $sub_folder . '/' . $fileFullName)) {
                            @unlink('files/ads/' . $sub_folder . '/' . $photo);
                        }
                    } else {
                        $this->flash->error('Định dạng file không cho phép. Hãy upload một hình ảnh.');
                    }
                }
            }

            if ($item->save()) {
                

                TmpBannerBannerType::deleteByRawSql('banner_id ='. $id .'');
                $tmp_banner_banner_type = new TmpBannerBannerType();
                $tmp_banner_banner_type->assign([
                    'banner_id' => $id,
                    'banner_type_id' => $banner_type_id
                ]);
                $tmp_banner_banner_type->save();
                
                $this->flashSession->success("Cập nhật thành công");

                $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

                $this->response->redirect($url);
            } else {
                $this->flashSession->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/banner_type/index/'. $banner_type_id .'">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->item = $item;
        $this->view->banner_type_id = $banner_type_id;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/update_banner');
    }

    public function showBannerAction($type, $banner_type_id, $id, $page = 1)
    {
        $item = Banner::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

        $item->assign([
            'active' => 'Y'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success("Hiển thị hình ảnh con thành công");
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $this->response->redirect($url);
    }

    public function hideBannerAction($type, $banner_type_id, $id, $page = 1)
    {
        $item = Banner::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        // $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $type . '/'  . $banner_type_id . '#banner' : ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $type . '/' . $banner_type_id . '#banner';

        $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

        $item->assign([
            'active' => 'N'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success("Hiển thị hình ảnh con thành công");
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $this->response->redirect($url);
    }

    public function deleteBannerAction($type, $banner_type_id, $id, $page = 1)
    {
        $item = Banner::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        // $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $type . '/'  . $banner_type_id . '#banner' : ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $type . '/' . $banner_type_id . '#banner';

        $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

        $item->assign([
            'deleted' => 'Y'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success("Xóa hình ảnh con thành công");
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $this->response->redirect($url);
    }

    public function _deleteBannerAction($type, $banner_type_id, $id, $page = 1)
    {
        $sub_folder = $this->_get_subdomainFolder();
        $item = Banner::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $photo = $item->photo;

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            
            $tmp_banner_banner_type = TmpBannerBannerType::find([
                'banner_id' => $item->id
            ]);
            TmpBannerBannerType::deleteByRawSql('banner_id ='. $id .' AND banner_type_id = '. $banner_type_id .'');

            @unlink('files/ads/' . $sub_folder . '/' . $photo);
            $this->flashSession->success("Xóa hình ảnh thành công");
        }

        $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        $this->response->redirect($url);
    }

    public function _deleteAction(int $id, $page = 1)
    {
        $item = BannerType::findFirst([
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
            $item = BannerType::findFirst([
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

    public function count_banner(int $id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['b' => 'Modules\Models\Banner'])
            ->join('Modules\Models\TmpBannerBannerType', 'tmp.banner_id = b.id', 'tmp')
            ->where('tmp.banner_type_id = '. $id .' AND b.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }
}
