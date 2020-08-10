<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Category;
use Modules\Models\Product;
use Modules\Models\ProductPhoto;
use Modules\Models\Subdomain;
use Modules\Models\ProductElement;
use Modules\Models\ProductElementDetail;
use Modules\Models\News;
use Modules\Models\TmpProductProductElementDetail;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Phalcon\Cache\Backend\File as BackFile;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Paginator\Adapter\QueryBuilder;
use Phalcon\Mvc\View;

class ProductController extends BaseController
{
    private $_cacheKey;

    private $_numper_item;

    private $_product_link;

    private $_product_namespace = 'Modules\Models\Product';

    public function onConstruct()
    {
        parent::onConstruct();
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
        $this->_product = "Modules\Models\Product";
        $this->_numper_item = $this->_config['_cf_text_number_item_on_page'];
        $this->_product_link = $this->languageCode == 'vi' ? 'san-pham' : $this->languageCode . '/product';
    }

    /**
     * Display index page
     *
     * @param int $page
     * 
     * @return View
     */
    public function indexAction($page = 1)
    {
        $breadcrumb = "<li class='active'>". $this->_word['_san_pham'] ."</li>";
        $paginator = $this->product_service->getPaginatorIndex($page, $this->_numper_item);
		if ($paginator->total_items == 1) {
            return $this->dispatcher->forward(['controller' => 'product', 'action' => 'detail', 'params' => [$paginator->items[0]->slug]]);
        }

        $title_bar = $this->_word['_san_pham'];
        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('san-pham') : $this->tag->site_url($langCode . '/' . $this->router->getControllerName());
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->title = $this->_word['_san_pham'];
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title_bar = $title_bar;
        $this->view->page = $paginator;
        $this->view->url_page = ($this->languageCode == 'vi') ? 'san-pham' : $this->languageCode . '/' . $this->router->getControllerName();
    }   

