<?php
error_reporting(E_ALL);
//error_reporting(0);

date_default_timezone_set('Asia/Ho_Chi_Minh');
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 300);
// echo Phalcon\Version::get();
try {
    /**
     *
     * load env environmentn
     *
     */
    define('PROJECT_PATH', realpath('..'));
    require_once __DIR__ . '/../vendor/autoload.php';
    (new Dotenv\Dotenv(PROJECT_PATH))->load();
    
    /**
     * Read the configuration
     */
    include __DIR__ . "/../app/econfig/define.php";

    $config = include __DIR__ . "/../app/econfig/config.php";

    /**
     * Read auto-loader in Moudel in backeend vs forntend
     */
    //include __DIR__ . "/../app/econfig/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/econfig/services.php";	
    /**
     * Handle the request
     */

    /**
     * Load shortcode resources
     */
    include __DIR__ . "/../app/resources/shortcode/app.php";
    $baseUriShort = (!empty($config->application->baseUriShort)) ?  $config->application->baseUriShort : null;
    define('_URL', '//'.$_SERVER["SERVER_NAME"] . $baseUriShort);

    $application = new \Phalcon\Mvc\Application();
    $application->setDI($di);
    $application->registerModules(array(
                'frontend' => array(
                    'className' => 'Modules\Frontend\Module',
                    'path' => '../app/frontend/Module.php'
                ),
                'backend' => array(
                    'className' => 'Modules\Backend\Module',
                    'path' => '../app/backend/Module.php'
                )
    ));

    echo $application->handle()->getContent();
} catch (\Exception $e) {
    if (getenv('APP_ENV') == 'development') {
        echo get_class($e), ': ', $e->getMessage(), '\n';
        echo ' File=', $e->getFile(), '\n';
        echo ' Line=', $e->getLine(), '\n';
        echo $e->getTraceAsString();
    }
}
