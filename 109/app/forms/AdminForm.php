<?php namespace Modules\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
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
use Phalcon\Validation\Validator\Callback;
use Modules\Models\Users;
use Modules\PhalconVn\MainGlobal;

class AdminForm extends Form
{
    public function initialize()
    {
        $mainGlobal = new MainGlobal();
        $this->_subdomain_id = $mainGlobal->getDomainId();

        //username
        $username = new Text('username', array(
            'placeholder' => 'username'
        ));

        $username->addValidators(array(
            new PresenceOf(array(
                'message' => 'Tên đăng nhập không được rỗng'
            )),
            new Callback(
                [
                    "message" => "Tên đăng nhập không đúng",
                    "callback" => function () {
                        if (!($this->checkUsername($this->request->getPost('username')))) {
                            return false;
                        }
                     
                        return true;
                    }
                ]
            )
        ));

        $this->add($username);

        //Password
        $password = new Password('password', array(
            'placeholder' => 'Password'
        ));

        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Mật khẩu không được rỗng'
            )),
            new Callback(
                [
                    "message" => "Mật khẩu bạn nhập không đúng",
                    "callback" => function () {
                        if (!($this->checkPassword($this->request->getPost('username'), $this->request->getPost('password')))) {
                            return false;
                        }
                     
                        return true;
                    }
                ]
            )
        ));

        $this->add($password);

        //Remember
        $remember = new Check('remember', array(
            'value' => 'yes'
        ));

        $remember->setLabel('Remember me');

        $this->add($remember);

        //CSRF
        $csrf = new Hidden('csrf');

        /*$csrf->addValidator(
            new Identical(array(
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed'
            ))
        );*/

        $this->add($csrf);
    }

    /**
     *
     * @param string $username
     * @return boolean
     *
     */
    private function checkUsername($username)
    {
        $user = Users::findFirst([
            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND (username = "'. $username .'" OR email = "'. $username .'")'
        ]);

        if (!$user) {
            return false;
        }

        return  true;
    }

    /**
     *
     * @param string $username, string $password
     * @return boolean
     *
     */
    private function checkPassword($username, $password)
    {
        if (self::checkUsername($username)) {
            $user = Users::findFirst([
                'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND (username = "'. $username .'" OR email = "'. $username .'")'
            ]);

            if (!$this->security->checkHash($password, $user->password)) {
                return false;
            }

            return true;
        }
        
        return true;
    }

    /**
     * Prints messages for a specific element
     */
    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
