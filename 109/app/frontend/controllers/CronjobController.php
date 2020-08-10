<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Subdomain;
use Modules\Models\WordItem;

class CronjobController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
    }

    /**
     * Convert word from database to file
     * 
     * @param  integer $id
     * 
     * @return bolean    
     */
    public function convertWordAction($id)
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
