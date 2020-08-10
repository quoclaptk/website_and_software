<?php
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

define('HOST', $_SERVER['HTTP_HOST']);
define('HTTP_HOST', '//'.$_SERVER['HTTP_HOST']);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('ACP_NAME', 'hi');
define('ROW_ID_COOKIE_TIME', 2592000);
define('ROOT_DOMAIN', getenv('ROOT_DOMAIN'));
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('PROTOCOL', $protocol);
define('MODELS', 'Modules\Models');
define('MODELCACHE', 'modelsCache');
define('LANGDEFAULTID', 1);
define('LANGDEFAULTCODE', 'vi');
define('_upload_default', 'files/default/');
define('_upload_news', 'files/news/');
define('_upload_product', 'files/product/');
define('_upload_video', 'files/video_media/media/');
define('_upload_tv', 'files/tv/');
define('_upload_youtube', 'files/youtube/');
define('_upload_layout', 'files/layout/');
