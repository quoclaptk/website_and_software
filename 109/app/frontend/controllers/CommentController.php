<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\CustomerComment;
use Phalcon\Text;

class CommentController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
    }

    public function indexAction()
    {
        $titleBar = $this->_word['_y_kien_khach_hang'];
        $breadcrumb = "<li class='active'>$titleBar</li>";
        $customerComments = $this->customer_comment_service->getListCustomerComment();
        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('y-kien-khach-hang') : $this->tag->site_url($langCode . '/' . $this->router->getControllerName());
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->customerComments = $customerComments ;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function sendAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $customerComment = new CustomerComment();

            $data = [
                'subdomain_id' => $this->_subdomain_id,
                'name' => ($this->request->getPost('cc_f_name')) ? $this->request->getPost('cc_f_name', array('striptags', 'string')) : '',
                'phone' => ($this->request->getPost('cc_f_phone')) ? $this->request->getPost('cc_f_phone') : '',
                'email' => ($this->request->getPost('cc_f_email')) ? $this->request->getPost('cc_f_email') : '',
                'address' => ($this->request->getPost('cc_f_address')) ? $this->request->getPost('cc_f_address') : '',
                'comment' => ($this->request->getPost('cc_f_comment')) ? $this->request->getPost('cc_f_comment', array('striptags', 'string')) : '',
                'active' => 'N'
            ];

            if ($this->request->hasFiles() == true) {
                $subFolder = $this->subdomain->folder;
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    $ext = $file->getType();
                    if (!empty($file->getName())) {
                        if ($file->getKey() == 'file_images_custom_cmts') {
                            if ($this->extImageCheck($ext)) {
                                $fileName = basename($file->getName(), "." . $file->getExtension());
                                $fileName = $this->general->create_slug($fileName);
                                $subCode = Text::random(Text::RANDOM_ALNUM);
                                $fileFullName = $fileName . '_' . $subCode . '.' . $file->getExtension();

                                 if (!is_dir("uploads")) {
                                    mkdir("uploads", 0777);
                                }

                                if (!is_dir("uploads/" . $subFolder)) {
                                    mkdir("uploads/" . $subFolder, 0777);
                                }
                                $folder_upload = "uploads/" . $subFolder . "/cdn/customer_photo/" . $fileFullName;
                                if ($file->moveTo($folder_upload)) {
                                    $data['photo'] = "/" . $folder_upload;
                                }
                            } else {
                                $this->flashSession->error('Định dạng hình không cho phép. Hãy upload một tệp tin khác.');
                                echo 0;
                            }
                        }
                    }
                }
            }

            $customerComment->assign($data);
            if ($customerComment->save()) {
                echo 1;
            } else {
                echo 0;
            }
        }

        $this->view->disable();
    }
}
