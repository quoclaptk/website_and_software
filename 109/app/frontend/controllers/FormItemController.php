<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\FormItem;
use Modules\Models\Subdomain;
use Modules\Models\TmpProductFormItem;
use Phalcon\Text;

class FormItemController extends BaseController
{
    private $_subdomain_id;

    public function onConstruct()
    {
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
    }

    
    public function sendAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $formItem = new FormItem();
            $id = $this->request->getPost("frm_item_id");
            $data = [
                "subdomain_id" => $this->_subdomain_id,
                "form_group_id" => $this->request->getPost("frm_item_id"),
                "name" => ($this->request->getPost("frm_item_name_" . $id)) ? $this->request->getPost("frm_item_name_" . $id, array("striptags", "string")) : '',
                "phone" => ($this->request->getPost("frm_item_phone_" . $id)) ? $this->request->getPost("frm_item_phone_" . $id) : '',
                "email" => ($this->request->getPost("frm_item_email_" . $id)) ? $this->request->getPost("frm_item_email_" . $id, "email") : '',
                "class" => ($this->request->getPost("frm_item_class_" . $id)) ? $this->request->getPost("frm_item_class_" . $id, "striptags") : "",
                "subjects" => ($this->request->getPost("frm_item_subjects_" . $id)) ? $this->request->getPost("frm_item_subjects_" . $id, "striptags") : "",
                "studen_number" => ($this->request->getPost("frm_item_student_number_" . $id)) ? $this->request->getPost("frm_item_student_number_" . $id, "striptags") : "",
                "learning_level" => ($this->request->getPost("frm_item_learning_level_" . $id)) ? $this->request->getPost("frm_item_learning_level_" . $id, "striptags") : "",
                "learning_time" => ($this->request->getPost("frm_item_learning_time_" . $id)) ? $this->request->getPost("frm_item_learning_day_" . $id, "striptags") : "",
                "learning_day" => ($this->request->getPost("frm_item_learning_day_" . $id)) ? $this->request->getPost("frm_item_learning_time_" . $id, "striptags") : "",
                "request" => ($this->request->getPost("frm_item_request_" . $id)) ? $this->request->getPost("frm_item_request_" . $id, "striptags") : "",
                "teacher_code" => ($this->request->getPost("frm_item_teacher_code_" . $id)) ? $this->request->getPost("frm_item_teacher_code_" . $id, "striptags") : "",
                "subject" => ($this->request->getPost("frm_item_subject_" . $id)) ? $this->request->getPost("frm_item_subject_" . $id, array("striptags", "string")) : '',
                "comment" => ($this->request->getPost("frm_item_comment_" . $id)) ? $this->request->getPost("frm_item_comment_" . $id, array("striptags", "string")) : '',
                "place_pic" => ($this->request->getPost("frm_item_place_pic_" . $id)) ? $this->request->getPost("frm_item_place_pic_" . $id, array("striptags", "string")) : '',
                "place_arrive" => ($this->request->getPost("frm_item_place_arrive_" . $id)) ? $this->request->getPost("frm_item_place_arrive_" . $id, array("striptags", "string")) : '',
                "type" => ($this->request->getPost("frm_item_type_" . $id)) ? $this->request->getPost("frm_item_type_" . $id, "string") : '',
                "day" => ($this->request->getPost("frm_item_day_" . $id)) ? $this->mainGlobal->convertToDateType($this->request->getPost("frm_item_day_" . $id)) : date('Y-m-d'),
                "hour" => ($this->request->getPost("frm_item_hour_" . $id)) ? $this->request->getPost("frm_item_hour_" . $id) : '',
                "hour" => ($this->request->getPost("frm_item_hour_one_" . $id)) ? $this->request->getPost("frm_item_hour_one_" . $id) : '',
                "minute" => ($this->request->getPost("frm_item_minute_" . $id)) ? $this->request->getPost("frm_item_minute_" . $id) : '',
                "method" => ($this->request->getPost("frm_item_method_" . $id)) ? $this->request->getPost("frm_item_method_" . $id) : '',
                "number_ticket" => ($this->request->getPost("frm_item_number_ticket_" . $id)) ? $this->request->getPost("frm_item_number_ticket_" . $id) : 1,
            ];

            if ($this->request->getPost("frm_item_start_time_" . $id)) {
                $startTime = $this->request->getPost("frm_item_start_time_" . $id);
                $startTime = str_replace('/', '-', $startTime);
                $startTime = date('Y-m-d H:i:s', strtotime($startTime));
                $data['start_time'] = $startTime;
            }

            if ($this->request->getPost("frm_item_end_time_" . $id)) {
                $endTime = $this->request->getPost("frm_item_end_time_" . $id);
                $endTime = str_replace('/', '-', $endTime);
                $endTime = date('Y-m-d H:i:s', strtotime($endTime));
                $data['end_time'] = $endTime;
            }

            if ($this->request->hasFiles() == true) {
                $subFolder = $this->subdomain->folder;
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    $ext = $file->getType();
                    if (!empty($file->getName())) {
                        if ($file->getKey() == 'frm_item_file_' . $id) {
                            if ($this->extFileDocumentCheck($ext)) {
                                $fileName = basename($file->getName(), "." . $file->getExtension());
                                $fileName = $this->general->create_slug($fileName);
                                $subCode = Text::random(Text::RANDOM_ALNUM);
                                $fileFullName = $fileName . '_' . $subCode . '.' . $file->getExtension();

                                if (!is_dir("files/document")) {
                                    mkdir("files/document", 0777);
                                }

                                if (!is_dir("files/document/" . $subFolder)) {
                                    mkdir("files/document/" . $subFolder, 0777);
                                }

                                if ($file->moveTo("files/document/" . $subFolder . "/" . $fileFullName)) {
                                    $data['file'] = $fileFullName;
                                }
                            } else {
                                $this->flashSession->error('Định dạng file không cho phép. Hãy upload một hình ảnh.');
                                echo 0;
                            }
                        }
                    }
                }
            }

            $formItem->assign($data);
            if ($formItem->save()) {
                if ($this->request->getPost('product_id')) {
                    $tmpProductFormItem = new TmpProductFormItem();
                    $tmpProductFormItem->assign([
                        'subdomain_id' => $this->_subdomain_id,
                        'form_item_id' => $formItem->id,
                        'product_id' => $this->request->getPost('product_id')
                    ]);
                    $tmpProductFormItem->save();
                }
                echo 1;
            } else {
                echo 0;
            }
        }
        $this->view->disable();
    }
}
