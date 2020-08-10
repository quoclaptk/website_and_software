<?php
namespace Modules\Translate;

use Phalcon\Mvc\User\Component;
use Statickidz\GoogleTranslate;

class GoogleTranslation extends Component
{
    public function getTranslator($source, $target, $text)
    {
        $trans = new GoogleTranslate();
        $result = $trans->translate($source, $target, $text);

        return $result;
    }
}
