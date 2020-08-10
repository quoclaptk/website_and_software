<?php

namespace Modules\PhalconVn;

use Modules\Models\WordItem;
use Modules\Models\Subdomain;
use Phalcon\Translate\Adapter\NativeArray;

class WordTranslateService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->_folder = $this->mainGlobal->getDomainFolder();
    }

    public function getWordTranslation()
    {
        $messageFolder = $this->config->application->messages;
        $langFile = $messageFolder . 'subdomains/' . $this->_folder . '/' . $this->_lang_code . '.json';
        if (file_exists($langFile)) {
            $translations = json_decode(
                file_get_contents($langFile),
                true
            );
        } else {
            $words = WordItem::find([
            "columns" => "id, name, word_key, word_translate",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND active = 'Y' AND deleted = 'N'"
            ]);

            $translations = [];
            if (count($words) > 0) {
                foreach ($words as $word) {
                    $translations[$word->name] = $word->word_translate;
                }
            }
        }

        return new NativeArray(
            [
                'content' => $translations,
            ]
        );
    }

    public function getWordTranslationSubdomain($subdomainId, $lang = 'vi')
    {
        $subdomain = Subdomain::findFirstById($subdomainId);
        $messageFolder = $this->config->application->messages;

        $langFile = $messageFolder . 'subdomains/' . $subdomain->folder . '/' . $lang . '.json';
        if (file_exists($langFile)) {
            $translations = json_decode(
                file_get_contents($langFile),
                true
            );
            if (empty($translations)) {
                $words = WordItem::find([
                    "columns" => "id, name, word_key, word_translate",
                    "conditions" => "subdomain_id = ". $subdomainId ." AND active = 'Y' AND deleted = 'N'"
                ]);

                $translations = [];
                if (count($words) > 0) {
                    foreach ($words as $word) {
                        $translations[$word->name] = $word->word_translate;
                    }
                }
            }
        } else {
            $words = WordItem::find([
                "columns" => "id, name, word_key, word_translate",
                "conditions" => "subdomain_id = ". $subdomainId ." AND active = 'Y' AND deleted = 'N'"
            ]);

            $translations = [];
            if (count($words) > 0) {
                foreach ($words as $word) {
                    $translations[$word->name] = $word->word_translate;
                }
            }
        }



        return new NativeArray(
            [
                'content' => $translations,
            ]
        );
    }

    /**
     * Get message translation from message file
     *
     * @return array
     */
    public function getMessageTranslation()
    {
        $messageLangDefault = $this->config_service->getConfigItemDetail("_cf_radio_select_message_lang_default");
        $langCode = ($messageLangDefault != 'vi' && $this->_lang_code == 'vi') ? $messageLangDefault : $this->_lang_code;
        $langFile = 'messages/' . $langCode . '.json';
        if (file_exists($langFile)) {
            $translations = json_decode(
                file_get_contents($langFile),
                true
            );

            return new NativeArray(
                [
                    'content' => $translations,
                ]
            );
        } else {
            return 'No result';
        }
    }

    /**
     * Get message word on Fo from admin file
     * 
     * @return array
     */
    public function getMessageAdminFile()
    {
        $adminFile = 'messages/adminWord.json';
        if (file_exists($adminFile)) {
            $messages = json_decode(
                file_get_contents($adminFile),
                true
            );

            return new NativeArray(
                [
                    'content' => $messages,
                ]
            );
        } else {
            return 'No result';
        }
    }
}
