<?php

namespace Modules\Frontend\Controllers;

use Modules\Forms\PageContactForm;
use Modules\Models\Newsletter;
use Modules\Models\Setting;
use Modules\Models\Subdomain;
use Modules\PhalconVn\DirectAdmin;
use Modules\Mail\MyPHPMailer;
use Modules\PhalconVn\General;

class AjaxController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
        $this->directAdmin = new DirectAdmin();
        $this->directAdmin->connect($this->config->directAdmin->ip, $this->config->directAdmin->port);
        $this->directAdmin->set_login($this->config->directAdmin->username, $this->config->directAdmin->password);
        $this->directAdmin->set_method('get');
        $subdomain = $this->mainGlobal->getDomainInfo();
        $domain = $this->mainGlobal->getDomainCustomerInfo();
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $this->_domain = (!empty($domain)) ? $domain->name : $subdomain->name . '.' . ROOT_DOMAIN;
        $this->_url_email = (!empty($domain)) ? $protocol . $domain->name . '/' . ACP_NAME . '/orders' : $protocol . $subdomain->name . '.' . ROOT_DOMAIN . '/' . ACP_NAME . '/orders';
    }

    public function newsletterAction()
    {
        if ($this->request->isPost()) {
            $newsletter = Newsletter::findFirstByEmail($this->request->getPost("email"));
            if ($newsletter) {
                echo -1;
            } else {
                $newsletter = new Newsletter();
                $newsletter->subdomain_id = $this->_subdomain_id;
                $newsletter->email = $this->request->getPost("email");
                $newsletter->save();
                echo 1;
            }
        }
        $this->view->disable();
    }

    public function sendMailAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            if ($this->_config['_cf_send_mail'] == true) {
                $title = $this->request->getPost('title');
                $params = [
                    'name' => $title,
                    'url' => $this->_url_email
                ];
                if ($this->request->getPost('formData')) {
                    $params['formData'] = $this->request->getPost('formData');
                }

                $setting = Setting::findFirst([
                    'columns' => 'name, email_order',
                    'conditions' => 'subdomain_id = '. $this->_subdomain_id .''
                ]);

                if ($this->_config['_cf_text_email_order'] != '') {
                    $mail = new MyPHPMailer();
                    $subject = 'Bạn có '. $title .' - Mới';
                    $mail->send($this->_config['_cf_text_email_order'], $setting->name, $subject, $params);
                }
            }
        }
        $this->view->disable();
    }

    public function addToServerAction($id)
    {
        $general = new General();
        $identity = $this->auth->getIdentity();
        $subdomain = Subdomain::findFirstById($id);
        if ($subdomain && $subdomain->add_to_server == 'N') {
            $this->directAdmin->query('/CMD_API_SUBDOMAINS', array(
                'domain' => $this->config->directAdmin->hostname,
                'action' => 'create',
                'subdomain' => $subdomain->name,
            ));

            $result = $this->directAdmin->fetch_parsed_body();
            if (isset($result['error']) && $result['error'] == 0) {
                if (is_dir("../" . $subdomain->name)) {
                    $general->deleteDirectory("../" . $subdomain->name);
                }

                //delete cron_jobs
                $this->directAdmin->query('/CMD_API_CRON_JOBS', array(
                    'domain' => $this->config->directAdmin->hostname,
                    'action' => 'delete',
                    'select0' => 0,
                ));

                $sub = Subdomain::findFirstById($id);
                if ($sub) {
                    $sub->assign(['add_to_server' => 'Y']);
                    $sub->save();
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                print_r($result);
            }
        }

        $this->view->disable();
    }

    public function deleteSubOnServerAction($name)
    {
        $general = new General();
        $identity = $this->auth->getIdentity();
        $this->directAdmin->query('/CMD_API_SUBDOMAINS', array(
            'domain' => $this->config->directAdmin->hostname,
            'action' => 'delete',
            'select0' => $name,
        ));

        $result = $this->directAdmin->fetch_parsed_body();
        if (isset($result['error']) && $result['error'] == 0) {
            if (is_dir("../" . $name)) {
                $general->deleteDirectory("../" . $name);
            }

            //delete cron_jobs
            $this->directAdmin->query('/CMD_API_CRON_JOBS', array(
                'domain' => $this->config->directAdmin->hostname,
                'action' => 'delete',
                'select0' => 0,
            ));

            $result = $this->directAdmin->fetch_parsed_body();
        }

        echo $result['error'];

        $this->view->disable();
    }

    public function deleteCronJobsAction()
    {
        $this->directAdmin->query('/CMD_API_CRON_JOBS', array(
            'domain' => $this->config->directAdmin->hostname,
            'action' => 'delete',
            'select0' => 0,
        ));

        $result = $this->directAdmin->fetch_parsed_body();
        print_r($result);

        $this->view->disable();
    }

    public function ajaxRedisAction()
    {
        $this->view->disable();
        if ($this->redis->set('test', 1111)) {
            echo 'Luu redis thanh cong';
        } else {
            echo 'Luu redis that bai';
        }
    }
}
