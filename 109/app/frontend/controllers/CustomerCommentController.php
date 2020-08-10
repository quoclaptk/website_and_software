<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\CustomerComment;

class CustomerCommentController extends BaseController
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
                'phone' => ($this->request->getPost('cc_f_phone')) ? $this->request->getPost('cc_f_phone', 'int') : '',
                'email' => ($this->request->getPost('cc_f_email')) ? $this->request->getPost('cc_f_email') : '',
                'photo' => ($this->request->getPost('cc_f_photo')) ? $this->request->getPost('cc_f_photo') : '',
                'address' => ($this->request->getPost('cc_f_address')) ? $this->request->getPost('cc_f_address', array('striptags', 'string')) : '',
                'comment' => ($this->request->getPost('cc_f_comment')) ? $this->request->getPost('cc_f_comment', array('striptags', 'string')) : '',
                'active' => 'N'
            ];

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
