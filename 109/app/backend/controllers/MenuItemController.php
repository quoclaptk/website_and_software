<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Category;
use Modules\Models\NewsMenu;
use Modules\Models\NewsCategory;
use Modules\Models\Menu;
use Modules\Models\MenuItem;
use Modules\Models\ModuleItem;
use Modules\Models\NewsType;
use Modules\Models\Position;
use Modules\Models\TmpPositionModuleItem;
use Modules\Forms\MenuForm;
use Modules\Forms\MenuItemForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Text;
use Phalcon\Image\Adapter\GD;

class MenuItemController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Menu';
    }

    public function updateAction(int $id, int $page = 1)
    {
        $item = MenuItem::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND language_id = 1 AND id = $id"
        ]);
        
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new MenuItemForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $photo = $item->photo;
            $data = [
                'name' => $this->request->getPost('name'),
                'url' => $this->request->getPost('url'),
                'other_url' => $this->request->getPost('other_url'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
                'font_class' => $this->request->getPost('font_class'),
                'icon_type' => $this->request->getPost('icon_type'),
                'new_blank' => $this->request->getPost('new_blank'),
            ];

            $sub_folder = $this->_get_subdomainFolder();
            $general = new General();
            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                if (!empty($files[0]->getType())) {
                    $ext = $files[0]->getType();
                    if ($this->extFileCheck($ext)) {
                        $fileName = basename($files[0]->getName(), "." . $files[0]->getExtension());
                        $fileName = $general->create_slug($fileName);
                        $subCode = Text::random(Text::RANDOM_ALNUM);
                        $fileFullName = $fileName . '_' . $subCode . '.' . $files[0]->getExtension();

                        $data['photo'] = $fileFullName;

                        if (!is_dir("files/icon/" . $sub_folder)) {
                            mkdir("files/icon/" . $sub_folder, 0777);
                        }

                        if ($files[0]->moveTo("files/icon/" . $sub_folder . "/" . $fileFullName)) {
                            if (!empty($photo)) {
                                @unlink("files/icon/" . $sub_folder . "/" . $photo);
                            }
                        }
                    } else {
                        $this->flash->error('Định dạng file không cho phép. Hãy upload một hình ảnh.');
                    }
                }
            }

            $item->assign($data);

            if ($item->save()) {
                $menuItemLangs = MenuItem::findByDependId($id);
                if (count($menuItemLangs) > 0) {
                    foreach ($menuItemLangs as $menuItemLang) {
                        $menuItemLang->sort = $item->sort;
                        $menuItemLang->active = $item->active;
                        $menuItemLang->font_class = $item->font_class;
                        $menuItemLang->icon_type = $item->icon_type;
                        $menuItemLang->new_blank = $item->new_blank;
                        $menuItemLang->photo = $item->photo;
                        $menuItemLang->save();
                    }
                }

                // update elastic
                $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);
                
                $this->flashSession->success($this->_message["edit"]);
                $url = ACP_NAME . '/menu/update/' . $item->menu_id;

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

