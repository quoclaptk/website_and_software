<?php

namespace Modules\PhalconVn;

use Modules\Models\Product;
use Modules\Models\ProductContent;
use Modules\Models\ProductElement;
use Modules\Models\ProductElementDetail;
use Modules\Models\TmpProductProductElementDetail;
use Modules\Models\PriceRange;
use Modules\Models\Category;

class ShortcodeService
{
    public function __construct()
    {
        $mainGlobal = new MainGlobal();
        $this->_subdomain_id = $mainGlobal->getDomainId();
    }

    public function getCategoryTreeId($parent_id = 0, $trees = array(), $options = null)
    {
        $subdomain_id = $this->_subdomain_id;
        $active = (isset($options['notActive']) && $options['notActive'] == true) ? '' : ' AND active = "Y"';
        if ($parent_id != 0) {
            $trees[] = $parent_id;
        }
        $categories = Category::find(["columns" => "id", "conditions" => "parent_id = " . $parent_id . $active ." AND deleted = 'N'"]);
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $trees[] = $category->id;
                $this->getCategoryTreeId($category->id, $trees, $options);
            }
        }

        $trees = array_unique($trees);
        return $trees;
    }

    public function getProductsInListCategory($categorySlug, $limit = 20)
    {
        $category = Category::findFirst([
            "conditions" => "subdomain_id = $this->_subdomain_id AND slug = '$categorySlug'"
        ]);

        if ($category) {
            $categoryId = $category->id;
            $listCategoryTreeId = $this->getCategoryTreeId($categoryId);
            $listCategoryTreeId = (count($listCategoryTreeId) > 1) ? implode(",", $listCategoryTreeId) : $listCategoryTreeId[0];

            $products = Product::query()
                ->columns(["Modules\Models\Product.id", "name", "slug", "cost", "price", "photo", "folder", "summary", "link", "enable_link, out_stock, purchase_number, hits, cart_link"])
                ->join("Modules\Models\TmpProductCategory", "tmp.product_id = Modules\Models\Product.id", "tmp")
                ->where("category_id IN ($listCategoryTreeId)")
                ->andWhere("active = :active:")
                ->andWhere("deleted = :deleted:")
                ->bind(["active" => "Y", "deleted" => "N"])
                ->orderBy("sort ASC, Modules\Models\Product.id DESC")
                ->limit($limit)
                ->groupBy("Modules\Models\Product.id")
                ->execute();

            return $products;
        }
    }
}
