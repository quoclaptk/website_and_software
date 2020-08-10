<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Background;
use Modules\Models\Layout;
use Modules\Models\LayoutConfig;
use Modules\Models\ModuleGroup;
use Modules\Models\ModuleItem;
use Modules\Models\Position;
use Modules\Models\Setting;
use Modules\Models\TmpLayoutModule;
use Modules\Models\TmpPositionModuleItem;
use Modules\Models\BannerType;
use Modules\Models\Menu;
use Modules\Models\Posts;
use Modules\Models\TmpModuleGroupLayout;

class ModuleItemController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Module';
    }

    public function updateAction($groupType)
    {
        $moduleGroup = ModuleGroup::findFirst([
            "conditions" => "type = '$groupType' AND active = 'Y' AND deleted = 'N'"
        ]);
        if (!$moduleGroup) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
        }
        $moduleGroupId = $moduleGroup->id;
        $item = ModuleItem::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND module_group_id = $moduleGroupId"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
        }

        $setting = Setting::findFirst([
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() .""
        ]);

        if ($this->request->isPost()) {
            
            $position_id = $this->request->getPost('position');

            $layout = Layout::find([
                'conditions' => 'active = "Y"'
            ]);

            if (!empty($position_id)) {
                TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $item->id .'');
                TmpLayoutModule::deleteByRawSql('module_item_id ='. $item->id .' AND subdomain_id = '. $item->subdomain_id .'');
                foreach ($position_id as $rowPosition) {
                    //insert tmp position module item
                    $tmp_position_module_item = new TmpPositionModuleItem();

                    $tmp_position_module_item->assign([
                        'module_item_id' => $item->id,
                        'position_id' => $rowPosition
                    ]);

                    $tmp_position_module_item->save();

                    /*
                    $tmpLayoutModule = TmpLayoutModule::findFirst([
                        "conditions" => "position_id = ". $rowPosition ." AND module_item_id = ". $item->id ." AND subdomain_id = ". $item->subdomain_id .""
                    ]);*/


                    $layout_module = TmpLayoutModule::findFirst([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND position_id = $rowPosition",
                        "order" => "sort DESC"
                    ]);
                    $sort = (count($layout_module) > 0) ? $layout_module->sort + 1 : 1;
                    foreach ($layout as $rowLayout) {
                        $tmp_layout_module = new TmpLayoutModule();
                        $tmp_layout_module->assign([
                            'position_id' => $rowPosition,
                            'subdomain_id' => $this->_get_subdomainID(),
                            'layout_id' => $rowLayout->id,
                            'module_item_id' => $item->id,
                            'sort' => $sort
                        ]);
                        $tmp_layout_module->save();
                    }


                    /*
                    $tmpLayoutModule->assign([
                        'sort' => $this->request->getPost("sort_" . $rowPosition)
                    ]);

                    $tmpLayoutModule->save();*/

                    switch ($groupType) {
                        case "_fanpage_left_right":
                            $setting->assign([
                                "facebook" => $this->request->getPost("facebook")
                            ]);
                            $setting->save();
                            break;
                    }

                    $this->flashSession->success($this->_message["edit"]);
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $groupType;

                    $this->response->redirect($url);
                }
            }
        }

        $tmp_position_module_item_c = TmpPositionModuleItem::findByModuleItemId($item->id);
        $tmp_position_module_item_arr = array();
        if (!empty($tmp_position_module_item_c)) {
            foreach ($tmp_position_module_item_c as $row) {
                $tmp_position_module_item_arr[] = $row->position_id;
            }
        }

        $position = Position::find([
            'columns' => 'id, name',
            "conditions" => "active = 'Y'",
            'order' => 'sort ASC, id DESC'
        ]);
        $dataPosition = [];
        if (count($position) > 0) {
            foreach ($position->toArray() as $key => $value) {
                $dataPosition[] = $value;
                $tmpLayoutModule = TmpLayoutModule::findFirst([
                    "conditions" => "module_item_id = ". $item->id ." AND position_id = ". $value['id'] .""
                ]);
                if (!empty($tmpLayoutModule)) {
                    $dataPosition[$key]['sort'] = $tmpLayoutModule->sort;
                }
            }
        }

