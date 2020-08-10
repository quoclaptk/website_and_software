<?php

namespace Modules\Backend\Controllers;

use Modules\Models\ProductElement;
use Modules\Models\ProductElementDetail;
use Modules\Forms\ProductElementForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Text as TextRandom;
use Phalcon\Image\Adapter\GD;

class ProductElementController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Thuộc tính sản phẩm';
    }

    public function indexAction()
    {
        $list = ProductElement::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND deleted = 'N'"
            ]
        );

        $list_j_product_element_detail = $this->modelsManager->createBuilder()
            ->columns(['pe.id'])
            ->from(['pe' => 'Modules\Models\ProductElement'])
            ->join('Modules\Models\ProductElementDetail', 'ped.product_element_id = pe.id', 'ped')
            ->where('pe.deleted = "N" AND ped.deleted = "N"')
            ->groupBy('pe.id')
            ->getQuery()
            ->execute();

        $arr_list_j_product_element_detail = array();
        if (!empty($list_j_product_element_detail)) {
            foreach ($list_j_product_element_detail as $row) {
                $arr_list_j_product_element_detail[] = $row->id;
            }
        }

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            [
                "data" => $list,
                "limit" => 10,
                "page" => $numberPage
            ]
        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
        $this->view->arr_list_j_product_element_detail = $arr_list_j_product_element_detail;
    }

    public function createAction()
    {
        $form = new ProductElementForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new ProductElement();
            $general = new General();
            $slug = $general->create_slug($this->request->getPost('name'));

            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);

            if ($item->save()) {
                $id = $item->id;
                //save other language
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $data = [];
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $slug = $general->create_slug($this->request->getPost('name_' . $langCode));
                            $data = [
                                'subdomain_id' => $this->_get_subdomainID(),
                                'language_id' => $langId,
                                'depend_id' => $id,
                                'sort' => $this->request->getPost('sort'),
                                'active' => $this->request->getPost('active'),
                                'name' => $this->request->getPost('name_' . $langCode),
                                'slug' => $slug,
                            ];

                            $productElementLang = new ProductElement();
                            $productElementLang->assign($data);
                            if (!$productElementLang->save()) {
                                foreach ($productElementLang->getMessages() as $message) {
                                    $this->flashSession->error($message);
                                }
                            }
                        }
                    }
                }

                
                $this->flashSession->success($this->_message["add"]);

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
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (count($this->_tmpSubdomainLanguages) > 0) {
            $itemFormData = $item->toArray();
            $itemLangData = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $itemLang = ProductElement::findFirst(array(
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $tmp->language_id .' AND depend_id = '. $id .''
                    ));

                    if ($itemLang) {
                        $itemLangData[$langCode] = $itemLang;
                        $itemLang = $itemLang->toArray();
                        $itemLangKeys = array_keys($itemLang);
                        foreach ($itemLangKeys as $itemLangKey) {
                            $itemFormData[$itemLangKey . '_' . $langCode] = $itemLang[$itemLangKey];
                        }
                    }
                }
            }

            $itemFormData = (object) $itemFormData;
        } else {
            $itemFormData = $item;
        }

        $form = new ProductElementForm($itemFormData, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();
            $slug = $general->create_slug($this->request->getPost('name'));

            $data = [
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);

            if ($item->save()) {
                //save other language
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $data = [];
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $slug = $general->create_slug($this->request->getPost('name_' . $langCode));

                            $data = [
                                'subdomain_id' => $this->_get_subdomainID(),
                                'language_id' => $langId,
                                'depend_id' => $id,
                                'name' => $this->request->getPost('name_' . $langCode),
                                'slug' => $slug,
                                'sort' => $this->request->getPost('sort'),
                                'active' => $this->request->getPost('active')
                            ];

                            $productElementLang = ProductElement::findFirst(array(
                                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .' AND depend_id = '. $id .''
                            ));
                            if (!$productElementLang) {
                                $productElementLang = new ProductElement();
                            }

                            $productElementLang->assign($data);
                            $productElementLang->save();
                        }
                    }
                }

                
                $this->flashSession->success($this->_message["hide"]);
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id . '/' . $page;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function showAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'Y',
        ]);

        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementLang = ProductElement::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementLang) {
                            $productElementLang->active = 'Y';
                            $productElementLang->save();
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        ;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'N'
        ]);

        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementLang = ProductElement::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementLang) {
                            $productElementLang->active = 'N';
                            $productElementLang->save();
                        }
                    }
                }
            }

            
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
            $item = ProductElement::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'Y'
                ]);
                if ($item->save()) {
                    // save other lang
                    if (count($this->_tmpSubdomainLanguages) > 0) {
                        foreach ($this->_tmpSubdomainLanguages as $tmp) {
                            $langId = $tmp->language->id;
                            $langCode = $tmp->language->code;
                            if ($langCode != 'vi') {
                                $productElementLang = ProductElement::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                ]);
                                if ($productElementLang) {
                                    $productElementLang->active = 'Y';
                                    $productElementLang->save();
                                }
                            }
                        }
                    }
                }

                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            
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
            $item = ProductElement::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'N'
                ]);
                if ($item->save()) {
                    // save other lang
                    if (count($this->_tmpSubdomainLanguages) > 0) {
                        foreach ($this->_tmpSubdomainLanguages as $tmp) {
                            $langId = $tmp->language->id;
                            $langCode = $tmp->language->code;
                            if ($langCode != 'vi') {
                                $productElementLang = ProductElement::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                ]);
                                if ($productElementLang) {
                                    $productElementLang->active = 'N';
                                    $productElementLang->save();
                                }
                            }
                        }
                    }
                }

                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function showSearchAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'search' => 'Y'
        ]);

        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementLang = ProductElement::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementLang) {
                            $productElementLang->search = 'Y';
                            $productElementLang->save();
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["edit"]);
            $this->response->redirect($url);
        }
    }

    public function hideSearchAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'search' => 'N'
        ]);

        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementLang = ProductElement::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementLang) {
                            $productElementLang->search = 'N';
                            $productElementLang->save();
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["edit"]);
            $this->response->redirect($url);
        }
    }

    public function showShowPriceAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'show_price' => 'Y'
        ]);

        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementLang = ProductElement::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementLang) {
                            $productElementLang->show_price = 'Y';
                            $productElementLang->save();
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["edit"]);
            $this->response->redirect($url);
        }
    }

    public function hideShowPriceAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'show_price' => 'N'
        ]);

        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementLang = ProductElement::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementLang) {
                            $productElementLang->show_price = 'N';
                            $productElementLang->save();
                        }
                    }
                }
            }
            
            
            $this->flashSession->success($this->_message["edit"]);
            $this->response->redirect($url);
        }
    }

    public function showstaticAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'static' => 'Y'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function hidestaticAction(int $id, $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'static' => 'N'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    /**
     * Check combo
     *
     * @param int $id
     * @param int $page
     * @return Phalcon\Http\Response
     */
    public function isComboAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->combo = ($item->combo == 'Y') ? 'N' : 'Y';
        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementLang = ProductElement::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementLang) {
                            $productElementLang->combo = $item->combo;
                            $productElementLang->save();
                        }
                    }
                }
            }
            
            
            $this->flashSession->success($this->_message["edit"]);
            $this->response->redirect($url);
        }
    }

    /**
     * Check color
     *
     * @param int $id
     * @param int $page
     * @return Phalcon\Http\Response
     */
    public function isColorAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->is_color = ($item->is_color == 'Y') ? 'N' : 'Y';
        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementLang = ProductElement::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementLang) {
                            $productElementLang->is_color = $item->combo;
                            $productElementLang->save();
                        }
                    }
                }
            }
            
            
            $this->flashSession->success($this->_message["edit"]);
            $this->response->redirect($url);
        }
    }

    /**
     * Check product photo
     *
     * @param int $id
     * @param int $page
     * @return Phalcon\Http\Response
     */
    public function isProductPhotoAction(int $id, int $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->is_product_photo = ($item->is_product_photo == 'Y') ? 'N' : 'Y';
        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementLang = ProductElement::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementLang) {
                            $productElementLang->is_product_photo = $item->combo;
                            $productElementLang->save();
                        }
                    }
                }
            }
            
            
            $this->flashSession->success($this->_message["edit"]);
            $this->response->redirect($url);
        }
    }

    public function deleteAction(int $id, $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        $count_product_element_detail = $this->count_product_element_detail($id);

        if ($count_product_element_detail > 0) {
            $this->flashSession->error("Không thể xóa mục này vì chứa liên kết dữ liệu.");
            return $this->response->redirect($url);
        }

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

    public function deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        foreach ($listid as $id) {
            $item = ProductElement::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);

            $count_product_element_detail = $this->count_product_element_detail($id);

            if ($count_product_element_detail > 0) {
                $this->flashSession->error("Không thể xóa mục này vì chứa liên kết dữ liệu.");
                return $this->response->redirect($url);
            }

            if ($item) {
                $item->assign([
                    'deleted' => 'Y'
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

    public function _deleteAction(int $id, $page = 1)
    {
        $item = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            $productElementDetails = ProductElementDetail::findByProductElementId($id);
            if (count($productElementDetails) > 0) {
                foreach ($productElementDetails as $productElementDetail) {
                    $productElementDetail->delete();
                }
            }

            //delete other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementLang = ProductElement::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementLang) {
                            $productElementDetails = ProductElementDetail::findByProductElementId($productElementLang->id);
                            if (count($productElementDetails) > 0) {
                                foreach ($productElementDetails as $productElementDetail) {
                                    $productElementDetail->delete();
                                }
                            }

                            $productElementLang->delete();
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["delete"]);
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function _deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = ProductElement::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                if ($item->delete()) {
                    $productElementDetails = ProductElementDetail::findByProductElementId($id);
                    if (count($productElementDetails) > 0) {
                        foreach ($productElementDetails as $productElementDetail) {
                            $productElementDetail->delete();
                        }
                    }

                    //delete other lang
                    if (count($this->_tmpSubdomainLanguages) > 0) {
                        foreach ($this->_tmpSubdomainLanguages as $tmp) {
                            $langId = $tmp->language->id;
                            $langCode = $tmp->language->code;
                            if ($langCode != 'vi') {
                                $productElementLang = ProductElement::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                ]);
                                if ($productElementLang) {
                                    $productElementDetails = ProductElementDetail::findByProductElementId($productElementLang->id);
                                    if (count($productElementDetails) > 0) {
                                        foreach ($productElementDetails as $productElementDetail) {
                                            $productElementDetail->delete();
                                        }
                                    }

                                    $productElementLang->delete();
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

    public function updateSubdomainIdAction()
    {
        $productElementDetails = ProductElementDetail::find();
        foreach ($productElementDetails as $productElementDetail) {
            if ($productElementDetail->product_element) {
                $productElementDetail->subdomain_id = $productElementDetail->product_element->subdomain_id;
                $productElementDetail->save();
            }
        }
    }

    public function count_product_element_detail(int $id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['ped' => '\Modules\Models\ProductElementDetail'])
            ->join('Modules\Models\ProductElement', 'pe.id = ped.product_element_id', 'pe')
            ->where('pe.id = '. $id .' AND ped.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }
}
