<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\CustomerMessage;
use Modules\Models\Subdomain;
use Phalcon\Http\Response;

class CustomerMessageController extends BaseController
{
    private $_subdomain_id;

    public function onConstruct()
    {
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
    }

    
    public function sendAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->view->disable();
            $customerMessage = new CustomerMessage();
            $subjects = null;
            $class = null;
            $teachingTime = null;
            if ($this->request->getPost('c_mgs_subjects')) {
                $subjectsPost = $this->request->getPost('c_mgs_subjects');
                if (count($subjectsPost) > 0) {
                    if (count($subjectsPost) == 1) {
                        $subjects = $subjectsPost[0];
                    } else {
                        $subjects = implode(', ', $subjectsPost);
                    }
                }
            }

            if ($this->request->getPost('c_mgs_class')) {
                $classPost = $this->request->getPost('c_mgs_class');
                if (count($classPost) > 0) {
                    if (count($classPost) == 1) {
                        $class = $classPost[0];
                    } else {
                        $class = implode(', ', $classPost);
                    }
                }
            }

            if ($this->request->getPost('c_mgs_teaching_time')) {
                $teachingTimePost = $this->request->getPost('c_mgs_teaching_time');
                if (count($teachingTimePost) > 0) {
                    if (count($teachingTimePost) == 1) {
                        $teachingTime = $teachingTimePost[0];
                    } else {
                        $teachingTime = implode(', ', $teachingTimePost);
                    }
                }
            }

            $data = [
                'subdomain_id' => $this->_subdomain_id,
                'name' => ($this->request->getPost('c_mgs_name')) ? $this->request->getPost('c_mgs_name', array('striptags', 'string')) : null,
                'phone' => ($this->request->getPost('c_mgs_phone')) ? $this->request->getPost('c_mgs_phone') : null,
                'comment' => ($this->request->getPost('c_mgs_comment')) ? $this->request->getPost('c_mgs_comment', array('striptags', 'string')) : null,
                'box_select_option' => ($this->request->getPost('c_mgs_box_option_select')) ? $this->request->getPost('c_mgs_box_option_select', array('string')) : null,
                'work_province' => ($this->request->getPost('c_mgs_work_province')) ? $this->request->getPost('c_mgs_work_province', array('string')) : null,
                'birthday' => ($this->request->getPost('c_mgs_birthday')) ? $this->mainGlobal->convertToDateType($this->request->getPost('c_mgs_birthday')) : null,
                'home_town' => ($this->request->getPost('c_mgs_home_town')) ? $this->request->getPost('c_mgs_home_town', array('string')) : null,
                'voice' => ($this->request->getPost('c_mgs_voice')) ? $this->request->getPost('c_mgs_voice', array('string')) : null,
                'address' => ($this->request->getPost('c_mgs_address')) ? $this->request->getPost('c_mgs_address', array('striptags', 'string')) : null,
                'college_address' => ($this->request->getPost('c_mgs_college_address')) ? $this->request->getPost('c_mgs_college_address', array('striptags', 'string')) : null,
                'major' => ($this->request->getPost('c_mgs_major')) ? $this->request->getPost('c_mgs_major', array('striptags', 'string')) : null,
                'graduation_year' => ($this->request->getPost('c_mgs_graduation_year')) ? $this->request->getPost('c_mgs_graduation_year', array('striptags', 'string')) : null,
                'level' => ($this->request->getPost('c_mgs_level')) ? $this->request->getPost('c_mgs_level', array('string')) : null,
                'gender' => ($this->request->getPost('c_mgs_gender')) ? $this->request->getPost('c_mgs_gender', array('string')) : null,
                'forte' => ($this->request->getPost('c_mgs_forte')) ? $this->request->getPost('c_mgs_forte', array('striptags', 'string')) : null,
                'salary' => ($this->request->getPost('c_mgs_salary')) ? $this->request->getPost('c_mgs_salary', array('striptags', 'string')) : null,
                'other_request' => ($this->request->getPost('c_mgs_other_request')) ? $this->request->getPost('c_mgs_other_request', array('striptags', 'string')) : null,
                'portrait_image' => ($this->request->getPost('c_mgs_portrait_image')) ? $this->request->getPost('c_mgs_portrait_image', array('striptags', 'string')) : null,
                'certificate_image' => ($this->request->getPost('c_mgs_certificate_image')) ? $this->request->getPost('c_mgs_certificate_image', array('striptags', 'string')) : null,
                'subjects' => $subjects,
                'class' => $class,
                'teaching_time' => $teachingTime
            ];

            $customerMessage->assign($data);

            $response = new Response();
            //Set the content of the response
            
            if ($customerMessage->save()) {
                $response->setContent(json_encode(1));
            } else {
                $response->setContent(json_encode(0));
            }

            return $response;
        }
    }
}
