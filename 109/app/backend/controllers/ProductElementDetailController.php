<?php

namespace Modules\Backend\Controllers;

use Modules\Models\ProductElement;
use Modules\Models\ProductElementDetail;
use Modules\Models\TmpProductProductElementDetail;
use Modules\Forms\ProductElementDetailForm;
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

class ProductElementDetailController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Thuộc tính sản phẩm';
    }

    /**
     * Product element detail home
     *
     * @param int $peId
     * @return View
     */
    public function indexAction($peId)
    {
        $url_page = ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName() . '/' . $peId;
        $productElement = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND language_id = 1 AND id = $peId"
        ]);

        if (!$productElement) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $this->view->module_name = 'Danh sách ' . $productElement->name;

        $list = ProductElementDetail::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "product_element_id = ". $peId ." AND deleted = 'N'"
            ]
        );

        $arr_list_j_product_element_product = array();

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            [
                "data" => $list,
                "limit" => 50,
                "page" => $numberPage
            ]
        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/product_element' . '">Thuộc tính</a></li><li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
        $this->view->productElement = $productElement;
        $this->view->arr_list_j_product_element_product = $arr_list_j_product_element_product;
        $this->view->type = $peId;
        $this->view->url_page = $url_page;
    }

    public function createAction($peId)
    {
        $product_element = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $peId"
        ]);

        if (!$product_element) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new ProductElementDetailForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new ProductElementDetail();
            $general = new General();
            $slug = $general->create_slug($this->request->getPost('name'));

            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'product_element_id' => $peId,
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
                            $productElementLang = ProductElement::findFirst([
                                'conditions' => 'depend_id = '. $peId .' AND language_id = '. $langId .''
                            ]);
                            if ($productElementLang) {
                                $slug = $general->create_slug($this->request->getPost('name_' . $langCode));
                                $data = [
                                    'subdomain_id' => $this->_get_subdomainID(),
                                    'product_element_id' => $productElementLang->id,
                                    'language_id' => $langId,
                                    'depend_id' => $id,
                                    'sort' => $this->request->getPost('sort'),
                                    'active' => $this->request->getPost('active'),
                                    'name' => $this->request->getPost('name_' . $langCode),
                                    'slug' => $slug,
                                ];

                                $productElementDetailLang = new ProductElementDetail();
                                $productElementDetailLang->assign($data);
                                if (!$productElementDetailLang->save()) {
                                    foreach ($productElementDetailLang->getMessages() as $message) {
                                        $this->flashSession->error($message);
                                    }
                                }
                            }
                        }
                    }
                }

                
                $this->flashSession->success($this->_message["add"]);

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create/' . $peId;
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $peId;
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $peId . '/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->module_name = $product_element->name;
        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/product_element' . '">Thuộc tính</a></li><li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'.$this->view->module_name.'</li>';

        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->type = $peId;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction($peId, $id, $page = 1)
    {
        $product_element = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $peId"
        ]);

        $item = ProductElementDetail::findFirst([
            "conditions" => "product_element_id = $peId AND id = $id"
        ]);

        if (!$product_element || !$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (count($this->_tmpSubdomainLanguages) > 0) {
            $itemFormData = $item->toArray();
            $itemLangData = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $itemLang = ProductElementDetail::findFirst(array(
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

        $form = new ProductElementDetailForm($itemFormData, ['edit' => true]);

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();
            $slug = $general->create_slug($this->request->getPost('name'));

            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'product_element_id' => $peId,
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
                            $productElementLang = ProductElement::findFirst([
                                'conditions' => 'depend_id = '. $peId .' AND language_id = '. $langId .''
                            ]);
                            if ($productElementLang) {
                                $slug = $general->create_slug($this->request->getPost('name_' . $langCode));

                                $data = [
                                    'subdomain_id' => $this->_get_subdomainID(),
                                    'product_element_id' => $productElementLang->id,
                                    'language_id' => $langId,
                                    'depend_id' => $id,
                                    'name' => $this->request->getPost('name_' . $langCode),
                                    'slug' => $slug,
                                    'sort' => $this->request->getPost('sort'),
                                    'active' => $this->request->getPost('active')
                                ];

                                $productElementDetailLang = ProductElementDetail::findFirst(array(
                                    'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .' AND depend_id = '. $id .''
                                ));
                                if (!$productElementDetailLang) {
                                    $productElementDetailLang = new ProductElementDetail();
                                }

                                $productElementDetailLang->assign($data);
                                $productElementDetailLang->save();
                            }
                        }
                    }
                }

                
                $this->flashSession->success($this->_message["hide"]);

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create/' . $peId . '/' . $page;
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $peId . '/' . $page;
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $peId . '/' . $id . '/' . $page;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->module_name = $product_element->name;
        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/product_element' . '">Thuộc tính</a></li><li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'.$this->view->module_name.'</li>';

        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->type = $peId;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function showAction($peId, $id, $page = 1)
    {
        $product_element = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $peId"
        ]);

        $item = ProductElementDetail::findFirst([
            "conditions" => "product_element_id = $peId AND id = $id"
        ]);

        if (!$product_element || !$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $peId . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$peId;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementDetailLang = ProductElementDetail::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementDetailLang) {
                            $productElementDetailLang->active = 'Y';
                            $productElementDetailLang->save();
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction($peId, $id, $page = 1)
    {
        $product_element = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $peId"
        ]);

        $item = ProductElementDetail::findFirst([
            "conditions" => "product_element_id = $peId AND id = $id"
        ]);

        if (!$product_element || !$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $peId . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$peId;

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            // save other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementDetailLang = ProductElementDetail::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementDetailLang) {
                            $productElementDetailLang->active = 'N';
                            $productElementDetailLang->save();
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showmultyAction($peId, $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $product_element = ProductElement::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $peId"
            ]);

            $item = ProductElementDetail::findFirst([
                "conditions" => "product_element_id = $peId AND id = $id"
            ]);

            if (!$product_element || !$item) {
                $this->flash->error("Không tìm thấy dữ liệu");
                return $this->dispatcher->forward(array('action' => 'index'));
            }

            if ($item) {
                $item->assign(array(
                    'active' => 'Y',
                ));
                if ($item->save()) {
                    // save other lang
                    if (count($this->_tmpSubdomainLanguages) > 0) {
                        foreach ($this->_tmpSubdomainLanguages as $tmp) {
                            $langId = $tmp->language->id;
                            $langCode = $tmp->language->code;
                            if ($langCode != 'vi') {
                                $productElementDetailLang = ProductElementDetail::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                ]);
                                if ($productElementDetailLang) {
                                    $productElementDetailLang->active = 'Y';
                                    $productElementDetailLang->save();
                                }
                            }
                        }
                    }
                }

                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $peId . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$peId;

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["show"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function hidemultyAction($peId, $page= 1)
    {
        $product_element = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $peId"
        ]);
        if (!$product_element) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = ProductElementDetail::findFirst([
                "conditions" => "product_element_id = $peId AND id = $id"
            ]);

            if (!$item) {
                $this->flash->error("Không tìm thấy dữ liệu");
                return $this->dispatcher->forward(array('action' => 'index'));
            }

            if ($item) {
                $item->assign(array(
                    'active' => 'N',
                ));
                if ($item->save()) {
                    // save other lang
                    if (count($this->_tmpSubdomainLanguages) > 0) {
                        foreach ($this->_tmpSubdomainLanguages as $tmp) {
                            $langId = $tmp->language->id;
                            $langCode = $tmp->language->code;
                            if ($langCode != 'vi') {
                                $productElementDetailLang = ProductElementDetail::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                ]);
                                if ($productElementDetailLang) {
                                    $productElementDetailLang->active = 'N';
                                    $productElementDetailLang->save();
                                }
                            }
                        }
                    }
                }

                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $peId . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$peId;

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }


    /**
     * Deletes a Product Elment Detail
     *
     * @param int $id
     */
    public function deleteAction($peId, $id, $page = 1)
    {
        $product_element = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $peId"
        ]);

        $item = ProductElementDetail::findFirst([
            "conditions" => "product_element_id = $peId AND id = $id"
        ]);

        if (!$product_element || !$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'deleted' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $peId . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$peId;
        $this->response->redirect($url);
    }

    public function deletemultyAction($peId, $page = 1)
    {
        $product_element = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $peId"
        ]);
        if (!$product_element) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = ProductElementDetail::findFirst([
                "conditions" => "product_element_id = $peId AND id = $id"
            ]);

            if (!$item) {
                $this->flash->error("Không tìm thấy dữ liệu");
                return $this->dispatcher->forward(array('action' => 'index'));
            }

            if ($item) {
                $item->assign(array(
                    'deleted' => 'Y',
                ));
                $item->save();
                $d++;
            }
        }
        //echo $d;die;
        if ($d > 0) {
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $peId . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$peId;
        $this->response->redirect($url);
    }

    public function _deleteAction($peId, $id, $page = 1)
    {
        $product_element = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $peId"
        ]);

        $item = ProductElementDetail::findFirst([
            "conditions" => "product_element_id = $peId AND id = $id"
        ]);

        if (!$product_element || !$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            TmpProductProductElementDetail::deleteByRawSql('product_element_detail_id ='. $id .'');

            // delete tmp product id elastic
            $this->elastic_service->deleteTmpProductElmDetail($id);

            //delete other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $productElementDetailLang = ProductElementDetail::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($productElementDetailLang) {
                            TmpProductProductElementDetail::deleteByRawSql('product_element_detail_id ='. $productElementDetailLang->id .'');
                            $productElementDetailLang->delete();
                            // delete tmp product id elastic
                            $this->elastic_service->deleteTmpProductElmDetail($productElementDetailLang->id);
                        }
                    }
                }
            }

            
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $peId . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$peId;
        $this->response->redirect($url);
    }

    public function _deletemultyAction($peId, $page = 1)
    {
        $product_element = ProductElement::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $peId"
        ]);

        if (!$product_element) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = ProductElementDetail::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if (!$item) {
                $this->flash->error("Không tìm thấy dữ liệu");
                return $this->dispatcher->forward(array('action' => 'index'));
            }

            if ($item->delete()) {
                TmpProductProductElementDetail::deleteByRawSql('product_element_detail_id ='. $id .'');

                // delete tmp product id elastic
                $this->elastic_service->deleteTmpProductElmDetail($id);
                            
                //delete other lang
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language->id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $productElementDetailLang = ProductElementDetail::findFirst([
                                'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                            ]);
                            if ($productElementDetailLang) {
                                TmpProductProductElementDetail::deleteByRawSql('product_element_detail_id ='. $productElementDetailLang->id .'');
                                $productElementDetailLang->delete();

                                // delete tmp product id elastic
                                $this->elastic_service->deleteTmpProductElmDetail($productElementDetailLang->id);
                            }
                        }
                    }
                }

                $d++;
            }
        }
        //echo $d;die;
        if ($d > 0) {
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $peId . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$peId;
        $this->response->redirect($url);
    }

    /**
     * Update product element detail color
     *
     * @return string
     */
    public function updateColorAction()
    {
        $this->view->disable();
        if ($this->request->isAjax() && $this->request->isPost()) {
            $id = $this->request->getPost('id');
            $color = $this->request->getPost('color');
            $productElementDetail = ProductElementDetail::findFirstById($id);
            $result = 0;
            if ($productElementDetail) {
                $productElementDetail->color = $color;
                $productElementDetail->save();
                $result = 1;
            }

            return $this->response->setContent($result);
        }
    }

    public function updateSubdomainIdAction()
    {
        $tmpProductProductElementDetails = TmpProductProductElementDetail::findBySubdomainId(0);
        foreach ($tmpProductProductElementDetails as $tmpProductProductElementDetail) {
            if ($tmpProductProductElementDetail->product) {
                $tmpProductProductElementDetail->subdomain_id = $tmpProductProductElementDetail->product->subdomain_id;
                $tmpProductProductElementDetail->save();
            }
        }
    }
}
