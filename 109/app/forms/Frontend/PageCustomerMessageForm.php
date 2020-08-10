<?php

namespace Modules\Forms\Frontend;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Email;
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
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Uniqueness;

use Modules\Helpers\FormHelper;

class PageCustomerMessageForm extends BaseFrontendForm
{
    public function initialize($entity = null, $options = null)
    {
        $formHelper = new FormHelper();
        $messages = $this->getMessageLanguage();

        $work_province = new Select('c_mgs_work_province', $formHelper->proviceSelect(['special' => true]), ['id' => null]);
        $work_province->addValidators(array(
            new PresenceOf(array(
                'message' => $messages->_('province_is_required')
            ))
        ));
        $this->add($work_province);

        $box_option_select = new Select('c_mgs_box_option_select', $formHelper->boxOptionSelect(), ['id' => null]);
        $box_option_select->addValidators(array(
            new PresenceOf(array(
                'message' => $messages->_('require_field')
            ))
        ));
        $this->add($box_option_select);

        $name = new Text('c_mgs_name', [
            'id' => null
        ]);
        $name->addValidators([
            new PresenceOf([
                'message' => $messages->_('name_is_required')
            ])
        ]);
        $this->add($name);

        $birthday = new Text('c_mgs_birthday', [
            'id' => null
        ]);
        $birthday->addValidators([
            new PresenceOf([
                'message' => $messages->_('birthday_is_required')
            ])
        ]);
        $this->add($birthday);

        $home_town = new Select('c_mgs_home_town', $formHelper->proviceSelect(), ['id' => null]);
        $home_town->addValidators(array(
            new PresenceOf(array(
                'message' => $messages->_('hometown_is_required')
            ))
        ));
        $this->add($home_town);

        $voice = new Select('c_mgs_voice', $formHelper->voiceSelect(), ['id' => null]);
        $voice->addValidators(array(
            new PresenceOf(array(
                'message' => $messages->_('voice_is_required')
            ))
        ));
        $this->add($voice);

        $email = new Email('c_mgs_email', [
            'placeholder' => 'Email',
            'id' => null
        ]);
        $this->add($email);

        $phone = new Text('c_mgs_phone', [
            'id' => null
        ]);
        $phone->addValidators([
            new Numericality([
                'message' => $messages->_('phone_not_in_correct_format')
            ])
        ]);
        $this->add($phone);

        $address = new Text('c_mgs_address', [
            'id' => null
        ]);
        $this->add($address);

        $portrait_image= new File('c_mgs_portrait_image', array());
        $this->add($portrait_image);

        $certificate_image= new File('c_mgs_certificate_image', array());
        $this->add($certificate_image);

        $college_address = new Text('c_mgs_college_address', [
            'id' => null
        ]);
        $this->add($college_address);

        $major = new Text('c_mgs_major', [
            'id' => null
        ]);
        $this->add($major);

        $graduation_year = new Text('c_mgs_graduation_year', [
            'id' => null
        ]);
        $this->add($graduation_year);

        $level = new Select('c_mgs_level', $formHelper->levelSelect(), ['id' => null]);
        $level->addValidators(array(
            new PresenceOf(array(
                'message' => $messages->_('level_is_required')
            ))
        ));
        $this->add($level);

        $gender = new Select('c_mgs_gender', $formHelper->genderSelect(), ['id' => null]);
        $gender->addValidators(array(
            new PresenceOf(array(
                'message' => $messages->_('gender_is_required')
            ))
        ));
        $this->add($gender);

        $forte = new TextArea('c_mgs_forte', [
            "value" => "",
            "rows" => 3,
            'id' => null
        ]);
        $this->add($forte);

        $subject = new Text('c_mgs_subject', [
            'id' => null
        ]);
        $this->add($subject);

        $subjects = new Check("c_mgs_subjects", array(
            "id" => null,
            "name" => "c_mgs_subjects[]"
        ));
        $this->add($subjects);

        $class = new Check("c_mgs_class", array(
            "id" => null,
            "name" => "c_mgs_class[]",
        ));
        $this->add($class);

        $training_time = new Check("c_mgs_training_time", array(
            "id" => null,
            "name" => "c_mgs_training_time[]"
        ));
        $this->add($training_time);

        $salary = new Text('c_mgs_salary', [
            'id' => null
        ]);
        $this->add($salary);

        $comment = new TextArea('c_mgs_comment', [
            "value" => "",
            "rows" => 3,
            'id' => null
        ]);
        $this->add($comment);

        $other_request = new TextArea('c_mgs_other_request', [
            "value" => "",
            "rows" => 3,
            'id' => null
        ]);
        $this->add($other_request);
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