//        $this->print_array($menu_item);die;

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->item = $item;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function deletePhotoAction($id)
    {
        $item = MenuItem::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ACP_NAME . '/menu_item/update/' . $id;

        $sub_folder = $this->_get_subdomainFolder();
        $folder = $item->folder;
        $photo = $item->photo;
        $item->photo = '';
        if ($item->save()) {
            
            @unlink("files/icon/" . $sub_folder . "/" . $folder . "/" . $photo);
            $this->flashSession->success("Xóa hình ảnh thành công");
        } else {
            $this->flashSession->error($item->getMessages());
        }


        $this->response->redirect($url);
    }

    public function showAction(int $id, int $page = 1)
    {
        $item = MenuItem::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url =  ACP_NAME . '/menu/update/' . $item->menu_id;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'Y',
        ]);

        if ($item->save()) {
            // update elastic
            $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction(int $id, int $page = 1)
    {
        $item = MenuItem::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url =  ACP_NAME . '/menu/update/' . $item->menu_id;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'N',
        ]);

        if ($item->save()) {
            $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);
            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showmultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = MenuItem::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'Y'
                ]);
                $item->save();
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);;
            
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
            $item = Menu::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item = MenuItem::findFirst([
                    "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
                ]);
                if ($item) {
                    $item->assign([
                        'active' => 'N'
                    ]);
                    $item->save();
                    $d++;
                }
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);
            
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function showNewBankAction(int $id, int $page = 1)
    {
        $item = MenuItem::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url =  ACP_NAME . '/menu/update/' . $item->menu_id;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'new_blank' => 'Y',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function hideNewBankAction(int $id, int $page = 1)
    {
        $item = MenuItem::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url =  ACP_NAME . '/menu/update/' . $item->menu_id;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'new_blank' => 'N',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Ẩn dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function deleteAction(int $id, $page = 1)
    {
        $item = MenuItem::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url =  ACP_NAME . '/menu/update/' . $item->menu_id;

        $count_child = $this->count_child($id);
        if ($count_child > 0) {
            $this->flashSession->error("Không thể xóa vì có chứa ràng buộc dữ liệu");
            $this->response->redirect($url);
        }
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'deleted' => 'Y',
        ]);

        if ($item->save()) {
            $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);
            
            $this->flashSession->success('Xóa dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;

        foreach ($listid as $id) {
            $item = MenuItem::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            $url =  ACP_NAME . '/menu/update/' . $item->menu_id;

            $count_child = $this->count_child($id);
            if ($count_child > 0) {
                $this->flash->error("Không thể xóa vì chứa ràng buộc dữ liệu");
                $this->response->redirect($url);
            }

            if ($item) {
                $item->assign([
                    'deleted' => 'N'
                ]);
                $item->save();
                $d++;
            }
        }


        if ($d > 0) {
            $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);
            
            $this->flashSession->success("Xóa dữ liệu thành công");
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction($id, $page = 1)
    {
        $item = MenuItem::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        if ($item) {
            switch ($item->module_name) {
                case 'category':
                    $category = Category::findFirstById($item->module_id);
                    if ($category) {
                        $category->menu = 'N';
                        $category->save();
                        $categoryLangs = Category::findByDependId($category->id);
                        if (count($categoryLangs) > 0) {
                            foreach ($categoryLangs as $categoryLang) {
                                $categoryLang->menu = 'N';
                                $categoryLang->save();
                            }
                        }
                    }
                    break;

                case 'news_menu':
                    $newsMenu = NewsMenu::findFirstById($item->module_id);
                    if ($newsMenu) {
                        $newsMenu->menu = 'N';
                        $newsMenu->save();
                        $newsMenuLangs = NewsMenu::findByDependId($newsMenu->id);
                        if (count($newsMenuLangs) > 0) {
                            foreach ($newsMenuLangs as $newsMenuLang) {
                                $newsMenuLang->menu = 'N';
                                $newsMenuLang->save();
                            }
                        }
                    }
                    break;

                case 'news_category':
                    $newsCategory = NewsCategory::findFirstById($item->module_id);
                    if ($newsCategory) {
                        $newsCategory->menu = 'N';
                        $newsCategory->save();
                    }
                    break;
            }
        }

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url =  ACP_NAME . '/menu/update/' . $item->menu_id;

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            $menuItems = MenuItem::findByDependId($id);
            if (count($menuItems) > 0) {
                foreach ($menuItems as $menuItem) {
                    $menuItem->delete();
                }
            }
            
            $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);
            
            $this->flashSession->success($this->_message["delete"]);
        }

        
        $this->response->redirect($url);
    }

    public function _deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Menu::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            $url =  ACP_NAME . '/menu/update/' . $item->menu_id;
            if ($item) {
                if ($item->delete()) {
                    $d++;
                }
            }
        }
        //echo $d;die;
        if ($d > 0) {
            $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function count_child(int $id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['n' => '\Modules\Models\MenuItem'])
            ->where('n.parent_id = '. $id .' AND n.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }
}
