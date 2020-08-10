<?php
return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => getenv('DATABASE_HOST'),
        'username'    => getenv('DATABASE_USER'),
        'password'    => getenv('DATABASE_PASS'),
        'dbname'      => getenv('DATABASE_NAME'),
        'charset'     => 'utf8',
    ),
    'application' => array(
        'controllersBack' => __DIR__ . '/../../app/backend/controllers/',
        'messages'      => __DIR__ . '/../../app/messages/',
        'formsBack'       => __DIR__ . '/../../app/forms/',
        'viewsBack'       => __DIR__ . '/../../app/backend/views/',
        'libraryBack'     => __DIR__ . '/../../app/backend/library/',
        'pluginsBack'     => __DIR__ . '/../../app/backend/plugins/',
        'cacheBack'       => __DIR__ . '/../../app/backend/cache/',
        'viewsFront'      => __DIR__ . '/../../app/frontend/views/',
        'cacheFront'      => __DIR__ . '/../../app/frontend/cache/',
        'task'      => __DIR__ . '/../../app/tasks/',
        'baseUriShort'        	 => '',
        'publicUrl'		     => '118.phalconphp.com',
        'cryptSalt'		     => '$9diko$.f#11',
        'appDir'         => APP_PATH . '/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ),
    'media' => array(
        'upload_path' => __DIR__ . '/../../public/files/',
        'minResize' => 102400,
        'maxSize' => 512000,
        'thumb' => array(
            'width' => 600,
            'height' => 600,
        )
    ),
    'directAdmin' => array(
        'ip' => getenv('DIRECTADMIN_IP'),
        'oldhostname' => getenv('DIRECTADMIN_OLDHOST'),
        'hostname' => getenv('DIRECTADMIN_HOST'),
        'port' => getenv('DIRECTADMIN_PORT'),
        'username' => getenv('DIRECTADMIN_USER'),
        'password' => getenv('DIRECTADMIN_PASS'),
    ),
    'mail' => array(
        'fromName' => '110.vn',
        'fromEmail' => '110',
        'smtp' => array(
            'server' => '45.117.169.19',
            'port' => 587,
            'secure' => 'tls',
            'username' => 'noreply@110.vn',
            'password' => 'IiUKlVVa',
        )
    ),
    'queue' => array(
        'host' => '192.168.0.21',
        'port' => '11300',
    ),
    'google' => array(
        'API_KEY' => 'AIzaSyBxVuzR8jyoU49rwObfy3jZP9fpzJA33To'
    ),
    'amazon' => array(
        'AWSAccessKeyId' => "",
        'AWSSecretKey' => ""
    )
));
