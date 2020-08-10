<?php

namespace Modules\PhalconVn;

use Modules\Models\NewsCategory;

class NewsCategoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function nestedCategory($parentId = 0)
    {
        $subdomain_id = $this->_subdomain_id;

        $category = NewsCategory::findFirst([
            "columns" => "id, name, slug, parent_id, level",
            "order" => "sort ASC, id DESC",
            "conditions" => "id = ". $parentId ." AND subdomain_id = $subdomain_id AND active='Y' AND deleted = 'N'"
        ]);

        $trees = array();

        if (count($category) > 0) {
            $category = $category->toArray();
            $trees = array(
                "id" => $category["id"],
                "parent_id" => $category["parent_id"],
                "name" => $category["name"],
                "slug" => $category["slug"],
                "level" => $category["level"]
            );

            if ($category["level"] > 0) {
                $trees["parent"] = $this->nestedCategory($category["parent_id"]);
            }
        }

        return $trees;
    }

    public function getCategoryTreeId($parent_id = 0, $trees = array())
    {
        $subdomain_id = $this->_subdomain_id;
        if (!$trees) {
            $trees = array();
        }
        $trees[] = $parent_id;

        $categories = NewsCategory::find(
            [
                "columns" => "id",
                "order" => "sort ASC, id DESC",
                "conditions" => "parent_id = ". $parent_id ." AND subdomain_id = $subdomain_id AND active='Y' AND deleted = 'N'"
            ]
        );

        $trees_obj = array();
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $trees[] = $category->id;
                $trees   = $this->getCategoryTreeId($category->id, $trees);
            }
        }
        if (!empty($trees)) {
            foreach ($trees as $tree) {
                $trees_obj[] = $tree;
            }
        }

        $trees_obj = array_unique($trees_obj);
        return $trees_obj;
    }

    public function getCategoryNewsDetail($newsId)
    {
        $category = NewsCategory::query()
            ->columns(["id", "name", "slug", "parent_id"])
            ->join("Modules\Models\TmpNewsNewsCategirt", "tmp.category_id = Modules\Models\NewsCategory.id", "tmp")
            ->where("news_id = :news_id:")
            ->andWhere("active = :active:")
            ->andWhere("deleted = :deleted:")
            ->bind(["news_id" => $newsId, "active" => "Y", "deleted" => "N"])
            ->orderBy("id DESC")
            ->execute();

        return $category;
    }

    public function recursive($type_id, $parent_id = 0, $level = 0, $options = null)
    {
        $tag = new Tag();
        $categories = NewsCategory::find(["columns" => "id, name, slug, level, parent_id", "order" => "sort ASC, id DESC", "conditions" => "type_id = $type_id AND parent_id = " . $parent_id . " AND subdomain_id = " . $this->_subdomain_id . " AND active='Y' AND deleted = 'N'"]);
        if (count($categories) > 0) {
            $ret = '<ul>';
            foreach ($categories as $category) {
                if (isset($options['router'])) {
                    $link = $tag->site_url($options['router'] . '/' . $category->slug);
                } else {
                    $link = $tag->site_url($category->slug);
                }
                if ($category->parent_id == $parent_id) {
                    $ret.= '<li class="main_category_' . $level . '"><a href="' . $link . '">' . $category->name . '</a>';
                    $ret.= $this->recursive($category->id, $level + 1);
                    $ret.= '</li>';
                }
            }
            return $ret . '</ul>';
        }
    }
}
