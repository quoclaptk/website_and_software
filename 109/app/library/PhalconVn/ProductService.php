<?php

namespace Modules\PhalconVn;

use Modules\Models\Product;
use Modules\Models\ProductContent;
use Modules\Models\ProductElement;
use Modules\Models\ProductElementDetail;
use Modules\Models\ProductPhoto;
use Modules\Models\TmpProductProductElementDetail;
use Modules\Models\PriceRange;
use Modules\Models\Category;
use Modules\PhalconVn\CategoryService;

class ProductService extends BaseService
{
    /**
     * @var string
     */
    protected $cacheKey;

    /**
     * @var integer
     */
    protected $_subdomain_id;

    /**
     * @var array
     */
    protected $cacheArrayKeys;

    public function __construct()
    {
        parent::__construct();
        $this->cacheKey = 'product:' . $this->_subdomain_id;
        $this->cacheArrayKeys = [
            'getPaginatorIndex' => 'getPaginatorIndex:' . $this->_subdomain_id,
            'getPaginatorCategory' => 'getPaginatorCategory:' . $this->_subdomain_id,
        ];
    }

    /**
     * Get product type
     * 
     * @param  string $type 
     * @param  integer $limit
     * 
     * @return mixed       
     */
    public function getProductHot($type = "hot", $limit)
    {   
        $hasKey = __FUNCTION__ . '_' . $type . '_' . $limit;
        $products = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($products === null) {
            $conditions = "$type = 'Y' AND subdomain_id = ". $this->_subdomain_id ." AND language_id = $this->_lang_id AND active = 'Y' AND deleted = 'N'";

            $products = $this->productRepository->findByParams([
                "columns" => "id, name, slug, cost, price, photo, photo_secondary, folder, summary, link, enable_link, out_stock, purchase_number, hits, cart_link",
                "conditions" => $conditions,
                "order" => "sort ASC, id DESC",
                "limit" => $limit
            ]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $products);
        }

        return $products;
    }

    /**
     * Get product content by product id
     * 
     * @param  integer $productId
     * @return mixed           
     */
    public function getProductDetail($productId)
    {
        $hasKey = __FUNCTION__ . '_' . $productId;
        $productContents = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($productContents === null) {
            $productContents = ProductContent::query()
                ->columns(["pd.id", "pd.name", "content"])
                ->join("Modules\Models\ProductDetail", "pd.id = Modules\Models\ProductContent.product_detail_id", "pd")
                ->where("product_id = :product_id:")
                ->andWhere("pd.active = :active:")
                ->andWhere("pd.deleted = :deleted:")
                ->andWhere("pd.subdomain_id = :subdomain_id:")
                ->bind(["product_id" => $productId, "active" => "Y", "deleted" => "N", "subdomain_id" => $this->_subdomain_id])
                ->orderBy("pd.sort ASC, pd.id DESC")
                ->execute();

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $productContents);
        }

