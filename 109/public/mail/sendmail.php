<?php
    //goi thu vien
    include('class.smtp.php');
    include "class.phpmailer.php";
    include "functions.php";
    $title = 'mail guide';
    $content = 'test send sendmail';
    $nTo = 'Huudepzai';
    $mTo = 'congngotn@gmail.com';
    $diachi = 'noreply@110.vn';
    //test gui mail
    $mail = sendMail($title, $content, $nTo, $mTo, $diachicc='');
    if ($mail==1) {
        echo 'mail của bạn đã được gửi đi hãy kiếm tra hộp thư đến để xem kết quả. ';
    } else {
        echo 'Co loi!';
    }
