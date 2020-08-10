<?php

namespace Modules\Backend\Controllers;

use Modules\Models\ConfigCore;
use Modules\Models\ConfigGroup;
use Modules\Models\ConfigItem;
use Modules\Models\ModuleGroup;
use Modules\Models\ModuleItem;
use Modules\Models\Position;
use Modules\Models\Subdomain;
use Modules\Models\WordCore;
use Modules\Models\WordItem;
use Modules\Forms\ConfigCoreForm;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;

class WordItemController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Cấu hình';
    }

    public function indexAction()
    {
        $messageFolder = $this->config->application->messages;
        $langFile = $messageFolder . 'subdomains/' . $this->_get_subdomainFolder() . '/vi.json';

        if (file_exists($langFile)) {
            $messages = json_decode(file_get_contents($langFile));
            $wordDatas = [];
            foreach ($messages as $key => $message) {
                $wordCore = WordCore::findFirstByName($key);
                if ($wordCore) {
                    $item['id'] = $wordCore->id;
                    $item['word_key'] = $wordCore->word_key;
                    $item['word_translate'] = $message;
                    array_push($wordDatas, $item);
                }
            }

            $this->view->wordDatas = $wordDatas;
        } else {
            $list = WordItem::find([
                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND active = "Y"',
                'order' => 'name ASC, id DESC'
            ]);

            $this->view->list = $list;
        }

        // get languages
        $wordDataLangs = [];
        if (count($this->_tmpSubdomainLanguages) > 1) {
            $tmpSubdomainLanguages = $this->_tmpSubdomainLanguages;
            foreach ($tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langeCode = $tmpSubdomainLanguage->language->code;
                if ($langeCode != 'vi') {
                    $langFile = $messageFolder . 'subdomains/' . $this->_get_subdomainFolder() . '/' . $langeCode . '.json';

                    if (file_exists($langFile)) {
                        $messages = json_decode(file_get_contents($langFile));
                        $wordDatas = [];
                        foreach ($messages as $key => $message) {
                            $wordCore = WordCore::findFirstByName($key);
                            if ($wordCore) {
                                $item['id'] = $wordCore->id;
                                $item['word_key'] = $wordCore->word_key;
                                $item['word_translate'] = $message;
                                array_push($wordDatas, $item);
                            }
                        }

                        $wordDataLangs[$langeCode] = $wordDatas;
                    }
                }
            }
        }

        if ($this->request->isPost()) {
            if (!is_dir($messageFolder . 'subdomains')) {
                mkdir($messageFolder . 'subdomains', 0777);
            }

            if (!is_dir($messageFolder . 'subdomains/' . $this->_get_subdomainFolder())) {
                mkdir($messageFolder . 'subdomains/' . $this->_get_subdomainFolder(), 0777);
            }

            $request = $this->request->getPost();
            $wordTranslate = [];
            foreach ($request['word_translate'] as $key => $value) {
                $wordCore = WordCore::findFirstById($key);
                $wordTranslate[$wordCore->name] = trim($value);
            }

            file_put_contents($messageFolder . 'subdomains/' . $this->_get_subdomainFolder() . '/vi.json', json_encode($wordTranslate, JSON_UNESCAPED_UNICODE));

            // other lang
            if (!empty($wordDataLangs)) {
                foreach ($wordDataLangs as $keyLang => $wordDataLang) {
                    $wordTranslate = [];
                    foreach ($request['word_translate_' . $keyLang] as $key => $value) {
                        $wordCore = WordCore::findFirstById($key);
                        $wordTranslate[$wordCore->name] = trim($value);
                    }

                    file_put_contents($messageFolder . 'subdomains/' . $this->_get_subdomainFolder() . '/' . $keyLang . '.json', json_encode($wordTranslate, JSON_UNESCAPED_UNICODE));
                }
            }

            
            $this->flashSession->success($this->_message["edit"]);
            $url = ACP_NAME . '/' . $this->_getControllerName();
            $this->response->redirect($url);
        }

        $this->view->wordDataLangs = $wordDataLangs;
        $this->view->module_name = 'Từ ngữ web';
        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
    }

    /**
     * Convert word from database to file
     * 
     * @param  integer $id
     * 
     * @return bolean    
     */
    public function convertAction($id)
    {
        $subdomain = Subdomain::findFirstById($id);
        if (!$subdomain) {
            throw new \Exception("Subdomain not exits");
        }

        $messageFolder = $this->config->application->messages;
        $dir = $messageFolder . "subdomains/". $subdomain->folder;
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }

        $file = $dir . "/vi.json";
        $wordDatas = [];
        if (!file_exists($file)) {
            $wordItems = WordItem::find([
                "conditions" => "subdomain_id = $id",
                "order" => "name ASC, id DESC"
            ]);
            if ($wordItems->count() > 0) {
                foreach ($wordItems as $wordItem) {
                    $key = $wordItem->name;
                    $value = trim($wordItem->word_translate);
                    $wordDatas[$key] = $value;
                }

                if (!empty($wordDatas)) {
                    file_put_contents($file, json_encode($wordDatas, JSON_UNESCAPED_UNICODE));
                }
            }
        }

        $this->view->disable();
    }
}
