<?php namespace Modules\Backend\Controllers;

use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Text;
use Phalcon\Image\Adapter\GD;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;

use Modules\Models\BannerHtml;
use Modules\Models\Setting;
use Modules\Forms\SettingForm;

class BannerHtmlController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Banner';
        // $this->view->setTemplateBefore('private');
    }

    public function indexAction()
    {
        $this->assets->addJs('backend/dist/js/ajaxupload.js?time=' . time());
        $setting = Setting::findFirstBySubdomainId($this->_get_subdomainID());

        $banner_1 = $setting->banner_1;

        $form = new SettingForm($setting);
        $general = new General();

        if ($this->request->isPost()) {
            
            $folder = $this->_get_subdomainFolder();

            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                if (!empty($files[0]->getType())) {
                    if ($this->imageCheck($files[0]->getType())) {
                        $fileName = basename($files[0]->getName(), "." . $files[0]->getExtension());
                        $fileName = $general->create_slug($fileName);
                        $subCode = Text::random(Text::RANDOM_ALNUM);
                        $fileFullName = $fileName . '_' . $subCode . '.' . $files[0]->getExtension();
                        $setting->assign(array(
                            'banner_1' => $fileFullName
                        ));

                        if ($files[0]->moveTo('files/default/' . $this->_get_subdomainFolder() . '/' . $fileFullName)) {
                            @unlink('files/default/' . $this->_get_subdomainFolder() . '/' . $banner_1);
                        }
                    } else {
                        $this->flash->error('Định dạng file không cho phép. Hãy upload một hình ảnh.');
                    }
                }
            }

            if ($setting->save()) {
                $this->flashSession->success($this->_message["edit"]);

                // Make a full HTTP redirection
                return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName());
            } else {
                $this->flash->error($setting->getMessages());
            }
        }

        $bannerHtmlSub = BannerHtml::findFirstById($setting->banner_html_id);

        $bannerHtml = BannerHtml::query()
            ->join("Modules\Models\Subdomain", "s.id = Modules\Models\BannerHtml.subdomain_id", "s")
            ->where("subdomain_id != :subdomain_id:")
            ->andWhere("s.hot = :hot:")
            ->bind(["subdomain_id" => $this->_get_subdomainID(),
                "hot" => "Y",
            ])
            ->orderBy("s.id ASC")
            ->execute();

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->setting = $setting;
        $this->view->banner_html_sub = $bannerHtmlSub;
        $this->view->banner_html = $bannerHtml;
        $this->view->folder = $this->_get_subdomainFolder();
    }

    public function loadBannerAction()
    {
        $this->view->setTemplateBefore('load_banner');

        $setting = Setting::findFirstBySubdomainId($this->_get_subdomainID());
        $bannerHtmlSub = BannerHtml::findFirstById($setting->banner_html_id);

        $conHtmlBaner = ($bannerHtmlSub) ? "id != ". $bannerHtmlSub->id ." AND" : "";
        $bannerHtml = BannerHtml::find([
            "order" => "id DESC",
            "conditions" => "$conHtmlBaner active='Y' AND deleted = 'N'"
        ]);
        $folder = $this->_get_subdomainFolder();

        $this->view->banner_html_sub = $bannerHtmlSub;
        $this->view->banner_html = $bannerHtml;
        $this->view->setting = $setting;
        $this->view->folder = $folder;
        $this->view->pick($this->_getControllerName() . '/load_banner');
    }

    public function editCssAction()
    {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $this->view->setTemplateBefore('edit_css');
            $file = 'bannerhtml/' . $this->_get_subdomainFolder() . '/' . $id . '/style.css';
            $cssFile = file_get_contents($file);
            $this->view->css_file = $cssFile;
            $this->view->pick($this->_getControllerName() . '/edit_css');
        }
    }

    public function saveCssAction($id)
    {
        if ($this->request->isPost()) {
            $file = 'bannerhtml/' . $this->_get_subdomainFolder() . '/' . $id . '/style.css';
            file_put_contents($file, "");
            file_put_contents($file, $this->request->getPost("css"), FILE_APPEND | LOCK_EX);
        }
        $this->view->disable();
    }

    public function createBannerAction()
    {
        $general = new General();
        if ($this->request->isPost()) {
            $banner_logo = $this->request->getPost('banner_logo');
            $banner_bgr = $this->request->getPost('banner_bgr');
            $banner_1 = $this->request->getPost('banner_1');
            $banner_2 = $this->request->getPost('banner_2');
            $banner_3 = $this->request->getPost('banner_3');

            $banner_html = $this->request->getPost('banner_html');

            $bannerHtml = new BannerHtml();
            $bannerHtml->assign([
                'subdomain_id' => $this->_get_subdomainID()
            ]);
            $bannerHtml->save();
            $bannerHtmlId = $bannerHtml->id;

            $folder = $this->_get_subdomainFolder();

            if (!is_dir("bannerhtml")) {
                mkdir("bannerhtml", 0777);
            }

            if (!is_dir("bannerhtml/" . $folder)) {
                mkdir("bannerhtml/" . $folder, 0777);
            }

            if (!is_dir("bannerhtml/" . $folder . "/" . $bannerHtmlId)) {
                mkdir("bannerhtml/" . $folder . "/" . $bannerHtmlId, 0777);
            }

            if (!is_dir("bannerhtml/" . $folder . "/" . $bannerHtmlId . "/background")) {
                mkdir("bannerhtml/" . $folder . "/" . $bannerHtmlId . "/background", 0777);
            }

            if (!is_dir("bannerhtml/" . $folder . "/" . $bannerHtmlId . "/css")) {
                mkdir("bannerhtml/" . $folder . "/" . $bannerHtmlId . "/css", 0777);
            }

            if (!is_dir("bannerhtml/" . $folder . "/" . $bannerHtmlId . "/logo")) {
                mkdir("bannerhtml/" . $folder . "/" . $bannerHtmlId . "/logo", 0777);
            }

            if (!is_dir("bannerhtml/" . $folder . "/" . $bannerHtmlId . "/product")) {
                mkdir("bannerhtml/" . $folder . "/" . $bannerHtmlId . "/product", 0777);
            }

            if (!is_dir("bannerhtml")) {
                mkdir("bannerhtml", 0777);
            }

            // move logo
            $path_parts = pathinfo($banner_logo);
            $extension = $path_parts['extension'];
            rename('ajaxupload/server/php/files/' . $banner_logo, 'bannerhtml/'  . $folder . '/' . $bannerHtmlId . '/logo/logo.' . $extension);

            // move bgr
            $path_parts = pathinfo($banner_bgr);
            $extension = $path_parts['extension'];
            rename('ajaxupload/server/php/files/' . $banner_bgr, 'bannerhtml/'  . $folder . '/' . $bannerHtmlId . '/background/background.' . $extension);

            // move product
            $path_parts = pathinfo($banner_1);
            $extension = $path_parts['extension'];
            rename('ajaxupload/server/php/files/' . $banner_1, 'bannerhtml/'  . $folder . '/' . $bannerHtmlId . '/product/1.' . $extension);

            $path_parts = pathinfo($banner_2);
            $extension = $path_parts['extension'];
            rename('ajaxupload/server/php/files/' . $banner_2, 'bannerhtml/'  . $folder . '/' . $bannerHtmlId . '/product/2.' . $extension);

            $path_parts = pathinfo($banner_3);
            $extension = $path_parts['extension'];
            rename('ajaxupload/server/php/files/' . $banner_3, 'bannerhtml/'  . $folder . '/' . $bannerHtmlId . '/product/3.' . $extension);

            $setting = Setting::findFirstBySubdomainId($this->_get_subdomainID());
            $setting->assign(['banner_html_id' => $bannerHtmlId]);
            $setting->save();
            $bannerHtmlCopy =  BannerHtml::findFirstById($banner_html);
            copy('bannerhtml/' . $bannerHtmlCopy->subdomain->folder . '/' . $banner_html . '/css/style.css', 'bannerhtml/'  . $folder . '/' . $bannerHtmlId . '/css/style.css');
            $url = 'bannerhtml/' . $bannerHtmlCopy->subdomain->folder . '/' . $banner_html . '/index.html';
            
            // copy to html
            $htmlContent = file_get_contents($url);
            $htmlContent = preg_replace('/<div .*?class="(.*?bannercompany.*?)">(.*?)<\/div>/', '<div class="$1">'. $setting->name .'</div>', $htmlContent);
            $htmlContent = preg_replace('/<div .*?class="(.*?bannerslogan.*?)">(.*?)<\/div>/', '<div class="$1">'. $setting->slogan .'</div>', $htmlContent);
            $htmlContent = preg_replace('/<div .*?class="(.*?bannerhotline.*?)">(.*?)<\/div>/', '<div class="$1">Hotline: '. $setting->hotline .' - Mail: '. $setting->email .'</div>', $htmlContent);
            $htmlContent = preg_replace('/<div .*?class="(.*?banneraddress.*?)">(.*?)<\/div>/', '<div class="$1">Địa chỉ: '. $setting->address .'</div>', $htmlContent);
            // $htmlContent = preg_replace('#<head(.*?)>(.*?)</head>#is', '', $htmlContent);

            file_put_contents('bannerhtml/'  . $folder . '/' . $bannerHtmlId . '/index.html', $htmlContent);

            $files = glob('ajaxupload/server/php/files/*', GLOB_BRACE);
            ; // get all file names
            foreach ($files as $file) { // iterate files
              if (is_file($file)) {
                  unlink($file);
              } // delete file
            }

            $files_thumbnail = glob('ajaxupload/server/php/files/thumbnail/*', GLOB_BRACE);
            ; // get all file names
            foreach ($files_thumbnail as $file) { // iterate files
              if (is_file($file)) {
                  unlink($file);
              } // delete file
            }

            //copy to volt
            /*$htmlContent = file_get_contents($url);
            $htmlContent = preg_replace('#<head(.*?)>(.*?)</head>#is', '', $htmlContent);
            $htmlContent = preg_replace('/<div .*?class="(.*?bannercompany.*?)">(.*?)<\/div>/','<div class="$1">{{ setting.name }}</div>',$htmlContent);
            $htmlContent = preg_replace('/<div .*?class="(.*?bannerslogan.*?)">(.*?)<\/div>/','<div class="$1">{{ setting.slogan }}</div>',$htmlContent);
            $htmlContent = preg_replace('/<div .*?class="(.*?bannerhotline.*?)">(.*?)<\/div>/','<div class="$1">Hotline: {{ setting.hotline }} - Mail: {{ setting.email }}</div>',$htmlContent);
            $htmlContent = preg_replace('/<div .*?class="(.*?banneraddress.*?)">(.*?)<\/div>/','<div class="$1">Địa chỉ: {{ setting.address }}</div>',$htmlContent);
            $imgProduct1 = 'bannerhtml/' . $bannerHtmlCopy->subdomain->folder . '/' . $banner_html . '/product/1.jpg';
            $imgProduct2 = 'bannerhtml/' . $bannerHtmlCopy->subdomain->folder . '/' . $banner_html . '/product/2.jpg';
            $imgProduct3 = 'bannerhtml/' . $bannerHtmlCopy->subdomain->folder . '/' . $banner_html . '/product/3.jpg';
            $htmlContent = preg_replace('/<img .*?class="(.*?producth1.*?)" src="(.*?)" \/>>/','<img class="$1" src="'. $imgProduct1 .'" />',$htmlContent);
            $htmlContent = preg_replace('/<img .*?class="(.*?producth2.*?)" src="(.*?)" \/>>/','<img class="$1" src="'. $imgProduct2 .'" />',$htmlContent);
            $htmlContent = preg_replace('/<img .*?class="(.*?producth3.*?)" src="(.*?)" \/>>/','<img class="$1" src="'. $imgProduct3 .'" />',$htmlContent);

            file_put_contents('bannerhtml/'  . $folder . '/' . $bannerHtmlId . '/index.html', $htmlContent);*/
        }
        $this->view->disable();
    }

    public function listAction()
    {
        $this->view->setTemplateBefore('list');
        $bannerHtml = BannerHtml::find([
            "order" => "id DESC",
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active='Y' AND deleted = 'N'"
        ]);
        $setting = Setting::findFirstBySubdomainId($this->_get_subdomainID());
        $folder = $this->_get_subdomainFolder();
        $this->view->setting = $setting;
        $this->view->folder = $folder;
        $this->view->banner_html = $bannerHtml;
        $this->view->pick($this->_getControllerName() . '/list');
    }

    public function viewAction($id)
    {
        $this->view->setTemplateBefore('view');
        
        $item = BannerHtml::findFirstById($id);
        
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        $setting = Setting::findFirstBySubdomainId($item->subdomain_id);
        $folder = $item->subdomain->folder;
   
        $this->view->setting = $setting;
        $this->view->folder = $folder;
        $this->view->id = $id;
        $this->view->pick($this->_getControllerName() . '/view');
    }

    public function addAction()
    {
        $folder = $this->_get_subdomainFolder();
        $bannerHtml = new BannerHtml();
        $bannerHtml->assign(['subdomain_id' => $this->_get_subdomainID()]);
        $bannerHtml->save();
        $bannerHtmlId = $bannerHtml->id;
        $setting = Setting::findFirstBySubdomainId($this->_get_subdomainID());
        $setting->assign(['banner_html_id' => $bannerHtmlId]);
        $setting->save();

        if (!is_dir("bannerhtml")) {
            mkdir("bannerhtml", 0777);
        }

        if (!is_dir("bannerhtml/" . $folder)) {
            mkdir("bannerhtml/" . $folder, 0777);
        }

        if (!is_dir("bannerhtml/" . $folder . "/" . $bannerHtmlId)) {
            mkdir("bannerhtml/" . $folder . "/" . $bannerHtmlId, 0777);
        }

        copy('assets/source/bannerhtml/css/style3.css', 'bannerhtml/'  . $folder . '/' . $bannerHtmlId . '/style.css');

        $this->view->disable();
    }

    public function copyAction()
    {
        $this->view->disable();
        if ($this->request->isPost()) {
            $setting = Setting::findFirstBySubdomainId($this->_get_subdomainID());
            $id = $this->request->getPost('id');
            $bannerHtml = BannerHtml::findFirstById($id);
            $result = copy('bannerhtml/'  . $bannerHtml->subdomain->folder . '/' . $id . '/style.css', 'bannerhtml/'  . $this->_get_subdomainFolder() . '/' . $setting->banner_html_id . '/style.css');
            return $result;
        }
    }

    private function imageCheck($extension)
    {
        $allowedTypes = [
            'image/gif',
            'image/jpg',
            'image/png',
            'image/bmp',
            'image/jpeg',
            'image/x-icon'
        ];

        return in_array($extension, $allowedTypes);
    }
}
