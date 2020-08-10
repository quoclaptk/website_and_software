<?php

namespace Modules\PhalconVn;

use Modules\Models\ModuleItem;
use Modules\Models\TmpLayoutModule;
use Modules\Models\TmpModuleGroupLayout;

class ModuleItemService extends BaseService
{
    /**
     * @var cache key $cacheKey
     */
    protected $cacheKey;

    public function __construct()
    {
        parent::__construct();
        $this->cacheKey = 'general:' . $this->_subdomain_id;
    }

    /**
     * get module child of module parent
     * @param  int $moduleId module parent id
     * @return mixed
     */
    public function getModuleChild($moduleId)
    {
        $moduleItemChilds = ModuleItem::query()
        ->columns([
            "Modules\Models\ModuleItem.parent_id",
            "Modules\Models\ModuleItem.name AS module_name",
            "Modules\Models\ModuleItem.id AS module_id",
            "Modules\Models\ModuleItem.module_group_id",
            "Modules\Models\ModuleItem.sort AS module_sort",
            "Modules\Models\ModuleItem.type AS module_type",
            "Modules\Models\ModuleItem.type_id AS module_type_id",
            "tmp.id",
            "tmp.layout_id",
            "tmp.position_id",
            "tmp.active",
            "tmp.sort",
        ])
        ->join("Modules\Models\TmpLayoutModule", "Modules\Models\ModuleItem.id = tmp.module_item_id", "tmp")
        ->where("Modules\Models\ModuleItem.subdomain_id = ". $this->_subdomain_id ." AND parent_id = ". $moduleId ." AND tmp.active = 'Y'")
        ->orderBy("tmp.sort ASC, tmp.id DESC")
        ->execute();

        if (count($moduleItemChilds) == 0) {
            $moduleItemChilds = ModuleItem::query()
                ->columns([
                    "Modules\Models\ModuleItem.parent_id",
                    "Modules\Models\ModuleItem.name AS module_name",
                    "Modules\Models\ModuleItem.id AS module_id",
                    "Modules\Models\ModuleItem.module_group_id",
                    "Modules\Models\ModuleItem.sort AS module_sort",
                    "Modules\Models\ModuleItem.type AS module_type",
                    "Modules\Models\ModuleItem.type_id AS module_type_id",
                    "tmp.id",
                    "tmp.layout_id",
                    "tmp.position_id",
                    "tmp.active",
                    "tmp.sort",
                ])
                ->join("Modules\Models\TmpLayoutModule", "Modules\Models\ModuleItem.id = tmp.module_item_id", "tmp")
                ->where("Modules\Models\ModuleItem.subdomain_id = ". $this->_subdomain_id ." AND parent_id = ". $moduleId ." AND tmp.active = 'N'")
                ->orderBy("tmp.sort ASC, tmp.id DESC")
                ->execute();

            if (count($moduleItemChilds) == 0) {
                $moduleItemChilds = ModuleItem::find([
                    'columns' => 'id AS module_id, parent_id, name AS module_name, level, type AS module_type,  type_id AS module_type_id, sort, active',
                    'conditions' => 'parent_id = '. $moduleId .''
                ]);
            }
        }

        return $moduleItemChilds;
    }

    /**
     * get list module type subdomain
     * @param  string $type
     * @return mixed
     */
    public function getTmpTypeModules($type = 'product')
    {
        $tmpTypeModules = $this->modelsManager->createBuilder()
            ->columns(
                "tmp.id,
                tmp.active,
                tmp.sort,
                mi.module_group_id,
                mi.parent_id,
                mi.name AS module_name,
                mi.id AS module_id,
                mi.module_group_id,
                mi.sort AS module_sort,
                mi.type"
            )
            ->addFrom("Modules\Models\TmpTypeModule", "tmp")
            ->join("Modules\Models\ModuleItem", "mi.id = tmp.module_item_id", "mi")
            ->where("mi.subdomain_id = ". $this->_subdomain_id ." AND mi.parent_id = 0 AND tmp.type = '". $type ."'")
            ->orderBy("tmp.sort ASC, tmp.id DESC, mi.name ASC, mi.sort ASC, mi.type ASC, mi.id DESC")
            ->getQuery()
            ->execute();

        return $tmpTypeModules;
    }

