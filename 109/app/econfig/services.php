<?php

use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Crypt;
use Phalcon\Mvc\Dispatcher  as PhDispatcher;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Files as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;

use Phalcon\Db\Dialect\MySQL as SqlDialect;

use Modules\Auth\Auth;
use Modules\Auth\AuthFront;
use Modules\Acl\Acl;
use Modules\Acl\AclFront;
use Modules\Mail\Mail;
use Modules\Translate\Locale;
use Modules\Elements;

use Modules\PhalconVn\MainGlobal;
use Modules\PhalconVn\Tag;
use Modules\PhalconVn\ModuleItemService;
use Modules\PhalconVn\CategoryService;
use Modules\PhalconVn\ProductService;
use Modules\PhalconVn\NewsService;
use Modules\PhalconVn\MenuService;
use Modules\PhalconVn\BannerService;
use Modules\PhalconVn\NewsCategoryService;
use Modules\PhalconVn\NewsMenuService;
use Modules\PhalconVn\ClipService;
use Modules\PhalconVn\CustomerCommentService;
use Modules\PhalconVn\UsuallyQuestionService;
use Modules\PhalconVn\WordTranslateService;
// use Modules\PhalconVn\CacheService;
use Modules\PhalconVn\ConfigService;
use Modules\PhalconVn\PostService;
use Modules\PhalconVn\SubdomainService;
use Modules\PhalconVn\ElasticService;
use Modules\PhalconVn\PaginationService;
use Modules\PhalconVn\ImageService;
use Modules\PhalconVn\UploadService;
use Modules\PhalconVn\RedisService;
use Modules\PhalconVn\General;
use Modules\PhalconVn\Counter;
use Modules\Helpers\ProductHelper;
use Modules\Helpers\Helper;
use Modules\Helpers\NewsHelper;
use Modules\Helpers\BannerHelper;
use Modules\Helpers\FormHelper;
use Modules\Logger\MyDbListener;
use Elasticsearch\ClientBuilder;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use Modules\Shopping\ShoppingCart;

use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Cache\Backend\Redis as RedisCache;

use Modules\Repositories\CategoryRepository;
use Modules\Repositories\ProductRepository;
use Modules\Repositories\ClipRepository;
use Modules\Repositories\LandingPageRepository;
use Modules\Repositories\NewsMenuRepository;
use Modules\Repositories\NewsRepository;
use Modules\Repositories\SettingRepository;

$loader = new Loader();

$loader->registerNamespaces(array(
    'Modules\Frontend\Controllers' => __DIR__.'/controllers/',
    'Modules\Backend\Models' =>   '../app/backend/models/',
    'Modules\Frontend\Plugins' => __DIR__.'/plugins/',
    'Modules' => '../app/library/',
    'Modules\Models' => '../app/models/',
    'Modules\Forms' => '../app/forms',
    'Modules\Helpers' => '../app/helpers',
    'Modules\Tasks' => '../app/tasks',
    'Modules\Logger' => '../app/logger',
    'Modules\Repositories' => '../app/repositories',
));

$loader->register();
/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Register the global configuration as config
 */
$di->set('config', $config);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
}, true);

/**
 * Setting up the view component backand and forntend in module.php
 */
$di->set('view', function () use ($config) {
    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {
            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir . 'volt/',
                'compiledSeparator' => '_'
            ));

            $compiler = $volt->getCompiler();
            $compiler->addFunction('number_format', 'number_format');

            return $volt;
        }
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */

$di->set('db', function () use ($config) {
    $eventsManager = new EventsManager();

    // Create a database listener
    /*
    $dbListener = new MyDbListener();

    // set log slow query
    $eventsManager->attach(
        'db',
        $dbListener
    );
    */

    /*$logger = new Phalcon\Logger\Adapter\File(__DIR__ ."/../log/debug.log");
    //Listen all the database events
    $eventsManager->attach('db', function($event, $connection) use ($logger) {
    if ($event->getType() == 'beforeQuery') {
        $logger->log($connection->getSQLStatement(), Phalcon\Logger::INFO);
    }
    });*/
    $connection = new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset'=> 'utf8'
    ));
    //Assign the eventsManager to the db adapter instance
    $connection->setEventsManager($eventsManager);

    
    return $connection;
});

$di->setShared("redis", function () {
    $redis = new Redis();
    $redis->pconnect(getenv('REDIS_HOST'), getenv('REDIS_PORT'));
    $redis->select(getenv('REDIS_TABLE'));
    return $redis;
});

/*$di->setShared('amqconnection', function () {
    return new AMQPStreamConnection('llama-01.rmq.cloudamqp.com', 5672, 'avevegll', 'Y5iKnxqEanxWa2JK5tDY8tVQD82jSn6r', 'avevegll');
});*/


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
/*$di->set('modelsMetadata', function() use ($config) {
    return new MetaDataAdapter(array(
        'metaDataDir' => $config->application->cacheDir . 'metaData/'
    ));
});*/

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();
    return $session;
});


/**
 * Crypt service
 */
$di->set('crypt', function () use ($config) {
    $crypt = new Crypt();
    $crypt->setKey($config->application->cryptSalt);
    return $crypt;
});

