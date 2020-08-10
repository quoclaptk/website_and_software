<?php namespace Modules\Backend\Controllers;

use Modules\Models\Subdomain;
use Modules\Models\Domain;
use Modules\Models\Product;
use Modules\Models\News;
use Modules\Forms\CategoryForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Security\Random;
use Phalcon\Image\Adapter\GD;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;
use Modules\PhalconVn\DirectAdmin;

class CronjobController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Cronjob';
        $this->directAdmin = new DirectAdmin();
        $this->directAdmin->connect($this->config->directAdmin->ip, $this->config->directAdmin->port);
        $this->directAdmin->set_login($this->config->directAdmin->username, $this->config->directAdmin->password);
        $this->directAdmin->set_method('get');
        $this->_message = $this->getMessage();
    }

    /**
     * Create cronjob 001
     *
     * @param int $page
     * 
     * @return array [Response create cronjob on server]
     */
    public function createJob001Action($page = 1)
    {
        $offset = $page * 500;

        $subdomains = Subdomain::find([
            "columns" => "id, folder",
            "order" => "id",
        ]);

        $messageFolder = $this->config->application->messages;
        if ($subdomains->count() > 0) {
            foreach ($subdomains as $key => $subdomain) {
                $id = $subdomain->id;
                $dir = $messageFolder . "subdomains/". $subdomain->folder;
                if (!is_dir($dir)) {
                    $time = $key * 5;
                    $hour = date("H", time() + $time);
                    $minute = date("i", time() + $time);
                    $day = date('d', time() + $time);
                    $month = date('m', time() + $time);
                    $this->directAdmin->query('/CMD_API_CRON_JOBS', array(
                        'domain' => $this->config->directAdmin->hostname,
                        'action' => 'create',
                        'minute' => $minute,
                        'hour' => $hour,
                        'dayofmonth' => $day,
                        'month' => $month,
                        'dayofweek' => '*',
                        'command' => '/usr/bin/wget -O /dev/null 110.vn/cronjob/convertWord/' . $id
                    ));
                }
            }
        }

        $this->view->disable();
    }
}
