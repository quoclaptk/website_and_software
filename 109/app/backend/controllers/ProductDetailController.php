<?php

namespace Modules\Backend\Controllers;

use Modules\Models\ProductDetail;
use Modules\Models\ProductContent;
use Modules\Forms\ProductDetailForm;
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

class ProductDetailController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Chi tiết sản phẩm';
    }

    public function indexAction()
    {
        $list = ProductDetail::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND deleted = 'N'"
            ]
        );

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
    }

    public function createAction()
    {
        $form = new ProductDetailForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new ProductDetail();
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

                            $productDetailLang = new ProductDetail();
                            $productDetailLang->assign($data);
                            if (!$productDetailLang->save()) {
                                foreach ($productDetailLang->getMessages() as $message) {
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
        $item = ProductDetail::findFirst([
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
                    $itemLang = ProductDetail::findFirst(array(
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

        $form = new ProductDetailForm($itemFormData, ['edit' => true]);
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
                                'active' => $this->request->getPost('active'),
                                'enable_delete' => $item->enable_delete,
                            ];

                            $productDetailLang = ProductDetail::findFirst(array(
                                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .' AND depend_id = '. $id .''
                            ));
                            if (!$productDetailLang) {
                                $productDetailLang = new ProductDetail();
                            }

                            $productDetailLang->assign($data);
                            $productDetailLang->save();
                        }
                    }
                }

                
                $this->flashSession->success($this->_message["edit"]);
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
        $item = ProductDetail::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        if ($item->enable_delete == 'Y') {
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
                            $productDetailLang = ProductDetail::findFirst([
                                'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                            ]);
                            if ($productDetailLang) {
                                $productDetailLang->active = 'Y';
                                $productDetailLang->save();
                            }
                        }
                    }
                }

                
                $this->flashSession->success($this->_message["show"]);
                $this->response->redirect($url);
            }
        } else {
            $this->flash->error("Not enable");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
    }

    public function hideAction(int $id, int $page = 1)
    {
        $item = ProductDetail::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        ;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        if ($item->enable_delete == 'Y') {
            $item->assign([
                'active' => 'N'
            ]);

            if ($item->save()) {
                // other lang
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language->id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $productDetailLang = ProductDetail::findFirst([
                                'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                            ]);
                            if ($productDetailLang) {
                                $productDetailLang->active = 'N';
                                $productDetailLang->save();
                            }
                        }
                    }
                }

                
                $this->flashSession->success($this->_message["hide"]);
                $this->response->redirect($url);
            }
        } else {
            $this->flash->error("Not enable");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
    }

    public function showmultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = ProductDetail::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                if ($item->enable_delete == 'Y') {
                    $item->assign([
                        'active' => 'Y'
                    ]);
                    if ($item->save()) {
                        // other lang
                        if (count($this->_tmpSubdomainLanguages) > 0) {
                            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                $langId = $tmp->language->id;
                                $langCode = $tmp->language->code;
                                if ($langCode != 'vi') {
                                    $productDetailLang = ProductDetail::findFirst([
                                        'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                    ]);
                                    if ($productDetailLang) {
                                        $productDetailLang->active = 'Y';
                                        $productDetailLang->save();
                                    }
                                }
                            }
                        }
                    }

                    $d++;
                } else {
                    $this->flash->error("Not enable");
                    return $this->dispatcher->forward(array('action' => 'index'));
                }
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
            $item = ProductDetail::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                if ($item->enable_delete == 'Y') {
                    $item->assign([
                        'active' => 'N'
                    ]);
                    if ($item->save()) {
                        // other lang
                        if (count($this->_tmpSubdomainLanguages) > 0) {
                            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                $langId = $tmp->language->id;
                                $langCode = $tmp->language->code;
                                if ($langCode != 'vi') {
                                    $productDetailLang = ProductDetail::findFirst([
                                        'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                    ]);
                                    if ($productDetailLang) {
                                        $productDetailLang->active = 'N';
                                        $productDetailLang->save();
                                    }
                                }
                            }
                        }
                    }

                    $d++;
                } else {
                    $this->flash->error("Not enable");
                    return $this->dispatcher->forward(array('action' => 'index'));
                }
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

    public function showmenuAction(int $id, int $page = 1)
    {
        $item = ProductDetail::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'menu' => 'Y'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function hidemenuAction(int $id, int $page = 1)
    {
        $item = ProductDetail::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'menu' => 'N'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function showstaticAction(int $id, int $page = 1)
    {
        $item = ProductDetail::findFirst([
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
        $item = ProductDetail::findFirst([
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

    public function deleteAction(int $id, $page = 1)
    {
        $item = ProductDetail::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        $count_product_content = $this->count_product_content($id);

        if ($count_product_content > 0) {
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
            $item = ProductDetail::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);

            $count_product_content = $this->count_product_content($id);

            if ($count_product_content > 0) {
                $this->flashSession->error("Không thể xóa mục này vì chứa liên kết dữ liệu.");
                return $this->response->redirect($url);
            }

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

    public function _deleteAction(int $id, $page = 1)
    {
        $item = ProductDetail::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if ($item->enable_delete == 'Y') {
            if ($item->delete()) {
                $productContents = ProductContent::findByProductDetailId($id);
                if (count($productContents) > 0) {
                    foreach ($productContents as $productContent) {
                        $productContent->delete();
                    }
                }

                //delete other lang
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language->id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $productDetailLang = ProductDetail::findFirst([
                                'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                            ]);
                            if ($productDetailLang) {
                                $productContentLangs = ProductContent::findByProductDetailId($productDetailLang->id);
                                if (count($productContentLangs) > 0) {
                                    foreach ($productContentLangs as $productContentLang) {
                                        $productContentLang->delete();
                                    }
                                }

                                $productDetailLang->delete();
                            }
                        }
                    }
                }

                
                $this->flashSession->success($this->_message["delete"]);
            }
        } else {
            $this->flash->error("Not enable delete this property");
            return $this->dispatcher->forward(array('action' => 'index'));
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
            $item = ProductDetail::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                if ($item->enable_delete == 'Y') {
                    if ($item->delete()) {
                        $productContents = ProductContent::findByProductDetailId($id);
                        if (count($productContents) > 0) {
                            foreach ($productContents as $productContent) {
                                $productContent->delete();
                            }
                        }

                        //delete other lang
                        if (count($this->_tmpSubdomainLanguages) > 0) {
                            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                $langId = $tmp->language->id;
                                $langCode = $tmp->language->code;
                                if ($langCode != 'vi') {
                                    $productDetailLang = ProductDetail::findFirst([
                                        'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                    ]);
                                    if ($productDetailLang) {
                                        $productContentLangs = ProductContent::findByProductDetailId($productDetailLang->id);
                                        if (count($productContentLangs) > 0) {
                                            foreach ($productContentLangs as $productContentLang) {
                                                $productContentLang->delete();
                                            }
                                        }
                                        
                                        $productDetailLang->delete();
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $this->flash->error("Not enable delete this property");
                    return $this->dispatcher->forward(array('action' => 'index'));
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

    public function updateSubdomainIdAction($page = 0)
    {
        $offset = 10000 * $page;
        $productContents = ProductContent::find([
            'limit' => 10000,
            'offset' => $offset,
            'order' => 'id ASC'
        ]);
        foreach ($productContents as $productContent) {
            if ($productContent->product_detail) {
                $productContent->subdomain_id = $productContent->product_detail->subdomain_id;
                $productContent->save();
            }
        }
    }

    public function count_product_content(int $id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['pc' => '\Modules\Models\ProductContent'])
            ->join('Modules\Models\ProductDetail', 'pd.id = pc.product_detail_id', 'pd')
            ->where('pc.id = '. $id .' AND pd.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }
}