//        $this->print_array($dataPosition);die;

        $this->view->title_bar = 'Cập nhật module';
        $breadcrumb = '<li><li class="active">'. $this->view->title_bar .'</li>';

        $this->view->breadcrumb = $breadcrumb;
        $this->view->group_type  = $groupType ;
        $this->view->setting  = $setting ;
        $this->view->module_group = $moduleGroup;
        $this->view->item = $item;
        $this->view->position = $position;
        $this->view->data_position = $dataPosition;
        $this->view->tmp_position_module_item_c = $tmp_position_module_item_c;
        $this->view->tmp_position_module_item_arr = $tmp_position_module_item_arr;
    }

    public function addModuleToPositionAction()
    {
        if ($this->request->isPost()) {
            
            $position = $this->request->getPost("position");
            switch ($position) {
                case 'header':
                    $position_id = 5;
                    break;

                case 'left':
                    $position_id = 6;
                    break;

                case 'right':
                    $position_id = 7;
                    break;

                case 'center':
                    $position_id = 8;
                    break;

                case 'footer':
                    $position_id = 9;
                    break;
            }
            $layout = $this->request->getPost("layout");
            $moduleSelect = $this->request->getPost("module_select");

            $layout_module = TmpLayoutModule::findFirst([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND position_id = $position_id",
                "order" => "sort DESC"
            ]);
            $sort = (count($layout_module) > 0) ? $layout_module->sort + 1 : 1;

            foreach ($moduleSelect as $row) {
                $tmp_layout_module = new TmpLayoutModule();
                $tmp_layout_module->assign([
                    'subdomain_id' => $this->_get_subdomainID(),
                    'layout_id' => $layout,
                    'position_id' => $position_id,
                    'module_item_id' => $row,
                    'active' => 'Y',
                    'sort' => $sort,
                ]);
                $tmp_layout_module->save();
            }
            echo 1;
        }
        $this->view->disable();
    }

    public function loadModulePositionAction()
    {
        if ($this->request->isPost()) {
            $this->view->setTemplateBefore('load_module_position');
            $layoutId = $this->request->getPost('layout');

            $layoutConfig = LayoutConfig::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND layout_id = $layoutId"
            ]);
            $css_item = (!empty($layoutConfig->css)) ? json_decode($layoutConfig->css) : "";

            $tmpModuleGroupLayout = TmpModuleGroupLayout::find();
            $arrayTmp = [];
            if (count($tmpModuleGroupLayout) > 0) {
                foreach ($tmpModuleGroupLayout as $row) {
                    $arrayTmp[$row->layout_id][0] = 0;
                    $arrayTmp[$row->layout_id][] = $row->module_group_id;
                }
            }

            $background = Background::findFirst([
                "conditions" => "layout_config_id = $layoutConfig->id"
            ]);

            $tmpLayoutModule = TmpLayoutModule::query()
            ->columns([
                "Modules\Models\TmpLayoutModule.id",
                "Modules\Models\TmpLayoutModule.layout_id",
                "Modules\Models\TmpLayoutModule.position_id",
                "Modules\Models\TmpLayoutModule.active",
                "Modules\Models\TmpLayoutModule.sort",
                "mi.parent_id",
                "mi.name AS module_name",
                "mi.id AS module_id",
                "mi.module_group_id",
                "mi.sort AS module_sort",
                "mi.type AS module_type",
                "p.code as position_name",
            ])
            ->join("Modules\Models\Position", "p.id = Modules\Models\TmpLayoutModule.position_id", "p")
            ->join("Modules\Models\ModuleItem", "mi.id = Modules\Models\TmpLayoutModule.module_item_id", "mi")
            ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
            ->andWhere("layout_id = :layout_id:")
            ->andWhere("mi.parent_id = :parent_id:")
            ->bind(["subdomain_id" => $this->_get_subdomainID(),
                "layout_id" => $layoutId,
                "parent_id" => 0
            ])
            ->inWhere("mi.module_group_id", $arrayTmp[$layoutId])
            ->orderBy("p.sort ASC, Modules\Models\TmpLayoutModule.sort ASC, mi.name ASC, Modules\Models\TmpLayoutModule.id DESC")
            ->execute();


            $position_module_array = array();
            foreach ($tmpLayoutModule as $row) {
                switch ($row->module_type) {
                    case 'banner':
                        $bannerType = BannerType::findFirstByModuleItemId($row->module_id);
                        $url = ACP_NAME . '/banner_type/index/' . $bannerType->type;
                        break;
                    case 'post':
                        $post = Posts::findFirstByModuleItemId($row->module_id);
                        $url = ACP_NAME . '/posts';
                        break;
                    case 'menu':
                        $menu = Menu::findFirstByModuleItemId($row->module_id);
                        $url = ACP_NAME . '/menu/update/' . $menu->id;
                        break;
                    
                    default:
                        // $url = ACP_NAME . '/module_item/update/' . $row->module_type;
                        $url = '';
                        break;
                }
                $position_module_array[$row->position_name][] = [
                    'id' => $row->id,
                    'layout_id' => $row->layout_id,
                    'active' => $row->active,
                    'parent_id' => $row->parent_id,
                    'module_id' => $row->module_id,
                    'module_name' => $row->module_name,
                    'position_name' => $row->position_name,
                    'position_id' => $row->position_id,
                    'module_sort' => $row->module_sort,
                    'module_type' => $row->module_type,
                    'sort' => $row->sort,
                    'url' => $url
                ];
                $j = 0;
                foreach ($position_module_array as $row) {
                    for ($i = 0; $i < count($row); $i++) {
                        $child = TmpLayoutModule::query()
                            ->columns([
                                "Modules\Models\TmpLayoutModule.id",
                                "Modules\Models\TmpLayoutModule.layout_id",
                                "Modules\Models\TmpLayoutModule.position_id",
                                "Modules\Models\TmpLayoutModule.active",
                                "Modules\Models\TmpLayoutModule.sort",
                                "mi.parent_id",
                                "mi.name AS module_name",
                                "mi.id AS module_id",
                                "mi.sort AS module_sort",
                                "mi.type AS module_type",
                                "p.code as position_name",
                            ])
                            ->join("Modules\Models\ModuleItem", "mi.id = Modules\Models\TmpLayoutModule.module_item_id", "mi")
                            ->join("Modules\Models\Position", "p.id = Modules\Models\TmpLayoutModule.position_id", "p")
                            ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
                            ->andWhere("layout_id = :layout_id:")
                            ->andWhere("mi.parent_id = :parent_id:")
                            ->bind(["subdomain_id" => $this->_get_subdomainID(), "layout_id" => $layoutId, "parent_id" => $row[$i]['module_id']])
                            ->orderBy("p.sort ASC, Modules\Models\TmpLayoutModule.sort ASC, mi.name ASC, Modules\Models\TmpLayoutModule.id DESC")
                            ->execute();
                        if (count($child) > 0) {
                            $child_module_array = array();
                            foreach ($child as $rowChild) {
                                $child_module_array[] = [
                                    'id' => $rowChild->id,
                                    'layout_id' => $rowChild->layout_id,
                                    'active' => $rowChild->active,
                                    'parent_id' => $rowChild->parent_id,
                                    'module_id' => $rowChild->module_id,
                                    'module_name' => $rowChild->module_name,
                                    'position_name' => $rowChild->position_name,
                                    'position_id' => $rowChild->position_id,
                                    'module_sort' => $rowChild->module_sort,
                                    'module_type' => $rowChild->module_type,
                                    'sort' => $rowChild->sort,
                                ];
                            }
                        }

                        // if(count($child) > 0) $position_module_array[$row[$i]['position_name']][$i]['child'] = $child_module_array; else $position_module_array[$row[$i]['position_name']][$i]['child'] = '';
                        $position_module_array[$row[$i]['position_name']][$i]['child'] = '';
                    }
                    $j++;
                }
            }

            if ($layoutId == 1) {
                unset($position_module_array["left"]);
                unset($position_module_array["right"]);
            }
            if ($layoutId == 4) {
                unset($position_module_array["left"]);
            }
            if ($layoutId == 3) {
                unset($position_module_array["right"]);
            }

            $moduleItem = ModuleItem::find([
                "order" => "name ASC, id DESC",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND parent_id = 0 AND deleted = 'N' AND module_group_id IN (" . implode(',', $arrayTmp[$layoutId]) .")",
            ]);

            foreach ($moduleItem->toArray() as $row) {
                $tmpLayoutModuleHeader = TmpLayoutModule::findFirst([
                    "colums" => "id",
                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND layout_id = $layoutId AND module_item_id = ". $row['id'] ." AND position_id = 5"
                ]);
                if (empty($tmpLayoutModuleHeader)) {
                    $moduleArray["header"][] = $row;
                }

                if ($layoutId == 2 || $layoutId == 3) {
                    $tmpLayoutModuleLeft = TmpLayoutModule::findFirst([
                        "colums" => "id",
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND layout_id = $layoutId AND module_item_id = ". $row['id'] ." AND position_id = 6"
                    ]);
                    if (empty($tmpLayoutModuleLeft)) {
                        $moduleArray["left"][] = $row;
                    }
                }

                if ($layoutId == 2 || $layoutId == 4) {
                    $tmpLayoutModuleRight = TmpLayoutModule::findFirst([
                        "colums" => "id",
                        "conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND layout_id = $layoutId AND module_item_id = " . $row['id'] . " AND position_id = 7"
                    ]);
                    if (empty($tmpLayoutModuleRight)) {
                        $moduleArray["right"][] = $row;
                    }
                }

                $tmpLayoutModuleCenter = TmpLayoutModule::findFirst([
                    "colums" => "id",
                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND layout_id = $layoutId AND module_item_id = ". $row['id'] ." AND position_id = 8"
                ]);
                if (empty($tmpLayoutModuleCenter)) {
                    $moduleArray["center"][] = $row;
                }

                $tmpLayoutModuleFooter = TmpLayoutModule::findFirst([
                    "colums" => "id",
                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND layout_id = $layoutId AND module_item_id = ". $row['id'] ." AND position_id = 9"
                ]);
                if (empty($tmpLayoutModuleFooter)) {
                    $moduleArray["footer"][] = $row;
                }
            }
            $this->view->position_module_array = $position_module_array;
            $this->view->layout_id = $layoutId;
            $this->view->module_item = $moduleItem;
            $this->view->module_array = $moduleArray;
            $this->view->background = $background;
            $this->view->layout_config = $layoutConfig;
            $this->view->css_item = $css_item;
            $this->view->list_color = $this->mainGlobal->colorDefault();
            $this->view->pick($this->_getControllerName() . '/load_module_position');
        }
    }

    public function listAction()
    {
        $this->view->title_bar = 'Danh sách module';
        $breadcrumb = '<li><li class="active">'. $this->view->title_bar .'</li>';
        $items = ModuleItem::find([
            'conditions' => 'module_group_id = 0 AND type != "post"',
            'group' => 'name'
        ]);

        $this->view->items = $items;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function updateListAction($name)
    {
        if ($this->request->getPost('name') != '') {
            $moduleItems = ModuleItem::findByName($name);
            if (count($moduleItems) > 0) {
                foreach ($moduleItems as $moduleItem) {
                    $moduleItem->name = $this->request->getPost('name');
                    $moduleItem->save();
                }
            }

            $this->flashSession->success("Cập nhật dữ liệu thành công!");
            $controllerName = $this->_getControllerName();
            $url = ACP_NAME . '/' . $controllerName . '/list';

            $this->response->redirect($url);
        }
        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '/list' . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->name = $name;
        $this->view->pick($this->_getControllerName() . '/update_list');
    }

    public function addModulePostToFooterTotalAction($subdomainId)
    {
        $this->view->disable();
        $item = ModuleGroup::findFirstByType('footer_total_posts');
        if ($item) {
            $id = $item->id;
            $moduleItems = ModuleItem::find([
                'columns' => 'id',
                'conditions' => 'module_group_id = '. $item->id .' AND subdomain_id = '. $subdomainId .' AND type = "post"'
            ]);

            if ($moduleItems->count() == 0) {
                $module_item_parent = ($item->parent_id != 0) ? ModuleItem::findFirst(['columns' => 'id', 'conditions' => 'subdomain_id = '. $subdomainId .' AND module_group_id = '. $item->parent_id .''])->id : 0;
           
                $posts = Posts::find([
                    'conditions' => 'subdomain_id = '. $subdomainId .'',
                    'order' => 'sort ASC, id DESC'
                ]);

                if ($posts->count() > 0) {
                    $i = 8;
                    $k = 1;
                    foreach ($posts as $post) {
                        $data_module_item = [
                            'subdomain_id' => $subdomainId,
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

                echo 'insert success';
            } else {
                echo 'no data';
            }
        }
    }

    public function updateSubdomainIdAction()
    {
        $tmpPositionModuleItems = TmpPositionModuleItem::find();
        foreach ($tmpPositionModuleItems as $tmpPositionModuleItem) {
            if ($tmpPositionModuleItem->module_item) {
                $tmpPositionModuleItem->subdomain_id = $tmpPositionModuleItem->module_item->subdomain_id;
                $tmpPositionModuleItem->save();
            }
        }
    }
}
