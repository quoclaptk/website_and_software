<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Orders;
use Modules\Models\Subdomain;
use Modules\Models\Product;
use Modules\Models\Setting;
use Modules\Forms\Frontend\PageCheckoutForm;
use Phalcon\Security\Random;
use Modules\Mail\MyPHPMailer;

class CheckoutController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
        $subdomain = $this->mainGlobal->getDomainInfo();
        $domain = $this->mainGlobal->getDomainCustomerInfo();
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $this->_domain = (!empty($domain)) ? $domain->name : $subdomain->name . '.' . ROOT_DOMAIN;
        $this->_url_email = (!empty($domain)) ? $protocol . $domain->name . '/' . ACP_NAME . '/orders?active=order' : $protocol . $subdomain->name . '.' . ROOT_DOMAIN . '/' . ACP_NAME . '/orders?active=order';
        $this->_url_order_success = $this->languageCode == 'vi' ? 'dat-hang-thanh-cong' : $this->languageCode . '/orderSuccess';
    }

    public function indexAction()
    {
        if (empty($this->cart_service->getContent())) {
            return $this->dispatcher->forward(['controller' => 'index', 'action' => 'notfound']);
        }
        $titleBar = $this->_word['_thanh_toan'];
        $breadcrumb = "<li class='active'>$titleBar</li>";

        $identity = $this->authFront->getIdentity();
        $fullName = ($identity) ? $identity['fullName'] : '';
        $email = ($identity) ? $identity['email'] : '';
        $phone = ($identity) ? $identity['phone'] : '';
        $address = ($identity) ? $identity['address'] : '';

        $form = new PageCheckoutForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $orders = new Orders();
            $random = new Random();

            $orderInfo = array();
            $currencyOdr = !empty($this->_config['_cf_send_mail']) ?  $this->_config['_cf_send_mail'] : 'VNĐ';
            foreach ($this->cart_service->getContent() as $row) {
                $item["id"] = $row["id"];
                $item["name"] = $row["name"];
                $item["qty"] = $row["qty"];
                $item["link"] = $row["link"];
                $item["photo"] = $row["photo"];
                $item["price"] = $row["price"];
                $item["currency"] = $row["currency"] ?? 'VNĐ';
                $item["total"] = $row["total"];
                if (isset($row['options'])) {
                    $item["options"] = $row['options'];
                }

                $currencyOdr = $item["currency"];
                $orderInfo[] = $item;
            }

            $data = [
                "subdomain_id" => $this->_subdomain_id,
                "name" => $this->request->getPost('name', array('striptags', 'string')),
                "email" => $this->request->getPost("email"),
                "phone" => $this->request->getPost("phone"),
                "address" => $this->request->getPost('address', array('striptags', 'string')),
                "comment" => $this->request->getPost('comment', array('striptags', 'string')),
                "payment_method" => $this->request->getPost("payment_method"),
                "order_info" => json_encode($orderInfo, JSON_UNESCAPED_UNICODE),
                "total" => $this->cart_service->getTotal(),
                "currency" => $currencyOdr,
                "code" => $random->base58(7),
                "order_status" => 1,
                "member_id" => $identity ? $identity['id'] : 0
            ];

            $orders->assign($data);
            if ($orders->save()) {
                $this->cart_service->destroy();
                if ($this->_config['_cf_send_mail'] == true) {
                    $setting = Setting::findFirst([
                        'columns' => 'name, email_order',
                        'conditions' => 'subdomain_id = '. $this->_subdomain_id .''
                    ]);

                    $formData = [
                        "name" => $orders->name,
                        "email" => $orders->email,
                        "phone" => $orders->phone,
                        "address" => $orders->address,
                        "comment" => $orders->comment,
                        "total" => $orders->total,
                        "order_currency" => $orders->currency,
                        "code" => $orders->code,
                        "order_info" => $orders->order_info,
                        "payment_method" => $orders->payment_method == 1 ? $this->_word->_('_thanh_toan_khi_nhan_hang') : $this->_word->_('_thanh_toan_chuyen_khoan_qua_ngan_hang')
                    ];

                    if ($this->_config['_cf_text_email_order'] != '') {
                        $mail = new MyPHPMailer();
                        $params = [
                            'type' => 'order',
                            'name' => 'đơn hàng',
                            'url' => $this->_url_email,
                            'formData' => $formData
                        ];
                        $mail->send($this->_config['_cf_text_email_order'], $setting->name, "Bạn có đơn hàng mới từ ". $this->_domain ."", $params);
                    }
                }
                
                $this->flashSession->success($this->_word->_('_message_dat_hang_thanh_cong'));
                return $this->response->redirect($this->_url_order_success);
            }
        }

        $this->view->cart = $this->cart_service->getContent();
        $this->view->form = $form;
        $this->view->fullName = $fullName;
        $this->view->email = $email;
        $this->view->phone = $phone;
        $this->view->address = $address;
        $this->view->title_bar = $titleBar;
        $this->view->title = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function successAction()
    {
        if (!empty($this->cart_service->getContent())) {
            return $this->dispatcher->forward(['controller' => 'index', 'action' => 'notfound']);
        }
        $titleBar = $this->_word->_('_dat_hang_thanh_cong');
        $breadcrumb = "<li class='active'>$titleBar</li>";

        $this->view->title = $titleBar;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }
}
