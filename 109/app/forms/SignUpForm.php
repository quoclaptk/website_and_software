<?php namespace Modules\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\email;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class SignUpForm extends Form
{
    public function initialize($entity=null, $options=null)
    {
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
            $birthday = new Text('birthday', array(
                    'class' 	 =>'datepicker',
                    'data-date-format' =>'yyyy-mm-dd',
            ));
            $birthday->addValidators(array(
                    new Regex(array(
                    'pattern' => '/^([0-9]{4})[-\/](0[1-9]|1[012])[-\/](0[1-9]|[12][0-9]|3[01])$/',
                    'message' => 'Định dạng ngày tháng không đúng'))
                    
            ));
        } else {
            $id = new Text('id');
            $birthday = new Text('birthday', array(
                    'placeholder'=>'vd.19/08/1990',
                    'class' 	 =>'datepicker',
                    'data-date-format' =>'dd/mm/yyyy',
            ));
            $birthday->addValidators(array(
                    new Regex(array(
                    'pattern' => '/^(0[1-9]|[12][0-9]|3[01])[-\/](0[1-9]|1[012])[-\/][0-9]{4}$/',
                    'message' => 'Định dạng ngày tháng không đúng'))
                    
            ));
        }
        $this->add($birthday);

        $fullName = new Text('fullName', array('placeholder' =>'Tran Duy Thien'));//<input type="text" value="" id="fullName" name="fullName">
        $fullName->setLabel('Họ Và Tên');//<label for="fullName">fullName</label>

        $fullName->addValidators(array(
            new PresenceOf(array(
                'message' => 'Họ và tên không được rỗng'
            ))
        ));
        $this->add($fullName);
                
        $username = new Text('username', array('placeholder' =>'Tran Duy Thien'));//<input type="text" value="" id="fullName" name="fullName">
        $username->setLabel('Tên Tài Khoản');//<label for="fullName">fullName</label>

        $username->addValidators(array(
            new PresenceOf(array(
                'message' => 'Tài khoản không được rỗng'
            ))
        ));
        $this->add($username);
                

        $sex = new Select('sex', array(
                '' => 'Giới Tính',
                'Chưa Biết' =>'Chưa Biết',
                'Nam'=>'Nam',
                'Nữ' => 'Nữ',
                
        ));
        $sex->setLabel('Giới Tính');
        $sex->addValidators(array(
            new PresenceOf(array(
                'message' => 'Giới Tính Không Được rỗng'
                ))
        ));
        $this->add($sex);


        $birthday->setLabel('Ngày Sinh');

        $cardId = new Numeric('cardId', array('placeholder'=>'vd. 221271045'));
        $cardId->setLabel('Số Chứng Minh Thư');
        $cardId->addValidators(array(
            new StringLength(array(
                'min' => 9,
                'maxlength' =>10,
                'messageMinimum' => 'Số chứng minh thư phải 9 ký tự'
            )),
        ));
        $this->add($cardId);



        $phone = new Numeric('phone', array('placeholder'=>'vd. 0909340777'));
        $phone->setLabel('Điện Thoại Di Động');
        $phone->addValidators(array(
            new StringLength(array(
                'min'=>'10',
                'maxlength'=>'11',
                'messageMinimum'=>'Điện thoại phải là số và lớn hơn 9 ký tự'
                ))
        ));
        $this->add($phone);
        $email = new Text('email', array('placeholder'=>'fcduythien@gmail.com'));
        $email->setLabel('E-mail');
        $email->addValidators(array(
            new email(array(
                'message' => 'Địa chỉ email không hợp lệ'
            ))
        ));
        $this->add($email);


        $cityRegion =new Select('cityRegion', array(
            ''=>'Nơi sinh','TP Hà Nội'=>'TP Hà Nội','Bắc Giang'=>'Bắc Giang',
            'Bắc Kạn'=>'Bắc Kạn','Bắc Ninh'=>'Bắc Ninh','Cao Bằng'=>'Cao Bằng',
            'Điện Biên'=>'Điện Biên','Hà Giang'=>'Hà Giang','Hà Nam'=>'Hà Nam',
            'Hà Tây'=>'Hà Tây','Hải Dương'=>'Hải Dương','TP Hải Phòng'=>'TP Hải Phòng',
            'Hòa Bình'=>'Hòa Bình','Hưng Yên'=>'Hưng Yên','Lai Châu'=>'Lai Châu',
            'Lào Cai'=>'Lào Cai','Lạng Sơn'=>'Lạng Sơn','Nam Định'=>'Nam Định','Thanh Hóa'=>'Thanh Hóa',
            'Ninh Bình'=>'Ninh Bình','Phú Thọ'=>'Phú Thọ','Quảng Ninh'=>'Quảng Ninh',
            'Sơn La'=>'Sơn La','Thái Bình'=>'Thái Bình','Thái Nguyên'=>'Thái Nguyên',
            'Tuyên Quang'=>'Tuyên Quang','Vĩnh Phúc'=>'Vĩnh Phúc','Yên Bái'=>'Yên Bái',
            'TP Đà Nẵng'=>'TP Đà Nẵng','Bình Định'=>'Bình Định','Phú Yên'=>'Phú Yên','Ninh Thuận'=>'Ninh Thuận',
            'Khánh Hòa'=>'Khánh Hòa','Đắk Lắk'=>'Đắk Lắk','Gia Lai'=>'Gia Lai','Bình Thuận'=>'Bình Thuận',
            'Đắk Nông'=>'Đắk Nông','Hà Tĩnh'=>'Hà Tĩnh','Kon Tum'=>'Kon Tum','Lâm Đồng'=>'Lâm Đồng',
            'Nghệ An'=>'Nghệ An','Quảng Bình'=>'Quảng Bình','Quảng Nam'=>'Quảng Nam',
            'Quảng Ngãi'=>'Quảng Ngãi','Quảng Trị'=>'Quảng Trị','Thừa Thiên Huế'=>'Thừa Thiên Huế',
            'TP. Hồ Chí Minh'=>'TP. Hồ Chí Minh','An Giang'=>'An Giang','Vũng Tàu'=>'Vũng Tàu',
            'Bạc Liêu'=>'Bạc Liêu','Bến Tre'=>'Bến Tre','Bình Dương'=>'Bình Dương','Bình Phước'=>'Bình Phước',
            'TP Cần Thơ'=>'TP Cần Thơ','Đồng Tháp'=>'Đồng Tháp','Hậu Giang'=>'Hậu Giang','Kiên Giang'=>'Kiên Giang',
            'Đồng Nai'=>'Đồng Nai','Cà Mau'=>'Cà Mau','Long An'=>'Long An','Tây Ninh'=>'Tây Ninh','Tiền Giang'=>'Tiền Giang',
            'Trà Vinh'=>'Trà Vinh','Vĩnh Long'=>'Vĩnh Long','Sóc Trăng'=>'Sóc Trăng'
        ));
        $cityRegion->setLabel('Nơi sinh');
        $this->add($cityRegion);
        



        //Password
        $password = new Password('password');

        $password->setLabel('Mật Khẩu');

        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Mật khẩu không được rỗng'
            )),
            new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'Mật khẩu phải lớn hơn 8 ký tự'
            )),
            new Confirmation(array(
                'message' => 'Mật khẩu không khớp',
                'with' => 'confirmPassword'
            ))
        ));

        $this->add($password);

        //Confirm Password
        $confirmPassword = new Password('confirmPassword');

        $confirmPassword->setLabel('Nhập lại mật khẩu');

        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'The confirmation password is required'
            ))
        ));

        $this->add($confirmPassword);

        //Remember
        $terms = new Check('terms', array(
            'value' => 'yes'
        ));

        $terms->setLabel('Đồng ý với Điều khoản dịch vụ và chính sách bảo mật của chúng tôi');

        $terms->addValidator(
            new Identical(array(
                'value' => 'yes',
                'message' => 'Bạn chưa chọn'
            ))
        );

        $this->add($terms);

        //CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(
            new Identical(array(
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed'
            ))
        );

        $this->add($csrf);

        //Sign Up
        $this->add(new Submit('Sign Up', array(
            'class' => 'btn btn-primary pull-right'
        )));
    }

    /**
     * Prints messages for a specific element
     */
    public function messages($username)
    {
        if ($this->hasMessagesFor($username)) {
            foreach ($this->getMessagesFor($username) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