    /**
     * Display category page
     *
     * @param string $slug
     * @param int $page
     * @return View
     */
    public function categoryAction($slug, $page = 1)
    {
        $category = Category::findFirst([
            "conditions" => "subdomain_id = $this->_subdomain_id AND language_id = $this->languageId AND slug = '$slug' AND active = 'Y' AND deleted = 'N'"
        ]);

        if (!$category) {
            return $this->dispatcher->forward(['controller' => 'index', 'action' => 'notfound']);
        }

        $breadcrumb = "";
        $breadcrumb .= "<li><a href='". $this->tag->site_url($this->_product_link) ."'>". $this->_word['_san_pham'] ."</a></li>";
        if ($category->level > 0) {
            $nested = $this->category_service->nestedCategory($category->parent_id);

            if (isset($nested['parent'])) {
                $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['parent']['slug']) .'">'. $nested['parent']['name'] .'</a></li>';
            }
            $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['slug']) .'">'. $nested['name'] .'</a></li>';
        }
        $breadcrumb .= "<li class='active'>$category->name</li>";

        $listCategoryTreeId = $this->category_service->getCategoryTreeId($category->id);
        $listCategoryTreeId = (count($listCategoryTreeId) > 1) ? implode(",", $listCategoryTreeId) : $listCategoryTreeId[0];

        //get products
        $paginator = $this->product_service->getPaginatorCategory($listCategoryTreeId, $page, $this->_numper_item);
		
		if ($paginator->total_items == 1) {
            return $this->dispatcher->forward(['controller' => 'product', 'action' => 'detail', 'params' => [$paginator->items[0]->slug]]);
        }

        $title_bar = $category->name;
        $title = (!empty($category->title)) ? $category->title : $category->name;

        // tmp module type
        $tmpTypeModules = $this->module_item_service->getTmpTypeModules('category');

        $languageSlugs = [];
        if ($this->languageCode == 'vi') {
            $languageSlugs[$this->languageId] = $category->slug;
            $dependCategories = Category::findByDependId($category->id);
            if (count($dependCategories) > 0) {
                foreach ($dependCategories as $dependCategory) {
                    $languageSlugs[$dependCategory->language_id] = $dependCategory->slug;
                }
            }
        } else {
            $categoryVi = Category::findFirstById($category->depend_id);
            $languageSlugs[$categoryVi->language_id] = $categoryVi->slug;
            $dependCategories = Category::findByDependId($categoryVi->id);
            if (count($dependCategories) > 0) {
                foreach ($dependCategories as $dependCategory) {
                    $languageSlugs[$dependCategory->language_id] = $dependCategory->slug;
                }
            }
        }

        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langId = $tmpSubdomainLanguage->language_id;
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url($languageSlugs[$langId]) : $this->tag->site_url($langCode . '/' . $languageSlugs[$langId]);
            }
        }

        // set active menu
        $activeMenu = [
            'type' => 'category',
            'id' => $category->id
        ];

        $this->view->category = $category;
        $this->view->activeMenu = $activeMenu;
        $this->view->languageUrls = $languageUrls;
        $this->view->title = $title;
        $this->view->keywords = $category->keywords;
        $this->view->description = $category->description;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->category = $category;
        $this->view->tmpTypeModules = $tmpTypeModules;
        $this->view->title_bar = $title_bar;
        $this->view->page = $paginator;
        $this->view->url_page = ($this->languageCode == 'vi') ? $category->slug : $this->languageCode . '/' . $category->slug;
    }

    /**
     * Display product detail
     *
     * @param string $slug
     * @return resource The page to redirect to
     */
    public function detailAction($slug)
    {
        $folderJs = 'frontend/js';
        if (getenv('APP_ENV') == 'development') {
            $folderJs = 'assets/js';
        }

        $this->assets->addCss('frontend/css/helpers/jquery.fancybox-thumbs.css');
        $this->assets->addJs('frontend/js/jquery.fancybox.pack.js');
        $this->assets->addJs('frontend/css/helpers/jquery.fancybox-thumbs.js');
        $this->assets->addCss('frontend/plugins/flexslider/flexslider.css');
        $this->assets->addJs('frontend/plugins/flexslider/jquery.flexslider-min.js');
        $this->assets->addJs($folderJs . '/product_detail.js?time=' . time());

        // get item data
        $detail = $this->elastic_service->searchWithSlug($slug, 'product');

        if (empty($detail)) {
            return $this->dispatcher->forward(['controller' => 'index', 'action' => 'notfound']);
        }

        $id = $detail->id;
        
        $hits= $detail->hits+ 1;
        $detail->hits = $hits;
        $detail->save();
        // $this->elastic_service->updateProduct($id);

        $productElements = $this->product_service->getElementProductId($id);
        $productElementPrices = $this->product_service->getElementProductShowPrices($id);
        $productElementComboPrices = $this->product_service->getElementProductShowComboPrices($id);
        
        if (!empty($productElementComboPrices)) {
            if ($productElementComboPrices['productPhotoElementId'] != null) {
                $productPhotos = ProductPhoto::find([
                    "columns" => "id, folder, photo, thumb",
                    "conditions" => "subdomain_id = $this->_subdomain_id AND product_id = $id AND product_element_detail_id = ". $productElementComboPrices['productPhotoElementId'] ." AND active = 'Y' AND deleted = 'N'",
                    "order" => "id DESC"
                ]);
            }
        } else {
            // get product photos without color
            $productPhotos = ProductPhoto::find([
                "columns" => "id, folder, photo, thumb",
                "conditions" => "subdomain_id = $this->_subdomain_id AND product_id = $id AND (product_element_detail_id IS NULL OR product_element_detail_id = 0) AND active = 'Y' AND deleted = 'N'",
                "order" => "id DESC"
            ]);
        }
        
        $breadcrumb = "";
        $breadcrumb .= "<li><a href='". $this->tag->site_url($this->_product_link) ."'>". $this->_word['_san_pham'] ."</a></li>";
        $categoryInfos = $this->category_service->getCategoryProductDetail($detail->id);

        $categoryIdArr = array();
        $otherProduct = array();
        if (count($categoryInfos) > 0) {
            $nested = $this->category_service->nestedCategory($categoryInfos[0]->id);

            if (!empty($nested['parent']['parent'])) {
                $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['parent']['parent']['slug']) .'">'. $nested['parent']['parent']['name'] .'</a></li>';
            }

            if (!empty($nested['parent'])) {
                $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['parent']['slug']) .'">'. $nested['parent']['name'] .'</a></li>';
            }

            if (!empty($nested)) {
                $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['slug']) .'">'. $nested['name'] .'</a></li>';
            }

            if ($this->_config['_cf_select_type_other_product'] == 1) {
                $otherProduct = $this->product_service->getOtherProduct($id, $categoryInfos[0]->id);
            } else {
                if (!empty($nested)) {
                    $categoryIdArr[] = $nested['id'];
                    if ($nested['parent_id'] != 0) {
                        $categoryIdArr[] = $nested['parent_id'];
                        $categoryChilds = Category::find([
                            "columns" => "id",
                            "conditions" => "parent_id = ". $nested['parent_id'] ." AND active = 'Y' AND subdomain_id = $this->_subdomain_id"
                        ]);
                        if (count($categoryChilds) > 0) {
                            foreach ($categoryChilds as $categoryChild) {
                                if ($categoryChild->id != $nested['id']) {
                                    $categoryIdArr[] = $categoryChild->id;
                                }
                            }
                        }
                    }

                    $categoryIdArr = (count($categoryIdArr) > 0) ? implode(",", $categoryIdArr) : $categoryIdArr[0];
                    $otherProduct = $this->product_service->getOtherProduct($id, $categoryIdArr);
                } else {
                    $otherProduct = $this->product_service->getOtherProduct($id, $categoryInfos[0]->id);
                }
            }
        }

        $productDetail = $this->product_service->getProductDetail($id);
        $title_bar = $detail->name;
        $title = (!empty($detail->title)) ? $detail->title : $detail->name;
        $image_meta = PROTOCOL . HOST . '/' . _upload_product . $this->mainGlobal->getDomainFolder() . '/' . $detail->folder . '/' . $detail->photo;

        // tmp module type
        $tmpTypeModules = $this->module_item_service->getTmpTypeModules();

        // multilang
        $languageSlugs = [];
        if ($this->languageCode == 'vi') {
            $languageSlugs[$this->languageId] = $detail->slug;
            $dependLangs = Product::findByDependId($detail->id);
            if (count($dependLangs) > 0) {
                foreach ($dependLangs as $dependLang) {
                    $languageSlugs[$dependLang->language_id] = $dependLang->slug;
                }
            }
        } else {
            $itemVi = Product::findFirstById($detail->depend_id);
            $languageSlugs[$itemVi->language_id] = $itemVi->slug;
            $dependLangs = Product::findByDependId($itemVi->id);
            if (count($dependLangs) > 0) {
                foreach ($dependLangs as $dependLang) {
                    $languageSlugs[$dependLang->language_id] = $dependLang->slug;
                }
            }
        }

        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langId = $tmpSubdomainLanguage->language_id;
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url($languageSlugs[$langId]) : $this->tag->site_url($langCode . '/' . $languageSlugs[$langId]);
            }
        }

        $this->view->languageUrls = $languageUrls;

        $this->view->title = $title;
        $this->view->keywords = $detail->keywords;
        $this->view->description = $detail->description;
        $this->view->image_meta = $image_meta;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->detail = $detail;
        $this->view->productPhotos = $productPhotos;
        $this->view->product_detail = $productDetail;
        $this->view->other_product = $otherProduct;
        $this->view->productElements = $productElements;
        $this->view->productElementPrices = $productElementPrices;
        $this->view->productElementComboPrices = $productElementComboPrices;
        $this->view->tmpTypeModules = $tmpTypeModules;
        $this->view->title_bar = $title_bar;
    }

    /**
     * Search Advance
     *
     * @return mixed
     */
    public function searchAction()
    {
        if ($this->request->isGet()) {
            $breadcrumb = "";
            $breadcrumb .= "<li class='active'>". $this->_word['_tim_kiem'] ."</li>";
            $q = $this->request->getQuery('q');

            $query = $this->modelsManager->createBuilder()
                ->columns("$this->_product.id, $this->_product.name, $this->_product.slug, $this->_product.photo, $this->_product.photo_secondary, $this->_product.folder, $this->_product.cost, $this->_product.price, $this->_product.summary, $this->_product.link, $this->_product.enable_link, $this->_product.out_stock, $this->_product.purchase_number, $this->_product.hits, $this->_product.cart_link")->from($this->_product);

            $where = "$this->_product_namespace.subdomain_id = $this->_subdomain_id AND $this->_product_namespace.language_id = $this->languageId AND $this->_product_namespace.active = 'Y' AND $this->_product_namespace.deleted = 'N'";
            if ($q != '') {
                $where .= " AND $this->_product_namespace.name LIKE '%". $q ."%'";
            }

            if ($this->request->getQuery('catID') != 0) {
                $category = $this->request->getQuery('catID');
                $listCategoryTreeId = $this->category_service->getCategoryTreeId($category);
                $listCategoryTreeId = (count($listCategoryTreeId) > 1) ? implode(",", $listCategoryTreeId) : $listCategoryTreeId[0];
                $query->join("Modules\Models\TmpProductCategory", "tmp.product_id = $this->_product_namespace.id", "tmp");
                $where .= " AND category_id IN ($listCategoryTreeId)";
            }

            if ($this->request->getQuery('price') != '') {
                $price = $this->request->getQuery('price');
                $priceParam = explode('-', $price);
                if (count($priceParam) > 1) {
                    $from_price = $priceParam[0];
                    $to_price = $priceParam[1];
                    $where .= " AND cost BETWEEN $from_price AND $to_price";
                } else {
                    $from_price = $priceParam[0];
                    $where .= " AND cost >= $from_price";
                }
            }

            //search element action
            $productElements = ProductElement::find([
                'columns' => 'id, name',
                'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND language_id = '. $this->languageId .' AND search = "Y"',
                'order' => 'sort ASC, id DESC'
            ]);

            
            if (count($productElements) > 0) {
                $productElementIds = [];
                $productElementDetailIds = [];
                foreach ($productElements as $key => $productElement) {
                    if ($this->request->getQuery('element' . $key) != 0) {
                        $elementValue = $this->request->getQuery('element' . $key);
                        $productElementDetailIds[] =  $this->request->getQuery('element' . $key);
                    }
                }
                if (!empty($productElementDetailIds)) {
                    $listPrElmId = (count($productElementDetailIds) > 1) ? implode(",", $productElementDetailIds) : $productElementDetailIds[0];

                    $tmpPrElmDetails = TmpProductProductElementDetail::find([
                        'conditions' => "product_element_detail_id IN ($listPrElmId)"
                    ]);

                    if (count($tmpPrElmDetails) > 0) {
                        $tmpPrElmDetails = $tmpPrElmDetails->toArray();
                        $listTmpPrIds = [];
                        foreach ($tmpPrElmDetails as $tmpPrElmDetail) {
                            $listTmpPrIds[$tmpPrElmDetail['product_id']][] = $tmpPrElmDetail;
                        }

                        $listPrId = [];
                        foreach ($listTmpPrIds as $key => $listTmpPrId) {
                            if (count($listTmpPrId) == count($productElementDetailIds)) {
                                $listPrId[] = $key;
                            }
                        }

                        if (!empty($listPrId)) {
                            $listPrId = (count($listPrId) > 1) ? implode(",", $listPrId) : $listPrId[0];
                            $where .= " AND $this->_product_namespace.id IN ($listPrId)";
                        } else {
                            // set conditions null result
                            $where .= " AND $this->_product_namespace.id IN (0)";
                        }
                    }
                }
            }

            $query->where($where);
            $query->orderBy("$this->_product_namespace.sort ASC, $this->_product_namespace.id DESC")
            ->groupBy("$this->_product_namespace.id");

            $products = $query;

            $page = 1;
            $paginator = $this->pagination_service->queryBuilder($products, 200 ,$page);

            // news result
            if (!empty($q)) {
                $news = News::find([
                    "columns" => "id, name, slug, photo, folder, summary, created_at",
                    "conditions" => "subdomain_id = $this->_subdomain_id AND deleted = 'N' AND name LIKE '%". $q ."%'",
                    "order" => "sort ASC, id DESC"
                ]);
            } else {
                $news = [];
            }

            $title = $this->_word['_tim_kiem'];
            $title_bar = $this->_word['_ket_qua_tim_kiem'];
            $this->view->title = $title;
            $this->view->title_bar = $title_bar;
            $this->view->news = $news;
            $this->view->breadcrumb = $breadcrumb;
            $this->view->products = $products;
            $this->view->title_bar = $title_bar;
            $this->view->page = $paginator;
        }
    }

    /**
     * Load product photo with product element detail
     *
     * @return View
     */
    public function loadProductPhotoAction()
    {
        if ($this->request->isAjax()) {
            $productId = $this->request->getPost('product_id');
            $productElementDetailId = $this->request->getPost('product_element_detail_id');
            $productPhotos = ProductPhoto::find([
                "columns" => "id, folder, photo, thumb",
                "conditions" => "subdomain_id = $this->_subdomain_id AND product_id = $productId AND product_element_detail_id = $productElementDetailId AND active = 'Y' AND deleted = 'N'",
                "order" => "id DESC"
            ]);

            if (count($productPhotos) > 0) {
                $this->view->setRenderLevel(
                    View::LEVEL_ACTION_VIEW
                );
                $this->view->productPhotos = $productPhotos;
                $this->view->pick($this->_getControllerName() . '/loadProductPhoto');
            } else {
                $this->view->disable();
                return $this->response->setJsonContent([
                    'code' => '404',
                    'message' => 'empty'
                ]);
            }
        }
    }

    public function getProductElementInfoAction()
    {
        $this->view->disable();
        if ($this->request->isAjax()) {
            $result = [];
            $productId = $this->request->getPost('productId');
            $colorId = $this->request->getPost('colorId');
            $capacityId = $this->request->getPost('capacityId');

            $comboId = $colorId . ',' . $capacityId;
            $tmp = TmpProductProductElementDetail::findFirst([
                "conditions" => "subdomain_id = $this->_subdomain_id AND combo_id = '$comboId' AND product_id = $productId"
            ]);

            if ($tmp) {
                $data = $tmp->toArray();
                if ($data['cost'] > 0) $data['cost'] = number_format($data['cost'], 0, ',', '.');
                if ($data['price'] > 0) $data['price'] = number_format($data['price'], 0, ',', '.');
                if ($data['cost_usd'] > 0) $data['cost_usd'] = number_format($data['cost_usd'], 0, ',', '.');
                if ($data['price_usd'] > 0) $data['price_usd'] = number_format($data['price_usd'], 0, ',', '.');
                $productElmSelectedName = '';
                $peSelected = explode(',', $tmp->combo_id);
                foreach ($peSelected as $keyPeSelect => $peSelect) {
                    $productElementDetailSelected = ProductElementDetail::findFirstById($peSelect);
                    if ($productElementDetailSelected->product_element->is_product_photo == 'Y') {
                        $productPhotoElementId = $peSelect;
                    }

                    $productElmSelectedName .= $productElementDetailSelected->name;
                    if ($keyPeSelect < count($peSelected) - 1) $productElmSelectedName .= ' - ';
                }

                $data['productElementComboName'] = $productElmSelectedName;
                $usdCurrency = null;
                if (isset($this->_config['_cf_text_price_usd_currency'])) {
                    $usdCurrency = $this->_config['_cf_text_price_usd_currency'];
                }

                $data['usdCurrency'] = $usdCurrency;

                $result = [
                    'code' => '200',
                    'data' => $data
                ];
            } else {
                $result = [
                    'code' => '404',
                    'message' => 'empty'
                ];
            }

            return $this->response->setJsonContent($result);
        }
    }

    /**
     * Ajax load product
     * 
     * @return View
     */
    public function allProductAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->view->setRenderLevel(
                View::LEVEL_ACTION_VIEW
            );
            $page = $this->request->getPost('page');

            $products = $this->modelsManager->createBuilder()

                ->columns("$this->_product.id, $this->_product.name, $this->_product.slug, $this->_product.photo, $this->_product.folder, s.name as subdomain_name, s.folder as subdomain_folder")
                ->where("$this->_product.language_id = 1")
                ->from($this->_product)
                ->join("Modules\Models\Subdomain", "s.id = $this->_product.subdomain_id", "s")
                ->orderBy("$this->_product.id DESC");

            $paginator = $this->pagination_service->queryBuilder($products, 10 , $page);

            $this->view->page = $paginator;
            $this->view->url_page = 'all-news';
            $this->view->pick($this->_getControllerName() . '/allProduct');
        }
    }
}
