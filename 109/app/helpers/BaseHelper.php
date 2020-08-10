<?php

namespace Modules\Helpers;

use Phalcon\Mvc\User\Component;
use Modules\Models\Languages;

class BaseHelper extends Component
{
    protected $_lang_id;

    protected $_lang_code;

    public function __construct()
    {
        $languageCode = ($this->dispatcher->getParam("language")) ? $this->dispatcher->getParam("language") : 'vi';
        $languageInfo = Languages::findFirstByCode($languageCode);
        $this->_lang_id = $languageInfo->id;
        $this->_lang_code = $languageInfo->code;
    }
}
