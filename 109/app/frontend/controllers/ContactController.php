<?php

namespace Modules\Frontend\Controllers;

use Modules\Forms\Frontend\PageContactForm;
use Modules\Models\Contact;
use Modules\Models\Subdomain;
use Modules\Models\Setting;
use Modules\Mail\MyPHPMailer;

class ContactController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
        $subdomain = $this->mainGlobal->getDomainInfo();
        $domain = $this->mainGlobal->getDomainCustomerInfo();
        $this->_domain = (!empty($domain)) ? $domain->name : $subdomain->name . '.' . ROOT_DOMAIN;
        $this->_url_email = (!empty($domain)) ? '//' . $domain->name . '/' . ACP_NAME . '/orders' : '//' . $subdomain->name . '.' . ROOT_DOMAIN . '/' . ACP_NAME . '/orders';
    }

    public function indexAction()
    {
        $form = new PageContactForm();
        $titleBar = $this->_word['_lien_he'];
        $breadcrumb = "<li class='active'>$titleBar</li>";

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $contact = new Contact();

            $data = [
                "subdomain_id" => $this->_subdomain_id,
                "name" => $this->request->getPost('name', array("striptags", "string")),
                "email" => $this->request->getPost("email"),
                "phone" => $this->request->getPost("phone"),
                "address" => $this->request->getPost("address", array("striptags", "string")),
                "subject" => $this->request->getPost("subject", array("striptags", "string")),
                "comment" => $this->request->getPost("comment", array("striptags", "string")),
            ];

            $formData = [
                "name" => $data['name'],
                "email" => $data['email'],
                "phone" => $data['phone'],
                "address" => $data['address'],
                "comment" => $data['comment'],
            ];

            $contact->assign($data);
            if ($contact->save()) {
                if ($this->_config['_cf_send_mail'] == true) {
                    $setting = Setting::findFirst([
                        'columns' => 'name, email_order',
                        'conditions' => 'subdomain_id = '. $this->_subdomain_id .''
                    ]);

                    if ($this->_config['_cf_text_email_order'] != '') {
                        $mail = new MyPHPMailer();
                        $params = [
                            'name' => 'thư liên hệ',
                            'url' => $this->_url_email,
                            'formData' => $formData
                        ];
                        $mail->send($this->_config['_cf_text_email_order'], $setting->name, "Bạn có thư liên hệ mới từ ". $this->_domain ."", $params);
                    }
                }
                $this->flashSession->success("Bạn đã gửi thông tin liên hệ thành công. Chúng tôi sẽ phản hồi lại bạn trong thời gian sớm nhất!");
                return $this->response->redirect('/lien-he');
            }
        }

        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('lien-he') : $this->tag->site_url($langCode . '/' . $this->router->getControllerName());
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->form = $form;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function sendFormAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $contact = new Contact();

            $data = [
                'subdomain_id' => $this->_subdomain_id,
                'name' => ($this->request->getPost('name')) ? $this->request->getPost('name', array('striptags', 'string')) : '',
                'phone' => ($this->request->getPost('phone')) ? $this->request->getPost('phone') : '',
                'email' => ($this->request->getPost('email')) ? $this->request->getPost('email') : '',
                'address' => ($this->request->getPost('address')) ? $this->request->getPost('address') : '',
                'subject' => ($this->request->getPost('subject')) ? $this->request->getPost('subject', array('striptags', 'string')) : '',
                'comment' => ($this->request->getPost('comment')) ? $this->request->getPost('comment', array('striptags', 'string')) : '',
            ];

            $contact->assign($data);
            if ($contact->save()) {
                echo 1;
            } else {
                echo 0;
            }
        }

        $this->view->disable();
    }

    public function layoutAction($layout = 1)
    {
        $this->view->setTemplateBefore('demo0'. $layout);
        $form = new PageContactForm();
        $titleBar = "Liên hệ";
        $breadcrumb = "<li class='active'>$titleBar</li>";

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $contact = new Contact();

            $data = [
                "subdomain_id" => $this->_subdomain_id,
                "name" => $this->request->getPost('name', array("striptags", "string")),
                "email" => $this->request->getPost("email"),
                "phone" => $this->request->getPost("phone"),
                "address" => $this->request->getPost("address", array("striptags", "string")),
                "subject" => $this->request->getPost("subject", array("striptags", "string")),
                "comment" => $this->request->getPost("comment", array("striptags", "string")),
            ];

            $contact->assign($data);
            if ($contact->save()) {
                $this->flashSession->success("Bạn đã gửi thông tin liên hệ thành công. Chúng tôi sẽ phản hồi lại bạn trong thời gian sớm nhất!");
                return $this->response->redirect('/lien-he');
            }
        }

        $this->view->layout_router = $layout;
        $this->view->layout = $layout;
        $this->view->form = $form;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->pick($this->_getControllerName() . '/index');
    }

    public function demmoAction($subdomainName)
    {
        $subdomain = Subdomain::findFirstByName($subdomainName);
        if ($subdomain) {
            $layoutInfo = $this->mainGlobal->getLayoutTemplate($subdomain);
            $layout = $layoutInfo['layout'];
            $layout_id = $layoutInfo['layout_id'];
            $this->view->setTemplateBefore($layout);
            $this->view->setTemplateBefore('demo0'. $layout);
            $form = new PageContactForm();
            $titleBar = "Liên hệ";
            $breadcrumb = "<li class='active'>$titleBar</li>";

            if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
                $contact = new Contact();

                $data = [
                    "subdomain_id" => $this->_subdomain_id,
                    "name" => $this->request->getPost('name', array("striptags", "string")),
                    "email" => $this->request->getPost("email"),
                    "phone" => $this->request->getPost("phone"),
                    "address" => $this->request->getPost("address", array("striptags", "string")),
                    "subject" => $this->request->getPost("subject", array("striptags", "string")),
                    "comment" => $this->request->getPost("comment", array("striptags", "string")),
                ];

                $contact->assign($data);
                if ($contact->save()) {
                    $this->flashSession->success("Bạn đã gửi thông tin liên hệ thành công. Chúng tôi sẽ phản hồi lại bạn trong thời gian sớm nhất!");
                    return $this->response->redirect('/lien-he');
                }
            }

            $this->view->demo_router = $subdomain->name;
            $this->view->demo_folder = $subdomain->folder;
            $this->view->layout = $layout_id;
            $this->view->form = $form;
            $this->view->title_bar = $titleBar;
            $this->view->breadcrumb = $breadcrumb;
            $this->view->pick($this->_getControllerName() . '/index');
        } else {
            return $this->dispatcher->forward(['controller' => 'index', 'action' => 'notfound']);
        }
    }

    public function sendAction()
    {
        if ($this->request->isPost() == true) {
            $name = $this->request->getPost('name', array('striptags', 'string'));
            $email = $this->request->getPost('email', 'email');
            $comments = $this->request->getPost('comments', array('striptags', 'string'));

            $contact = new Contact();
            $contact->name = $name;
            $contact->email = $email;
            $contact->comments = $comments;
            $contact->created_at = new \Phalcon\Db\RawValue('now()');
            if ($contact->save() == false) {
                foreach ($contact->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $this->flash->success('Thanks, We will contact you in the next few hours');
                return $this->dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ));
            }
        }
        return $this->dispatcher->forward(array(
                        'controller' => 'contact',
                        'action' => 'index'
            ));
    }
}
