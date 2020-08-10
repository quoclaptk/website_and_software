<?php
namespace Modules\Mail;

use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\View;

require('phpmailer/class.phpmailer.php');
require('phpmailer/class.smtp.php');

class MyPHPMailer extends Component
{
    public function getTemplate($name, $params)
    {
        $parameters = array_merge([
            'publicUrl' => $this->config->application->publicUrl,
            'word' => $this->word_translate->getWordTranslation()
        ], $params);
        $viewTemplate = 'emailTemplates';
        if (isset($params['type']) && $params['type'] == 'order') {
            $viewTemplate = 'emailOrderTemplates';
        }

        return $this->view->getRender($viewTemplate, $name, $parameters, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

        return $view->getContent();
    }


    public function send($to, $title, $subject, $params, $options = null)
    {
        $template = $this->getTemplate($title, $params);
        $mail = new \PHPMailer();
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->Host       = $this->config->mail->smtp->server; // SMTP server
        if (!empty($this->config->mail->smtp->secure)) {
            $mail->SMTPSecure = $this->config->mail->smtp->secure;   // sets the prefix to the servier
        }                  //
        $mail->Username   = $this->config->mail->smtp->username; // SMTP account username
        $mail->Password   = $this->config->mail->smtp->password;
        $mail->Port = $this->config->mail->smtp->port ; // or 587
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->IsHTML(true);
        $mail->SetFrom($this->config->mail->smtp->username, $title);
        $mail->CharSet = "utf-8";
        $mail->Timeout       =   30;
        $mail->AddAddress($to);
        $mail->Subject = $subject;
        $mail->Body    = $template;
        if ($mail->send()) {
            echo 1;
        } else {
            echo 0;
        }
    }
}
