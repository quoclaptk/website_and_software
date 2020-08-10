<?php

namespace Modules\Backend\Controllers;

use Modules\Models\TmpLayoutModule;
use Modules\Models\Position;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Image\Adapter\GD;

class TmpLayoutModuleController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Cấu hình layout';
    }

    public function savePositionAction()
    {
        if ($this->request->isPost()) {
            
            $layoutId = $this->request->getPost('layout');
            $positionCode = $this->request->getPost('position');
            // $input_module_key = $this->request->getPost('input_module_key');
            $sort_module_key = $this->request->getPost('sort_module_key');

            $position = Position::findFirstByCode($positionCode);

            $tmpLayoutModule = TmpLayoutModule::query()
            ->columns([
                "Modules\Models\TmpLayoutModule.id",
                "Modules\Models\TmpLayoutModule.layout_id",
                "Modules\Models\TmpLayoutModule.position_id",
                "Modules\Models\TmpLayoutModule.active",
                "Modules\Models\TmpLayoutModule.sort",
                "mi.parent_id",
            ])
            ->join("Modules\Models\ModuleItem", "mi.id = Modules\Models\TmpLayoutModule.module_item_id", "mi")
            ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
            ->andWhere("layout_id = :layout_id:")
            ->andWhere("position_id = :position_id:")
            ->andWhere("mi.parent_id = :parent_id:")
            ->bind(["subdomain_id" => $this->_get_subdomainID(), "layout_id" => $layoutId, "position_id" => $position->id, "parent_id" => 0])
            ->orderBy("Modules\Models\TmpLayoutModule.sort ASC, mi.name ASC, Modules\Models\TmpLayoutModule.id DESC")
            ->execute();
            $i = 0;
            foreach ($tmpLayoutModule as $row) {
                $tmp = TmpLayoutModule::findFirstById($row->id);
                // $active = (in_array($row->id, $input_module_key)) ? 'Y' : 'N';
                $tmp->assign([
                    'sort' => $sort_module_key[$i],
                    // 'active' => $active
                ]);
                $tmp->save();
                $i++;
            }
            echo 1;
        }
        $this->view->disable();
    }

    public function deleteMultyAction()
    {
        if ($this->request->isPost()) {
            
            $layoutId = $this->request->getPost('layout');
            $positionCode = $this->request->getPost('position');
            $input_module_key = $this->request->getPost('input_module_key');

            $position = Position::findFirstByCode($positionCode);

            $tmpLayoutModule = TmpLayoutModule::query()
            ->columns([
                "Modules\Models\TmpLayoutModule.id",
                "Modules\Models\TmpLayoutModule.layout_id",
                "Modules\Models\TmpLayoutModule.position_id",
                "Modules\Models\TmpLayoutModule.active",
                "Modules\Models\TmpLayoutModule.sort",
                "mi.parent_id",
            ])
            ->join("Modules\Models\ModuleItem", "mi.id = Modules\Models\TmpLayoutModule.module_item_id", "mi")
            ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
            ->andWhere("Modules\Models\TmpLayoutModule.id IN (" . implode(',', $input_module_key) .")")
            ->andWhere("layout_id = :layout_id:")
            ->andWhere("position_id = :position_id:")
            ->andWhere("mi.parent_id = :parent_id:")
            ->bind(["subdomain_id" => $this->_get_subdomainID(), "layout_id" => $layoutId, "position_id" => $position->id, "parent_id" => 0])
            ->orderBy("Modules\Models\TmpLayoutModule.sort ASC, mi.name ASC, Modules\Models\TmpLayoutModule.id DESC")
            ->execute();
            $i = 0;
            foreach ($tmpLayoutModule as $row) {
                $tmp = TmpLayoutModule::findFirstById($row->id);
                $tmp->delete();
                $i++;
            }
            echo 1;
        }
        $this->view->disable();
    }

    public function showAction()
    {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $item = TmpLayoutModule::findFirstById($id);
            $item->assign([
                'active' => 'Y'
            ]);
            if ($item->save()) {
                
                echo 1;
            } else {
                echo 0;
            }
        }
        $this->view->disable();
    }

    public function hideAction()
    {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $item = TmpLayoutModule::findFirstById($id);
            $item->assign([
                'active' => 'N'
            ]);
            if ($item->save()) {
                
                echo 1;
            } else {
                echo 0;
            }
        }
        $this->view->disable();
    }
}
