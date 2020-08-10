<?php
    require_once('vendor/autoload.php');
    use Statickidz\GoogleTranslate;

    $source = 'vi';
    $target = 'en';
    $text = 'Cảm ơn';

    $trans = new GoogleTranslate();
    $result = $trans->translate($source, $target, $text);

    echo $result;
