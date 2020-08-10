<?php namespace Modules\Backend\Controllers;

use Modules\Models\Banner;
use Modules\Models\BannerType;
use Modules\Models\TmpBannerBannerType;
use Modules\Forms\BannerForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Text;
use Phalcon\Image\Adapter\GD;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;

/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class BannerController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Banner hình ảnh';
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction()
    {
        $banners = Banner::query()
            ->where("subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND deleted = 'N'")
            ->orderBy("sort ASC, id DESC")
            ->execute();

        $bannerTypes = BannerType::find([
            'columns' => 'id, name',
            'conditions' => 'active="Y" AND subdomain_id = '. $this->_get_subdomainID() .'',
            'order' => 'name DESC, id DESC'
        ]);

        $bannerList = [];
        if (count($banners) > 0) {
            $i = 0;
            foreach ($banners->toArray() as $banner) {
                $bannerList[] = (object) $banner;
                $countBannerType = [];
                $j = 0;
                foreach ($bannerTypes as $bannerType) {
                    $tmpBannerBannerType = TmpBannerBannerType::find([
                        'conditions' => 'banner_id = '. $banner['id'] .' AND banner_type_id = '. $bannerType->id .''
                    ]);
                    $countBannerType[$j] = count($tmpBannerBannerType);
                    $j++;
                }
                $bannerList[$i]->count = $countBannerType;
                $i++;
            }
        }
        $bannerList = (object) $bannerList;

        if ($this->request->isPost()) {
            foreach ($banners as $banner) {
                TmpBannerBannerType::deleteByRawSql('banner_id ='. $banner->id .'');
                $bannerTypeValues = $this->request->getPost('banner_' . $banner->id);
                if (!empty($bannerTypeValues)) {
                    foreach ($bannerTypeValues as $bannerTypeValue) {
                        $tmpBannerBannerType = new TmpBannerBannerType();
                        $tmpBannerBannerType->assign([
                            'subdomain_id' => $this->_get_subdomainID(),
                            'banner_id' => $banner->id,
                            'banner_type_id' => $bannerTypeValue
                        ]);
                        $tmpBannerBannerType->save();
                    }
                }

                

                //save left right ads
                $leftAdsValues = $this->request->getPost('left_ads_' . $banner->id);
                if (!empty($leftAdsValues)) {
                    $banner->left_ads = 'Y';
                } else {
                    $banner->left_ads = 'N';
                }

                $rightAdsValues = $this->request->getPost('right_ads_' . $banner->id);
                if (!empty($rightAdsValues)) {
                    $banner->right_ads = 'Y';
                } else {
                    $banner->right_ads = 'N';
                }

                $mdBanner2Values = $this->request->getPost('md_banner_2_' . $banner->id);
                if (!empty($mdBanner2Values)) {
                    $banner->md_banner_2 = 'Y';
                } else {
                    $banner->md_banner_2 = 'N';
                }

                $mdBanner3Values = $this->request->getPost('md_banner_3_' . $banner->id);
                if (!empty($mdBanner3Values)) {
                    $banner->md_banner_3 = 'Y';
                } else {
                    $banner->md_banner_3 = 'N';
                }

                $verticalSliderValues = $this->request->getPost('vertical_slider_' . $banner->id);
                if (!empty($verticalSliderValues)) {
                    $banner->vertical_slider = 'Y';
                } else {
                    $banner->vertical_slider = 'N';
                }

                if ($banner->save()) {
                    //update banner lang
                    $bannerLangs = Banner::findByDependId($banner->id);
                    $bannerVi = $banner->toArray();
                    unset($bannerVi['id']);
                    if (count($bannerLangs) > 0) {
                        foreach ($bannerLangs as $bannerLang) {
                            TmpBannerBannerType::deleteByRawSql('banner_id ='. $bannerLang->id .'');
                            if (!empty($bannerTypeValues)) {
                                foreach ($bannerTypeValues as $bannerTypeValue) {
                                    $tmpBannerBannerType = new TmpBannerBannerType();
                                    $tmpBannerBannerType->assign([
                                        'subdomain_id' => $this->_get_subdomainID(),
                                        'banner_id' => $bannerLang->id,
                                        'banner_type_id' => $bannerTypeValue
                                    ]);
                                    $tmpBannerBannerType->save();
                                }
                            }

                            $bannerVi['language_id'] = $bannerLang->language_id;
                            $bannerVi['depend_id'] = $bannerLang->depend_id;
                            $bannerVi['name'] = $bannerLang->name;
                            $bannerVi['link'] = $bannerLang->link;
                            $bannerLang->assign($bannerVi);
                            $bannerLang->save();
                        }
                    }
                }
            }
            
            
            $this->flashSession->success($this->_message["edit"]);
            $url = ACP_NAME . '/' . $this->_getControllerName();
            $this->response->redirect($url);
        }

        $numberPage = $this->request->getQuery("page", "int");

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';

        $this->view->banner_type = $bannerTypes;
        $this->view->bannerList = $bannerList;
        $this->view->title_bar = 'Danh sách';
        $this->view->breadcrumb = $breadcrumb;
        // $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }

    public function createAction()
    {
        $sub_folder = $this->_get_subdomainFolder();
        $form = new BannerForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $banner_type_p = $this->request->getPost('banner_type');

            $banner = new Banner();
            $general = new General();

            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'name' => $this->request->getPost('name'),
                'link' => $this->request->getPost('link'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            $subFolder = $this->_get_subdomainFolder();
            $subfolderUrl = 'files/ads/' . $subFolder;

            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    if (!empty($file->getName())) {
                        if ($file->getKey() == 'photo') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                            if (!empty($dataUpload['file_name'])) {
                                $data['photo'] = $dataUpload['file_name'];
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }
                    }
                }
            }

            $banner->assign($data);
            if ($banner->save()) {
                $id = $banner->id;
                foreach ($banner_type_p as $row) {
                    $tmp_banner_banner_type = new TmpBannerBannerType();
                    $tmp_banner_banner_type->assign([
                        'subdomain_id' => $this->_get_subdomainID(),
                        'banner_id' => $id,
                        'banner_type_id' => $row
                    ]);

                    $tmp_banner_banner_type->save();
                }

                //save other language
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        $data = [];
                        if ($langCode != 'vi') {
                            $itemVi = $banner->toArray();
                            $itemVi['language_id'] = $langId;
                            $itemVi['depend_id'] = $id;
                            $itemVi['name'] = $this->request->getPost('name_' . $langCode);
                            $itemVi['link'] = $this->request->getPost('link_' . $langCode);
                            if ($this->request->hasFiles() == true) {
                                $files = $this->request->getUploadedFiles();
                                foreach ($files as $file) {
                                    $ext = $file->getType();
                                    if (!empty($file->getName())) {
                                        if ($file->getKey() == 'photo_' . $langCode) {
                                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                                            if (!empty($dataUpload['file_name'])) {
                                                $itemVi['photo'] = $dataUpload['file_name'];
                                            } else {
                                                $this->flashSession->error( $dataUpload['message']);
                                                return $this->response->redirect($this->router->getRewriteUri());
                                            }
                                        }
                                    }
                                }
                            }

                            unset($itemVi['id']);
                            $bannerLang = new Banner();
                            $bannerLang->assign($itemVi);
                            if ($bannerLang->save()) {
                                foreach ($banner_type_p as $row) {
                                    $tmp_banner_banner_type = new TmpBannerBannerType();
                                    $tmp_banner_banner_type->assign([
                                        'subdomain_id' => $this->_get_subdomainID(),
                                        'banner_id' => $bannerLang->id,
                                        'banner_type_id' => $row
                                    ]);

                                    $tmp_banner_banner_type->save();
                                }
                            }
                        }
                    }
                }

                
                $this->flashSession->success("Thêm mớithành công");

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName();
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($banner->getMessages());
            }
        }

        $banner_type = BannerType::find([
            'columns' => 'id, name',
            'conditions' => 'active="Y" AND subdomain_id = '. $this->_get_subdomainID() .'',
            'order' => 'name ASC, id DESC'
        ]);

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/banner">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->banner_type = $banner_type;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction($id, $page = 1)
    {
        $sub_folder = $this->_get_subdomainFolder();
        $item = Banner::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy bài viết");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $banner_type = BannerType::find([
            'columns' => 'id, name',
            'conditions' => 'active="Y" AND subdomain_id = '. $this->_get_subdomainID() .'',
            'order' => 'name ASC, id DESC'
        ]);

        $tmp_banner_banner_type = TmpBannerBannerType::find([
            'conditions' => "banner_id = $item->id"
        ]);

        $tmp_banner_banner_type_arr = array();
        if (!empty($tmp_banner_banner_type)) {
            foreach ($tmp_banner_banner_type as $row) {
                $tmp_banner_banner_type_arr[] = $row->banner_type_id;
            }
        }

        $photo = $item->photo;

        if (count($this->_tmpSubdomainLanguages) > 0) {
            $itemFormData = $item->toArray();
            $itemLangData = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $itemLang = Banner::findFirst(array(
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
            $this->view->itemLangData = $itemLangData;
        } else {
            $itemFormData = $item;
        }

        $form = new BannerForm($itemFormData, array('edit' => true));
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $banner_type_p = $this->request->getPost('banner_type');

            $general = new General();
            $data = array(
                'name' => $this->request->getPost('name'),
                'link' => $this->request->getPost('link'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
            );

            $subFolder = $this->_get_subdomainFolder();
            $subfolderUrl = 'files/ads/' . $subFolder;

            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    $ext = $file->getType();
                    
                    if (!empty($file->getName())) {
                        if ($file->getKey() == 'photo') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                            if (!empty($dataUpload['file_name'])) {
                                $data['photo'] = $dataUpload['file_name'];
                                @unlink($subfolderUrl . '/' . $photo);
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }
                    }
                }
            }

            $item->assign($data);
            if ($item->save()) {
                if (!empty($banner_type_p)) {
                    TmpBannerBannerType::deleteByRawSql('banner_id ='. $id .'');
                    foreach ($banner_type_p as $row) {
                        $tmp_banner_banner_type = new TmpBannerBannerType();
                        $tmp_banner_banner_type->assign([
                            'subdomain_id' => $this->_get_subdomainID(),
                            'banner_id' => $id,
                            'banner_type_id' => $row
                        ]);
                        $tmp_banner_banner_type->save();
                    }
                }

                //save other language
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        $data = [];
                        if ($langCode != 'vi') {
                            $itemVi = $item->toArray();
                            $itemVi['language_id'] = $langId;
                            $itemVi['depend_id'] = $id;
                            $itemVi['name'] = $this->request->getPost('name_' . $langCode);
                            $itemVi['link'] = $this->request->getPost('link_' . $langCode);
                            $photoLang = null;
                            if ($this->request->hasFiles() == true) {
                                $files = $this->request->getUploadedFiles();
                                foreach ($files as $file) {
                                    $ext = $file->getType();
                                    if (!empty($file->getName())) {
                                        if ($file->getKey() == 'photo_' . $langCode) {
                                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                                            if (!empty($dataUpload['file_name'])) {
                                                $photoLang = $dataUpload['file_name'];
                                            } else {
                                                $this->flashSession->error( $dataUpload['message']);
                                                return $this->response->redirect($this->router->getRewriteUri());
                                            }
                                        }   
                                    }
                                }
                            }

                            unset($itemVi['id']);
                            $bannerLang = Banner::findFirst(array(
                                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .' AND depend_id = '. $id .''
                            ));
                            if (!$bannerLang) {
                                $bannerLang = new Banner();
                            } else {
                                $itemVi['photo'] = ($photoLang != null) ? $photoLang : $bannerLang->photo;
                                if ($photoLang != null) {
                                    @unlink($subfolderUrl . '/' . $bannerLang->photo);
                                }
                            }

                            $bannerLang->assign($itemVi);
                            if ($bannerLang->save()) {
                                if (!empty($banner_type_p)) {
                                    TmpBannerBannerType::deleteByRawSql('banner_id ='. $bannerLang->id .'');
                                    foreach ($banner_type_p as $row) {
                                        $tmp_banner_banner_type = new TmpBannerBannerType();
                                        $tmp_banner_banner_type->assign([
                                            'subdomain_id' => $this->_get_subdomainID(),
                                            'banner_id' => $bannerLang->id,
                                            'banner_type_id' => $row
                                        ]);
                                        $tmp_banner_banner_type->save();
                                    }
                                }
                            }
                        }
                    }
                }

                
                $this->flashSession->success("Cập nhật thành công");

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flashSession->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/banner">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->item = $item;
        $this->view->banner_type = $banner_type;
        $this->view->tmp_banner_banner_type_arr = $tmp_banner_banner_type_arr;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function showAction($id, $page = 1)
    {
        $item = Banner::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction($id, $page = 1)
    {
        $item = Banner::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$type;

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showmultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Banner::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign(array(
                    'active' => 'Y',
                ));
                $item->save();
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$type;

        if ($d > 0) {
            $this->flashSession->success($this->_message["show"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function hidemultyAction($page= 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Banner::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign(array(
                    'active' => 'N',
                ));
                $item->save();
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$type;

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["hi"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }


    /**
     * Deletes a News
     *
     * @param int $id
     */
    public function deleteAction($id, $page = 1)
    {
        $item = Banner::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type = $type"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'deleted' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success("Xóa dữ liệu thành công");
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' .$type;
        $this->response->redirect($url);
    }

    public function deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Banner::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
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
            
            $this->flashSession->success("Xóa dữ liệu thành công");
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function _deleteAction($id, $page = 1)
    {
        $sub_folder = $this->_get_subdomainFolder();
        $item = Banner::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $photo = $item->photo;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
            $this->flashSession->error("Không tìm thấy dữ liệu");
        } else {
            TmpBannerBannerType::deleteByRawSql('banner_id ='. $id .'');
            @unlink('files/ads/' . $sub_folder . '/' . $photo);
            $bannerLangs = Banner::findByDependId($id);
            if (count($bannerLangs) > 0) {
                foreach ($bannerLangs as $bannerLang) {
                    @unlink('files/ads/' . $sub_folder . '/' . $bannerLang->photo);
                    TmpBannerBannerType::deleteByRawSql('banner_id ='. $id .'');
                    $bannerLang->delete();
                }
            }
           
            
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function _deletemultyAction($page = 1)
    {
        $sub_folder = $this->_get_subdomainFolder();
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Banner::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $photo = $item->photo;
                if ($item->delete()) {
                    
                    TmpBannerBannerType::deleteByRawSql('banner_id ='. $id .'');
                    @unlink('files/ads/' . $sub_folder . '/' . $photo);
                    $bannerLangs = Banner::findByDependId($id);
                    if (count($bannerLangs) > 0) {
                        foreach ($bannerLangs as $bannerLang) {
                            @unlink('files/ads/' . $sub_folder . '/' . $bannerLang->photo);
                            TmpBannerBannerType::deleteByRawSql('banner_id ='. $id .'');
                            $bannerLang->delete();
                        }
                    }

                    $d++;
                }
            }
        }
        //echo $d;die;
        if ($d > 0) {
            
            $this->flashSession->success("Xóa dữ liệu thành công");
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function updateSubdomainIdAction()
    {
        $tmpBannerBannerTypes = TmpBannerBannerType::findBySubdomainId(0);
        foreach ($tmpBannerBannerTypes as $tmpBannerBannerType) {
            if ($tmpBannerBannerType->banner) {
                $tmpBannerBannerType->subdomain_id = $tmpBannerBannerType->banner->subdomain_id;
                $tmpBannerBannerType->save();
            }
        }
    }

    private function deleteCache()
    {
        
    }
}
