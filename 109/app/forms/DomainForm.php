<?php
/**
 * Created by PhpStorm.
 * User: Congngo
 * Date: 1/7/2018
 * Time: 8:59 AM
 */

namespace Modules\Forms;

use Modules\Models\Domain;
use Modules\Models\Users;
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
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Callback;

class DomainForm extends Form
{
    public function initialize($entity = null, $options = null)
    {

        //In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Hidden('id');
        }

        $this->add($id);

        $name = new Text('name', array(
            'placeholder' => 'Tên domain'
        ));

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Tên domain không được rỗng'
            )),
            new Callback(
                [
                    "message" => "Tên domain không đúng định dạng",
                    "callback" => function () {
                        if (!($this->filter_var_domain($this->request->getPost('name')))) {
                            return false;
                        }

                        return true;
                    }
                ]
            )
        ));
        if (isset($options['edit']) && $options['edit']) {
            $name->addValidators(array(
                new Callback(
                    [
                        "message" => "Tên domain đã tồn tại",
                        "callback" => function () {
                            if (!($this->check_exist_domain($this->request->getPost('id'), $this->request->getPost('name')))) {
                                return false;
                            }

                            return true;
                        }
                    ]
                )
            ));
        } else {
            $name->addValidators(array(
                new Uniqueness(array(
                    "model"   => new Domain(),
                    "attribute" => "name",
                    'message' => 'Tên domain đã tồn tại'
                ))
            ));
        }


        $this->add($name);
    }

    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }

    protected function check_exist_domain($id, $name)
    {
        $result = Domain::find([
            "columns" => "id",
            "conditions" => "id != $id AND name = '$name'"
        ]);
        if (count($result) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function filter_var_domain($domain)
    {
        if (stripos($domain, 'http://') === 0) {
            $domain = substr($domain, 7);
        }

        ///Not even a single . this will eliminate things like abcd, since http://abcd is reported valid
        if (!substr_count($domain, '.')) {
            return false;
        }

        if (stripos($domain, 'www.') === 0) {
            $domain = substr($domain, 4);
        }

        $again = 'http://' . $domain;
        return filter_var($again, FILTER_VALIDATE_URL);
    }
}
