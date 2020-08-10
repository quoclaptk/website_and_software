<?php
function sendMail($title, $content, $nTo, $mTo, $diachicc='')
{
    $nFrom = '110.vn';
    $mFrom = 'noreaply@hopgiamtoc.net';	//dia chi email cua ban
    $mPass = '123456';		//mat khau email cua ban
    $mail             = new PHPMailer();
    $body             = $content;
    $mail->IsSMTP();
    $mail->CharSet 	= "utf-8";
    $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                  	// enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = "103.1.237.164";
    $mail->Port       = 25;
    $mail->Username   = $mFrom;  // GMAIL username
    $mail->Password   = $mPass;           	 // GMAIL password
    $mail->SetFrom($mFrom, $nFrom);
    //chuyen chuoi thanh mang
    $ccmail = explode(',', $diachicc);
    $ccmail = array_filter($ccmail);
    if (!empty($ccmail)) {
        foreach ($ccmail as $k => $v) {
            $mail->AddCC($v);
        }
    }
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->Subject    = $title;
    $mail->MsgHTML($body);
    $address = $mTo;
    $mail->AddAddress($address, $nTo);
    $mail->AddReplyTo('info@110.vn', '110.vn');
    if (!$mail->Send()) {
        return 0;
    } else {
        return 1;
    }
}

function sendMailAttachment($title, $content, $nTo, $mTo, $diachicc='', $file, $filename)
{
    $nFrom = 'Freetuts.net';
    $mFrom = 'xxxx@gmail.com';	//dia chi email cua ban
    $mPass = 'passlamatkhua';		//mat khau email cua ban
    $mail             = new PHPMailer();
    $body             = $content;
    $mail->IsSMTP();
    $mail->CharSet 	= "utf-8";
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                  	// enable SMTP authentication
    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";
    $mail->Port       = 465;
    $mail->Username   = $mFrom;  // GMAIL username
    $mail->Password   = $mPass;           	 // GMAIL password
    $mail->SetFrom($mFrom, $nFrom);
    //chuyen chuoi thanh mang
    $ccmail = explode(',', $diachicc);
    $ccmail = array_filter($ccmail);
    if (!empty($ccmail)) {
        foreach ($ccmail as $k => $v) {
            $mail->AddCC($v);
        }
    }
    $mail->Subject    = $title;
    $mail->MsgHTML($body);
    $address = $mTo;
    $mail->AddAddress($address, $nTo);
    $mail->AddReplyTo('info@freetuts.net', 'Freetuts.net');
    $mail->AddAttachment($file, $filename);
    if (!$mail->Send()) {
        return 0;
    } else {
        return 1;
    }
}