    /**
     * get list module display
     * @param  int $layoutId
     * @return array
     */
    public function getTmpLayoutModule($layoutId)
    {
        /*$tmpModuleGroupLayout = TmpModuleGroupLayout::find();
        $arrayTmp = [];
        if (count($tmpModuleGroupLayout) > 0) {
            foreach ($tmpModuleGroupLayout as $row) {
                $arrayTmp[$row->layout_id][0] = 0;
                $arrayTmp[$row->layout_id][] = $row->module_group_id;
            }
        }*/
        $hasKey = __FUNCTION__ . '_' . $layoutId;
        $positionModuleArray = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array', 'lang' => false]);
        if ($positionModuleArray === null) {
            $tmpLayoutModules = TmpLayoutModule::query()
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
                    "mi.type",
                    "p.code as position_name",
                ])
                ->join("Modules\Models\Position", "p.id = Modules\Models\TmpLayoutModule.position_id", "p")
                ->join("Modules\Models\ModuleItem", "mi.id = Modules\Models\TmpLayoutModule.module_item_id", "mi")
                ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
                ->andWhere("layout_id = :layout_id:")
                ->andWhere("mi.parent_id = :parent_id:")
                ->andWhere("mi.subdomain_id = :subdomain_id:")
                ->bind(["subdomain_id" => $this->_subdomain_id, "layout_id" => $layoutId, "parent_id" => 0])
                // ->inWhere("mi.module_group_id", $arrayTmp[$layout_id])
                ->orderBy("p.sort ASC, Modules\Models\TmpLayoutModule.sort ASC, Modules\Models\TmpLayoutModule.id DESC")
                ->execute();

            $positionModuleArray = array();
            foreach ($tmpLayoutModules as $tmpLayoutModule) {
                $positionModuleArray[$tmpLayoutModule->position_name][] = [
                    'id' => $tmpLayoutModule->id,
                    'layout_id' => $tmpLayoutModule->layout_id,
                    'subdomain_id' => $this->_subdomain_id,
                    'active' => $tmpLayoutModule->active,
                    'parent_id' => $tmpLayoutModule->parent_id,
                    'module_id' => $tmpLayoutModule->module_id,
                    'module_name' => $tmpLayoutModule->module_name,
                    'position_name' => $tmpLayoutModule->position_name,
                    'position_id' => $tmpLayoutModule->position_id,
                    'module_sort' => $tmpLayoutModule->module_sort,
                    'sort' => $tmpLayoutModule->sort,
                    'type' => $tmpLayoutModule->type,
                ];
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $positionModuleArray, ['lang' => false]);
        }

        return $positionModuleArray;
    }

    /**
     * get list module display with positon
     * @param  int $layoutId
     * @param  string $positionCode
     * @return array
     */
    public function getTmpLayoutModulePosition($layoutId, $positionCode)
    {
        /*$tmpModuleGroupLayout = TmpModuleGroupLayout::find();
        $arrayTmp = [];
        if (count($tmpModuleGroupLayout) > 0) {
            foreach ($tmpModuleGroupLayout as $row) {
                $arrayTmp[$row->layout_id][0] = 0;
                $arrayTmp[$row->layout_id][] = $row->module_group_id;
            }
        }*/
        $hasKey = __FUNCTION__ . '_' . $layoutId . '_' . $positionCode;
        $results = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array', 'lang' => false]);
        if ($results === null) {
            $tmpLayoutModules = TmpLayoutModule::query()
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
                    "mi.type",
                    "p.code as position_name",
                ])
                ->join("Modules\Models\Position", "p.id = Modules\Models\TmpLayoutModule.position_id", "p")
                ->join("Modules\Models\ModuleItem", "mi.id = Modules\Models\TmpLayoutModule.module_item_id", "mi")
                ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
                ->andWhere("layout_id = :layout_id:")
                ->andWhere("mi.parent_id = :parent_id:")
                ->andWhere("mi.subdomain_id = :subdomain_id:")
                ->andWhere("p.code = :code:")
                ->bind(["subdomain_id" => $this->_subdomain_id, "layout_id" => $layoutId, "parent_id" => 0, 'code' => $positionCode])
                // ->inWhere("mi.module_group_id", $arrayTmp[$layout_id])
                ->orderBy("p.sort ASC, Modules\Models\TmpLayoutModule.sort ASC, Modules\Models\TmpLayoutModule.id DESC")
                ->execute();

            $results = array();
            if (count($tmpLayoutModules) > 0) {
                foreach ($tmpLayoutModules as $tmpLayoutModule) {
                    $results[] = [
                        'id' => $tmpLayoutModule->id,
                        'layout_id' => $tmpLayoutModule->layout_id,
                        'subdomain_id' => $this->_subdomain_id,
                        'active' => $tmpLayoutModule->active,
                        'parent_id' => $tmpLayoutModule->parent_id,
                        'module_id' => $tmpLayoutModule->module_id,
                        'module_name' => $tmpLayoutModule->module_name,
                        'position_name' => $tmpLayoutModule->position_name,
                        'position_id' => $tmpLayoutModule->position_id,
                        'module_sort' => $tmpLayoutModule->module_sort,
                        'sort' => $tmpLayoutModule->sort,
                        'type' => $tmpLayoutModule->type,
                    ];
                }
            }
            

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $results, ['lang' => false]);
        }

        return $results;
    }
}