/**
 * Dispatcher use a default namespace in Module backend and fondend
 */
/*$di->set('dispatcher', function() {
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('Eduapps\Backend\Controllers');
    return $dispatcher;
});*/
/**
* Setting up the menu componet
*/
$di->set(
    'dispatcher',
    function () use ($di) {
        $evManager = $di->getShared('eventsManager');

        $evManager->attach(
            "dispatch:beforeException",
            function ($event, $dispatcher, $exception) {
                switch ($exception->getCode()) {
                    case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward(
                            array(
                                'controller' => 'index',
                                'action'     => 'notfound',
                            )
                        );
                        return false;
                }
            }
        );
        $dispatcher = new PhDispatcher();
        $dispatcher->setEventsManager($evManager);
        return $dispatcher;
    },
    true
);

$di->set('elastic', function () {
    $clientBuilder = ClientBuilder::create();
    $clientBuilder->setHosts([getenv('ELASTICSEARCH_HOST') . ':' . getenv('ELASTICSEARCH_PORT') ]);
    $client = $clientBuilder->build();
    return $client;
});

$di->set('elements', function () {
    return new Elements();
}, true);

/**
 * Loading routes from the routes.php file
 */
$di->set('router', function () {
    return require __DIR__ . '/routes.php';
});

/**
 * Flash service with custom CSS classes
 */
$di->set('flash', function () {
    return new Flash(array(
        'warning' => 'alert alert-warning',
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success alert-dismissable',
        'notice' => 'alert alert-info',
    ));
});


// Set up the flash session service
$di->set(
    'flashSession',
    function () {
        return new FlashSession(
            [
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning',
            ]
        );
    }
);


/**
 * Custom authentication component
 */
$di->set('auth', function () {
    return new Auth();
});
//front end auth
$di->set('authFront', function () {
    return new AuthFront();
});

/**
 * Mail service uses AmazonSES
 */
$di->set('mail', function () {
    return new Mail();
});

/**
 * Access Control List
 */
$di->set('acl', function () {
    return new Acl();
});

$di->set('aclFront', function () {
    return new AclFront();
});

$di->set('locale', function () {
    return new Locale();
});

// elastic service
$di->set('elastic_service', function () {
    return new ElasticService();
});

// pagination
$di->set('pagination_service', function () {
    return new PaginationService();
});

$di->set('general', function () {
    return new General();
});

$di->set('mainGlobal', function () {
    return new MainGlobal();
});

$di->set('general', function () {
    return new General();
});

$di->set('counter', function () {
    return new Counter();
});

//cache
$di->set('redis_service', function () {
    return new RedisService();
});

$di->set('categoryRepository', function () {
    return new CategoryRepository(new \Modules\Models\Category());
});

$di->set('productRepository', function () {
    return new ProductRepository(new \Modules\Models\Product());
});

$di->set('newsMenuRepository', function () {
    return new NewsMenuRepository(new \Modules\Models\NewsMenu());
});

$di->set('newsRepository', function () {
    return new NewsRepository(new \Modules\Models\News());
});

$di->set('landingPageRepository', function () {
    return new LandingPageRepository(new \Modules\Models\LandingPage());
});

$di->set('clipRepository', function () {
    return new ClipRepository(new \Modules\Models\Clip());
});

$di->set('settingRepository', function () {
    return new SettingRepository(new \Modules\Models\Setting());
});

/*$di->set('cache_service', function () {
    return new CacheService();
});*/

$di->set('config_service', function () {
    return new ConfigService();
});

$di->set('tag', function () {
    return new Tag();
});

$di->set('upload_service', function () {
    return new UploadService();
});

$di->set('module_item_service', function () {
    return new ModuleItemService();
});

$di->set('category_service', function () {
    return new CategoryService();
});

$di->set('news_menu_service', function () {
    return new NewsMenuService();
});

$di->set('product_service', function () {
    return new ProductService();
});

$di->set('news_service', function () {
    return new NewsService();
});

$di->set('menu_service', function () {
    return new MenuService();
});

$di->set('banner_service', function () {
    return new BannerService();
});

$di->set('news_category', function () {
    return new NewsCategoryService();
});

$di->set('clip_service', function () {
    return new ClipService();
});

$di->set('customer_comment_service', function () {
    return new CustomerCommentService();
});

$di->set('usually_question_service', function () {
    return new UsuallyQuestionService();
});

$di->set('post_service', function () {
    return new PostService();
});

$di->set('word_translate', function () {
    return new WordTranslateService();
});

$di->set('subdomain_service', function () {
    return new SubdomainService();
});

$di->set('image_service', function () {
    return new ImageService();
});

//shopping
$di->set('cart_service', function () {
    return new ShoppingCart();
});

//helper
$di->set('product_helper', function () {
    return new ProductHelper();
});

$di->set('news_helper', function () {
    return new NewsHelper();
});


$di->set('banner_helper', function () {
    return new BannerHelper();
});

//helper
$di->set('form_helper', function () {
    return new FormHelper();
});


//helper
$di->set('helper', function () {
    return new Helper();
});
