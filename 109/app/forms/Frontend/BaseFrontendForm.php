<?php

namespace Modules\Forms\Frontend;

use Modules\Models\TmpSubdomainLanguage;
use Modules\Models\Layout;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\File;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

use Phalcon\Mvc\Model\Validator\Email as EmailValidator;

class BaseFrontendForm extends Form
{
    protected function getMessageLanguage()
    {
        $messages = $this->word_translate->getMessageTranslation();

        return $messages;
    }

    protected function _get_subdomainID()
    {
        $identity = $this->session->get('subdomain-child');
        return $identity['subdomain_id'];
    }

    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
