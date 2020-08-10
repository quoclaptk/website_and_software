<?php namespace Modules\Backend\Controllers;

use Modules\Models\Clip;
use Modules\Forms\ClipForm;
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
use Phalcon\Security\Random;

/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class ClipController extends BaseController
{
    public function onConstruct()
    {
        error_reporting(0);
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Video';
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction()
    {
        $list = Clip::find(
            array(
                "order" => "sort ASC, id DESC",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND deleted = 'N'"
            )
        );

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
                "data" => $list,
                "limit" => 10,
                "page" => $numberPage
            )

        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }


    /**
     * Creates a User
     *
     */
    public function createAction()
    {
        $random = new Random();
        if ($this->cookies->has('row_id_clip_' . $this->_get_subdomainID())) {
            // Get the cookie
            $rowIdCookie = $this->cookies->get('row_id_clip_' . $this->_get_subdomainID());

            // Get the cookie's value
            $row_id = $rowIdCookie->getValue();
        } else {
            $row_id = $random->hex(10);
            $this->cookies->set(
                'row_id_clip_' . $this->_get_subdomainID(),
                $row_id,
                time() + ROW_ID_COOKIE_TIME
            );
        }

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/clip/'. $row_id;
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
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $random = new Random();
                    if ($this->cookies->has('row_id_clip_' . $langCode . '_' . $this->_get_subdomainID())) {
                        // Get the cookie
                        $rowIdCookie = $this->cookies->get('row_id_clip_' . $langCode . '_' . $this->_get_subdomainID());

                        // Get the cookie's value
                        $row_id_cookie = $rowIdCookie->getValue();
                    } else {
                        $row_id_cookie = $random->hex(10);
                        $this->cookies->set(
                            'row_id_clip_' . $langCode . '_' . $this->_get_subdomainID(),
                            $row_id_cookie,
                            time() + ROW_ID_COOKIE_TIME
                        );
                    }

                    $row_id_lang[$langCode] = $row_id_cookie;

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/clip/'. $row_id_lang[$langCode];
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
        }

        $form = new ClipForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            // echo '<pre>'; print_r($this->request->getPost()_; echo '</pre>';die();
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $date = date('d-m-Y', time());
            $item = new Clip();
            $general = new General();
            $slug = $this->mainGlobal->validateUrlPageCreate($this->request->getPost('slug')) ? $this->request->getPost('slug') : $this->request->getPost('slug') . '-' . mt_rand(100, 999);
            $data = array(
                'subdomain_id' => $this->_get_subdomainID(),
                'row_id' => $row_id,
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'code' => $this->request->getPost('code'),
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'summary' => str_replace("public/files/", "files/", $this->request->getPost('summary')),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
                'folder' => $date,
            );

            $img_youtube = 'http://img.youtube.com/vi/'. $this->request->getPost('code') .'/0.jpg';
            $sub_folder = $this->_get_subdomainFolder();

            if (!is_dir("files/youtube/" . $sub_folder . "/" . $date)) {
                mkdir("files/youtube/" . $sub_folder . "/" . $date, 0777);
            }

            $subCode = TextRandom::random(TextRandom::RANDOM_ALNUM);
            if ($general->upload_file_content('files/youtube/' . $sub_folder .  '/' . $date . '/', $img_youtube, $subCode)) {
                $image_name = $subCode . '.jpg';

                /*if (!is_dir("files/youtube/" . $sub_folder . "/thumb/320x240")) {
                    mkdir("files/youtube/" . $sub_folder . "/thumb/320x240", 0777);
                }
                if (!is_dir("files/youtube/" . $sub_folder . "/thumb/240x180")) {
                    mkdir("files/youtube/" . $sub_folder . "/thumb/240x180", 0777);
                }

            	if (!is_dir("files/youtube/" . $sub_folder . "/thumb/320x240/" . $date)) {
                    mkdir("files/youtube/" . $sub_folder . "/thumb/320x240/" . $date, 0777);
                }
                if (!is_dir("files/youtube/" . $sub_folder . "/thumb/240x180/" . $date)) {
                    mkdir("files/youtube/" . $sub_folder . "/thumb/240x180/" . $date, 0777);
                }

                $fileNameFolder = "files/youtube/" . $sub_folder .  "/" . $date . "/" . $image_name;
                $image = new GD($fileNameFolder);

                $image->resize(320, 240);
                $image->save("files/youtube/" . $sub_folder . "/thumb/320x240/" . $date . "/" . $image_name);
                $image->resize(240, 180);
                $image->save("files/youtube/" . $sub_folder . "/thumb/240x180/" . $date . "/" . $image_name);*/

                $data['photo'] = $image_name;
            } else {
                $this->flash->error('Mã nhúng youtube không tồn tại. Vui lòng kiểm tra lại!');
            }

            $item->assign($data);

            if ($item->save()) {
                $id = $item->id;
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $data = [];
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $slug = $this->mainGlobal->validateUrlPageCreate($this->request->getPost('slug_' . $langCode), $langId) ? $this->request->getPost('slug_' . $langCode) : $this->request->getPost('slug_' . $langCode) . '-' . mt_rand(100, 999);
                            $data = array(
                                'subdomain_id' => $this->_get_subdomainID(),
                                'language_id' => $langId,
                                'depend_id' => $id,
                                'row_id' => $row_id_lang[$langCode],
                                'name' => $this->request->getPost('name_' . $langCode),
                                'slug' => $slug,
                                'code' => $this->request->getPost('code'),
                                'title' => $this->request->getPost('title_' . $langCode),
                                'keywords' => $this->request->getPost('keywords_' . $langCode),
                                'description' => $this->request->getPost('description_' . $langCode),
                                'summary' => str_replace("public/files/", "files/", $this->request->getPost('summary_' . $langCode)),
                                'sort' => $this->request->getPost('sort'),
                                'active' => $this->request->getPost('active'),
                                'folder' => $date,
                                'photo' => $item->photo
                            );

                            $clipLang = new Clip();
                            $clipLang->assign($data);
                            if (!$clipLang->save()) {
                                foreach ($clipLang->getMessages() as $message) {
                                    $this->flashSession->error($message);
                                }
                            }
                        }
                    }
                }

                
                $this->cookies->get('row_id_clip_' . $this->_get_subdomainID())->delete();
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langCode = $tmp->language->code;
                    $this->cookies->get('row_id_clip_' . $langCode . '_' . $this->_get_subdomainID())->delete();
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
            }
            foreach ($item->getMessages() as $message) {
                $this->flashSession->error($message);
            }
        }

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->row_id = $row_id;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction($id, $page = 1)
    {
        $item = Clip::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND language_id = 1"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $photo = $item->photo;
        $folder = (!empty($item->folder)) ? $item->folder : date('Y-m-d');
        $code = $item->code;

        if (count($this->_tmpSubdomainLanguages) > 0) {
            $itemFormData = $item->toArray();
            $row_id_lang = [];
            $imgUploadLangPaths = [];
            $itemLangData = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $itemLang = Clip::findFirst(array(
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $tmp->language_id .' AND depend_id = '. $id .''
                    ));

                    if ($itemLang) {
                        $row_id_lang[$langCode] = $itemLang->row_id;
                        $itemLangData[$langCode] = $itemLang;
                        $itemLang = $itemLang->toArray();
                        $itemLangKeys = array_keys($itemLang);
                        foreach ($itemLangKeys as $itemLangKey) {
                            $itemFormData[$itemLangKey . '_' . $langCode] = $itemLang[$itemLangKey];
                        }
                    } else {
                        $random = new Random();
                        if ($this->cookies->has('row_id_clip_' . $langCode . '_' . $this->_get_subdomainID())) {
                            // Get the cookie
                            $rowIdCookie = $this->cookies->get('row_id_clip_' . $langCode . '_' . $this->_get_subdomainID());

                            // Get the cookie's value
                            $row_id_cookie = $rowIdCookie->getValue();
                        } else {
                            $row_id_cookie = $random->hex(10);
                            $this->cookies->set(
                                'row_id_clip_' . $langCode . '_' . $this->_get_subdomainID(),
                                $row_id_cookie,
                                time() + ROW_ID_COOKIE_TIME
                            );
                        }

                        $row_id_lang[$langCode] = $row_id_cookie;
                    }

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/clip/'. $row_id_lang[$langCode];
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
        } else {
            $itemFormData = $item;
        }

        $form = new ClipForm($itemFormData, array('edit' => true));
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();
            $slug = $this->mainGlobal->validateUrlPageUpdate($id, $this->request->getPost('slug'), 'clip') ? $this->request->getPost('slug') : $this->request->getPost('slug') . '-' . mt_rand(100, 999);

            $data = array(
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'code' => $this->request->getPost('code'),
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'summary' => str_replace("public/files/", "files/", $this->request->getPost('summary')),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
            );

            if ($this->request->getPost('code') != $code) {
                $img_youtube = '//img.youtube.com/vi/'. $this->request->getPost('code') .'/0.jpg';
                $sub_folder = $this->_get_subdomainFolder();

                if (!is_dir("files/youtube/" . $sub_folder . "/" . $folder)) {
                    mkdir("files/youtube/" . $sub_folder . "/" . $folder, 0777);
                }
                $subCode = TextRandom::random(TextRandom::RANDOM_ALNUM);
                if ($general->upload_file_content('files/youtube/' . $folder . '/', $img_youtube, $subCode)) {
                    $image_name = $subCode . '.jpg';

                    /*if (!is_dir("files/youtube/" . $sub_folder . "/thumb/320x240")) {
                        mkdir("files/youtube/" . $sub_folder . "/thumb/320x240", 0777);
                    }
                    if (!is_dir("files/youtube/" . $sub_folder . "/thumb/240x180")) {
                        mkdir("files/youtube/" . $sub_folder . "/thumb/240x180", 0777);
                    }

                    if (!is_dir("files/youtube/" . $sub_folder . "/thumb/320x240/" . $folder)) {
                        mkdir("files/youtube/" . $sub_folder . "/thumb/320x240/" . $folder, 0777);
                    }
                    if (!is_dir("files/youtube/" . $sub_folder . "/thumb/240x180/" . $folder)) {
                        mkdir("files/youtube/" . $sub_folder . "/thumb/240x180/" . $folder, 0777);
                    }

                    $fileNameFolder = "files/youtube/" . $sub_folder .  "/" . $folder . "/" . $image_name;
                    $image = new GD($fileNameFolder);
                    $image->resize(320, 240);
                    $image->save("files/youtube/" . $sub_folder . "/thumb/320x240/" . $folder . "/" . $image_name);
                    $image->resize(240, 180);
                    $image->save("files/youtube/" . $sub_folder . "/thumb/240x180/" . $folder . "/" . $image_name);*/

                    $data['photo'] = $image_name;
                    @unlink("files/youtube/" . $sub_folder . "/" . $folder . "/" . $photo);
                    @unlink("files/youtube/" . $sub_folder . "/thumb/320x240/" . $folder . '/' . $photo);
                    @unlink("files/youtube/" . $sub_folder . "/thumb/240x180/" . $folder . '/' . $photo);
                }
            }


            $item->assign($data);

            if ($item->save()) {
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $data = [];
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $slug = $this->mainGlobal->validateUrlPageUpdate($id, $this->request->getPost('slug_' . $langCode), 'clip', $langId) ? $this->request->getPost('slug_' . $langCode) : $this->request->getPost('slug_' . $langCode) . '-' . mt_rand(100, 999);
                            $data = array(
                                'subdomain_id' => $this->_get_subdomainID(),
                                'language_id' => $langId,
                                'depend_id' => $id,
                                'row_id' => $row_id_lang[$langCode],
                                'name' => $this->request->getPost('name_' . $langCode),
                                'slug' => $slug,
                                'code' => $this->request->getPost('code'),
                                'title' => $this->request->getPost('title_' . $langCode),
                                'keywords' => $this->request->getPost('keywords_' . $langCode),
                                'description' => $this->request->getPost('description_' . $langCode),
                                'summary' => str_replace("public/files/", "files/", $this->request->getPost('summary_' . $langCode)),
                                'sort' => $this->request->getPost('sort'),
                                'active' => $this->request->getPost('active'),
                                'folder' => $date,
                                'photo' => $item->photo
                            );

                            $clipLang = Clip::findFirst(array(
                                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .' AND depend_id = '. $id .''
                            ));
                            if (!$clipLang) {
                                $clipLang = new Clip();
                            }

                            $clipLang->assign($data);
                            if (!$clipLang->save()) {
                                foreach ($clipLang->getMessages() as $message) {
                                    $this->flashSession->error($message);
                                }
                            }
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


    public function showAction($id, $page = 1)
    {
        $item = Clip::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
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
                        $clipLangLang = Clip::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($clipLangLang) {
                            $clipLangLang->active = 'Y';
                            $clipLangLang->save();
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction($id, $page = 1)
    {
        $item = Clip::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
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
                        $clipLangLang = Clip::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($clipLangLang) {
                            $clipLangLang->active = 'N';
                            $clipLangLang->save();
                        }
                    }
                }
            }

            
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
            $item = Clip::findFirstById($id);
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
                                $clipLangLang = Clip::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                ]);
                                if ($clipLangLang) {
                                    $clipLangLang->active = 'Y';
                                    $clipLangLang->save();
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

    public function hidemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Clip::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
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
                                $clipLangLang = Clip::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                ]);
                                if ($clipLangLang) {
                                    $clipLangLang->active = 'N';
                                    $clipLangLang->save();
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

    public function deleteAction($id, $page = 1)
    {
        $item = Clip::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
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

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Clip::findFirst([
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
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if ($d > 0) {
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction($id, $page = 1)
    {
        $item = Clip::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        $photo = $item->photo;
        $folder = $item->folder;

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $sub_folder = $this->_get_subdomainFolder();
        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            //delete other lang
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langId = $tmp->language->id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $clipLang = Clip::findFirst([
                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                        ]);
                        if ($clipLang) {
                            $clipLang->delete();
                        }
                    }
                }
            }

            @unlink("files/youtube/" . $sub_folder . "/" . $folder . "/" . $photo);
            @unlink("files/youtube/" . $sub_folder . "/thumb/320x240/" . $folder . '/' . $photo);
            @unlink("files/youtube/" . $sub_folder . "/thumb/240x180/" . $folder . '/' . $photo);
            $this->flashSession->success($this->_message["delete"]);
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
            $item = $item = Clip::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            $photo = $item->photo;
            $folder = $item->folder;
            if ($item) {
                if ($item->delete()) {
                    if (count($this->_tmpSubdomainLanguages) > 0) {
                        foreach ($this->_tmpSubdomainLanguages as $tmp) {
                            $langId = $tmp->language->id;
                            $langCode = $tmp->language->code;
                            if ($langCode != 'vi') {
                                $clipLang = Clip::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                ]);
                                if ($clipLang) {
                                    $clipLang->delete();
                                }
                            }
                        }
                    }

                    @unlink("files/youtube/" . $sub_folder . "/" . $folder . "/" . $photo);
                    @unlink("files/youtube/" . $sub_folder . "/thumb/320x240/" . $folder . '/' . $photo);
                    @unlink("files/youtube/" . $sub_folder . "/thumb/240x180/" . $folder . '/' . $photo);
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

    private function deleteCache()
    {
        
    }
}
