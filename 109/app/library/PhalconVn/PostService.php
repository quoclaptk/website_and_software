<?php

namespace Modules\PhalconVn;

use Modules\Models\ModuleItem;
use Modules\Models\Posts;

class PostService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPost($postModuleid)
    {
        $moduleItemCurrent = ModuleItem::findFirstById($postModuleid);
        
        if ($moduleItemCurrent->subdomain_id != $this->_subdomain_id) {
            $moduleItem = ModuleItem::findFirst([
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND type = '". $moduleItemCurrent->type ."' AND active = 'Y' AND deleted = 'N'"
            ]);
            $moduleItemId = $moduleItem->id;
        } else {
            $moduleItemId = $postModuleid;
        }
        
        $post = null;
        if ($this->_lang_code == 'vi') {
            $post = Posts::findFirst(["columns" => "id, name, content, messenger_form, mic_support_head, mic_support_foot", "conditions" => "module_item_id = $moduleItemId AND active = 'Y' AND deleted = 'N'"]);
        } else {
            $postVi = Posts::findFirstByModuleItemId($moduleItemId);
            if ($postVi) {
                $post = Posts::findFirst(["columns" => "id, name, content, messenger_form, mic_support_head, mic_support_foot", "conditions" => "depend_id = $postVi->id AND language_id = $this->_lang_id AND active = 'Y' AND deleted = 'N'"]);
            }
        }

        return $post;
    }
}
