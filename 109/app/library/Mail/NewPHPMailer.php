<?php
namespace Modules\Mail;

use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\View;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class NewPHPMailer extends Component
{
    public function getTemplate($name, $params)
    {
        $parameters = array_merge([
            'publicUrl' => $this->config->application->publicUrl
        ], $params);

        return $this->view->getRender('emailTemplates', $name, $parameters, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

        return $view->getContent();
    }

    public function send()
    {
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = '103.216.115.34';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'noreply@110.vn';                 // SMTP username
            $mail->Password = 'IiUKlVVa';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 25;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('congngotn@gmail.com', 'Mailer');
            $mail->addAddress('congngotn@gmail.com', 'Joe User');     // Add a recipient

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 1;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function _send($to, $title, $subject, $params, $options = null)
    {
        $template = $this->getTemplate($title, $params);
        $mail = new \PHPMailer();
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
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
