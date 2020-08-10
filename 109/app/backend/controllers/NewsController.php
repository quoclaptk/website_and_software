<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Category;
use Modules\Models\News;
use Modules\Models\NewsCategory;
use Modules\Models\NewsMenu;
use Modules\Models\TmpNewsNewsCategory;
use Modules\Models\TmpNewsNewsMenu;
use Modules\Forms\NewsForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Text;
use Phalcon\Image\Adapter\GD;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Modules\Models\Tags;
use Modules\Models\TmpNewsTags;
use Phalcon\Security\Random;

use Phalcon\Validation;
use Phalcon\Validation\Validator\File as FileValidator;

/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class NewsController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Bài viết';
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction($type = 0)
    {
        $query = News::query();
        $conditions = "Modules\Models\News.subdomain_id = ". $this->_get_subdomainID() ." AND Modules\Models\News.language_id = 1 AND deleted = 'N'";
        $orderBy = "sort ASC, id DESC";
        if ($type != 0) {
            $conditions .= " AND type_id = $type";
        }

        if ($this->request->hasQuery('category') || $this->request->hasQuery('keyword')) {
            if ($this->request->get('category') != '' && $this->request->get('category') != 0) {
                $query->groupBy('Modules\Models\News.id');
            }

            if ($this->request->get('category') != 0 && $this->request->get('category') != '' && $this->request->get('keyword') == '') {
                $categoryId = $this->request->get('category');
                $listCategoryId = $this->news_menu_service->getCategoryTreeId($categoryId, array(), array('notActive' => true));
                $listCategoryId = count($listCategoryId) > 1 ? implode(',', $listCategoryId) : $listCategoryId[0];
                $query->join('Modules\Models\TmpNewsNewsMenu', 'tmp.news_id = Modules\Models\News.id', 'tmp');
                $conditions .= " AND news_menu_id IN ($listCategoryId)";
                $url_page = ACP_NAME . '/' . $this->_getControllerName() . '&category=' . $categoryId;
            }
            
            if ($this->request->get('category') == 0 && $this->request->get('keyword') != '') {
                $keyword = $this->request->get('keyword');
                $conditions .= " AND name LIKE '%". $keyword ."%'";
                $url_page = ACP_NAME . '/' . $this->_getControllerName() . '&keyword=' . $keyword;
            }

            if ($this->request->get('category') != 0 && $this->request->get('category') != '' && $this->request->get('keyword') != '') {
                $keyword = $this->request->get('keyword');
                $categoryId = $this->request->get('category');
                $listCategoryId = $this->news_menu_service->getCategoryTreeId($categoryId, array(), array('notActive' => true));
                $listCategoryId = count($listCategoryId) > 1 ? implode(',', $listCategoryId) : $listCategoryId[0];
                $query->join('Modules\Models\TmpNewsNewsMenu', 'tmp.news_id = Modules\Models\News.id', 'tmp');
                $conditions .= " AND news_menu_id IN ($listCategoryId)";
                $conditions .= " AND name LIKE '%". $keyword ."%'";
                $url_page = ACP_NAME . '/' . $this->_getControllerName() . '&category='. $categoryId .'&keyword=' . $keyword;
            }
        }

        $newses = $query->where($conditions)->orderBy($orderBy)->execute();
        $category = $this->recursive_news_menu(0);
        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
            "data" => $newses,
            "limit" => 20,
            "page" => $numberPage
            )
        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        if ($this->request->isPost()) {
            foreach ($paginator->getPaginate()->items as $news) {
                //save active
                $activeValue = $this->request->getPost('active_' . $news->id);
                if (!empty($activeValue)) {
                    $news->active = 'Y';
                } else {
                    $news->active = 'N';
                }

                //save hot
                $hotValue = $this->request->getPost('hot_' . $news->id);
                if (!empty($hotValue)) {
                    $news->hot = 'Y';
                } else {
                    $news->hot = 'N';
                }

                //save new
                $newValue = $this->request->getPost('new_' . $news->id);
                if (!empty($newValue)) {
                    $news->new = 'Y';
                } else {
                    $news->new = 'N';
                }

                //save introduct
                $introductValue = $this->request->getPost('introduct_' . $news->id);
                if (!empty($introductValue)) {
                    $news->introduct = 'Y';
                } else {
                    $news->introduct = 'N';
                }

                //save most view
                $mostViewValue = $this->request->getPost('most_view_' . $news->id);
                if (!empty($mostViewValue)) {
                    $news->most_view = 'Y';
                } else {
                    $news->most_view = 'N';
                }

                //save slider
                $sliderValue = $this->request->getPost('slider_' . $news->id);
                if (!empty($sliderValue)) {
                    $news->slider = 'Y';
                } else {
                    $news->slider = 'N';
                }

                //save hot effect
                $hotEffectValue = $this->request->getPost('hot_effect_' . $news->id);
                if (!empty($hotEffectValue)) {
                    $news->hot_effect = 'Y';
                } else {
                    $news->hot_effect = 'N';
                }

                //save statistical
                $statisticalValue = $this->request->getPost('statistical_' . $news->id);
                if (!empty($statisticalValue)) {
                    $news->statistical = 'Y';
                } else {
                    $news->statistical = 'N';
                }

                //save sort
                $sortValue = $this->request->getPost('sort_' . $news->id);
                if (!empty($sortValue)) {
                    $news->sort = $sortValue;
                } else {
                    $news->sort = 1;
                }

                $news->save();

                //update index id to elastic
                // $this->elastic_service->updateNews($news->id);

                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language->id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $newsLang = News::findFirst([
                                'conditions' => 'depend_id = '. $news->id .' AND language_id = '. $langId .''
                            ]);

                            if ($newsLang) {
                                //save active
                                $activeValue = $this->request->getPost('active_' . $newsLang->depend_id);
                                if (!empty($activeValue)) {
                                    $newsLang->active = 'Y';
                                } else {
                                    $newsLang->active = 'N';
                                }

                                //save hot
                                $hotValue = $this->request->getPost('hot_' . $newsLang->depend_id);
                                if (!empty($hotValue)) {
                                    $newsLang->hot = 'Y';
                                } else {
                                    $newsLang->hot = 'N';
                                }

                                //save new
                                $newValue = $this->request->getPost('new_' . $newsLang->depend_id);
                                if (!empty($newValue)) {
                                    $newsLang->new = 'Y';
                                } else {
                                    $newsLang->new = 'N';
                                }

                                //save introduct
                                $introductValue = $this->request->getPost('introduct_' . $newsLang->depend_id);
                                if (!empty($introductValue)) {
                                    $newsLang->introduct = 'Y';
                                } else {
                                    $newsLang->introduct = 'N';
                                }

                                //save most view
                                $mostViewValue = $this->request->getPost('most_view_' . $newsLang->depend_id);
                                if (!empty($mostViewValue)) {
                                    $newsLang->most_view = 'Y';
                                } else {
                                    $newsLang->most_view = 'N';
                                }

                                //save slider
                                $sliderValue = $this->request->getPost('slider_' . $newsLang->depend_id);
                                if (!empty($sliderValue)) {
                                    $newsLang->slider = 'Y';
                                } else {
                                    $newsLang->slider = 'N';
                                }

                                //save hot effect
                                $hotEffectValue = $this->request->getPost('hot_effect_' . $newsLang->depend_id);
                                if (!empty($hotEffectValue)) {
                                    $newsLang->hot_effect = 'Y';
                                } else {
                                    $newsLang->hot_effect = 'N';
                                }

                                //save statistical
                                $statisticalValue = $this->request->getPost('statistical_' . $newsLang->depend_id);
                                if (!empty($statisticalValue)) {
                                    $newsLang->statistical = 'Y';
                                } else {
                                    $newsLang->statistical = 'N';
                                }

                                //save sort
                                $sortValue = $this->request->getPost('sort_' . $newsLang->depend_id);
                                if (!empty($sortValue)) {
                                    $newsLang->sort = $sortValue;
                                } else {
                                    $newsLang->sort = 1;
                                }

                                $newsLang->save();

                                //update index id to elastic
                                // $this->elastic_service->updateNews($newsLang->id
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
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->type = $type;
        $this->view->page_current = $page_current;
    }

    public function createAction($type = 0)
    {
        $random = new Random();
        if ($this->cookies->has('row_id_news_' . $this->_get_subdomainID())) {
            // Get the cookie
            $rowIdCookie = $this->cookies->get('row_id_news_' . $this->_get_subdomainID());

            // Get the cookie's value
            $row_id = $rowIdCookie->getValue();
        } else {
            $row_id = $random->hex(10);
            $this->cookies->set(
                'row_id_news_' . $this->_get_subdomainID(),
                $row_id,
                time() + ROW_ID_COOKIE_TIME
            );
        }

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news/'. $row_id;
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
                    if ($this->cookies->has('row_id_news_' . $langCode . '_' . $this->_get_subdomainID())) {
                        // Get the cookie
                        $rowIdCookie = $this->cookies->get('row_id_news_' . $langCode . '_' . $this->_get_subdomainID());

                        // Get the cookie's value
                        $row_id_cookie = $rowIdCookie->getValue();
                    } else {
                        $row_id_cookie = $random->hex(10);
                        $this->cookies->set(
                            'row_id_news_' . $langCode . '_' . $this->_get_subdomainID(),
                            $row_id_cookie,
                            time() + ROW_ID_COOKIE_TIME
                        );
                    }

                    $row_id_lang[$langCode] = $row_id_cookie;

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news/'. $row_id_lang[$langCode];
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

        $form = new NewsForm();
        $news_category = $this->recursive(0, $type);
        $news_menu = $this->recursive_news_menu(0);

        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $folder = date('d-m-Y', time());
            $item = new News();
            $general = new General();
            $slug = $this->mainGlobal->validateUrlPageCreate($this->request->getPost('slug')) ? $this->request->getPost('slug') : $this->request->getPost('slug') . '-' . mt_rand(100, 999);

            $data = [
                'name' => $this->request->getPost('name'),
                'subdomain_id' => $this->_get_subdomainID(),
                'type_id' => $type,
                'slogan' => $this->request->getPost('slogan'),
                'summary' => $this->request->getPost('summary'),
                'content' => str_replace("public/files/", "files/", $this->request->getPost('content')),
                'slug' => $slug,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'head_content' => $this->request->getPost('head_content'),
                'body_content' => $this->request->getPost('body_content'),
                'sort' => $this->request->getPost('sort'),
                'active' => ($this->request->getPost('active') == 'Y') ? 'Y' : 'N',
                'folder' => $folder,
                'row_id' => $this->request->getPost('row_id'),
            ];

            if ($this->request->hasFiles() == true) {
                $subFolder = $this->_get_subdomainFolder();
                $subfolderUrl = 'files/news/' . $subFolder;
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    if (!empty($file->getName())) {
                        if ($file->getKey() == 'photo') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, $folder, 'news');
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

            $item->assign($data);

            if ($item->save()) {
                $id = $item->id;
                $news_category_input = $this->request->getPost('news_category');

                if (!empty($news_category_input)) {
                    $tmp_news_news_category = new TmpNewsNewsCategory();
                    foreach ($news_category_input as $t) {
                        $tmp_news_news_category->assign(array(
                          'subdomain_id' => $this->_get_subdomainID(),
                          'news_id' => $id,
                          'news_category_id' => $t
                       ));

                        $tmp_news_news_category->save();
                    }
                }

                //news menu
                $news_menu_input = $this->request->getPost('news_menu');

                if (!empty($news_menu_input)) {
                    $tmp_news_news_menu = new TmpNewsNewsMenu();
                    foreach ($news_menu_input as $t) {
                        $tmp_news_news_menu->assign(array(
                          'subdomain_id' => $this->_get_subdomainID(),
                          'news_id' => $id,
                          'news_menu_id' => $t
                       ));

                        $tmp_news_news_menu->save();
                    }
                }

                //save other language
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        $data = [];
                        if ($langCode != 'vi') {
                            $slug = $this->mainGlobal->validateUrlPageCreate($this->request->getPost('slug_' . $langCode), $langId) ? $this->request->getPost('slug_' . $langCode) : $this->request->getPost('slug_' . $langCode) . '-' . mt_rand(100, 999);

                            $itemVi = $item->toArray();
                            $itemVi['language_id'] = $langId;
                            $itemVi['depend_id'] = $id;
                            $itemVi['name'] = $this->request->getPost('name_' . $langCode);
                            $itemVi['slogan'] = $this->request->getPost('slogan_' . $langCode);
                            $itemVi['slug'] = $slug;
                            $itemVi['title'] = $this->request->getPost('title_' . $langCode);
                            $itemVi['keywords'] = $this->request->getPost('keywords_' . $langCode);
                            $itemVi['description'] = $this->request->getPost('description_' . $langCode);
                            $itemVi['summary'] = $this->request->getPost('summary_' . $langCode);
                            $itemVi['content'] = str_replace("public/files/", "files/", $this->request->getPost('content_' . $langCode));
                            unset($itemVi['id']);

                            $newsLang = new News();
                            $newsLang->assign($itemVi);
                            if ($newsLang->save()) {
                                if (!empty($news_menu_input)) {
                                    $tmp_news_news_menu = new TmpNewsNewsMenu();
                                    foreach ($news_menu_input as $t) {
                                        $newsMenu = NewsMenu::findFirst([
                                            'conditions' => 'depend_id = '. $t .' AND language_id = '. $langId .''
                                        ]);
                                        if ($newsMenu) {
                                            $tmp_news_news_menu->assign(array(
                                              'subdomain_id' => $this->_get_subdomainID(),
                                              'news_id' => $newsLang->id,
                                              'news_menu_id' => $newsMenu->id
                                            ));
                                        }

                                        $tmp_news_news_menu->save();
                                    }
                                }

                                //index to elastic
                                // $this->elastic_service->insertNews($newsLang->id);
                            }
                        }
                    }
                }
                
                $this->cookies->get('row_id_news_' . $this->_get_subdomainID())->delete();
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langCode = $tmp->language->code;
                    $this->cookies->get('row_id_news_' . $langCode . '_' . $this->_get_subdomainID())->delete();
                }
                $this->flashSession->success($this->_message["add"]);

                //index to elastic
                // $this->elastic_service->insertNews($id);

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create/' . $type;
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $type . '/' . $id;
                }

                $this->response->redirect($url);
            } else {
                foreach ($item->getMessages() as $message) {
                    echo $message;
                }
            }
        }

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->news_category = $news_category;
        $this->view->news_menu = $news_menu;
        $this->view->form = $form;
        $this->view->type = $type;
        $this->view->row_id = $row_id;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    /**
     * Saves the user from the 'edit' action
     *
     */
    public function updateAction($type = 0, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }

        $tmp_news_news_category = TmpNewsNewsCategory::find(
            array(
                'conditions' => 'news_id='. $id .'',
            )
        );
        $tmp_news_news_menu = TmpNewsNewsMenu::find(
            array(
                'conditions' => 'news_id='. $id .'',
            )
        );
        $tmp_news_category = array();
        if (count($tmp_news_news_category) > 0) {
            foreach ($tmp_news_news_category as $tmp) {
                $tmp_news_category[] = $tmp->news_category_id;
            }
        }
        $tmp_news_menu = array();
        if (count($tmp_news_news_menu) > 0) {
            foreach ($tmp_news_news_menu as $tmp) {
                $tmp_news_menu[] = $tmp->news_menu_id;
            }
        }

        if ($type != 0) {
            $news_category = $this->recursive(0, $type);
        } else {
            $news_category = '';
        }
        $news_menu = $this->recursive_news_menu(0);

        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy bài viết");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (count($this->_tmpSubdomainLanguages) > 0) {
            $itemFormData = $item->toArray();
            $row_id_lang = [];
            $imgUploadLangPaths = [];
            $itemLangData = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $itemLang = News::findFirst(array(
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
                        if ($this->cookies->has('row_id_news_' . $langCode . '_' . $this->_get_subdomainID())) {
                            // Get the cookie
                            $rowIdCookie = $this->cookies->get('row_id_news_' . $langCode . '_' . $this->_get_subdomainID());

                            // Get the cookie's value
                            $row_id_cookie = $rowIdCookie->getValue();
                        } else {
                            $row_id_cookie = $random->hex(10);
                            $this->cookies->set(
                                'row_id_news_' . $langCode . '_' . $this->_get_subdomainID(),
                                $row_id_cookie,
                                time() + ROW_ID_COOKIE_TIME
                            );
                        }

                        $row_id_lang[$langCode] = $row_id_cookie;
                    }

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news/'. $row_id_lang[$langCode];
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

        $photo = $item->photo;
        $folder = (!empty($item->folder)) ? $item->folder : date('Y-m-d');
        $row_id = ($item->row_id != 0) ? $item->row_id : $item->id;

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news/'. $row_id;
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

        $form = new NewsForm($itemFormData, array('edit' => true));
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();
            $slug = $this->mainGlobal->validateUrlPageUpdate($id, $this->request->getPost('slug'), 'news') ? $this->request->getPost('slug') : $this->request->getPost('slug') . '-' . mt_rand(100, 999);

            $data = [
                'name' => $this->request->getPost('name'),
                'slogan' => $this->request->getPost('slogan'),
                'summary' => $this->request->getPost('summary'),
                'content' => str_replace("public/files/", "files/", $this->request->getPost('content')),
                'slug' => $slug,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'sort' => $this->request->getPost('sort'),
                'active' => ($this->request->getPost('active') == 'Y') ? 'Y' : 'N',
            ];
            
            if ($this->request->hasFiles() == true) {
                $subFolder = $this->_get_subdomainFolder();
                $subfolderUrl = 'files/news/' . $subFolder;
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    $ext = $file->getType();
                    if (!empty($file->getName())) {
                        if (!empty($file->getName())) {
                            if ($file->getKey() == 'photo') {
                                $dataUpload = $this->upload_service->upload($file, $subfolderUrl, $folder, 'news');
                                if (!empty($dataUpload['file_name'])) {
                                    $data['photo'] = $dataUpload['file_name'];

                                    // delete folder thumb
                                    $this->general->deleteDirectory("files/news/" . $subFolder . "/" . $folder . "/thumb/" . $photo);

                                    @unlink("files/news/" . $subFolder . "/" . $folder . "/" . $photo);
                                    @unlink("files/news/" . $subFolder . "/thumb/320x320/" . $folder . '/' . $photo);
                                    @unlink("files/news/" . $subFolder . "/thumb/120x120/" . $folder . '/' . $photo);
                                } else {
                                    $this->flashSession->error( $dataUpload['message']);
                                    return $this->response->redirect($this->router->getRewriteUri());
                                }
                            }
                        }
                    }
                }
            }

            $item->assign($data);
            if ($item->save()) {
                if ($this->request->hasPost('news_category')) {
                    TmpNewsNewsCategory::deleteByRawSql('news_id ='. $id .'');
                    foreach ($this->request->getPost('news_category') as $t) {
                        $tmp_n_nc = new TmpNewsNewsCategory();
                        $tmp_n_nc->assign(array(
                        'subdomain_id' => $this->_get_subdomainID(),
                           'news_id' => $id,
                           'news_category_id' => $t
                        ));
                        $tmp_n_nc->save();
                    }
                }

                if ($this->request->hasPost('news_menu')) {
                    TmpNewsNewsMenu::deleteByRawSql('news_id ='. $id .'');
                    foreach ($this->request->getPost('news_menu') as $t) {
                        $tmp = new TmpNewsNewsMenu();
                        $tmp->assign(array(
                            'subdomain_id' => $this->_get_subdomainID(),
                            'news_id' => $id,
                            'news_menu_id' => $t
                        ));
                        $tmp->save();
                    }
                }

                //save other language
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $data = [];
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $slug = $this->mainGlobal->validateUrlPageUpdate($id, $this->request->getPost('slug_' . $langCode), 'news', $langId) ? $this->request->getPost('slug_' . $langCode) : $this->request->getPost('slug_' . $langCode) . '-' . mt_rand(100, 999);

                            $itemVi = $item->toArray();
                            $itemVi['language_id'] = $langId;
                            $itemVi['depend_id'] = $id;
                            $itemVi['name'] = $this->request->getPost('name_' . $langCode);
                            $itemVi['slug'] = $slug;
                            $itemVi['slogan'] = $this->request->getPost('slogan_' . $langCode);
                            $itemVi['title'] = $this->request->getPost('title_' . $langCode);
                            $itemVi['keywords'] = $this->request->getPost('keywords_' . $langCode);
                            $itemVi['description'] = $this->request->getPost('description_' . $langCode);
                            $itemVi['summary'] = $this->request->getPost('summary_' . $langCode);
                            $itemVi['content'] = str_replace("public/files/", "files/", $this->request->getPost('content_' . $langCode));
                            unset($itemVi['id']);

                            $newsLang = News::findFirst(array(
                                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .' AND depend_id = '. $id .''
                            ));
                            if (!$newsLang) {
                                $newsLang = new News();
                            }

                            $newsLang->assign($itemVi);
                            if ($newsLang->save()) {
                                if ($this->request->hasPost('news_menu')) {
                                    TmpNewsNewsMenu::deleteByRawSql('news_id ='. $newsLang->id .'');
                                    $news_menu_input = $this->request->getPost('news_menu');
                                    foreach ($news_menu_input as $t) {
                                        $newsMenu = NewsMenu::findFirst([
                                            'conditions' => 'depend_id = '. $t .' AND language_id = '. $langId .''
                                        ]);
                                       
                                        if ($newsMenu) {
                                            $tmp_news_news_menu = new TmpNewsNewsMenu();
                                            $tmp_news_news_menu->assign(array(
                                              'subdomain_id' => $this->_get_subdomainID(),
                                              'news_id' => $newsLang->id,
                                              'news_menu_id' => $newsMenu->id
                                            ));

                                            $tmp_news_news_menu->save();
                                        }
                                    }
                                }

                                //update index id to elastic
                                // $this->elastic_service->updateNews($newsLang->id
                            }
                        }
                    }
                }

                //update index id to elastic
                // $this->elastic_service->updateNews($id
                
                $this->flashSession->success($this->_message["edit"]);
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create/' . $type;
                } elseif (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $type . '/' . $id . '/' . $page;
                }

                
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langCode = $tmp->language->code;
                    $this->cookies->get('row_id_news_' . $langCode . '_' . $this->_get_subdomainID())->delete();
                }
                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->item = $item;
        $this->view->news_category = $news_category;
        $this->view->news_menu = $news_menu;
        $this->view->tmp_news_category = $tmp_news_category;
        $this->view->tmp_news_menu = $tmp_news_menu;
        $this->view->form = $form;
        $this->view->type = $type;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->row_id = $row_id;
        $this->view->pick($this->_getControllerName() . '/form');
    }


    public function showAction($type = 0, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'Y',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction($type = 0, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'N',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showmultyAction($type = 0, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = News::findFirst([
                "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'Y'
                ]);
                $item->save();
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["show"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function hidemultyAction($type, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = News::findFirst([
                "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'N'
                ]);
                $item->save();
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function showhotAction($type = 0, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'hot' => 'Y',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hidehotAction($type, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }

        $item->assign([
            'hot' => 'N',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Ẩn nổi bật thành công!');
            $this->response->redirect($url);
        }
    }

    public function showNewAction($type = 0, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'new' => 'Y',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideNewAction($type, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'new' => 'N',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showMostViewAction($type = 0, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'most_view' => 'Y',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideMostViewAction($type, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'most_view' => 'N',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showIntroductAction($type = 0, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'introduct' => 'Y',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideIntroductAction($type, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'introduct' => 'N',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function deleteAction($type, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign([
            'deleted' => 'Y'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        $this->response->redirect($url);
    }

    public function deletemultyAction($type = 0, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = News::findFirst([
                "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
            ]);
            if ($item) {
                $item->assign([
                    'deleted' => 'Y'
                ]);
                $item->save();
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if ($d > 0) {
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    /**
     * Deletes a News
     *
     * @param $id
     */
    public function _deleteAction($type = 0, $id, $page = 1)
    {
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        $item = News::findFirst([
            "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy bài viết");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $subFolder = $this->_get_subdomainFolder();
        $photo = $item->photo;
        $folder = $item->folder;
        $general = new General();
        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            // delete elastic search id id
            // $this->elastic_service->deleteNews($id);
            //delete other lang item
            $dependNewss = News::findByDependId($id);
            if (count($dependNewss) > 0) {
                foreach ($dependNewss as $dependNews) {
                    if ($dependNews->delete()) {
                        // delete elastic search id id
                        // $this->elastic_service->deleteNews($dependNews->id);
                        
                        TmpNewsNewsCategory::deleteByRawSql('news_id ='. $dependNews->id .'');
                        TmpNewsNewsMenu::deleteByRawSql('news_id ='. $dependNews->id .'');
                        if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news/" . $dependNews->row_id)) {
                            $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news/" . $dependNews->row_id);
                        }
                    }
                }
            }

            TmpNewsNewsCategory::deleteByRawSql('news_id ='. $id .'');
            TmpNewsNewsMenu::deleteByRawSql('news_id ='. $id .'');
            
            // delete folder thumb
            $this->general->deleteDirectory("files/news/" . $subFolder . "/" . $folder . "/thumb/" . $photo);

            @unlink("files/news/" . $subFolder . "/" . $folder . "/" . $photo);
            @unlink("files/news/" . $subFolder . "/thumb/320x320/" . $folder . '/' . $photo);
            @unlink("files/news/" . $subFolder . "/thumb/120x120/" . $folder . '/' . $photo);
            if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news/" . $item->row_id)) {
                $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news/" . $item->row_id);
            }
            $this->flashSession->success($this->_message["delete"]);
            $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
            $this->response->redirect($url);
        }
    }

    public function _deletemultyAction($type = 0, $page = 1)
    {
        $general = new General();
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        if ($type != 0) {
            $conditions = "type_id = $type AND ";
        } else {
            $conditions = "";
        }
        foreach ($listid as $id) {
            $item = News::findFirst([
                "conditions" => $conditions . "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\News.id = $id AND language_id = 1"
            ]);
            if ($item) {
                if ($item->delete()) {
                    // delete elastic search id id
                    // $this->elastic_service->deleteNews($id);

                    //delete other lang item
                    $dependNewss = News::findByDependId($id);
                    if (count($dependNewss) > 0) {
                        foreach ($dependNewss as $dependNews) {
                            if ($dependNews->delete()) {
                                // delete elastic search id id
                                // $this->elastic_service->deleteNews($dependNews->id);

                                TmpNewsNewsCategory::deleteByRawSql('news_id ='. $dependNews->id .'');
                                TmpNewsNewsMenu::deleteByRawSql('news_id ='. $dependNews->id .'');
                                if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news/" . $dependNews->row_id)) {
                                    $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news/" . $dependNews->row_id);
                                }
                            }
                        }
                    }

                    TmpNewsNewsCategory::deleteByRawSql('news_id ='. $id .'');
                    TmpNewsNewsMenu::deleteByRawSql('news_id ='. $id .'');
                    $subFolder = $this->_get_subdomainFolder();
                    $photo = $item->photo;
                    $folder = $item->folder;

                    // delete folder thumb
                    $this->general->deleteDirectory("files/news/" . $subFolder . "/" . $folder . "/thumb/" . $photo);

                    @unlink("files/news/" . $subFolder . "/" . $folder . "/" . $photo);
                    @unlink("files/news/" . $subFolder . "/thumb/320x320/" . $folder . '/' . $photo);
                    @unlink("files/news/" . $subFolder . "/thumb/120x120/" . $folder . '/' . $photo);
                    if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news/" . $item->row_id)) {
                        $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news/" . $item->row_id);
                    }
                }
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if ($d > 0) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function recursive($parent_id = 0, $type = 0, $space = "", $trees = array())
    {
        if (!$trees) {
            $trees = [];
        }
        $identity = $this->auth->getIdentity();
        $result = NewsCategory::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "parent_id = ". $parent_id ." AND type_id = $type AND Modules\Models\NewsCategory.subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N' AND active = 'Y'"
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
                $trees   = $this->recursive($row->id, $langId, $type, $space . '|---', $trees);
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

    public function recursive_news_menu($parent_id = 0, $langId = 1, $space = "", $trees = array())
    {
        if (!$trees) {
            $trees = [];
        }
        $identity = $this->auth->getIdentity();
        $result = NewsMenu::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "parent_id = $parent_id AND Modules\Models\NewsMenu.subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND deleted = 'N' AND active = 'Y'"
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
                $trees   = $this->recursive_news_menu($row->id, $langId, $space . '|---', $trees);
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

    private function deleteCache()
    {
        
        /*$this->cache_service->deleteCacheSubdomain($this->_get_subdomainID(), ['type' => 'news']);
        $this->cache_service->deleteCacheSubdomain($this->_get_subdomainID(), ['type' => 'newsType']);
        $this->cache_service->deleteCacheSubdomain($this->_get_subdomainID(), ['type' => 'newsCategory']);
        $this->cache_service->deleteCacheSubdomain($this->_get_subdomainID(), ['type' => 'newsMenu']);
        $this->cache_service->deleteCacheSubdomain($this->_get_subdomainID(), ['type' => 'menuItem']);*/
    }
}
