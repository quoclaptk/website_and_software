<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Category;
use Modules\Models\Product;
use Modules\Models\ProductContent;
use Modules\Models\ProductDetail;
use Modules\Models\ProductElement;
use Modules\Models\ProductElementDetail;
use Modules\Models\ProductPhoto;
use Modules\Models\TmpProductCategory;
use Modules\Models\TmpProductProductElementDetail;
use Modules\Models\TmpProductFormItem;
use Modules\Forms\ProductElementDetailForm;
use Modules\Forms\ProductForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Security\Random;

class ProductController extends BaseController
{
    
    /**
     * onstruct ProductController
     */
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Sản phẩm';
    }

    /**
     * Display product data
     * @return View|Phalcon\Http\Response
     */
    public function indexAction()
    {
        $url_page = ACP_NAME . '/' . $this->_getControllerName();
        $query = $this->productRepository->query();
        $conditions = "Modules\Models\Product.subdomain_id = ". $this->_get_subdomainID() ." AND Modules\Models\Product.language_id = 1 AND deleted = 'N'";
        $orderBy = "sort ASC, Modules\Models\Product.id DESC";
        if ($this->request->hasQuery('category') || $this->request->hasQuery('keyword')) {
            if ($this->request->get('category') != '' && $this->request->get('category') != 0) {
                $query->groupBy('Modules\Models\Product.id');
            }

            if ($this->request->get('category') != 0 && $this->request->get('category') != '' && $this->request->get('keyword') == '') {
                $categoryId = $this->request->get('category');
                $listCategoryId = $this->category_service->getCategoryTreeId($categoryId, array(), array('notActive' => true));
                $listCategoryId = count($listCategoryId) > 1 ? implode(',', $listCategoryId) : $listCategoryId[0];
                $query->join('Modules\Models\TmpProductCategory', 'tmp.product_id = Modules\Models\Product.id', 'tmp');
                $conditions .= " AND category_id IN ($listCategoryId)";
                $url_page = ACP_NAME . '/' . $this->_getControllerName() . '&category=' . $categoryId;
            }
            
            if ($this->request->get('category') == 0 && $this->request->get('keyword') != '') {
                $keyword = $this->request->get('keyword');
                $conditions .= " AND name LIKE '%". $keyword ."%'";
                $url_page = ACP_NAME . '/' . $this->_getControllerName() . '&keyword=' . $keyword;
            }

            if ($this->request->getQuery('category') != 0 && $this->request->getQuery('category') != '' && $this->request->get('keyword') != '') {
                $keyword = $this->request->get('keyword');
                $categoryId = $this->request->get('category');
                $listCategoryId = $this->category_service->getCategoryTreeId($categoryId, array(), array('notActive' => true));
                $listCategoryId = count($listCategoryId) > 1 ? implode(',', $listCategoryId) : $listCategoryId[0];
                $query->join('Modules\Models\TmpProductCategory', 'tmp.product_id = Modules\Models\Product.id', 'tmp');
                $conditions .= " AND category_id IN ($listCategoryId)";
                $conditions .= " AND name LIKE '%". $keyword ."%'";
                $url_page = ACP_NAME . '/' . $this->_getControllerName() . '&category='. $categoryId .'&keyword=' . $keyword;
            }
        }

        $products = $query->where($conditions)->orderBy($orderBy)->execute();

        // get product hide
        $conditions .= ' AND Modules\Models\Product.active = "N"';
        $productHides = $query->where($conditions)->execute();
        $itemInfo = [
            'total' => count($products),
            'hide' => count($productHides)
        ];

        $category = $this->recursive(0);

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
                "data" => $products,
                "limit" => 20,
                "page" => $numberPage
            )
        );

        $items = $paginator->getPaginate()->items;

        $page_current = ($numberPage > 1) ? $numberPage : 1;
        if ($this->request->isPost()) {
            foreach ($paginator->getPaginate()->items as $product) {
                //save active
                $activeValue = $this->request->getPost('active_' . $product->id);
                if (!empty($activeValue)) {
                    $product->active = 'Y';
                } else {
                    $product->active = 'N';
                }

                //save hot
                $hotValue = $this->request->getPost('hot_' . $product->id);
                if (!empty($hotValue)) {
                    $product->hot = 'Y';
                } else {
                    $product->hot = 'N';
                }

                //save new
                $newValue = $this->request->getPost('new_' . $product->id);
                if (!empty($newValue)) {
                    $product->new = 'Y';
                } else {
                    $product->new = 'N';
                }

                //save selling
                $sellingValue = $this->request->getPost('selling_' . $product->id);
                if (!empty($sellingValue)) {
                    $product->selling = 'Y';
                } else {
                    $product->selling = 'N';
                }

                //save promotion
                $promotionValue = $this->request->getPost('promotion_' . $product->id);
                if (!empty($promotionValue)) {
                    $product->promotion = 'Y';
                } else {
                    $product->promotion = 'N';
                }

                //save out_stock
                $outStockValue = $this->request->getPost('out_stock_' . $product->id);
                if (!empty($outStockValue)) {
                    $product->out_stock = 'Y';
                } else {
                    $product->out_stock = 'N';
                }

                //save sort
                $sortValue = $this->request->getPost('sort_' . $product->id);
                if (!empty($sortValue)) {
                    $product->sort = $sortValue;
                } else {
                    $product->sort = 1;
                }

                $product->save();

                // upadate elastic
                // $this->elastic_service->updateProduct($product->id);

                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language->id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $productLang = $this->productRepository->findFirstByParams([
                                'conditions' => 'depend_id = '. $product->id .' AND language_id = '. $langId .''
                            ]);

                            if ($productLang) {
                                //save active
                                $activeValue = $this->request->getPost('active_' . $product->id);
                                if (!empty($activeValue)) {
                                    $productLang->active = 'Y';
                                } else {
                                    $productLang->active = 'N';
                                }

                                //save hot
                                $hotValue = $this->request->getPost('hot_' . $product->id);
                                if (!empty($hotValue)) {
                                    $productLang->hot = 'Y';
                                } else {
                                    $productLang->hot = 'N';
                                }

                                //save new
                                $newValue = $this->request->getPost('new_' . $product->id);
                                if (!empty($newValue)) {
                                    $productLang->new = 'Y';
                                } else {
                                    $productLang->new = 'N';
                                }

                                //save selling
                                $sellingValue = $this->request->getPost('selling_' . $product->id);
                                if (!empty($sellingValue)) {
                                    $productLang->selling = 'Y';
                                } else {
                                    $productLang->selling = 'N';
                                }

                                //save promotion
                                $promotionValue = $this->request->getPost('promotion_' . $product->id);
                                if (!empty($promotionValue)) {
                                    $productLang->promotion = 'Y';
                                } else {
                                    $productLang->promotion = 'N';
                                }

                                //save out_stock
                                $outStockValue = $this->request->getPost('out_stock_' . $product->id);
                                if (!empty($outStockValue)) {
                                    $productLang->out_stock = 'Y';
                                } else {
                                    $productLang->out_stock = 'N';
                                }

                                //save sort
                                $sortValue = $this->request->getPost('sort_' . $product->id);
                                if (!empty($sortValue)) {
                                    $productLang->sort = $sortValue;
                                } else {
                                    $productLang->sort = 1;
                                }

                                $productLang->save();

                                // update elastic
                                // $this->elastic_service->updateProduct($productLang->id);
                            }
                        }
                    }
                }
            }

            $this->flashSession->success($this->_message["edit"]);
            $url = ($numberPage > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $numberPage :  ACP_NAME . '/' . $this->_getControllerName();
            $this->response->redirect($url);
        }

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        
        $this->assets->addJs('backend/dist/js/filter.js');
        $this->view->categoryId = $this->request->get('category');
        $this->view->keyword = $this->request->get('keyword');
        $this->view->category = $category;
        $this->view->itemInfo = $itemInfo;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
        $this->view->url_page = $url_page;
    }

    /**
     * Create new product
     * 
     * @return View|Phalcon\Http\Response
     */
    public function createAction()
    {
        $form = new ProductForm();
        $category = $this->recursive(0);
        $product_detail = ProductDetail::find(
            [
                "columns" => "id, name",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND deleted = 'N' AND active = 'Y'",
                "order" => "sort ASC, id ASC"
            ]
        );

        if (count($product_detail) > 0) {
            $product_detail = $product_detail->toArray();
        } else {
            $product_detail = [];
        }

        $product_element = ProductElement::find(
            [
                "columns" => "id, name",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND show_price = 'N' AND combo = 'N' AND language_id = 1 AND deleted = 'N' AND active = 'Y'",
                "order" => "sort ASC, id DESC"
            ]
        );

        $product_element_arr = [];
        if (count($product_element) > 0) {
            $product_element_arr = $product_element->toArray();
            for ($i = 0; $i < count($product_element_arr); $i++) {
                $pr_elm_id = $product_element_arr[$i]['id'];
                $product_element_detail = ProductElementDetail::find(
                     [
                        "columns" => "id, name",
                        "conditions" => "product_element_id = ". $pr_elm_id ." AND deleted = 'N' AND active = 'Y'",
                        "order" => "sort ASC, id DESC"
                    ]
                 );

                if ($product_element_detail) {
                    $product_element_arr[$i]['product_element_detail'] = $product_element_detail->toArray();
                }
            }
        }

        // get product element price
        $productElementPrices = ProductElement::find(
            [
                "columns" => "id, name",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND show_price = 'Y' AND combo = 'N' AND language_id = 1 AND deleted = 'N' AND active = 'Y'",
                "order" => "sort ASC, id DESC"
            ]
        );

        $productElementPriceArr = [];
        if ($productElementPrices->count() > 0) {
            $productElementPriceArr = $productElementPrices->toArray();
            for ($i = 0; $i < count($productElementPriceArr); $i++) {
                $pr_elm_id = $productElementPriceArr[$i]['id'];
                $productElementDetails = ProductElementDetail::find(
                    [
                        "columns" => "id, name",
                        "conditions" => "product_element_id = ". $pr_elm_id ." AND deleted = 'N' AND active = 'Y'",
                        "order" => "sort ASC, id DESC",
                        "limit" => 492
                    ]
                );

                if ($productElementDetails) {
                    $productElementPriceArr[$i]['product_element_detail'] = $productElementDetails->toArray();
                }
            }
        }

        // get product element combo
        $productElementCombos = ProductElement::find(
            [
                "columns" => "id, name",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND combo = 'Y' AND language_id = 1 AND deleted = 'N' AND active = 'Y'",
                "limit" => 2,
                "order" => "sort ASC, id DESC"
            ]
        );

        $productElmCombins = [];
        if (count($productElementCombos) == 2) {
            $prElmCombos = [];
            $prElmCombo1s = [];
            $productElementDetails = ProductElementDetail::find(
                [
                    "columns" => "id, name",
                    "conditions" => "product_element_id = ". $productElementCombos[0]->id ." AND deleted = 'N' AND active = 'Y'",
                    "order" => "sort ASC, id DESC"
                ]
            );
            if (count($productElementDetails) > 0) {
                foreach ($productElementDetails as $productElementDetail) {
                    $prElmCombos[] = $productElementDetail->toArray();
                }
            }

            if (!empty($prElmCombos) && isset($productElementCombos[1])) {
                $productElementDetails = ProductElementDetail::find(
                    [
                        "columns" => "id, name",
                        "conditions" => "product_element_id = ". $productElementCombos[1]->id ." AND deleted = 'N' AND active = 'Y'",
                        "order" => "sort ASC, id DESC"
                    ]
                );

                if (count($productElementDetails) > 0) {
                    foreach ($productElementDetails as $productElementDetail) {
                        $prElmCombo1s[] = $productElementDetail->toArray();
                    }
                }
            }

            if (!empty($prElmCombos) && $prElmCombo1s) {
                foreach ($prElmCombos as $key => $prElmCombo) {
                    foreach ($prElmCombo1s as $key1 => $prElmCombo1) {
                        $itemCombo = [
                            'id' => $prElmCombo['id'] . ',' . $prElmCombo1['id'],
                            'name' => trim($prElmCombo['name']) . ' - ' . trim($prElmCombo1['name'])
                        ];
                        $productElmCombins[] = $itemCombo;
                    }
                }
            }
        }

        $productElementProductPhotos = ProductElement::find(
            [
                "columns" => "id, name",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND is_product_photo = 'Y' AND language_id = 1 AND deleted = 'N' AND active = 'Y'",
                "order" => "sort ASC, id DESC"
            ]
        );

        // get product element detail product photo
        $productElmPrPhotos = [];
        if (count($productElementProductPhotos) > 0) {
            foreach ($productElementProductPhotos as $productElementProductPhoto) {
                $productElementDetails = ProductElementDetail::find(
                    [
                        "columns" => "id, name",
                        "conditions" => "product_element_id = ". $productElementProductPhoto->id ." AND deleted = 'N' AND active = 'Y'",
                        "order" => "sort ASC, id DESC"
                    ]
                );
                if (count($productElementDetails) > 0) {
                    foreach ($productElementDetails as $productElementDetail) {
                        $productElmPrPhotos[] = $productElementDetail->toArray();
                    }
                }
            }
        }

        $random = new Random();
        if ($this->cookies->has('row_id_product_' . $this->_get_subdomainID())) {
            // Get the cookie
            $rowIdCookie = $this->cookies->get('row_id_product_' . $this->_get_subdomainID());

            // Get the cookie's value
            $row_id = $rowIdCookie->getValue();
        } else {
            $row_id = $random->hex(10);
            $this->cookies->set(
                'row_id_product_' . $this->_get_subdomainID(),
                $row_id,
                time() + ROW_ID_COOKIE_TIME
            );
        }

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/product/'. $row_id;
        $dir = DOCUMENT_ROOT . '/public/' . $folderImg;
        $imgUploadPaths = [];
        if (is_dir($dir)) {
            $imgUploads = array_filter(scandir($dir), function ($item) {
                return $item[0] !== '.';
            });

            if (!empty($imgUploads)) {
                foreach ($imgUploads as $img) {
                    if ($img != 'medium') {
                        $imgUploadPaths[] = '/' . $folderImg . '/' . $img;
                    }
                }
            }
        }

        if (count($this->_tmpSubdomainLanguages) > 0) {
            $row_id_lang = [];
            $imgUploadLangPaths = [];
            $productDetailLang = [];
            $productElementArrLang = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langId = $tmp->language_id;
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $random = new Random();
                    //get product detail lang
                    $product_detail_lang = ProductDetail::find(
                        [
                            "columns" => "id, name",
                            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND deleted = 'N' AND active = 'Y'",
                            "order" => "sort ASC, id ASC"
                        ]
                    );

                    if (count($product_detail_lang) > 0) {
                        $productDetailLang[$langCode] = $product_detail_lang->toArray();
                    }

                    // get product element detail
                    $product_element_lang = ProductElement::find(
                        [
                            "columns" => "id, name",
                            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND deleted = 'N' AND active = 'Y'",
                            "order" => "sort ASC, id DESC"
                        ]
                    );
                    if (count($product_element_lang) > 0) {
                        $productElementArrLang = $product_element_lang->toArray();
                        for ($i = 0; $i < count($productElementArrLang); $i++) {
                            $pr_elm_id = $productElementArrLang[$i]['id'];
                            $product_element_detail = ProductElementDetail::find(
                                 [
                                    "columns" => "id, name",
                                    "conditions" => "product_element_id = ". $pr_elm_id ." AND deleted = 'N' AND active = 'Y'",
                                    "order" => "sort ASC, id DESC"
                                ]
                             );

                            if ($product_element_detail) {
                                $productElementArrLang[$i]['product_element_detail'] = $product_element_detail->toArray();
                            }
                        }
                    }

                    if ($this->cookies->has('row_id_product_' . $langCode . '_' . $this->_get_subdomainID())) {
                        // Get the cookie
                        $rowIdCookie = $this->cookies->get('row_id_product_' . $langCode . '_' . $this->_get_subdomainID());

                        // Get the cookie's value
                        $row_id_cookie = $rowIdCookie->getValue();
                    } else {
                        $row_id_cookie = $random->hex(10);
                        $this->cookies->set(
                            'row_id_product_' . $langCode . '_' . $this->_get_subdomainID(),
                            $row_id_cookie,
                            time() + ROW_ID_COOKIE_TIME
                        );
                    }

                    $row_id_lang[$langCode] = $row_id_cookie;

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/product/'. $row_id_lang[$langCode];
                    $dir = DOCUMENT_ROOT . '/public/' . $folderImg;
                    $imgUploadLangPaths[$langCode] = [];
                    if (is_dir($dir)) {
                        $imgUploads = array_filter(scandir($dir), function ($item) {
                            return ($item[0] !== '.');
                        });

                        if (!empty($imgUploads)) {
                            foreach ($imgUploads as $img) {
                                if ($img != 'medium') {
                                    $imgUploadLangPaths[$langCode][] = '/' . $folderImg . '/' . $img;
                                }
                            }
                        }
                    }
                }
            }

            $this->view->row_id_lang = $row_id_lang;
            $this->view->img_upload_lang_paths = $imgUploadLangPaths;
            $this->view->productDetailLang = $productDetailLang;
            $this->view->productElementArrLang = $productElementArrLang;
        }

        if ($this->request->isPost() && $form->isValid($this->request->getPost())== true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $folder = date('d-m-Y', time());
            $general = new General();

            $slug = $this->mainGlobal->validateUrlPageCreate($this->request->getPost('slug')) ? $this->request->getPost('slug') : $this->request->getPost('slug') . '-' . mt_rand(100, 999);

            $data = array(
                'subdomain_id' => $this->_get_subdomainID(),
                'row_id' => $this->request->getPost('row_id'),
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'link' => $this->request->getPost('link'),
                'enable_link' => $this->request->getPost('enable_link'),
                'cart_link' => $this->request->getPost('cart_link'),
                'code' => $this->request->getPost('code'),
                'cost' => !empty($this->request->getPost('cost')) ? (int) $this->request->getPost('cost') : 0,
                'price' => !empty($this->request->getPost('price')) ? (int) $this->request->getPost('price') : 0,
                'cost_usd' => !empty($this->request->getPost('cost_usd')) ? (int) $this->request->getPost('cost_usd') : 0,
                'price_usd' => !empty($this->request->getPost('price_usd')) ? (int) $this->request->getPost('price_usd') : 0,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'summary' => $this->request->getPost('summary'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
                'folder' => $folder,
            );

            $subFolder = $this->_get_subdomainFolder();
            $subfolderUrl = 'files/product/' . $subFolder;

            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    if (!empty($file->getName())) {
                        if ($file->getKey() == 'photo') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, $folder, 'product');
                            if (!empty($dataUpload['file_name'])) {
                                $data['photo'] = $dataUpload['file_name'];
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }

                        if ($file->getKey() == 'photo_secondary') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, $folder, 'product');
                            if (!empty($dataUpload['file_name'])) {
                                $data['photo_secondary'] = $dataUpload['file_name'];
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }
                    }
                }
            }

            $item = $this->productRepository->create($data);
            if ($item) {
                $id = $item->id;
                $category_input = $this->request->getPost('category');

                if (!empty($category_input)) {
                    foreach ($category_input as $t) {
                        $tmp_product_category = new TmpProductCategory();
                        $tmp_product_category->assign(array(
                            'subdomain_id' => $this->_get_subdomainID(),
                            'product_id' => $id,
                            'category_id' => $t
                        ));

                        $tmp_product_category->save();
                    }
                }

                $product_detail = $this->request->getPost('product_detail');
                if (!empty($product_detail)) {
                    foreach ($product_detail as $key => $val) {
                        $product_content = new ProductContent();
                        $product_content->assign([
                            'subdomain_id' => $this->_get_subdomainID(),
                            'product_id' => $id,
                            'product_detail_id' => $key,
                            'content' => str_replace("public/files/", "files/", $val),
                            'sort' => 1,
                            'active' => 'Y'
                        ]);

                        $product_content->save();
                    }
                }

                // save product element
                $product_element_p = $this->request->getPost('product_element');
                if (!empty($product_element_p)) {
                    foreach ($product_element_p as $row) {
                        if (!empty($row)) {
                            $tmp_product_product_element_detail = new TmpProductProductElementDetail();
                            $tmp_product_product_element_detail->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'product_id' => $id,
                                'product_element_detail_id' => $row,
                            ]);

                            $tmp_product_product_element_detail->save();
                        }
                    }
                }

                // save product element with price
                $productElementDetailSelects = $this->request->getPost('product_element_detail_select');
                if (!empty($productElementDetailSelects)) {
                    foreach ($productElementDetailSelects as $productElementDetailSelect) {
                        $tmpProductElementDetail = new TmpProductProductElementDetail();
                        $productElementDetailCostRequest = $this->request->getPost('product_element_detail_cost');
                        $productElementDetailPriceRequest = $this->request->getPost('product_element_detail_price');
                        $productElementDetailCostUsdRequest = $this->request->getPost('product_element_detail_cost_usd');
                        $productElementDetailPriceUsdRequest = $this->request->getPost('product_element_detail_price_usd');
                        $tmpProductElementDetail->assign([
                            'subdomain_id' => $this->_get_subdomainID(),
                            'product_id' => $id,
                            'product_element_detail_id' => $productElementDetailSelect,
                            'cost' => !empty($productElementDetailCostRequest[$productElementDetailSelect]) ? $productElementDetailCostRequest[$productElementDetailSelect] : $item->cost,
                            'price' => !empty($productElementDetailPriceRequest[$productElementDetailSelect]) ? $productElementDetailPriceRequest[$productElementDetailSelect] : $item->price,
                            'cost_usd' => !empty($productElementDetailCostUsdRequest[$productElementDetailSelect]) ? $productElementDetailCostUsdRequest[$productElementDetailSelect] : $item->cost_usd,
                            'price_usd' => !empty($productElementDetailPriceUsdRequest[$productElementDetailSelect]) ? $productElementDetailPriceUsdRequest[$productElementDetailSelect] : $item->price_usd,
                        ]);

                        $tmpProductElementDetail->save();
                    }
                }

                // save product element with combo
                $productElementDetailComboSelects = $this->request->getPost('product_element_detail_combo_select');
                if (!empty($productElementDetailComboSelects)) {
                    foreach ($productElementDetailComboSelects as $productElementDetailComboSelect) {
                        $tmpProductElementDetailCombo = new TmpProductProductElementDetail();
                        $productElementDetailComboCostRequest = $this->request->getPost('product_element_detail_combo_cost');
                        $productElementDetailComboPriceRequest = $this->request->getPost('product_element_detail_combo_price');
                        $productElementDetailComboCostUsdRequest = $this->request->getPost('product_element_detail_combo_cost_usd');
                        $productElementDetailComboPriceUsdRequest = $this->request->getPost('product_element_detail_combo_price_usd');
                        $dataTmpProductDetailCombo = [
                            'subdomain_id' => $this->_get_subdomainID(),
                            'product_id' => $id,
                            'combo_id' => $productElementDetailComboSelect,
                            'cost' => !empty($productElementDetailComboCostRequest[$productElementDetailComboSelect]) ? $productElementDetailComboCostRequest[$productElementDetailComboSelect] : $item->cost,
                            'price' => !empty($productElementDetailComboPriceRequest[$productElementDetailComboSelect]) ? $productElementDetailComboPriceRequest[$productElementDetailComboSelect] : $item->price,
                            'cost_usd' => !empty($productElementDetailComboCostUsdRequest[$productElementDetailComboSelect]) ? $productElementDetailComboCostUsdRequest[$productElementDetailComboSelect] : $item->cost_usd,
                            'price_usd' => !empty($productElementDetailComboPriceUsdRequest[$productElementDetailComboSelect]) ? $productElementDetailComboPriceUsdRequest[$productElementDetailComboSelect] : $item->price_usd,
                            'selected' => ($this->request->getPost('selected_combo') == $productElementDetailComboSelect) ?  1 : 0
                        ];
                       
                        $tmpProductElementDetailCombo->assign($dataTmpProductDetailCombo);
                        $tmpProductElementDetailCombo->save();
                    }
                }

                if ($this->request->hasFiles() == true) {
                    $files = $this->request->getUploadedFiles();
                    foreach ($files as $file) {
                        if (!empty($file->getName())) {
                            $path_parts = pathinfo($file->getKey());
                            $fileKey= $path_parts['filename'];
                            if ($fileKey == 'product_photo') {
                                $dataUpload = $this->upload_service->upload($file, $subfolderUrl, $folder, 'product');
                                if (!empty($dataUpload['file_name'])) {
                                    $fileFullName= $dataUpload['file_name'];
                                    $productPhoto = new ProductPhoto();
                                    $productPhoto->assign(
                                        [
                                            'subdomain_id' => $this->_get_subdomainID(),
                                            'product_id' => $id,
                                            'folder' => $folder,
                                            'photo' => $fileFullName,
                                            'sort' => 1,
                                            'active' => 'Y'
                                        ]
                                    );

                                    $productPhoto->save();
                                } else {
                                    $this->flashSession->error( $dataUpload['message']);
                                    return $this->response->redirect($this->router->getRewriteUri());
                                }
                            }

                            // upload product photo with size color
                            if (!empty($productElmPrPhotos)) {
                                foreach ($productElmPrPhotos as $productElmPrPhoto) {
                                    if ($fileKey == 'product_photo.' . $productElmPrPhoto['id']) {

                                        $dataUpload = $this->upload_service->upload($file, $subfolderUrl, $folder, 'product');
                                        if (!empty($dataUpload['file_name'])) {
                                            $fileFullName= $dataUpload['file_name'];
                                            $productPhoto = new ProductPhoto();
                                            $productPhoto->assign(
                                                [
                                                    'subdomain_id' => $this->_get_subdomainID(),
                                                    'product_id' => $id,
                                                    'product_element_detail_id' => $productElmPrPhoto['id'],
                                                    'folder' => $folder,
                                                    'photo' => $fileFullName,
                                                    'sort' => 1,
                                                    'active' => 'Y'
                                                ]
                                            );

                                            $productPhoto->save();
                                        } else {
                                            $this->flashSession->error( $dataUpload['message']);
                                            return $this->response->redirect($this->router->getRewriteUri());
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                //save other language
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $data = [];
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $slug = $this->mainGlobal->validateUrlPageCreate($this->request->getPost('slug_' . $langCode), $langId) ? $this->request->getPost('slug_' . $langCode) : $this->request->getPost('slug_' . $langCode) . '-' . mt_rand(100, 999);
                            $itemVi = $item->toArray();
                            $itemVi['language_id'] = $langId;
                            $itemVi['depend_id'] = $id;
                            $itemVi['name'] = $this->request->getPost('name_' . $langCode);
                            $itemVi['slug'] = $slug;
                            $itemVi['title'] = $this->request->getPost('title_' . $langCode);
                            $itemVi['keywords'] = $this->request->getPost('keywords_' . $langCode);
                            $itemVi['description'] = $this->request->getPost('description_' . $langCode);
                            $itemVi['summary'] = $this->request->getPost('summary_' . $langCode);
                            unset($itemVi['id']);

                            $productLang = $this->productRepository->create($itemVi);
                            if ($productLang->save()) {
                                //save tmp product category
                                if (!empty($category_input)) {
                                    foreach ($category_input as $t) {
                                        $tmp_product_category = new TmpProductCategory();
                                        $categoryLang = Category::findFirst([
                                            'conditions' => 'depend_id = '. $t .' AND language_id = '. $langId .''
                                        ]);
                                        if ($categoryLang) {
                                            $tmp_product_category->assign(array(
                                                'subdomain_id' => $this->_get_subdomainID(),
                                                'product_id' => $productLang->id,
                                                'category_id' => $categoryLang->id
                                            ));

                                            $tmp_product_category->save();
                                        }
                                    }
                                }

                                //save product detail
                                $product_detail = $this->request->getPost('product_detail_' . $langCode);
                                if (!empty($product_detail)) {
                                    foreach ($product_detail as $key => $val) {
                                        $product_content = new ProductContent();
                                        $product_content->assign([
                                            'subdomain_id' => $this->_get_subdomainID(),
                                            'language_id' => $langId,
                                            'product_id' => $productLang->id,
                                            'product_detail_id' => $key,
                                            'content' => str_replace("public/files/", "files/", $val),
                                            'sort' => 1,
                                            'active' => 'Y'
                                        ]);

                                        if (!$product_content->save()) {
                                            foreach ($product_content->getMessages as $message) {
                                                $this->flashSession->error($message);
                                            }
                                        }
                                    }
                                }

                                //save product photo
                                $productPhotos = ProductPhoto::findByProductId($id);
                                if (count($productPhotos) > 0) {
                                    $productPhotos = $productPhotos->toArray();
                                    foreach ($productPhotos as $productPhoto) {
                                        $productPhoto['language_id'] = $langId;
                                        $productPhoto['depend_id'] = $productPhoto['id'];
                                        unset($productPhoto['id']);
                                        unset($productPhoto['product_id']);
                                        unset($productPhoto['created_at']);
                                        unset($productPhoto['modified_in']);
                                        $productPhoto['product_id'] = $productLang->id;
                                        $productPhotoLang = new ProductPhoto();
                                        $productPhotoLang->assign($productPhoto);
                                        $productPhotoLang->save();
                                    }
                                }

                                //index to elastic
                                // $this->elastic_service->insertProduct($productLang->id);
                            } else {
                                foreach ($productLang->getMessages() as $message) {
                                    $this->flashSession->error($message);
                                }
                            }
                        }
                    }
                }
                
                $this->cookies->get('row_id_product_' . $this->_get_subdomainID())->delete();
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langCode = $tmp->language->code;
                    $this->cookies->get('row_id_product_' . $langCode . '_' . $this->_get_subdomainID())->delete();
                }

                $this->flashSession->success($this->_message["add"]);

                //index to elastic
                // $this->elastic_service->insertProduct($id);

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName();
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->category = $category;
        $this->view->product_detail = $product_detail;
        $this->view->product_element = $product_element_arr;
        $this->view->productElementPrices = $productElementPriceArr;
        $this->view->productElmCombins = $productElmCombins;
        $this->view->productElmPrPhotos = $productElmPrPhotos;
        $this->view->form = $form;
        $this->view->row_id = $row_id;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    /**
     * Saves the product from the 'update' action
     * 
     * @param  integer  $id   
     * @param  integer $page 
     * @return View|Phalcon\Http\Response
     */
    public function updateAction($id, $page = 1)
    {
        $item = $this->productRepository->getItemUpdateDetail($id);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $category = $this->recursive(0);
        $tmp_product_category = TmpProductCategory::find(
            array(
                'conditions' => 'product_id = '. $id .'',
            )
        );
        $tmp_product_category_arr = array();
        if (!empty($tmp_product_category)) {
            foreach ($tmp_product_category as $tmp) {
                $tmp_product_category_arr[] = $tmp->category_id;
            }
        }

        $product_detail = ProductDetail::find([
            "columns" => "id, name",
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND deleted = 'N' AND active = 'Y'",
            "order" => "sort ASC, id ASC"
        ]);

        if (count($product_detail) > 0) {
            $product_detail = $product_detail->toArray();
            foreach ($product_detail as $key => $value) {
                $product_content = ProductContent::findFirst([
                    "conditions" => "product_detail_id = ". $value['id'] ." AND product_id = $id AND deleted = 'N' AND active = 'Y'"
                ]);
                
                if ($product_content) {
                    $product_detail[$key]['content'] = $product_content->content;
                    $product_detail[$key]['product_id'] = $product_content->product_id;
                }
            }
        } else {
            $product_detail = [];
        }

        $product_photo = ProductPhoto::find(
            [
                "columns" => "id, photo, active",
                "conditions" => "product_id = ". $id ." AND language_id = 1 AND (product_element_detail_id IS NULL OR product_element_detail_id = 0) AND deleted = 'N'",
                "order" => "sort ASC, id DESC"
            ]
        );

        $tmp_product_product_element_detail = TmpProductProductElementDetail::find(
            array(
                'conditions' => 'product_id = '. $id .'',
            )
        );
        $tmp_product_product_element_detail_arr = [];
        $tmp_product_product_element_detail_combo_arr = [];
        if (!empty($tmp_product_product_element_detail)) {
            foreach ($tmp_product_product_element_detail as $tmp) {
                if ($tmp->product_element_detail_id != null) {
                    $tmp_product_product_element_detail_arr[$tmp->product_element_detail_id] = $tmp->toArray();
                }

                if ($tmp->combo_id != null) {
                    $tmp_product_product_element_detail_combo_arr[$tmp->combo_id] = $tmp->toArray();
                }
            }
        }

        // get product element
        $product_element = ProductElement::find(
            [
                "columns" => "id, name",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND show_price = 'N' AND combo = 'N' AND language_id = 1 AND deleted = 'N' AND active = 'Y'",
                "order" => "sort ASC, id DESC"
            ]
        );
        $product_element_arr = [];
        if ($product_element->count() > 0) {
            $product_element_arr = $product_element->toArray();
            for ($i = 0; $i < count($product_element_arr); $i++) {
                $pr_elm_id = $product_element_arr[$i]['id'];
                $product_element_detail = ProductElementDetail::find(
                    [
                        "columns" => "id, name",
                        "conditions" => "product_element_id = ". $pr_elm_id ." AND deleted = 'N' AND active = 'Y'",
                        "order" => "sort ASC, id DESC"
                    ]
                );

                if ($product_element_detail) {
                    $product_element_arr[$i]['product_element_detail'] = $product_element_detail->toArray();
                }
            }
        }

        // get product element price
        $productElementPrices = ProductElement::find(
            [
                "columns" => "id, name",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND show_price = 'Y' AND combo = 'N' AND language_id = 1 AND deleted = 'N' AND active = 'Y'",
                "order" => "sort ASC, id DESC"
            ]
        );

        $productElementPriceArr = [];
        if ($productElementPrices->count() > 0) {
            $productElementPriceArr = $productElementPrices->toArray();
            for ($i = 0; $i < count($productElementPriceArr); $i++) {
                $pr_elm_id = $productElementPriceArr[$i]['id'];
                $productElementDetails = ProductElementDetail::find(
                    [
                        "columns" => "id, name",
                        "conditions" => "product_element_id = ". $pr_elm_id ." AND deleted = 'N' AND active = 'Y'",
                        "order" => "sort ASC, id DESC",
                        "limit" => 480
                    ]
                );

                if ($productElementDetails) {
                    $productElementPriceArr[$i]['product_element_detail'] = $productElementDetails->toArray();
                }
            }
        }

        // get product element combo
        $productElementCombos = ProductElement::find(
            [
                "columns" => "id, name",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND combo = 'Y' AND language_id = 1 AND deleted = 'N' AND active = 'Y'",
                "limit" => 2,
                "order" => "sort ASC, id DESC"
            ]
        );

        $productElmCombins = [];
        if (count($productElementCombos) == 2) {
            $prElmCombos = [];
            $prElmCombo1s = [];
            $productElementDetails = ProductElementDetail::find(
                [
                    "columns" => "id, name",
                    "conditions" => "product_element_id = ". $productElementCombos[0]->id ." AND deleted = 'N' AND active = 'Y'",
                    "order" => "sort ASC, id DESC"
                ]
            );
            if (count($productElementDetails) > 0) {
                foreach ($productElementDetails as $productElementDetail) {
                    $prElmCombos[] = $productElementDetail->toArray();
                }
            }

            if (!empty($prElmCombos) && isset($productElementCombos[1])) {
                $productElementDetails = ProductElementDetail::find(
                    [
                        "columns" => "id, name",
                        "conditions" => "product_element_id = ". $productElementCombos[1]->id ." AND deleted = 'N' AND active = 'Y'",
                        "order" => "sort ASC, id DESC"
                    ]
                );

                if (count($productElementDetails) > 0) {
                    foreach ($productElementDetails as $productElementDetail) {
                        $prElmCombo1s[] = $productElementDetail->toArray();
                    }
                }
            }

            if (!empty($prElmCombos) && $prElmCombo1s) {
                foreach ($prElmCombos as $key => $prElmCombo) {
                    foreach ($prElmCombo1s as $key1 => $prElmCombo1) {
                        $itemCombo = [
                            'id' => $prElmCombo['id'] . ',' . $prElmCombo1['id'],
                            'name' => trim($prElmCombo['name']) . ' - ' . trim($prElmCombo1['name'])
                        ];
                        $productElmCombins[] = $itemCombo;
                    }
                }
            }
        }

        $productElementProductPhotos = ProductElement::find(
            [
                "columns" => "id, name",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND is_product_photo = 'Y' AND language_id = 1 AND deleted = 'N' AND active = 'Y'",
                "order" => "sort ASC, id DESC"
            ]
        );

        // get product element detail product photo
        $productElmPrPhotos = [];
        if (count($productElementProductPhotos) > 0) {
            foreach ($productElementProductPhotos as $productElementProductPhoto) {
                $productElementDetails = ProductElementDetail::find(
                    [
                        "columns" => "id, name",
                        "conditions" => "product_element_id = ". $productElementProductPhoto->id ." AND deleted = 'N' AND active = 'Y'",
                        "order" => "sort ASC, id DESC"
                    ]
                );
                if (count($productElementDetails) > 0) {
                    foreach ($productElementDetails as $productElementDetail) {
                        $productElmPrPhotos[] = $productElementDetail->toArray();
                    }
                }
            }
        }

        if (count($productElmPrPhotos) > 0) {
            foreach ($productElmPrPhotos as $key => $productElmPrPhoto) {
                $productPhotos = ProductPhoto::find(
                    [
                        "columns" => "id, photo, active",
                        "conditions" => "product_id = ". $id ." AND language_id = 1 AND product_element_detail_id = ". $productElmPrPhoto['id'] ." AND deleted = 'N'",
                        "order" => "sort ASC, id DESC"
                    ]
                );

                if (count($productPhotos) > 0) {
                    $productElmPrPhotos[$key]['productPhotos'] = $productPhotos->toArray(); 
                }
            }
        }

        $photo = $item->photo;
        $photoSecondary = $item->photo_secondary;
        $folder = (!empty($item->folder)) ? $item->folder : date('Y-m-d');
        $row_id = ($item->row_id != 0) ? $item->row_id : $item->id;
        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/product/'. $row_id;
        $dir = DOCUMENT_ROOT . '/public/' . $folderImg;
        $imgUploadPaths = [];
        if (is_dir($dir)) {
            $imgUploads = array_filter(scandir($dir), function ($item) {
                return $item[0] !== '.';
            });

            if (!empty($imgUploads)) {
                foreach ($imgUploads as $img) {
                    if ($img != 'medium') {
                        $imgUploadPaths[] = '/' . $folderImg . '/' . $img;
                    }
                }
            }
        }

        if (count($this->_tmpSubdomainLanguages) > 0) {
            $itemFormData = $item->toArray();
            $row_id_lang = [];
            $imgUploadLangPaths = [];
            $itemLangData = [];
            $productDetailLang = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langId = $tmp->language_id;
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $itemLang = Product::findFirst(array(
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $tmp->language_id .' AND depend_id = '. $id .''
                    ));

                    //get product detail lang
                    $product_detail_lang = ProductDetail::find(
                        [
                            "columns" => "id, name",
                            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND deleted = 'N' AND active = 'Y'",
                            "order" => "sort ASC, id ASC"
                        ]
                    );

                    if ($itemLang) {
                        $row_id_lang[$langCode] = $itemLang->row_id;
                        $itemLangData[$langCode] = $itemLang;
                        $itemLang = $itemLang->toArray();
                        $itemLangKeys = array_keys($itemLang);
                        foreach ($itemLangKeys as $itemLangKey) {
                            $itemFormData[$itemLangKey . '_' . $langCode] = $itemLang[$itemLangKey];
                        }

                        if (count($product_detail_lang) > 0) {
                            $productDetailLang[$langCode] = $product_detail_lang->toArray();
                            foreach ($product_detail_lang as $key => $value) {
                                $product_content_lang = ProductContent::findFirst([
                                    "conditions" => "product_detail_id = ". $value['id'] ." AND product_id = ". $itemLang['id'] ." AND deleted = 'N' AND active = 'Y'"
                                ]);
                                
                                if ($product_content_lang) {
                                    $productDetailLang[$langCode][$key]['content'] = $product_content_lang->content;
                                    $productDetailLang[$langCode][$key]['product_id'] = $product_content_lang->product_id;
                                }
                            }
                        }
                    } else {
                        if (count($product_detail_lang) > 0) {
                            $productDetailLang[$langCode] = $product_detail_lang->toArray();
                        }
                        $random = new Random();
                        if ($this->cookies->has('row_id_product_' . $langCode . '_' . $this->_get_subdomainID())) {
                            // Get the cookie
                            $rowIdCookie = $this->cookies->get('row_id_product_' . $langCode . '_' . $this->_get_subdomainID());

                            // Get the cookie's value
                            $row_id_cookie = $rowIdCookie->getValue();
                        } else {
                            $row_id_cookie = $random->hex(10);
                            $this->cookies->set(
                                'row_id_product_' . $langCode . '_' . $this->_get_subdomainID(),
                                $row_id_cookie,
                                time() + ROW_ID_COOKIE_TIME
                            );
                        }

                        $row_id_lang[$langCode] = $row_id_cookie;
                    }

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/product/'. $row_id_lang[$langCode];
                    $dir = DOCUMENT_ROOT . '/public/' . $folderImg;
                    $imgUploadLangPaths[$langCode] = [];
                    if (is_dir($dir)) {
                        $imgUploads = array_filter(scandir($dir), function ($item) {
                            return ($item[0] !== '.');
                        });

                        if (!empty($imgUploads)) {
                            foreach ($imgUploads as $img) {
                                if ($img != 'medium') {
                                    $imgUploadLangPaths[$langCode][] = '/' . $folderImg . '/' . $img;
                                }
                            }
                        }
                    }
                }
            }

            $itemFormData = (object) $itemFormData;
            $this->view->row_id_lang = $row_id_lang;
            $this->view->img_upload_lang_paths = $imgUploadLangPaths;
            $this->view->productDetailLang = $productDetailLang;
        } else {
            $itemFormData = $item;
        }

        $form = new ProductForm($itemFormData, array('edit' => true));

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();

            $slug = $this->mainGlobal->validateUrlPageUpdate($id, $this->request->getPost('slug'), 'product') ? $this->request->getPost('slug') : $this->request->getPost('slug') . '-' . mt_rand(100, 999);
            
            $data = array(
                'name' => $this->request->getPost('name'),
                'row_id' => $this->request->getPost('row_id'),
                'slug' => $slug,
                'link' => $this->request->getPost('link'),
                'enable_link' => $this->request->getPost('enable_link'),
                'cart_link' => $this->request->getPost('cart_link'),
                'code' => $this->request->getPost('code'),
                'cost' => !empty($this->request->getPost('cost')) ? (int) $this->request->getPost('cost') : 0,
                'price' => !empty($this->request->getPost('price')) ? (int) $this->request->getPost('price') : 0,
                'cost_usd' => !empty($this->request->getPost('cost_usd')) ? (int) $this->request->getPost('cost_usd') : 0,
                'price_usd' => !empty($this->request->getPost('price_usd')) ? (int) $this->request->getPost('price_usd') : 0,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'summary' => $this->request->getPost('summary'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            );

            $subFolder = $this->_get_subdomainFolder();
            $subfolderUrl = 'files/product/' . $subFolder;
            $productPhotoListId = [];
            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    if (!empty($file->getName())) {
                        if ($file->getKey() == 'photo') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, $folder, 'product');
                            if (!empty($dataUpload['file_name'])) {
                                $data['photo'] = $dataUpload['file_name'];

                                // delete folder thumb
                                $this->general->deleteDirectory($subfolderUrl . "/" . $folder . "/thumb/" . $photo);

                                @unlink($subfolderUrl . "/" . $folder . "/" . $photo);
                                @unlink($subfolderUrl . "/thumb/360x360/" . $folder . '/' . $photo);
                                @unlink($subfolderUrl . "/thumb/260x260/" . $folder . '/' . $photo);
                                @unlink($subfolderUrl . "/thumb/120x120/" . $folder . '/' . $photo);
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }

                        if ($file->getKey() == 'photo_secondary') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, $folder, 'product');
                            if (!empty($dataUpload['file_name'])) {
                                $data['photo_secondary'] = $dataUpload['file_name'];

                                // delete folder thumb
                                $this->general->deleteDirectory($subfolderUrl . "/" . $folder . "/thumb/" . $photoSecondary);
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }
                    }
                }
            }

            $item = $this->productRepository->updateById($id, $data);
            if ($item) {
                $category_input = $this->request->getPost('category');
                if (!empty($category_input)) {
                    TmpProductCategory::deleteByRawSql('product_id ='. $id .'');
                    foreach ($category_input as $t) {
                        $tmp_product_category_p = new TmpProductCategory();
                        $tmp_product_category_p->assign(array(
                            'subdomain_id' => $this->_get_subdomainID(),
                            'product_id' => $id,
                            'category_id' => $t
                        ));

                        $tmp_product_category_p->save();
                    }
                }

                $product_detail_post = $this->request->getPost('product_detail');
                if (!empty($product_detail_post)) {
                    foreach ($product_detail as $row) {
                        $product_content_item = ProductContent::findFirst([
                            'conditions' => 'product_id = '. $id .' AND product_detail_id = '. $row['id'] .''
                        ]);
                        if ($product_content_item) {
                            $product_content_item->delete();
                        }
                    }
                    foreach ($product_detail_post as $key => $val) {
                        $product_content_up = new ProductContent();
                        $product_content_up->assign([
                            'subdomain_id' => $this->_get_subdomainID(),
                            'product_id' => $id,
                            'product_detail_id' => $key,
                            'content' => str_replace("public/files/", "files/", $val),
                            'sort' => 1,
                            'active' => 'Y'
                        ]);

                        $product_content_up->save();
                    }
                }

                // save product element
                $product_element_p = $this->request->getPost('product_element');
                TmpProductProductElementDetail::deleteByRawSql('product_id ='. $id .'');
                if (!empty($product_element_p)) {
                    foreach ($product_element_p as $row) {
                        if (!empty($row)) {
                            $tmp_product_product_element_detail_up = new TmpProductProductElementDetail();
                            $tmp_product_product_element_detail_up->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'product_id' => $id,
                                'product_element_detail_id' => $row,
                            ]);

                            $tmp_product_product_element_detail_up->save();
                        }
                    }
                }

                // save product element with price
                $productElementDetailSelects = $this->request->getPost('product_element_detail_select');
                if (!empty($productElementDetailSelects)) {
                    foreach ($productElementDetailSelects as $productElementDetailSelect) {
                        $tmpProductElementDetail = new TmpProductProductElementDetail();
                        $productElementDetailCostRequest = $this->request->getPost('product_element_detail_cost');
                        $productElementDetailPriceRequest = $this->request->getPost('product_element_detail_price');
                        $productElementDetailCostUsdRequest = $this->request->getPost('product_element_detail_cost_usd');
                        $productElementDetailPriceUsdRequest = $this->request->getPost('product_element_detail_price_usd');
                        $tmpProductElementDetail->assign([
                            'subdomain_id' => $this->_get_subdomainID(),
                            'product_id' => $id,
                            'product_element_detail_id' => $productElementDetailSelect,
                            'cost' => !empty($productElementDetailCostRequest[$productElementDetailSelect]) ? $productElementDetailCostRequest[$productElementDetailSelect] : $item->cost,
                            'price' => !empty($productElementDetailPriceRequest[$productElementDetailSelect]) ? $productElementDetailPriceRequest[$productElementDetailSelect] : $item->price,
                            'cost_usd' => !empty($productElementDetailCostUsdRequest[$productElementDetailSelect]) ? $productElementDetailCostUsdRequest[$productElementDetailSelect] : $item->cost_usd,
                            'price_usd' => !empty($productElementDetailPriceUsdRequest[$productElementDetailSelect]) ? $productElementDetailPriceUsdRequest[$productElementDetailSelect] : $item->price_usd,
                        ]);

                        $tmpProductElementDetail->save();
                    }
                }

                // save product element with combo
                $productElementDetailComboSelects = $this->request->getPost('product_element_detail_combo_select');
                if (!empty($productElementDetailComboSelects)) {
                    foreach ($productElementDetailComboSelects as $productElementDetailComboSelect) {
                        $tmpProductElementDetailCombo = new TmpProductProductElementDetail();
                        $productElementDetailComboCostRequest = $this->request->getPost('product_element_detail_combo_cost');
                        $productElementDetailComboPriceRequest = $this->request->getPost('product_element_detail_combo_price');
                        $productElementDetailComboCostUsdRequest = $this->request->getPost('product_element_detail_combo_cost_usd');
                        $productElementDetailComboPriceUsdRequest = $this->request->getPost('product_element_detail_combo_price_usd');
                        $dataTmpProductDetailCombo = [
                            'subdomain_id' => $this->_get_subdomainID(),
                            'product_id' => $id,
                            'combo_id' => $productElementDetailComboSelect,
                            'cost' => !empty($productElementDetailComboCostRequest[$productElementDetailComboSelect]) ? $productElementDetailComboCostRequest[$productElementDetailComboSelect] : $item->cost,
                            'price' => !empty($productElementDetailComboPriceRequest[$productElementDetailComboSelect]) ? $productElementDetailComboPriceRequest[$productElementDetailComboSelect] : $item->price,
                            'cost_usd' => !empty($productElementDetailComboCostUsdRequest[$productElementDetailComboSelect]) ? $productElementDetailComboCostUsdRequest[$productElementDetailComboSelect] : $item->cost_usd,
                            'price_usd' => !empty($productElementDetailComboPriceUsdRequest[$productElementDetailComboSelect]) ? $productElementDetailComboPriceUsdRequest[$productElementDetailComboSelect] : $item->price_usd,
                            'selected' => ($this->request->getPost('selected_combo') == $productElementDetailComboSelect) ?  1 : 0
                        ];
                        
                        $tmpProductElementDetailCombo->assign($dataTmpProductDetailCombo);
                        $tmpProductElementDetailCombo->save();
                    }
                }

                if ($this->request->hasFiles() == true) {
                    $files = $this->request->getUploadedFiles();
                    foreach ($files as $file) {
                        if (!empty($file->getName())) {
                            $path_parts = pathinfo($file->getKey());
                            $fileKey= $path_parts['filename'];
                            if ($fileKey == 'product_photo') {
                                $dataUpload = $this->upload_service->upload($file, $subfolderUrl, $folder, 'product');

                                if (!empty($dataUpload['file_name'])) {
                                    $fileFullName= $dataUpload['file_name'];
                                    $productPhoto = new ProductPhoto();
                                    $productPhoto->assign(
                                        [
                                            'subdomain_id' => $this->_get_subdomainID(),
                                            'product_id' => $id,
                                            'folder' => $folder,
                                            'photo' => $fileFullName,
                                            'sort' => 1,
                                            'active' => 'Y'
                                        ]
                                    );

                                    $productPhoto->save();
                                } else {
                                    $this->flashSession->error( $dataUpload['message']);
                                    return $this->response->redirect($this->router->getRewriteUri());
                                }
                            }

                            // upload product photo with size color
                            if (!empty($productElmPrPhotos)) {
                                foreach ($productElmPrPhotos as $productElmPrPhoto) {
                                    if ($fileKey == 'product_photo.' . $productElmPrPhoto['id']) {

                                        $dataUpload = $this->upload_service->upload($file, $subfolderUrl, $folder, 'product');
                                        if (!empty($dataUpload['file_name'])) {
                                            $fileFullName= $dataUpload['file_name'];
                                            $productPhoto = new ProductPhoto();
                                            $productPhoto->assign(
                                                [
                                                    'subdomain_id' => $this->_get_subdomainID(),
                                                    'product_id' => $id,
                                                    'product_element_detail_id' => $productElmPrPhoto['id'],
                                                    'folder' => $folder,
                                                    'photo' => $fileFullName,
                                                    'sort' => 1,
                                                    'active' => 'Y'
                                                ]
                                            );

                                            $productPhoto->save();
                                        } else {
                                            $this->flashSession->error( $dataUpload['message']);
                                            return $this->response->redirect($this->router->getRewriteUri());
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                //save other language
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $data = [];
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $slug = $this->mainGlobal->validateUrlPageUpdate($id, $this->request->getPost('slug'), 'product', $langId) ? $this->request->getPost('slug_' . $langCode) : $this->request->getPost('slug_' . $langCode) . '-' . mt_rand(100, 999);

                            $itemVi = $item->toArray();
                            $itemVi['language_id'] = $langId;
                            $itemVi['depend_id'] = $id;
                            $itemVi['name'] = $this->request->getPost('name_' . $langCode);
                            $itemVi['slug'] = $slug;
                            $itemVi['title'] = $this->request->getPost('title_' . $langCode);
                            $itemVi['keywords'] = $this->request->getPost('keywords_' . $langCode);
                            $itemVi['description'] = $this->request->getPost('description_' . $langCode);
                            $itemVi['summary'] = $this->request->getPost('summary_' . $langCode);
                            unset($itemVi['id']);
                            $productCurrentLang = $this->productRepository->findFirstByParams(array(
                                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .' AND depend_id = '. $id .''
                            ));
                            $productLang = false;
                            if (!$productCurrentLang) {
                                $productLang = $this->productRepository->create($itemVi);
                            } else {
                                $productLang = $this->productRepository->updateById($productCurrentLang->id, $itemVi);
                            }

                            if ($productLang) {
                                //save tmp product category
                                if (!empty($category_input)) {
                                    foreach ($category_input as $t) {
                                        $tmp_product_category = new TmpProductCategory();
                                        TmpProductCategory::deleteByRawSql('product_id ='. $productLang->id .'');
                                        $categoryLang = Category::findFirst([
                                            'conditions' => 'depend_id = '. $t .' AND language_id = '. $langId .''
                                        ]);
                                        if ($categoryLang) {
                                            $tmp_product_category->assign(array(
                                                'subdomain_id' => $this->_get_subdomainID(),
                                                'product_id' => $productLang->id,
                                                'category_id' => $categoryLang->id
                                            ));

                                            $tmp_product_category->save();
                                        }
                                    }
                                }

                                //save product detail
                                $product_detail_post = $this->request->getPost('product_detail_' . $langCode);
                                
                                if (!empty($product_detail_post)) {
                                    foreach ($productDetailLang[$langCode] as $row) {
                                        $product_content_item = ProductContent::findFirst([
                                            'conditions' => 'product_id = '. $productLang->id .' AND product_detail_id = '. $row['id'] .''
                                        ]);
                                        if ($product_content_item) {
                                            $product_content_item->delete();
                                        }
                                    }
                                    foreach ($product_detail_post as $key => $val) {
                                        $product_content_up = new ProductContent();
                                        $product_content_up->assign([
                                            'subdomain_id' => $this->_get_subdomainID(),
                                            'language_id' => $langId,
                                            'product_id' => $productLang->id,
                                            'product_detail_id' => $key,
                                            'content' => str_replace("public/files/", "files/", $val),
                                            'sort' => 1,
                                            'active' => 'Y'
                                        ]);

                                        if (!$product_content_up->save()) {
                                            foreach ($product_content_up->getMessages as $message) {
                                                $this->flashSession->error($message);
                                            }
                                        }
                                    }
                                }

                                //save product photo
                                if (!empty($productPhotoListId)) {
                                    $productPhotoListIdStr = count($productPhotoListId) > 1 ? implode(',', $productPhotoListId) : $productPhotoListId[0];
                                    $productPhotos = ProductPhoto::find(array(
                                        'conditions' => 'id IN ('. $productPhotoListIdStr .') AND subdomain_id = '. $this->_get_subdomainID() .'',
                                    ));

                                    if (count($productPhotos) > 0) {
                                        $productPhotos = $productPhotos->toArray();
                                        foreach ($productPhotos as $productPhoto) {
                                            $productPhoto['language_id'] = $langId;
                                            $productPhoto['depend_id'] = $productPhoto['id'];
                                            $productPhoto['product_id'] = $productLang->id;
                                            unset($productPhoto['id']);
                                            unset($productPhoto['created_at']);
                                            unset($productPhoto['modified_in']);
                                            $productPhotoLang = new ProductPhoto();
                                            $productPhotoLang->assign($productPhoto);
                                            $productPhotoLang->save();
                                        }
                                    }
                                }

                                // upadate elastic
                                // $this->elastic_service->updateProduct($productLang->id);
                            } else {
                                foreach ($productLang->getMessages() as $message) {
                                    $this->flashSession->error($message);
                                }
                            }
                        }
                    }
                }
                
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langCode = $tmp->language->code;
                    $this->cookies->get('row_id_product_' . $langCode . '_' . $this->_get_subdomainID())->delete();
                }

                $this->flashSession->success($this->_message["edit"]);

                //update index id to elastic
                // $this->elastic_service->updateProduct($id);

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->category = $category;
        $this->view->item = $item;
        $this->view->product_detail = $product_detail;
        $this->view->product_element = $product_element_arr;
        $this->view->productElementPrices = $productElementPriceArr;
        $this->view->productElmCombins = $productElmCombins;
        $this->view->productElmPrPhotos = $productElmPrPhotos;
        $this->view->product_photo = $product_photo;
        $this->view->tmp_product_category_arr = $tmp_product_category_arr;
        $this->view->tmp_product_product_element_detail_arr = $tmp_product_product_element_detail_arr;
        $this->view->tmp_product_product_element_detail_combo_arr = $tmp_product_product_element_detail_combo_arr;
        $this->view->page = $page;
        $this->view->form = $form;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->row_id = $row_id;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    /**
     * Delete set deleted on field table note db in db
     * 
     * @param  int     $id   
     * @param  integer $page 
     * @return Phalcon\Http\Response
     */
    public function deleteAction(int $id, $page = 1)
    {
        $item = $this->productRepository->getItemUpdateDetail($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        $item->assign([
            'deleted' => 'Y'
        ]);

        if ($item->save()) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }


        $this->response->redirect($url);
    }

    /**
     * Delete multipe set deleted on field table note db in db
     * 
     * @param  int     $id   
     * @return Phalcon\Http\Response
     */
    public function deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        foreach ($listid as $id) {
            $item = $this->productRepository->getItemUpdateDetail($id);

            if ($item) {
                $item->assign([
                    'active' => 'N'
                ]);
                $item->save();
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

    /**
     * Delete item on db
     * 
     * @param  int     $id   
     * @param  integer $page 
     * @return Phalcon\Http\Response
     */
    public function _deleteAction(int $id, $page = 1)
    {
        $item = $this->productRepository->getItemUpdateDetail($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $subFolder = $this->_get_subdomainFolder();
        $photo = $item->photo;
        $photoSecondary = $item->photo_secondary;
        $folder = $item->folder;
        $general = new General();

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            // delete elastic search id id
            // $this->elastic_service->deleteProduct($id);

            TmpProductCategory::deleteByRawSql('product_id ='. $id .'');
            TmpProductProductElementDetail::deleteByRawSql('product_id ='. $id .'');
            TmpProductFormItem::deleteByRawSql('product_id ='. $id .'');
            
            // delete folder thumb
            $this->general->deleteDirectory("files/product/" . $subFolder . "/" . $folder . "/thumb/" . $photo);
            $this->general->deleteDirectory("files/product/" . $subFolder . "/" . $folder . "/thumb/" . $photoSecondary);

            @unlink("files/product/" . $subFolder . "/" . $folder . "/" . $photo);
            @unlink("files/product/" . $subFolder . "/thumb/360x360/" . $folder . '/' . $photo);
            @unlink("files/product/" . $subFolder . "/thumb/260x260/" . $folder . '/' . $photo);
            @unlink("files/product/" . $subFolder . "/thumb/120x120/" . $folder . '/' . $photo);
            @unlink("files/product/" . $subFolder . "/" . $folder . "/" . $photoSecondary);

            $productContents = ProductContent::findByProductId($id);
            if (count($productContents) > 0) {
                foreach ($productContents as $productContent) {
                    $productContent->delete();
                }
            }

            $product_photos = ProductPhoto::findByProductId($id);
            if (count($product_photos) > 0) {
                foreach ($product_photos as $product_photo) {
                    if ($product_photo->delete()) {
                        @unlink("files/product/" . $subFolder . "/" . $folder . "/" . $product_photo->photo);
                        @unlink("files/product/" . $subFolder . "/thumb/360x360/" . $folder . '/' . $product_photo->photo);
                        @unlink("files/product/" . $subFolder . "/thumb/260x260/" . $folder . '/' . $product_photo->photo);
                        @unlink("files/product/" . $subFolder . "/thumb/120x120/" . $folder . '/' . $product_photo->photo);
                    } else {
                        $messages = $product_photo->getMessages();

                        foreach ($messages as $message) {
                            echo $message, "\n";
                        }
                    }
                }
            }

            $tmpProductElementDetailCombos = TmpProductProductElementDetail::findByProductId($id);
            if (count($tmpProductElementDetailCombos) > 0) {
                foreach ($tmpProductElementDetailCombos as $tmpProductElementDetailCombo) {
                    if ($tmpProductElementDetailCombo->delete()) {
                        if ($tmpProductElementDetailCombo->combo_id != ''){
                            @unlink("files/product/" . $subFolder . "/" . $folder . "/" . $tmpProductElementDetailCombo->photo);
                        }
                    } else {
                        $messages = $tmpProductElementDetailCombo->getMessages();

                        foreach ($messages as $message) {
                            echo $message, "\n";
                        }
                    }
                }
            }

            if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/product/" . $item->row_id)) {
                $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/product/" . $item->row_id);
            }

            $dependProducts = $this->productRepository->findByPropertyName('DependId', $id);
            if (count($dependProducts) > 0) {
                foreach ($dependProducts as $dependProduct) {
                    if ($dependProduct->delete()) {
                        // delete elastic search id id
                        // $this->elastic_service->deleteProduct($dependProduct->id);

                        TmpProductCategory::deleteByRawSql('product_id ='. $dependProduct->id .'');
                        TmpProductProductElementDetail::deleteByRawSql('product_id ='. $dependProduct->id .'');
                        TmpProductFormItem::deleteByRawSql('product_id ='. $dependProduct->id .'');

                        $productContents = ProductContent::findByProductId($dependProduct->id);
                        if (count($productContents) > 0) {
                            foreach ($productContents as $productContent) {
                                $productContent->delete();
                            }
                        }

                        $product_photos = ProductPhoto::findByProductId($dependProduct->id);

                        if (count($product_photos) > 0) {
                            foreach ($product_photos as $product_photo) {
                                if ($product_photo->delete()) {
                                } else {
                                    $messages = $product_photo->getMessages();

                                    foreach ($messages as $message) {
                                        echo $message, "\n";
                                    }
                                }
                            }
                        }

                        if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/product/" . $dependProduct->row_id)) {
                            $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/product/" . $dependProduct->row_id);
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["delete"]);
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    /**
     * Delete multipe set deleted on field table note db in db
     * 
     * @param  int     $id   
     * @return Phalcon\Http\Response
     */
    public function _deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        $general = new General();
        foreach ($listid as $id) {
            $item = $this->productRepository->getItemUpdateDetail($id);
            if ($item) {
                $subFolder = $this->_get_subdomainFolder();
                $photo = $item->photo;
                $photoSecondary = $item->photo_secondary;
                $folder = $item->folder;

                if ($item->delete()) {
                    // delete elastic search id id
                    // $this->elastic_service->deleteProduct($id);

                    TmpProductCategory::deleteByRawSql('product_id ='. $id .'');
                    TmpProductProductElementDetail::deleteByRawSql('product_id ='. $id .'');
                    TmpProductFormItem::deleteByRawSql('product_id ='. $id .'');
                    // delete folder thumb
                    $this->general->deleteDirectory("files/product/" . $subFolder . "/" . $folder . "/thumb/" . $photo);
                    $this->general->deleteDirectory("files/product/" . $subFolder . "/" . $folder . "/thumb/" . $photoSecondary);

                    @unlink("files/product/" . $subFolder . "/" . $folder . "/" . $photo);
                    @unlink("files/product/" . $subFolder . "/thumb/360x360/" . $folder . '/' . $photo);
                    @unlink("files/product/" . $subFolder . "/thumb/260x260/" . $folder . '/' . $photo);
                    @unlink("files/product/" . $subFolder . "/thumb/120x120/" . $folder . '/' . $photo);
                    @unlink("files/product/" . $subFolder . "/" . $folder . "/" . $photoSecondary);

                    $productContents = ProductContent::findByProductId($id);
                    if (count($productContents) > 0) {
                        foreach ($productContents as $productContent) {
                            $productContent->delete();
                        }
                    }

                    $product_photos = ProductPhoto::findByProductId($id);

                    if (count($product_photos) > 0) {
                        foreach ($product_photos as $product_photo) {
                            if ($product_photo->delete()) {
                                @unlink("files/product/" . $subFolder . "/" . $folder . "/" . $product_photo->photo);
                                @unlink("files/product/" . $subFolder . "/thumb/360x360/" . $folder . '/' . $product_photo->photo);
                                @unlink("files/product/" . $subFolder . "/thumb/260x260/" . $folder . '/' . $product_photo->photo);
                                @unlink("files/product/" . $subFolder . "/thumb/120x120/" . $folder . '/' . $product_photo->photo);
                            } else {
                                $messages = $product_photo->getMessages();

                                foreach ($messages as $message) {
                                    echo $message, "\n";
                                }
                            }
                        }
                    }

                    $tmpProductElementDetailCombos = TmpProductProductElementDetail::findByProductId($id);
                    if (count($tmpProductElementDetailCombos) > 0) {
                        foreach ($tmpProductElementDetailCombos as $tmpProductElementDetailCombo) {
                            if ($tmpProductElementDetailCombo->delete()) {
                                if ($tmpProductElementDetailCombo->combo_id != ''){
                                    @unlink("files/product/" . $subFolder . "/" . $folder . "/" . $tmpProductElementDetailCombo->photo);
                                }
                            } else {
                                $messages = $tmpProductElementDetailCombo->getMessages();

                                foreach ($messages as $message) {
                                    echo $message, "\n";
                                }
                            }
                        }
                    }

                    if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/product/" . $item->row_id)) {
                        $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/product/" . $item->row_id);
                    }

                    $dependProducts = $this->productRepository->findByPropertyName('DependId', $id);
                    if (count($dependProducts) > 0) {
                        foreach ($dependProducts as $dependProduct) {
                            if ($dependProduct->delete()) {
                                // delete elastic search id id
                                // $this->elastic_service->deleteProduct($dependProduct->id);

                                TmpProductCategory::deleteByRawSql('product_id ='. $dependProduct->id .'');
                                TmpProductProductElementDetail::deleteByRawSql('product_id ='. $dependProduct->id .'');
                                TmpProductFormItem::deleteByRawSql('product_id ='. $dependProduct->id .'');

                                $productContents = ProductContent::findByProductId($dependProduct->id);
                                if (count($productContents) > 0) {
                                    foreach ($productContents as $productContent) {
                                        $productContent->delete();
                                    }
                                }

                                $product_photos = ProductPhoto::findByProductId($dependProduct->id);

                                if (count($product_photos) > 0) {
                                    foreach ($product_photos as $product_photo) {
                                        if ($product_photo->delete()) {
                                        } else {
                                            $messages = $product_photo->getMessages();

                                            foreach ($messages as $message) {
                                                echo $message, "\n";
                                            }
                                        }
                                    }
                                }

                                if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/product/" . $dependProduct->row_id)) {
                                    $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/product/" . $dependProduct->row_id);
                                }
                            }
                        }
                    }
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

    public function showproductphotoAction($product_id, $id, $page = 1)
    {
        $item = ProductPhoto::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\Product.id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $product_id . '/' . $page . '#other_product_photo' : ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $product_id . '#other_product_photo';

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

    public function hideproductphotoAction($product_id, $id, $page = 1)
    {
        $item = ProductPhoto::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\Product.id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $product_id . '/' . $page . '#other_product_photo' : ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $product_id . '#other_product_photo';

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

    /**
     * Delete product
     * 
     * @param  integer $id
     * @return Phalcon\Http\Response    
     */
    public function deletePhotoAction($id)
    {
        $item = $this->productRepository->getItemUpdateDetail($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $type = $this->request->get('type');

        $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id . '#other_product_photo';

        $subFolder = $this->_get_subdomainFolder();
        $folder = $item->folder;
        $photo = ($type == 'secondary') ? $item->photo_secondary : $item->photo;
        if ($type == 'secondary') {
            $item->photo_secondary = null;
        } else {
            $item->photo = null;
        }
        
        if ($item->save()) {
            // upadate elastic
            // $this->elastic_service->updateProduct($id);

            $dependProducts = $this->productRepository->findByPropertyName('DependId', $id);
            
            // update product depends
            if (count($dependProducts) > 0) {
                foreach ($dependProducts as $dependProduct) {
                    if ($type == 'secondary') {
                        $dependProduct->photo_secondary = null;
                    } else {
                        $dependProduct->photo = null;
                    }

                    $dependProduct->save();
                    // upadate elastic
                    // $this->elastic_service->updateProduct($dependProduct->id);
                }
            }

            $this->general->deleteDirectory("files/product/" . $subFolder . "/" . $folder . "/thumb/" . $photo);
            @unlink("files/product/" . $subFolder . "/" . $folder . "/" . $photo);
            @unlink("files/product/" . $subFolder . "/thumb/360x360/" . $folder . '/' . $photo);
            @unlink("files/product/" . $subFolder . "/thumb/260x260/" . $folder . '/' . $photo);
            @unlink("files/product/" . $subFolder . "/thumb/120x120/" . $folder . '/' . $photo);
            $this->flashSession->success("Xóa hình ảnh đại diện thành công");
        } else {
            $this->flashSession->error($item->getMessages());
        }


        $this->response->redirect($url);
    }

    public function deleteproductphotoAction($product_id, $id, $page = 1)
    {
        $item = ProductPhoto::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\Product.id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $product_id . '/' . $page . '#other_product_photo' : ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $product_id . '#other_product_photo';

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

    public function _deleteproductphotoAction($product_id, $id, $page = 1)
    {
        $item = ProductPhoto::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\ProductPhoto.id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $product_id . '/' . $page . '#other_product_photo' : ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $product_id . '#other_product_photo';
        $subFolder = $this->_get_subdomainFolder();
        $folder = $item->folder;
        $photo = $item->photo;
        if ($item->delete()) {
            @unlink("files/product/" . $subFolder . "/" . $folder . "/" . $photo);
            @unlink("files/product/" . $subFolder . "/thumb/360x360/" . $folder . '/' . $photo);
            @unlink("files/product/" . $subFolder . "/thumb/260x260/" . $folder . '/' . $photo);
            @unlink("files/product/" . $subFolder . "/thumb/120x120/" . $folder . '/' . $photo);

            $productPhotoLangs = ProductPhoto::findByDependId($id);
            if (count($productPhotoLangs) > 0) {
                foreach ($productPhotoLangs as $productPhotoLang) {
                    $productPhotoLang->delete();
                }
            }

            
            $this->flashSession->success("Xóa hình ảnh con thành công");
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $this->response->redirect($url);
    }

    /**
     * Ajax auto update price admin
     * 
     * @return Phalcon\Http\Response
     */
    public function updatePriceAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $id = $this->request->getPost('id');
            $price = str_replace(',', '', $this->request->getPost('price'));
            $type = $this->request->getPost('type');
            $product = Product::findFirstById($id);
            $result = 0;
            if ($product && $product->subdomain_id == $this->_get_subdomainID()) {
                switch ($type) {
                    case 1:
                        $product->cost = $price;
                        break;
                    case 2:
                        $product->price = $price;
                        break;
                    case 3:
                        $product->cost_usd = $price;
                        break;
                    case 4:
                        $product->price_usd = $price;
                        break;
                }

                if ($product->save()) {
                    $productLangs = Product::findByDependId($id);
                    if (count($productLangs) > 0) {
                        foreach ($productLangs as $productLang) {
                            switch ($type) {
                                case 1:
                                    $productLang->cost = $price;
                                    break;
                                case 2:
                                    $productLang->price = $price;
                                    break;
                                case 3:
                                    $productLang->cost_usd = $price;
                                    break;
                                case 4:
                                    $productLang->price_usd = $price;
                                    break;
                            }

                            $productLang->save();
                        }
                    }

                    $result = 1;
                } else {
                    $result = 0;
                }
            }
            
            return $this->response->setContent($result);
        }
    }

     /**
     * Ajax auto update code, hits,...
     * 
     * @return Phalcon\Http\Response
     */
    public function updateValueAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $id = $this->request->getPost('id');
            $code = $this->request->getPost('code');
            $type = $this->request->getPost('type');
            $product = Product::findFirstById($id);
            $response = $this->response;
            $result = 0;
            if ($product && $product->subdomain_id == $this->_get_subdomainID()) {
                switch ($type) {
                    case 'code':
                        $product->code = $code;
                        break;
                    
                    case 'purchase_number':
                        $product->purchase_number = $code;
                        break;

                    case 'hits':
                        $product->hits = $code;
                        break;

                    case 'in_stock':
                        $product->in_stock = $code;
                        break;
                }
                
                if ($product->save()) {
                    // $this->elastic_service->updateProduct($id);
                    $productLangs = Product::findByDependId($id);
                    if (count($productLangs) > 0) {
                        foreach ($productLangs as $productLang) {
                            switch ($type) {
                                case 'code':
                                    $productLang->code = $code;
                                    break;
                                
                                case 'purchase_number':
                                    $productLang->purchase_number = $code;
                                    break;

                                case 'hits':
                                    $productLang->hits = $code;
                                    break;

                                case 'in_stock':
                                    $productLang->in_stock = $code;
                                    break;
                            }

                            $productLang->save();
                            // $this->elastic_service->updateProduct($productLang->id);
                        }
                    }

                    $result = 1;
                } else {
                    $result = 0;
                }
            }
            
            $response->setContent($result);
            return $response;
        }
    }

    public function recursive($parent_id = 0, $langId = 1, $space = "", $trees = array())
    {
        if (!$trees) {
            $trees = [];
        }

        $result = Category::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "parent_id = ". $parent_id ." AND Modules\Models\Category.subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND deleted = 'N' AND active = 'Y'"
            ]
        );

        $trees_obj = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $trees[] = [
                    'id' => $row->id,
                    'parent_id' => $row->parent_id,
                    'level' => $row->level,
                    'name' => $space . $row->name,
                ];
                $trees   = $this->recursive($row->id, $langId, $space . '|---', $trees);
            }
        }

        if (!empty($trees)) {
            foreach ($trees as $tree) {
                $tree        = (object) $tree;
                $trees_obj[] = $tree;
            }
        }
        return $trees_obj;
    }
}
