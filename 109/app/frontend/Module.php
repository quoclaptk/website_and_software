<?php namespace Modules\Frontend;

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Crypt;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Files as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;

use Modules\Auth\AuthFront;
use Modules\Acl\AclFront;
use Modules\Mail\Mail;
use Modules\PHPExcel\PHPExcel;

class Module
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'Modules\Frontend\Controllers' => __DIR__.'/controllers/',
            'Modules\Backend\Models' =>   '../app/backend/models/',
            'Modules\Frontend\Plugins' => __DIR__.'/plugins/',
            'Modules' => '../app/library/',
            'Modules\Forms' => '../app/forms',
            'Modules\Helpers' => '../app/helpers',
        ));

        $loader->register();
    }

    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     */
    public function registerServices($di)
    {
        $config = include __DIR__ . "/../econfig/config.php";

        //Registering a dispatcher
        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('Modules\Frontend\Controllers');

            return $dispatcher;
        });


        $di->set('view', function () use ($config) {
            $view = new View();

            $view->setViewsDir($config->application->viewsFront);

            $view->registerEngines(array(
                    '.volt' => function ($view, $di) use ($config) {
                        $volt = new VoltEngine($view, $di);

                        $volt->setOptions(array(
                            'compiledPath' => $config->application->cacheFront . 'volt/',
                            'compiledSeparator' => '_'
                        ));
                        //load function php
                        $compiler = $volt->getCompiler();
                        $compiler->addFunction('strtotime', 'strtotime');
                        $compiler->addFunction('json_decode', 'json_decode');
                        $compiler->addFunction('file_exists', 'file_exists');
                        $compiler->addFunction('number_format', 'number_format');
                        $compiler->addFunction('count', 'count');
                        $compiler->addFunction('filter_var', 'filter_var');
                        $compiler->addFunction('ucfirst', 'ucfirst');
                        $compiler->addFunction('lcfirst', 'lcfirst');
                        $compiler->addFunction('explode', 'explode');
                        $compiler->addFunction('trim', 'trim');
                        $compiler->addFunction('do_shortcode', 'do_shortcode');
                        $compiler->addFunction('htmlDisplayShortCode', 'htmlDisplayShortCode');
                        $compiler->addFunction('htmlspecialchars_decode', 'htmlspecialchars_decode');
                        return $volt;
                    }
                ));

            return $view;
        }, true);
    }
}