        return $productContents;
    }

    /**
     * get other product detail
     *
     * @param int $productId
     * @param string $listCategoryTreeId
     * @param int $limit default 20
     * 
     * @return mixed
     */
    public function getOtherProduct($productId, $listCategoryTreeId, $limit = 20)
    {
        $hasKey = __FUNCTION__ . '_' . $productId;
        $products = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($products === null) {
            $products = Product::query()
                ->columns(["Modules\Models\Product.id", "name", "slug", "cost", "price", "photo", "photo_secondary", "folder", "summary", "link", "enable_link, out_stock, purchase_number, hits, cart_link"])
                ->join("Modules\Models\TmpProductCategory", "tmp.product_id = Modules\Models\Product.id", "tmp")
                ->where("category_id IN ($listCategoryTreeId) AND language_id = $this->_lang_id AND Modules\Models\Product.subdomain_id = $this->_subdomain_id AND Modules\Models\Product.id != $productId AND active = 'Y' AND deleted = 'N'")
                ->orderBy("sort ASC, Modules\Models\Product.id DESC")
                ->groupBy("Modules\Models\Product.id")
                ->limit($limit)
                ->execute();

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $products);
        }

        return $products;
    }

    public function _getProductCategoryShowHome($limit = 0)
    {
        $categories = Category::find([
            "columns" => "id, name, slug",
            "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND language_id = $this->_lang_id AND parent_id = 0 AND active = 'Y' AND show_home = 'Y' AND deleted = 'N'",
            "order" => "sort ASC, id DESC"
        ]);

        $result = [];
        if (count($categories) > 0) {
            $i = 0;
            foreach ($categories as $row) {
                $listCategoryTreeId = $this->category_service->getCategoryTreeId($row->id);
                $listCategoryTreeId = (count($listCategoryTreeId) > 1) ? implode(",", $listCategoryTreeId) : $listCategoryTreeId[0];
                $products = $this->getProductInCategoryLimit($listCategoryTreeId, $limit);

                $result[] = $row->toArray();
                $result[$i]['products'] = $products->toArray();

                //get category child
                $categoryChilds = Category::find([
                    "columns" => "id, name, slug",
                    "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND parent_id = $row->id AND active = 'Y' AND show_home = 'Y' AND deleted = 'N'",
                    "order" => "sort ASC, id DESC"
                ]);
                $resultChild = [];
                if (count($categoryChilds) > 0) {
                    $j = 0;
                    foreach ($categoryChilds as $categoryChild) {
                        $listCategoryChildTreeId = $this->category_service->getCategoryTreeId($categoryChild->id);
                        $listCategoryChildTreeId = (count($listCategoryChildTreeId) > 1) ? implode(",", $listCategoryChildTreeId) : $listCategoryChildTreeId[0];
                        $productChilds = $this->getProductInCategoryLimit($listCategoryChildTreeId, $limit);
                        $resultChild[] = $categoryChild->toArray();
                        $resultChild[$j]['products'] = $productChilds->toArray();
                        $j++;
                    }
                }

                $result[$i]['childs'] = $resultChild;
                $i++;
            }
        }
        
        return $result;
    }

    /**
     * Get list product in category
     *
     * @param int $limit
     * @return array $result
     */
    public function getProductCategoryShowHome($limit = 10)
    {
        $hasKey = __FUNCTION__ . '_' . $limit;
        $results = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array']);
        if ($results === null) {
            $categories = Category::find([
                "columns" => "id, name, slug",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND language_id = $this->_lang_id AND active = 'Y' AND show_home = 'Y' AND deleted = 'N'",
                "limit" => 10,
                "order" => "sort_home ASC, sort ASC, id DESC"
            ]);

            $results = [];
            if (count($categories) > 0) {
                $i = 0;
                foreach ($categories as $row) {
                    $listCategoryTreeId = $this->category_service->getCategoryTreeId($row->id);
                    $listCategoryTreeId = (count($listCategoryTreeId) > 1) ? implode(",", $listCategoryTreeId) : $listCategoryTreeId[0];
                    $products = $this->getProductInCategoryLimit($listCategoryTreeId, $limit);
                    $results[] = $row->toArray();
                    if (count($products) > 0) {
                        $results[$i]['products'] = $products;
                    } else {
                        $results[$i]['products'] = null;
                    }
                    
                    $i++;
                }
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $results);
        }
        
        return $results;
    }

    public function getProductInCategoryLimit($listCategoryTreeId, $limit)
    {
        $hasKey = __FUNCTION__ . '_' . $listCategoryTreeId;
        $products = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($products === null) {
            $products = Product::query()
                        ->columns(["Modules\Models\Product.id, name, slug, cost, price, photo, photo_secondary, folder, summary, link, enable_link, out_stock, purchase_number, hits, cart_link"])
                        ->join("Modules\Models\TmpProductCategory", "tmp.product_id = Modules\Models\Product.id", "tmp")
                        ->where("category_id IN ($listCategoryTreeId)")
                        ->andWhere("active = :active:")
                        ->andWhere("deleted = :deleted:")
                        ->bind(["active" => "Y", "deleted" => "N"])
                        ->orderBy("sort ASC, Modules\Models\Product.id DESC")
                        ->groupBy("Modules\Models\Product.id")
                        ->limit($limit)
                        ->execute();

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $products);

            // if not cache return to Array
            if (count($products) > 0) {
                return $products->toArray();
            }
        }

        return $products;
    }

    public function getProductElementSearch($options = null)
    {
        $where = 'subdomain_id = '. $this->_subdomain_id .' AND language_id = '. $this->_lang_id .' AND active = "Y" AND deleted = "N"';
        if (isset($options['search']) && $options['search'] == true) {
            $where .= ' AND search = "Y"';
        }

        $productElements = ProductElement::find([
            'columns' => 'id, name',
            'conditions' => $where,
            'order' => 'sort ASC, id DESC'
        ]);

        $result = [];
        if (count($productElements) > 0) {
            foreach ($productElements as $key => $productElement) {
                $productElementDetails = ProductElementDetail::find([
                    'columns' => 'id, name',
                    'conditions' => 'product_element_id = '. $productElement->id .' AND language_id = '. $this->_lang_id .' AND active = "Y" AND deleted = "N"',
                    'order' => 'sort ASC, id DESC'
                ]);
                $result[] = $productElement->toArray();
                if (count($productElementDetails) > 0) {
                    $result[$key]['details'] = $productElementDetails->toArray();
                }
            }
        }

        return $result;
    }

    /**
     * Get elememts by product id
     *
     * @param int $id
     * @return array $results
     */
    public function getElementProductId($id)
    {
        $hasKey = __FUNCTION__ . '_' . $id;
        $results = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array']);
        if ($results === null) {
            $results = [];
            $tmpProductProductElementDetails = TmpProductProductElementDetail::find([
                "conditions" => "subdomain_id = $this->_subdomain_id AND product_id = $id AND product_element_detail_id != ''"
            ]);
            if (count($tmpProductProductElementDetails) > 0) {
                $prElmDetailIds = [];
                foreach ($tmpProductProductElementDetails as $key => $tmp) {
                    if ($tmp->product_element_detail_id != null) {
                        $prElmDetailIds[] = $tmp->product_element_detail_id;
                    }
                }

                if (!empty($prElmDetailIds)) {
                    $listPrElmDetailIds = (count($prElmDetailIds) > 1) ? implode(',', $prElmDetailIds) : $prElmDetailIds[0];

                    $productElementDetails = $this->modelsManager->createBuilder()
                    ->columns("ped.id, ped.name, ped.slug, pe.name as pe_name")
                    ->addFrom("Modules\Models\ProductElementDetail", "ped")
                    ->join("Modules\Models\ProductElement", "pe.id = ped.product_element_id", "pe")
                    ->where("ped.id IN ($listPrElmDetailIds) AND pe.active = 'Y' AND ped.active = 'Y' AND pe.show_price = 'N' AND pe.combo = 'N'")
                    ->orderBy("pe.sort ASC, pe.id DESC, ped.sort ASC, ped.id DESC")
                    ->getQuery()
                    ->execute();

                    if (count($productElementDetails) > 0) {
                        foreach ($productElementDetails->toArray() as $key => $productElementDetail) {
                            $results[$productElementDetail['pe_name']] = $productElementDetail;
                        }
                    }
                }
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $results);
        }

        return $results;
    }

    public function _getElementProductShowPrices($id)
    {
        $result = [];
        $tmpProductProductElementDetails = $this->modelsManager->createBuilder()
        ->columns("ped.id as ped_id, ped.name, tmp.id, tmp.cost, tmp.price, pe.name as pe_name, pe.id as pe_id")
        ->addFrom("Modules\Models\TmpProductProductElementDetail", "tmp")
        ->join("Modules\Models\ProductElementDetail", "tmp.product_element_detail_id = ped.id", "ped")
        ->join("Modules\Models\ProductElement", "ped.product_element_id = pe.id", "pe")
        ->where("tmp.product_id = $id AND pe.active = 'Y' AND pe.combo = 'N' AND ped.active = 'Y' AND pe.show_price = 'Y'")
        ->orderBy("pe.sort ASC, pe.id DESC, ped.sort ASC, ped.id DESC")
        ->getQuery()
        ->execute();

        if (count($tmpProductProductElementDetails) > 0) {
            foreach ($tmpProductProductElementDetails as $tmpProductProductElementDetail) {
                $result[$tmpProductProductElementDetail->pe_name][] = $tmpProductProductElementDetail->toArray();
            }
        }
        
        return $result;
    }

    /**
     * Get element product show prices
     *
     * @param int $id
     * @return array $results
     */
    public function getElementProductShowPrices($id)
    {
        $hasKey = __FUNCTION__ . '_' . $id;
        $results = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array']);
        if ($results === null) {
            $result = [];
            $tmpProductProductElementDetails = TmpProductProductElementDetail::find([
                "conditions" => "subdomain_id = $this->_subdomain_id AND product_id = $id AND product_element_detail_id != ''"
            ]);
            if (count($tmpProductProductElementDetails) > 0) {
                $tmpId = [];
                foreach ($tmpProductProductElementDetails as $key => $tmp) {
                    if ($tmp->product_element_detail_id != null) {
                        $tmpId[] = $tmp->id;
                    }
                }

                if (!empty($tmpId)) {
                    $listTmp = (count($tmpId) > 1) ? implode(',', $tmpId) : $tmpId[0];

                    $tmpProductProductElementDetails = $this->modelsManager->createBuilder()
                    ->columns("ped.id as ped_id, ped.name, tmp.id, tmp.cost, tmp.price, tmp.cost_usd, tmp.price_usd, pe.name as pe_name, pe.id as pe_id")
                    ->addFrom("Modules\Models\TmpProductProductElementDetail", "tmp")
                    ->join("Modules\Models\ProductElementDetail", "tmp.product_element_detail_id = ped.id", "ped")
                    ->join("Modules\Models\ProductElement", "ped.product_element_id = pe.id", "pe")
                    ->where("tmp.id IN ($listTmp) AND tmp.product_id = $id AND pe.active = 'Y' AND pe.combo = 'N' AND ped.active = 'Y' AND pe.show_price = 'Y'")
                    ->orderBy("pe.sort ASC, pe.id DESC, ped.sort ASC, ped.id DESC")
                    ->getQuery()
                    ->execute();

                    if (count($tmpProductProductElementDetails) > 0) {
                        foreach ($tmpProductProductElementDetails as $tmpProductProductElementDetail) {
                            $result[$tmpProductProductElementDetail->pe_name][] = $tmpProductProductElementDetail->toArray();
                        }
                    }
                }
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $results);
        }
        
        return $result;
    }

    /**
     * Get product element combo info
     *
     * @param int $id
     * @return array
     */
    public function getElementProductShowComboPrices($id)
    {
        $hasKey = __FUNCTION__ . '_' . $id;
        $results = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array']);
        if ($results === null) {
            $result = [];
            $tmpElements = [];
            $tmpProductProductElementDetails = TmpProductProductElementDetail::find([
                "conditions" => "subdomain_id = $this->_subdomain_id AND product_id = $id AND combo_id != ''",
                "order" => "id"
            ]);
            $peSelected = [];
            $cost = 0;
            $price = 0;
            $costUsd = 0;
            $priceUsd = 0;
            $photo = null;
            $tmpProductElementSelected = null;
            $productPhotoElementId = null;
            $productElmSelectedName = '';
            if (count($tmpProductProductElementDetails) > 0) {
                $listComboId = null;
                foreach ($tmpProductProductElementDetails as $key => $tmpProductProductElementDetail) {
                    if ($tmpProductProductElementDetail->selected != 1) {
                        $tmpElements[] = $tmpProductProductElementDetail->toArray();
                    }
                    
                    $listComboId .= $tmpProductProductElementDetail->combo_id;
                    if ($key < count($tmpProductProductElementDetails) - 1) {
                        $listComboId .= ',';
                    }

                    if ($tmpProductProductElementDetail->selected == 1) {
                        $tmpProductElementSelected = $tmpProductProductElementDetail->id;
                        $peSelected = explode(',', $tmpProductProductElementDetail->combo_id);
                        foreach ($peSelected as $keyPeSelect => $peSelect) {
                            $productElementDetailSelected = ProductElementDetail::findFirstById($peSelect);
                            if ($productElementDetailSelected->product_element->is_product_photo == 'Y') {
                                $productPhotoElementId = $peSelect;
                            }

                            $productElmSelectedName .= $productElementDetailSelected->name;
                            if ($keyPeSelect < count($peSelected) - 1) $productElmSelectedName .= ' - ';
                        }
                        $cost = $tmpProductProductElementDetail->cost;
                        $price = $tmpProductProductElementDetail->price;
                        $costUsd = $tmpProductProductElementDetail->cost_usd;
                        $priceUsd = $tmpProductProductElementDetail->price_usd;
                        $photo = null;
                        if ($tmpProductProductElementDetail->photo != null) {
                            $photo = $tmpProductProductElementDetail->photo;
                        } elseif (isset($tmpElements[$key])) {
                            $product = Product::findFirstById($tmpElements[$key]['id']);
                            if ($product)
                                $photo = $product->photo;
                        }
                    }

                    if (isset($tmpElements[$key]) && $tmpElements[$key]['photo'] != null) {
                        $product = Product::findFirstById($tmpElements[$key]['id']);
                        if ($product)
                            $tmpElements[$key]['photo'] = $product->photo;
                    }
                }

                $productElements = ProductElement::find([
                    "columns" => "id, name, slug, is_color",
                    "conditions" => "subdomain_id = $this->_subdomain_id AND language_id = $this->_lang_id AND combo = 'Y' AND active = 'Y' AND deleted = 'N'",
                    "order" => "sort ASC, id DESC"
                ]);

                $objProductElements = [];
                if (count($productElements) > 0) {
                    foreach ($productElements as $key => $productElement) {
                        $objProductElements[] = $productElement->toArray();
                        $productElementDetails = ProductElementDetail::find([
                            "columns" => "id, name, slug, color",
                            "conditions" => "subdomain_id = $this->_subdomain_id AND product_element_id = $productElement->id AND id IN (". $listComboId .") AND active = 'Y' AND deleted = 'N'",
                            "order" => "sort ASC, id DESC"
                        ]);

                        if (count($productElementDetails) > 0) {
                            $objProductElements[$key]['productElementDetails'] = $productElementDetails->toArray(); 
                        } else {
                            $objProductElements[$key]['productElementDetails'] = [];
                        }
                    }
                }
            }

            if (!empty($tmpElements) && !empty($objProductElements)) {
                $result = compact('tmpElements', 'objProductElements', 'peSelected', 'cost', 'price', 'costUsd', 'priceUsd', 'productPhotoElementId', 'tmpProductElementSelected', 'productElmSelectedName');
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $results);
        }
        
        return $result;
    }

    /**
     * Get price range for search
     * 
     * @return mixed
     */
    public function getPriceRange()
    {
        $hasKey = __FUNCTION__;
        $priceRanges = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($priceRanges === null) {
            $priceRanges = PriceRange::find([
                'columns' => 'id, name, from_price, to_price',
                'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND active = "Y" AND deleted = "N"',
                'order' => 'sort ASC, id DESC'
            ]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $priceRanges);
        }

        return $priceRanges;
    }

    /**
     * Get paginator data product index
     * 
     * @param  int $page 
     * @param  int $numberItem 
     * 
     * @return mixed       
     */
    public function getPaginatorIndex($page, $numberItem)
    {
        $products = $this->modelsManager->createBuilder()
            ->from(MODELS . '\Product')
            ->columns("id, name, slug, cost, price, photo, photo_secondary, folder, summary, link, enable_link, out_stock, purchase_number, hits, cart_link")
            ->where("subdomain_id = $this->_subdomain_id AND language_id = $this->_lang_id")
            ->andWhere("active = 'Y' AND deleted = 'N'")
            ->orderBy("sort ASC, id DESC");
       
        $paginator = $this->pagination_service->queryBuilder($products, $numberItem, $page);

        return $paginator;
    }

    /**
     * Get paginator data product category
     * 
     * @param  array $listCategoryTreeId 
     * @param  int $page 
     * @param  int $numberItem 
     * 
     * @return mixed       
     */
    public function getPaginatorCategory($listCategoryTreeId, $page, $numberItem)
    {
        $products =  $this->modelsManager->createBuilder()
            ->from(MODELS . '\Product')
            ->columns("Modules\Models\Product.id, name, slug, cost, price, photo, photo_secondary, folder, summary, link, enable_link, out_stock, purchase_number, hits, cart_link")
            ->join("Modules\Models\TmpProductCategory", "tmp.product_id = Modules\Models\Product.id", "tmp")
            ->where("Modules\Models\Product.subdomain_id = $this->_subdomain_id AND category_id IN ($listCategoryTreeId) AND language_id = $this->_lang_id AND active = 'Y' AND deleted = 'N'")
            ->orderBy("sort ASC, Modules\Models\Product.id DESC")
            ->groupBy("Modules\Models\Product.id");
       
        $paginator = $this->pagination_service->queryBuilder($products, $numberItem, $page);

        return $paginator;
    }
}
