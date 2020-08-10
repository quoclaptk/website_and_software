<?php namespace Modules\Backend;

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Loader;
use Phalcon\Crypt;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Files as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Queue\Beanstalk;

class Module
{
    public function registerAutoloaders()
    {
        $loader = new Loader();
        $loader->registerNamespaces(array(
            'Modules\Backend\Controllers'=> __DIR__.'/controllers/',
            'Modules\Backend\Plugins'	 => __DIR__.'/plugins/',
            'Modules' => '../app/library/',
            'Modules\Forms' => '../app/forms',
            'Modules\Helpers' => '../app/helpers',
            'Modules\Tasks' => '../app/tasks',
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
            $dispatcher->setDefaultNamespace('Modules\Backend\Controllers');
            return $dispatcher;
        });

        /**
         * Setting up the view component
        */
        $di->set('view', function () use ($config) {
            $view = new View();

            $view->setViewsDir($config->application->viewsBack);

            $view->registerEngines(array(
                '.volt' => function ($view, $di) use ($config) {
                    $volt = new VoltEngine($view, $di);

                    $volt->setOptions(array(
                        'compiledPath' => $config->application->cacheBack . 'volt/',
                        'compiledSeparator' => '_'
                    ));
                    //load function php
                    $compiler = $volt->getCompiler();
                    $compiler->addFunction('strtotime', 'strtotime');
                    $compiler->addFunction('json_decode', 'json_decode');
                    $compiler->addFunction('json_encode', 'json_decode');
                    $compiler->addFunction('in_array', 'in_array');
                    $compiler->addFunction('number_format', 'number_format');
                    $compiler->addFunction('file_exists', 'file_exists');
                    $compiler->addFunction('count', 'count');
                    $compiler->addFunction('ucfirst', 'ucfirst');
                    $compiler->addFunction('lcfirst', 'lcfirst');
                    $compiler->addFunction('explode', 'explode');
                    $compiler->addFunction('implode', 'implode');
                    $compiler->addFunction('trim', 'trim');
                    $compiler->addFunction('rtrim', 'rtrim');
                    return $volt;
                }
            ));

            return $view;
        }, true);
    }
}
