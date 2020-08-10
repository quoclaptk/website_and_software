<?php
namespace Modules\Translate;

use Phalcon\Mvc\User\Component;
use Phalcon\Translate\Adapter\NativeArray;

class Locale extends Component
{
    public function getTranslator($language)
    {
        $translations = json_decode(
            file_get_contents('messages/' . $language . '.json'),
            true
        );

        // Return a translation object $messages comes from the require
        // statement above
        return new NativeArray(
            [
                'content' => $translations,
            ]
        );
    }
}
