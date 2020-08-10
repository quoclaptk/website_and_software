<?php

namespace Modules\Backend\Controllers;

use Modules\Models\ConfigCore;
use Modules\Models\ConfigGroup;
use Modules\Models\ConfigItem;
use Modules\Models\ModuleGroup;
use Modules\Models\ModuleItem;
use Modules\Models\Position;
use Modules\Models\Subdomain;
use Modules\Forms\ConfigCoreForm;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Mvc\View;

class ConfigItemController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Cấu hình';
    }

    public function groupAction($module = '')
    {
        $item = ConfigGroup::findFirst([
            "conditions" => "module = '$module' AND active = 'Y'"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array( 'module' => 'backend', 'controller' => 'index','action' => 'error'));
        }

        $list_config_item = ConfigItem::find([
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND config_group_id = " .$item->id ." AND active='Y'",
            "order" => "name ASC, id DESC"
        ]);

        $list_config_item_arr = array();
        if (count($list_config_item)) {
            $list_config_item_arr = $list_config_item->toArray();
            for ($i=0; $i < count($list_config_item_arr); $i++) {
                if ($list_config_item_arr[$i]['type'] == 'radio' || $list_config_item_arr[$i]['type'] == 'select' || $list_config_item_arr[$i]['type'] == 'checkbox') {
                    $list_config_item_arr[$i]['list_value'] = json_decode($list_config_item_arr[$i]['value'], true);
                }
            }
        }

        if ($this->request->isPost()) {
            $request = $this->request->getPost();
            if (!empty($list_config_item_arr)) {
                
                foreach ($list_config_item_arr as $row) {
                    if (isset($request[$row['field']])) {
                        $config_item = ConfigItem::findFirstById($row['id']);
                        if ($row['type'] == 'text' || $row['type'] == 'textarea') {
                            $config_item->assign([
                                'value' => $request[$row['field']]
                            ]);
                            $config_item->save();
                        }
                        if ($row['type'] == 'checkbox' || $row['type'] == 'radio') {
                            $array_list_value = array();
                            foreach ($row['list_value'] as $key => $value) {
                                $array_list_value[] = $value;
                                if (in_array($value['name'], $request[$row['field']])) {
                                    $array_list_value[$key]['select'] = 1;
                                }
                                if (!in_array($value['name'], $request[$row['field']])) {
                                    $array_list_value[$key]['select'] = 0;
                                }
                            }
                            $config_item->assign([
                                'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                            ]);
                            $config_item->save();
                        }
                        if ($row['type'] == 'select') {
                            $array_list_value = array();
                            foreach ($row['list_value'] as $key => $value) {
                                $array_list_value[] = $value;
                                if ($value['name'] == $request[$row['field']]) {
                                    $array_list_value[$key]['select'] = 1;
                                } else {
                                    $array_list_value[$key]['select'] = 0;
                                }
                            }
                            $config_item->assign([
                                'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                            ]);
                            $config_item->save();
                        }
                    }
                }
            }

            $this->flashSession->success($this->_message["edit"]);
            $url = ACP_NAME . '/' . $this->_getControllerName() . '/group/' . $module;
            $this->response->redirect($url);
        }

        $this->view->module_name = 'Cấu hình ' . $item->name;
        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->item = $item;
        $this->view->list_config_item_arr = $list_config_item_arr;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function viewGuideAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->view->setRenderLevel(
                View::LEVEL_ACTION_VIEW
            );
            $id = $this->request->getPost('id');
            $configCore = ConfigCore::findFirstById($id);
            $this->view->configCore = $configCore;
            $this->view->pick($this->_getControllerName() . '/viewGuide');
        }
    }

    public function updateNewConfigAction()
    {
        $configCores = ConfigCore::find();
        foreach ($configCores as $key => $configCore) {
            $configCoreId = $configCore->id;
            $configItem = ConfigItem::findFirst([
                'conditions' => "config_core_id = $configCoreId AND subdomain_id = " . $this->_get_subdomainID() .""
            ]);
            if (!$configItem) {
                $data_item = [
                    'subdomain_id' => $this->_get_subdomainID(),
                    'config_group_id' => $configCore->config_group_id,
                    'config_core_id' => $configCoreId,
                    'name' => $configCore->name,
                    'field' => $configCore->field,
                    'value' => $configCore->value,
                    'min_value' => $configCore->min_value,
                    'max_value' => $configCore->max_value,
                    'description' => $configCore->description,
                    'place_holder' => $configCore->place_holder,
                    'type' => $configCore->type,
                    'sort' => $configCore->sort,
                    'active' => $configCore->active,
                ];

                $config_item = new ConfigItem();
                $config_item->assign($data_item);
                $config_item->save();
            }
        }

        $this->flashSession->success("Cập nhật cấu hình mới thành công");
        $url = ACP_NAME . '/setting/config';
        $this->response->redirect($url);
    }

}
