<?php namespace Modules\Backend\Controllers;

use Modules\Models\Background;
use Modules\Models\Layout;
use Modules\Models\LayoutConfig;
use Modules\Models\ModuleItem;
use Modules\Models\TmpLayoutModule;
use Modules\Forms\LayoutForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Text;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Image\Adapter\GD;
use MatthiasMullie\Minify;


/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class LayoutController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Cấu hình layout';
    }

    /**
     * Default action, shows the search form
     */
    /*public function indexAction()
    {
        $list = Layout::find(
            array(
                "order" => "sort ASC, id DESC",
                "conditions" => "active =  'Y' AND deleted = 'N'"
            )
        );

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(array(
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
    }*/

    public function updateAction($id, $page = 1)
    {
//        echo $this->_get_subdomainID();die;
        $item = $this->modelsManager->createBuilder()
            ->columns(['l.*', 'lc.id AS lc_id, lc.main_color, lc.main_text_color, lc.css, lc.enable_css, lc.enable_color', 'bgr.id AS bgr_id, bgr.active AS bgr_active, bgr.color, bgr.photo AS bgr_photo, bgr.text_color AS bgr_text_color, bgr.type'])
            ->from(['l' => 'Modules\Models\Layout'])
            ->join('Modules\Models\LayoutConfig', 'lc.layout_id = l.id', 'lc')
            ->leftJoin('Modules\Models\Background', 'bgr.layout_config_id = lc.id', 'bgr')
            ->where('l.id = '.$id.' AND lc.subdomain_id = '. $this->_get_subdomainID() .'')
            ->getQuery()
            ->getSingleResult();

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $css_item = (!empty($item->css)) ? json_decode($item->css) : "";

        $form = new LayoutForm($item->l, array('edit' => true));
        $data_css = [];
        if ($this->request->isPost()) {
            
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $layout_config = LayoutConfig::findFirstById($item->lc_id);

            $background = Background::findFirstByLayoutConfigId($layout_config->id);

            $data_bgr = array();
            if (empty($background)) {
                $background = new Background();
                $data_bgr['subdomain_id'] = $this->_get_subdomainID();
                $data_bgr['layout_config_id'] = $layout_config->id;
                $data_bgr['sort'] = 1;
            } else {
                $photo = $background->photo;

                $data['css']["background_photo"] = $photo;
                if (file_exists(('files/default/' . $this->_get_subdomainFolder() . '/' . $photo))) {
                    $data_css["background_photo"] = $photo;
                }
            }

            $color = $this->request->getPost('color');

            if (!empty($color)) {
                $data_bgr['color'] = $color;
                $data_css["background_color"] = $color;
            }
            $data_bgr['text_color'] = $this->request->getPost('text_color');

            if ($this->request->hasFiles() == true) {
                $general = new General();
                $files = $this->request->getUploadedFiles();
                if (!empty($files[0]->getType())) {
                    if ($this->extFileCheck($files[0]->getType())) {
                        $fileName = basename($files[0]->getName(), "." . $files[0]->getExtension());
                        $fileName = $general->create_slug($fileName);
                        $subCode = Text::random(Text::RANDOM_ALNUM);
                        $fileFullName = $fileName . '_' . $subCode . '.' . $files[0]->getExtension();
                        $data_bgr['photo'] = $fileFullName;

                        if ($files[0]->moveTo('files/default/' . $this->_get_subdomainFolder() . '/' . $fileFullName) && isset($photo)) {
                            $data_css["background_photo"] = $fileFullName;
                            @unlink('files/default/' . $this->_get_subdomainFolder() . '/' . $photo);
                        }
                    } else {
                        $this->flash->error('Định dạng file không cho phép. Hãy upload một hình ảnh.');
                    }
                }
            }
            $data_bgr['type'] = $this->request->getPost('type');
            $data_bgr['active'] = $this->request->getPost('bgr_active');

            $data_css["background_type"] = $this->request->getPost('type');
            $data_css["background_active"] = $this->request->getPost('bgr_active');

            $background->assign($data_bgr);
            $background->save();

            //set css layout
            $data_css['bar_web_bgr'] = $this->request->getPost('bar_web_bgr');
            $data_css['bar_web_color'] = $this->request->getPost('bar_web_color');
            $data_css['txt_web_color'] = $this->request->getPost('txt_web_color');

            //set css layout
            $data_css['page_font_size'] = $this->request->getPost('page_font_size');
            $data_css['page_width'] = $this->request->getPost('page_width');
            $data_css['page_container_width'] = $this->request->getPost('page_container_width');

            // header
            $data_css['header_background'] = $this->request->getPost('header_background');
            $data_css['header_color'] = $this->request->getPost('header_color');
            $data_css['header_font_size'] = $this->request->getPost('header_font_size');
            $data_css['header_margin_top'] = $this->request->getPost('header_margin_top');
            $data_css['header_margin_bottom'] = $this->request->getPost('header_margin_bottom');
            $data_css['header_margin_left'] = $this->request->getPost('header_margin_left');
            $data_css['header_margin_right'] = $this->request->getPost('header_margin_right');
            $data_css['header_padding_top'] = $this->request->getPost('header_padding_top');
            $data_css['header_padding_bottom'] = $this->request->getPost('header_padding_bottom');
            $data_css['header_padding_left'] = $this->request->getPost('header_padding_left');
            $data_css['header_padding_right'] = $this->request->getPost('header_padding_right');

            //left
            $data_css['left_background'] = $this->request->getPost('left_background');
            $data_css['left_color'] = $this->request->getPost('left_color');
            $data_css['left_font_size'] = $this->request->getPost('left_font_size');
            $data_css['left_margin_top'] = $this->request->getPost('left_margin_top');
            $data_css['left_margin_bottom'] = $this->request->getPost('left_margin_bottom');
            $data_css['left_margin_left'] = $this->request->getPost('left_margin_left');
            $data_css['left_margin_right'] = $this->request->getPost('left_margin_right');
            $data_css['left_padding_top'] = $this->request->getPost('left_padding_top');
            $data_css['left_padding_bottom'] = $this->request->getPost('left_padding_bottom');
            $data_css['left_padding_left'] = $this->request->getPost('left_padding_left');
            $data_css['left_padding_right'] = $this->request->getPost('left_padding_right');

            // right
            $data_css['right_background'] = $this->request->getPost('right_background');
            $data_css['right_color'] = $this->request->getPost('right_color');
            $data_css['right_font_size'] = $this->request->getPost('right_font_size');
            $data_css['right_margin_top'] = $this->request->getPost('right_margin_top');
            $data_css['right_margin_bottom'] = $this->request->getPost('right_margin_bottom');
            $data_css['right_margin_left'] = $this->request->getPost('right_margin_left');
            $data_css['right_margin_right'] = $this->request->getPost('right_margin_right');
            $data_css['right_padding_top'] = $this->request->getPost('right_padding_top');
            $data_css['right_padding_bottom'] = $this->request->getPost('right_padding_bottom');
            $data_css['right_padding_left'] = $this->request->getPost('right_padding_left');
            $data_css['right_padding_right'] = $this->request->getPost('right_padding_right');

            // content
            $data_css['content_background'] = $this->request->getPost('content_background');
            $data_css['content_color'] = $this->request->getPost('content_color');
            $data_css['content_font_size'] = $this->request->getPost('content_font_size');
            $data_css['content_margin_top'] = $this->request->getPost('content_margin_top');
            $data_css['content_margin_bottom'] = $this->request->getPost('content_margin_bottom');
            $data_css['content_margin_left'] = $this->request->getPost('content_margin_left');
            $data_css['content_margin_right'] = $this->request->getPost('content_margin_right');
            $data_css['content_padding_top'] = $this->request->getPost('content_padding_top');
            $data_css['content_padding_bottom'] = $this->request->getPost('content_padding_bottom');
            $data_css['content_padding_left'] = $this->request->getPost('content_padding_left');
            $data_css['content_padding_right'] = $this->request->getPost('content_padding_right');

            // footer
            $data_css['footer_background'] = $this->request->getPost('footer_background');
            $data_css['footer_color'] = $this->request->getPost('footer_color');
            $data_css['footer_font_size'] = $this->request->getPost('footer_font_size');
            $data_css['footer_margin_top'] = $this->request->getPost('footer_margin_top');
            $data_css['footer_margin_bottom'] = $this->request->getPost('footer_margin_bottom');
            $data_css['footer_margin_left'] = $this->request->getPost('footer_margin_left');
            $data_css['footer_margin_right'] = $this->request->getPost('footer_margin_right');
            $data_css['footer_padding_top'] = $this->request->getPost('footer_padding_top');
            $data_css['footer_padding_bottom'] = $this->request->getPost('footer_padding_bottom');
            $data_css['footer_padding_left'] = $this->request->getPost('footer_padding_left');
            $data_css['footer_padding_right'] = $this->request->getPost('footer_padding_right');

            // echo '<pre>'; print_r($data_css); echo '</pre>';die;
            $enable_color = $this->request->getPost("enable_color");
            $css = json_encode($data_css);

            $pageCss = $this->getCustomCss($data_css, $enable_color);

            $file = "assets/css/pages/". $this->_get_subdomainFolder() ."/page" . $id . ".css";

            if ($this->request->getPost("enable_css") == 1) {
                file_put_contents($file, "");
                file_put_contents($file, $pageCss, FILE_APPEND | LOCK_EX);
            }


            $layout_config->assign([
                'css' => $css,
                'enable_css' => $this->request->getPost("enable_css"),
                'enable_color' => $this->request->getPost("enable_color"),
            ]);
            $layout_config->save();

            
            $this->flashSession->success("Cập nhật dữ liệu thành công");
            if (!empty($save_new)) {
                $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
            } elseif (!empty($save_close)) {
                $url = ACP_NAME . '/' . $this->_getControllerName() . '/setting';
            } else {
                $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id . '/' . $page;
            }

            $this->response->redirect($url);
        }

        $this->view->title_bar = 'Cấu hình giao diện ' . $item->l->name;
        $this->view->module_name = 'Cấu hình giao diện ' . $item->l->name;
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->item = $item;
        $this->view->css_item = $css_item;
        $this->view->page = $page;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    /**
     * Edit css
     *
     * @param int $id
     * @param int $page
     * @return Phalcon Response
     */
    public function cssAction($id, $page = 1)
    {
        $this->assets->addCss('backend/bower_components/codemirror/lib/codemirror.css');
        $this->assets->addCss('backend/bower_components/codemirror/addon/hint/show-hint.css');
        $this->assets->addJs('backend/bower_components/codemirror/lib/codemirror.js');
        $this->assets->addJs('backend/bower_components/codemirror/mode/css/css.js');
        $this->assets->addJs('backend/bower_components/codemirror/addon/hint/show-hint.js');
        $this->assets->addJs('backend/bower_components/codemirror/addon/hint/css-hint.js');
        $this->assets->addJs('backend/dist/js/codemirrorConfig.js');
        $item = $this->modelsManager->createBuilder()
            ->columns(['l.*', 'lc.main_color, lc.main_text_color, lc.css', 'bgr.id AS bgr_id, bgr.active AS bgr_active, bgr.color, bgr.photo AS bgr_photo, bgr.type'])
            ->from(['l' => 'Modules\Models\Layout'])
            ->join('Modules\Models\LayoutConfig', 'lc.layout_id = l.id', 'lc')
            ->leftJoin('Modules\Models\Background', 'bgr.layout_config_id = lc.id', 'bgr')
            ->where('l.id = '.$id.' AND lc.subdomain_id = '. $this->_get_subdomainID() .'')
            ->getQuery()
            ->getSingleResult();

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $file = "assets/css/pages/". $this->_get_subdomainFolder() ."/style" . $id . ".css";
        $minifyFile = "assets/css/pages/". $this->_get_subdomainFolder() ."/style.min.css";
        $minify = file_exists($minifyFile) ? 1 : 0;

        if ($this->request->isPost()) {
            $save_close = $this->request->getPost('save_close');
            file_put_contents($file, "");
            file_put_contents($file, $this->request->getPost("css"), FILE_APPEND | LOCK_EX);

            if ($this->request->getPost('minify')) {
                // save minified file to disk
                $minifier = new Minify\CSS($file);
                $minifier->minify($minifyFile);
            } else {
                @unlink($minifyFile);
            }
            
            $this->flashSession->success("Cập nhật dữ liệu thành công");
            if (!empty($save_close)) {
                $url = ACP_NAME;
            } else {
                $url = ACP_NAME . '/' . $this->_getControllerName() . '/css/' . $id . '/' . $page;
            }

            $this->response->redirect($url);
        }

        $cssFile = file_get_contents($file);

        $this->view->title_bar = 'Cấu hình giao diện ' . $item->l->name;
        $this->view->module_name = 'Cấu hình giao diện ' . $item->l->name;
        $breadcrumb = '<li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->cssFile = $cssFile;
        $this->view->minify = $minify;
        $this->view->item = $item;
        $this->view->page = $page;
    }

    public function moduleAction($id)
    {
        $item = $this->modelsManager->createBuilder()
            ->columns(['l.*', 'lc.main_color, lc.main_text_color, lc.css', 'bgr.id AS bgr_id, bgr.active AS bgr_active, bgr.color, bgr.photo AS bgr_photo, bgr.type'])
            ->from(['l' => 'Modules\Models\Layout'])
            ->join('Modules\Models\LayoutConfig', 'lc.layout_id = l.id', 'lc')
            ->leftJoin('Modules\Models\Background', 'bgr.layout_config_id = lc.id', 'bgr')
            ->where('l.id = '.$id.' AND lc.subdomain_id = '. $this->_get_subdomainID() .'')
            ->getQuery()
            ->getSingleResult();

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        /*$tmp_layout_module = TmpLayoutModule::find([
            'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND layout_id = '. $id .''
        ]);*/

        $tmp_layout_module = $this->modelsManager->createBuilder()
            ->columns(['tmp.*', 'mi.sort as module_sort, mi.name as module_name, mi.id as module_id, mi.parent_id', 'p.name as position_name, tmp.position_id'])
            ->from(['tmp' => 'Modules\Models\TmpLayoutModule'])
            ->join('Modules\Models\ModuleItem', 'mi.id = tmp.module_item_id', 'mi')
            ->join('Modules\Models\Position', 'p.id = tmp.position_id', 'p')
            ->where('tmp.subdomain_id = '. $this->_get_subdomainID() .' AND tmp.layout_id = '. $id .' AND mi.parent_id = 0 AND mi.active = "Y" AND mi.deleted = "N"')
            ->orderBy('p.sort ASC, tmp.sort ASC, mi.name ASC, tmp.id DESC')
            ->getQuery()
            ->execute();


        $position_module_array = array();
        foreach ($tmp_layout_module as $row) {
            $position_module_array[$row->position_name][] = [
                'id' => $row->tmp->id,
                'layout_id' => $row->tmp->layout_id,
                'active' => $row->tmp->active,
                'parent_id' => $row->parent_id,
                'module_id' => $row->module_id,
                'module_name' => $row->module_name,
                'position_name' => $row->position_name,
                'position_id' => $row->position_id,
                'module_sort' => $row->module_sort,
                'sort' => $row->tmp->sort,
            ];
        }


        if (!empty($position_module_array)) {
            $j = 0;
            foreach ($position_module_array as $row) {
                for ($i = 0; $i < count($row); $i++) {
                    $child = $this->modelsManager->createBuilder()
                        ->columns(['tmp.*', 'mi.sort as module_sort, mi.name as module_name, mi.id as module_id, mi.parent_id', 'p.name as position_name, tmp.position_id'])
                        ->from(['tmp' => 'Modules\Models\TmpLayoutModule'])
                        ->join('Modules\Models\ModuleItem', 'mi.id = tmp.module_item_id', 'mi')
                        ->join('Modules\Models\Position', 'p.id = tmp.position_id', 'p')
                        ->where('tmp.subdomain_id = '. $this->_get_subdomainID() .' AND tmp.layout_id = '. $id .' AND mi.parent_id = '. $row[$i]['module_id'] .' AND mi.active = "Y" AND mi.deleted = "N"')
                        ->orderBy('tmp.sort ASC, mi.name ASC, tmp.id DESC')
                        ->getQuery()
                        ->execute();
                    if (count($child) > 0) {
                        $child_module_array = array();
                        foreach ($child as $rowChild) {
                            $child_module_array[] = [
                                'id' => $rowChild->tmp->id,
                                'layout_id' => $rowChild->tmp->layout_id,
                                'active' => $rowChild->tmp->active,
                                'parent_id' => $rowChild->parent_id,
                                'module_id' => $rowChild->module_id,
                                'module_name' => $rowChild->module_name,
                                'position_name' => $rowChild->position_name,
                                'position_id' => $rowChild->position_id,
                                'module_sort' => $rowChild->module_sort,
                                'sort' => $rowChild->tmp->sort,
                            ];
                        }
                    }

                    if (count($child) > 0) {
                        $position_module_array[$row[$i]['position_name']][$i]['child'] = $child_module_array;
                    } else {
                        $position_module_array[$row[$i]['position_name']][$i]['child'] = '';
                    }
                }
                $j++;
            }
        }

        $this->view->title_bar = 'Cấu hình module ' . $item->l->name;
        $this->view->module_name = 'Cấu hình module ' . $item->l->name;
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->item = $item;
        $this->view->position_module_array = $position_module_array;
    }

    public function moduleCssAction()
    {
        $id = $this->request->getPost("id");
        $item = TmpLayoutModule::findFirstById($id);
        $module_item = ModuleItem::findFirstById($item->module_item_id);
        $html = "#" . $module_item->type . "_" . $id;

        $css_item = (!empty($item->css)) ? json_decode($item->css) : "";
        $data_css = [];
        if ($this->request->isPost()) {
            $data_css['background'] = $this->request->getPost('background');
            $data_css['color'] = $this->request->getPost('color');
            $data_css['font_size'] = $this->request->getPost('font_size');
            $data_css['margin_top'] = $this->request->getPost('margin_top');
            $data_css['margin_bottom'] = $this->request->getPost('margin_bottom');
            $data_css['margin_left'] = $this->request->getPost('margin_left');
            $data_css['margin_right'] = $this->request->getPost('margin_right');
            $data_css['padding_top'] = $this->request->getPost('padding_top');
            $data_css['padding_bottom'] = $this->request->getPost('padding_bottom');
            $data_css['padding_left'] = $this->request->getPost('padding_left');
            $data_css['padding_right'] = $this->request->getPost('padding_right');
        }

        $pageCss = $this->getCustomModuleCss($html, $data_css);

        $file = "assets/css/pages/". $this->_get_subdomainFolder() ."/module" . $id . ".css";

        file_put_contents($file, $pageCss, FILE_APPEND | LOCK_EX);

        $this->view->setTemplateBefore('module_css');
        $this->view->title_bar = "Cấu hình giao diện " . $module_item->name;
        $this->view->module_item = $module_item;
        $this->view->css_item = $css_item;
        $this->view->pick($this->_getControllerName() . '/module_css');
    }

    public function resetPageCssAction($layout_id, $id)
    {
        $layoutConfig = LayoutConfig::findFirst([
            "conditions" => "id = $id"
        ]);

        $layoutConfig->assign(["css" => ""]);
        $layoutConfig->save();
        $background = Background::findFirstByLayoutConfigId($id);
        if (!empty($background)) {
            if (!empty($background->photo) && is_dir("files/default/\" . $this->_get_subdomainFolder() . \"/\" . $background->photo")) {
                @unlink("files/default/" . $this->_get_subdomainFolder() . "/" . $background->photo);
            }
            $background->assign([
                "photo" => "",
                "color" => "",
                "text_color" => ""
            ]);
            $background->save();
        }

        copy('assets/source/css/page.css', 'assets/css/pages/' . $this->_get_subdomainFolder() . '/page' . $id . '.css');
        $this->flashSession->success("Khôi phục thành công");
        $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $layout_id;
        $this->response->redirect($url);
    }

    public function resetCssAction($id)
    {
        copy('assets/source/css/style' . $id . '.css', 'assets/css/pages/' . $this->_get_subdomainFolder() . '/style' . $id . '.css');
        $this->flashSession->success("Khôi phục thành công");
        $url = ACP_NAME . '/' . $this->_getControllerName() . '/css/' . $id;
        $this->response->redirect($url);
    }

    public function resetPageCssAjaxAction()
    {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $layout = $this->request->getPost('layout');
            $layoutConfig = LayoutConfig::findFirst([
                "conditions" => "id = $id"
            ]);

            $layoutConfig->assign(["css" => ""]);
            $layoutConfig->save();
            $background = Background::findFirst([
                "conditions" => "layout_config_id = $id"
            ]);

            if (!empty($background)) {
                if (!empty($background->photo) && file_exists(('files/default/' . $this->_get_subdomainFolder() . '/' . $background->photo))) {
                    @unlink("files/default/" . $this->_get_subdomainFolder() . "/" . $background->photo);
                };
                $background->assign([
                    "photo" => "",
                    "color" => "",
                    "text_color" => ""
                ]);
                $background->save();
            }
            $file = "assets/css/pages/". $this->_get_subdomainFolder() ."/page" . $layout . ".css";
            file_put_contents($file, "");
            echo 1;
        }
        $this->view->disable();
    }

    public function showAction($id, $layout_id)
    {
        $item = TmpLayoutModule::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ACP_NAME . '/' . $this->_getControllerName() . '/module/' . $layout_id;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function hideAction($id, $layout_id)
    {
        $item = TmpLayoutModule::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ACP_NAME . '/' . $this->_getControllerName() . '/module/' . $layout_id;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            
            $this->flashSession->success('Ẩn dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function updateSortAction()
    {
        $sort = $this->request->getPost('sort');
        $id = $this->request->getPost('id');
        $tmp_layout_module = TmpLayoutModule::findFirst(['conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND id = '. $id .'']);
        $tmp_layout_module->assign(['sort' => $sort]);
        $tmp_layout_module->save();
        $this->view->disable();
    }

    public function deletebackgroundphotoAction($layout_id, $id, $page = 1)
    {
        $item = Background::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $layout_id . '/' . $page . '#background' : ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $layout_id . '#background';

        $item->assign([
            'photo' => ''
        ]);

        if ($item->save()) {
            
            @unlink("files/default/" . $this->_get_subdomainFolder() . "/" . $item->photo);
            $this->flashSession->success("Xóa hình ảnh background thành công");
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $this->response->redirect($url);
    }

    protected function getCustomCss($data_css, $enable_color)
    {
        $content = "";

        $bodyCss = "";
        $barCss = "";
        $txtCss = "";
        $pageCss = "";
        $headerCss = "";
        $leftCss = "";
        $rightCss = "";
        $contentCss = "";
        $footerCss = "";

        //background css
        if (!empty($data_css['page_font_size'])) {
            $bodyCss .= "font-size: ". $data_css['page_font_size'] ."px;";
        }

        if (!empty($data_css['background_active'] == 'Y')) {
            if (!empty($data_css['background_color'])) {
                $bodyCss .= "background-color: ". $data_css['background_color'] .";";
            }

            if (!empty($data_css['background_photo'])) {
                $bodyCss .= "background-image: url(/files/default/". $this->_get_subdomainFolder() ."/". $data_css['background_photo'] .");";
            }

            if (!empty($data_css['background_type'])) {
                $bodyCss .= "background-repeat: ". $data_css['background_type'] .";";
                $bodyCss .= "background-position: top center";
            }
        }

        if (!empty($data_css['page_width'])) {
            $pageCss .= " 
                @media (min-width: 1200px){
                    #page {
                        width: ". $data_css['page_width'] ."px;
                    }
                }
            ";
        }

        if (!empty($data_css['page_container_width'])) {
            $pageCss .= " 
                @media (min-width: 1200px){
                    .container {
                        width: ". $data_css['page_container_width'] ."px;
                    }
                }
            ";
        }

        if ($enable_color == 1) {
            //bar css
            //header css
            if (!empty($data_css['bar_web_bgr'])) {
                $barCss .= "
                .bar_web_bgr {
                    background: ". $data_css['bar_web_bgr'] ." !important;
                    color: ". $data_css['bar_web_color'] ." !important;
                }
                .bar_web_bgr ul {
                    background: ". $data_css['bar_web_bgr'] ." !important;
                }
                .bar_web_bgr h1, bar_web_bgr h2, bar_web_bgr h3, .bar_web_bgr a {
                    color: ". $data_css['bar_web_color'] ." !important;
                }
                .category_bar a:before {
                    background: ". $data_css['bar_web_bgr'] ." !important;
                }
                ";
            }

            if (!empty($data_css['txt_web_color'])) {
                $txtCss .= "
                .txt_web_color {
                    color: ". $data_css['txt_web_color'] ." !important;
                }
                .txt_web_color li {
                    color: ". $data_css['txt_web_color'] . " !important;
                }
                .txt_web_color h1, .txt_web_color h2 {
                    color: ". $data_css['txt_web_color'] . " !important;
                    border-left: 3px solid ". $data_css['txt_web_color'] ." !important;
                }
                .media-calendar-date{
                    border-bottom: 1px solid ". $data_css['txt_web_color'] ." !important;
                }
                ";
            }
        }

        //header css
        if (!empty($data_css['background'])) {
            $headerCss .= "background: ". $data_css['header_background'] .";";
        }

        if (!empty($data_css['header_color'])) {
            $headerCss .= "color: ". $data_css['header_color'] .";";
        }

        if (!empty($data_css['header_font_size'])) {
            $headerCss .= "font-size: ". $data_css['header_font_size'] ."px;";
        }

        if (!empty($data_css['header_margin_top'])) {
            $headerCss .= "margin-top: ". $data_css['header_margin_top'] ."px;";
        }

        if (!empty($data_css['header_margin_bottom'])) {
            $headerCss .= "margin-bottom: ". $data_css['header_margin_bottom'] ."px;";
        }

        if (!empty($data_css['header_margin_left'])) {
            $headerCss .= "margin-left: ". $data_css['header_margin_left'] ."px;";
        }

        if (!empty($data_css['header_margin_right'])) {
            $headerCss .= "margin-right: ". $data_css['header_margin_right'] ."px;";
        }

        if (!empty($data_css['header_padding_top'])) {
            $headerCss .= "padding-top: ". $data_css['header_padding_top'] ."px;";
        }

        if (!empty($data_css['header_padding_bottom'])) {
            $headerCss .= "padding-bottom: ". $data_css['header_padding_bottom'] ."px;";
        }

        if (!empty($data_css['header_padding_left'])) {
            $headerCss .= "padding-left: ". $data_css['header_padding_left'] ."px;";
        }

        if (!empty($data_css['header_padding_right'])) {
            $headerCss .= "padding-right: ". $data_css['header_padding_right'] ."px;";
        }

        //left css
        if (!empty($data_css['left_background'])) {
            $leftCss .= "background: ". $data_css['left_background'] .";";
        }

        if (!empty($data_css['left_color'])) {
            $leftCss .= "color: ". $data_css['left_color'] .";";
        }

        if (!empty($data_css['left_font_size'])) {
            $leftCss .= "font-size: ". $data_css['left_font_size'] ."px;";
        }

        if (!empty($data_css['left_margin_top'])) {
            $leftCss .= "margin-top: ". $data_css['left_margin_top'] ."px;";
        }

        if (!empty($data_css['left_margin_bottom'])) {
            $leftCss .= "margin-bottom: ". $data_css['left_margin_bottom'] ."px;";
        }

        if (!empty($data_css['left_margin_left'])) {
            $leftCss .= "margin-left: ". $data_css['left_margin_left'] ."px;";
        }

        if (!empty($data_css['left_margin_right'])) {
            $leftCss .= "margin-right: ". $data_css['left_margin_right'] ."px;";
        }

        if (!empty($data_css['left_padding_top'])) {
            $leftCss .= "padding-top: ". $data_css['left_padding_top'] ."px;";
        }

        if (!empty($data_css['left_padding_bottom'])) {
            $leftCss .= "padding-bottom: ". $data_css['left_padding_bottom'] ."px;";
        }

        if (!empty($data_css['left_padding_left'])) {
            $leftCss .= "padding-left: ". $data_css['left_padding_left'] ."px;";
        }

        if (!empty($data_css['left_padding_right'])) {
            $leftCss .= "padding-right: ". $data_css['left_padding_right'] ."px;";
        }

        //right css
        if (!empty($data_css['right_background'])) {
            $rightCss .= "background: ". $data_css['right_background'] .";";
        }

        if (!empty($data_css['right_color'])) {
            $rightCss .= "color: ". $data_css['right_color'] .";";
        }

        if (!empty($data_css['right_font_size'])) {
            $rightCss .= "font-size: ". $data_css['right_font_size'] ."px;";
        }

        if (!empty($data_css['right_margin_top'])) {
            $rightCss .= "margin-top: ". $data_css['right_margin_top'] ."px;";
        }

        if (!empty($data_css['right_margin_bottom'])) {
            $rightCss .= "margin-bottom: ". $data_css['right_margin_bottom'] ."px;";
        }

        if (!empty($data_css['right_margin_left'])) {
            $rightCss .= "margin-left: ". $data_css['right_margin_left'] ."px;";
        }

        if (!empty($data_css['right_margin_right'])) {
            $rightCss .= "margin-right: ". $data_css['right_margin_right'] ."px;";
        }

        if (!empty($data_css['right_padding_top'])) {
            $rightCss .= "padding-top: ". $data_css['right_padding_top'] ."px;";
        }

        if (!empty($data_css['right_padding_bottom'])) {
            $rightCss .= "padding-bottom: ". $data_css['right_padding_bottom'] ."px;";
        }

        if (!empty($data_css['right_padding_left'])) {
            $rightCss .= "padding-left: ". $data_css['right_padding_left'] ."px;";
        }

        if (!empty($data_css['right_padding_right'])) {
            $rightCss .= "padding-right: ". $data_css['right_padding_right'] ."px;";
        }

        //content css
        if (!empty($data_css['content_background'])) {
            $contentCss .= "background: ". $data_css['content_background'] .";";
        }

        if (!empty($data_css['content_color'])) {
            $contentCss .= "color: ". $data_css['content_color'] .";";
        }

        if (!empty($data_css['content_font_size'])) {
            $contentCss .= "font-size: ". $data_css['content_font_size'] ."px;";
        }

        if (!empty($data_css['content_margin_top'])) {
            $contentCss .= "margin-top: ". $data_css['content_margin_top'] ."px;";
        }

        if (!empty($data_css['content_margin_bottom'])) {
            $contentCss .= "margin-bottom: ". $data_css['content_margin_bottom'] ."px;";
        }

        if (!empty($data_css['content_margin_left'])) {
            $contentCss .= "margin-left: ". $data_css['content_margin_left'] ."px;";
        }

        if (!empty($data_css['content_margin_right'])) {
            $contentCss .= "margin-right: ". $data_css['content_margin_right'] ."px;";
        }

        if (!empty($data_css['content_padding_top'])) {
            $contentCss .= "padding-top: ". $data_css['content_padding_top'] ."px;";
        }

        if (!empty($data_css['content_padding_bottom'])) {
            $contentCss .= "padding-bottom: ". $data_css['content_padding_bottom'] ."px;";
        }

        if (!empty($data_css['content_padding_left'])) {
            $contentCss .= "padding-left: ". $data_css['content_padding_left'] ."px;";
        }

        if (!empty($data_css['content_padding_right'])) {
            $contentCss .= "padding-right: ". $data_css['content_padding_right'] ."px;";
        }

        //footer css
        if (!empty($data_css['footer_background'])) {
            $footerCss .= "background: ". $data_css['footer_background'] .";";
        }

        if (!empty($data_css['footer_color'])) {
            $footerCss .= "color: ". $data_css['footer_color'] .";";
        }

        if (!empty($data_css['footer_font_size'])) {
            $footerCss .= "font-size: ". $data_css['footer_font_size'] ."px;";
        }

        if (!empty($data_css['footer_margin_top'])) {
            $footerCss .= "margin-top: ". $data_css['footer_margin_top'] ."px;";
        }

        if (!empty($data_css['footer_margin_bottom'])) {
            $footerCss .= "margin-bottom: ". $data_css['footer_margin_bottom'] ."px;";
        }

        if (!empty($data_css['footer_margin_left'])) {
            $footerCss .= "margin-left: ". $data_css['footer_margin_left'] ."px;";
        }

        if (!empty($data_css['footer_margin_right'])) {
            $footerCss .= "margin-right: ". $data_css['footer_margin_right'] ."px;";
        }

        if (!empty($data_css['footer_padding_top'])) {
            $footerCss .= "padding-top: ". $data_css['footer_padding_top'] ."px;";
        }

        if (!empty($data_css['footer_padding_bottom'])) {
            $footerCss .= "padding-bottom: ". $data_css['footer_padding_bottom'] ."px;";
        }

        if (!empty($data_css['footer_padding_left'])) {
            $footerCss .= "padding-left: ". $data_css['footer_padding_left'] ."px;";
        }

        if (!empty($data_css['footer_padding_right'])) {
            $footerCss .= "padding-right: ". $data_css['footer_padding_right'] ."px;";
        }

        $content .=
            "body { $bodyCss }
$pageCss 
$barCss 
$txtCss
header { $headerCss }
#box_left_element{ $leftCss }
#box_right_element{ $rightCss }
#content{ $contentCss }
footer { $footerCss }
";

        return $content;
    }

    protected function getCustomModuleCss($htmlId, $data_css)
    {
        $content = "";

        $css = "";

        //header css
        if (!empty($data_css['background'])) {
            $css .= "background: " . $data_css['background'] . ";";
        }

        if (!empty($data_css['color'])) {
            $css .= "color: " . $data_css['color'] . ";";
        }

        if (!empty($data_css['font_size'])) {
            $css .= "font-size: " . $data_css['font_size'] . "px;";
        }

        if (!empty($data_css['margin_top'])) {
            $css .= "margin-top: " . $data_css['margin_top'] . "px;";
        }

        if (!empty($data_css['margin_bottom'])) {
            $css .= "margin-bottom: " . $data_css['margin_bottom'] . "px;";
        }

        if (!empty($data_css['margin_left'])) {
            $css .= "margin-left: " . $data_css['margin_left'] . "px;";
        }

        if (!empty($data_css['margin_right'])) {
            $css .= "margin-right: " . $data_css['margin_right'] . "px;";
        }

        if (!empty($data_css['padding_top'])) {
            $css .= "padding-top: " . $data_css['padding_top'] . "px;";
        }

        if (!empty($data_css['padding_bottom'])) {
            $css .= "padding-bottom: " . $data_css['padding_bottom'] . "px;";
        }

        if (!empty($data_css['padding_left'])) {
            $css .= "padding-left: " . $data_css['padding_left'] . "px;";
        }

        if (!empty($data_css['padding_right'])) {
            $css .= "padding-right: " . $data_css['padding_right'] . "px;";
        }


        $content .=
            $htmlId . "{" . $css . "}";
        return $content;
    }
}
