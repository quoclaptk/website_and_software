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

use Modules\Models\FormGroup;
use Modules\Helpers\FormHelper;

class FormItemForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        $formHelper = new FormHelper();
        $formGroups = FormGroup::find([
            'columns' => 'id'
        ]);

        $id = new Hidden('frm_item_id', ['id' => null]);
        $this->add($id);

        if (count($formGroups) > 0) {
            foreach ($formGroups as $formGroup) {
                $formGroupId = $formGroup->id;

                $name = new Text('frm_item_name_' . $formGroupId, [
                    'placeholder' => 'Xin mời nhập Họ tên',
                    'id' => null
                ]);
                $name->addValidators([
                    new PresenceOf([
                        'message' => 'Xin vui lòng nhập tên của bạn'
                    ])
                ]);
                $this->add($name);

                $email = new Email('frm_item_email_' . $formGroupId, [
                    'placeholder' => 'Email',
                    'id' => null
                ]);
                $this->add($email);

                $phone = new Text('frm_item_phone_' . $formGroupId, [
                    'placeholder' => 'Vui lòng nhập số điện thoại của bạn',
                    'id' => null
                ]);
                $phone->addValidators([
                    new Numericality([
                        'message' => 'Số điện thoại không đúng định dạng'
                    ])
                ]);
                $this->add($phone);

                $address = new Text('frm_item_address_' . $formGroupId, [
                    'placeholder' => 'Địa chỉ',
                    'id' => null
                ]);
                $this->add($address);

                $subject = new Text('frm_item_subject_' . $formGroupId, [
                    'placeholder' => 'Chủ đề',
                    'id' => null
                ]);
                $this->add($subject);

                $comment = new TextArea('frm_item_comment_' . $formGroupId, [
                    "value" => "",
                    'placeholder' => 'Hãy gửi tin nhắn cho chúng tôi về vấn đề của bạn',
                    "rows" => 3,
                    'id' => null
                ]);
                $this->add($comment);

                $place_arrive = new Text('frm_item_place_arrive_' . $formGroupId, [
                    'id' => null
                ]);
                $this->add($place_arrive);

                $place_pic = new Text('frm_item_place_pic_' . $formGroupId, [
                    'id' => null
                ]);
                $this->add($place_pic);

                $type = new Select('frm_item_type_' . $formGroupId, $formHelper->carTypeSelect(), [
                    'id' => null
                ]);
                $this->add($type);

                $day = new Text('frm_item_day_' . $formGroupId, [
                    'id' => null,
                ]);
                $this->add($day);

                $hour = new Select('frm_item_hour_' . $formGroupId, $formHelper->hourSelect(), [
                    'id' => null
                ]);
                $this->add($hour);

                $hourOne = new Select('frm_item_hour_one_' . $formGroupId, $formHelper->hourSelectOne(8, 20), [
                    'id' => null
                ]);
                $this->add($hourOne);

                $minute = new Select('frm_item_minute_' . $formGroupId, $formHelper->minuteSelect(), [
                    'id' => null
                ]);
                $this->add($minute);

                $class = new Select('frm_item_class_' . $formGroupId, $formHelper->classSelect(), ['id' => null]);
                $class->addValidators(array(
                    new PresenceOf(array(
                        'message' => 'Bạn phải chọn lớp'
                    ))

                ));
                $this->add($class);

                $subjects = new Text('frm_item_subjects_' . $formGroupId, [
                    'id' => null
                ]);
                $subjects->addValidators([
                    new PresenceOf([
                        'message' => 'Xin vui lòng nhập tên môn học'
                    ])
                ]);
                $this->add($subjects);

                $studentNumber = new Text('frm_item_student_number_' . $formGroupId, [
                    'id' => null
                ]);
                $studentNumber->addValidators([
                    new PresenceOf([
                        'message' => 'Xin vui lòng nhập số lượng học sinh'
                    ])
                ]);
                $this->add($studentNumber);

                $learningLevel = new Text('frm_item_learning_level_' . $formGroupId, [
                    'id' => null
                ]);
                $learningLevel->addValidators([
                    new PresenceOf([
                        'message' => 'Xin vui lòng nhập học lực hiện tại'
                    ])
                ]);
                $this->add($learningLevel);

                $learningTime = new Select('frm_item_learning_time_' . $formGroupId, $formHelper->caseClassSelect(), ['id' => null]);
                $learningTime->addValidators(array(
                    new PresenceOf(array(
                        'message' => 'Bạn phải chọn số buổi'
                    ))
                ));
                $this->add($learningTime);

                $learningDay = new Text('frm_item_learning_day_' . $formGroupId, [
                    'id' => null
                ]);
                $learningDay->addValidators([
                    new PresenceOf([
                        'message' => 'Xin vui lòng nhập thời gian học'
                    ])
                ]);
                $this->add($learningDay);

                $request = new Select('frm_item_request_' . $formGroupId, $formHelper->requestLevelSelect(), ['id' => null]);
                $request->addValidators(array(
                    new PresenceOf(array(
                        'message' => 'Bạn phải chọn yêu cầu'
                    ))
                ));
                $this->add($request);

                $teacherCode = new Text('frm_item_teacher_code_' . $formGroupId, [
                    'id' => null,
                ]);
                $this->add($teacherCode);

                $method = new Select('frm_item_method_' . $formGroupId, $formHelper->methodSelect(), [
                    'id' => null
                ]);
                $this->add($method);

                $startTime = new Text('frm_item_start_time_' . $formGroupId, [
                    'id' => null,
                ]);
                $this->add($startTime);

                $endTime = new Text('frm_item_end_time_' . $formGroupId, [
                    'id' => null,
                ]);
                $this->add($endTime);

                $numberTicket = new Text('frm_item_number_ticket_' . $formGroupId, [
                    'id' => null,
                ]);
                $this->add($numberTicket);

                $file = new File('frm_item_file_' . $formGroupId, array());
                $this->add($file);
            }
        }
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
