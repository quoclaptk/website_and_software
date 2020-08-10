<?php namespace Modules\Backend\Controllers;

use Modules\Models\Subdomain;
use Modules\Models\Product;
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

class RedisController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Redis';
        $this->_message = $this->getMessage();
    }

    public function indexAction()
    {
        //Store the micro time so that we know
        //when our script started to run.
        $executionStartTime = microtime(true);
        $redis = $this->cache_service->get('redis-subdomains', ['type' => 'redis']);
        $executionEndTime = microtime(true);
        echo $seconds = $executionEndTime - $executionStartTime;
        $executionStartTime = microtime(true);
        $subdomainRedises = $this->redis->hGetAll('subdomains');
        foreach ($subdomainRedises as $subdomain) {
            $item = json_decode($subdomain);
            $all[] = $item;

            if ($item->active == 'Y' && $item->suspended == 'N' && $item->closed == 'N' && $item->deleted == 'N') {
                $active[] = $item;
            }
            
            if ($item->suspended == 'N' && $item->closed == 'N' && $item->deleted == 'N') {
                $list[] = $item;
            }
        }
        $executionEndTime = microtime(true);
        $seconds = $executionEndTime - $executionStartTime;
        echo count($subdomainRedises) . '<br>';
        echo "This script took $seconds to execute." . '<br>';
        // echo '<pre>'; print_r($subdomains); echo '</pre>';die;
        
        $start = microtime(true);
        $subdomainDbs = Subdomain::find();
        $end = microtime(true);
         
        //The result will be in seconds and milliseconds.
        $seconds = $end - $start;
        $active = [];
        $list = [];
        $all = [];
        foreach ($subdomainDbs as $subdomain) {
            $item = (object) $subdomain->toArray();
            if (count($subdomain->domain) > 0) {
                $item->domains = $subdomain->domain->toArray();
            }
            
            $all[] = $item;
            if ($subdomain->active == 'Y' && $subdomain->suspended == 'N' && $subdomain->closed == 'N' && $subdomain->deleted == 'N') {
                $active[] = (object) $item;
            }
            
            if ($subdomain->suspended == 'N' && $subdomain->closed == 'N' && $subdomain->deleted == 'N') {
                $list[] = (object) $item;
            }
        }
         
        //Print it out
        echo count($subdomainDbs) . '<br>';
        echo "This script 1 took $seconds to execute." . '<br>';
        // echo '<pre>'; print_r($subdomains); echo '</pre>';
        // echo '<pre>'; print_r($subdomainDbs->toArray()); echo '</pre>';die;
        $start = microtime(true);
        $productRedis = $this->redis->hGetAll('products');
        $end = microtime(true);
        $seconds = $end - $start;
        echo count($productRedis) . '<br>';
        echo '<pre>';
        print_r($productRedis);
        echo '</pre>';
        echo "This script took $seconds to execute." . '<br>';

        $start = microtime(true);
        $products = Product::find();
        $end = microtime(true);
        $seconds = $end - $start;
        // echo '<pre>'; print_r($productRedis); echo '</pre>';
        echo "This script took $seconds to execute." . '<br>';

        $this->view->disable();
    }

    public function testAction()
    {
        $subdomains = $this->redis->hGetAll('subdomains');
        foreach ($subdomains as $subdomain) {
            $item = json_decode($subdomain);
            $all[] = $item;

            if ($item->active == 'Y' && $item->suspended == 'N' && $item->closed == 'N' && $item->deleted == 'N') {
                $active[] = $item;
            }
            
            if ($item->suspended == 'N' && $item->closed == 'N' && $item->deleted == 'N') {
                $list[] = $item;
            }
        }

        echo '<pre>';
        print_r($list);
        echo '</pre>';

        $this->view->disable();
    }

    public function _indexAction()
    {
        $subdomains = Subdomain::find();
        foreach ($subdomains as $key => $subdomain) {
            $id = $subdomain->id;
            $subdomainValue = $subdomain->toArray();
            $this->redis->hMSet('domain' . $key, $subdomainValue);
        }

        $this->view->disable();
    }

    public function subdomainAction()
    {
        $subdomains = Subdomain::find([
            "order" => "special DESC, active DESC, id DESC"
        ]);
        $redisSubdomain = [];
        foreach ($subdomains as $key => $subdomain) {
            $id = $subdomain->id;
            $redisSubdomain[$id] = json_encode($subdomain->toArray(), JSON_UNESCAPED_UNICODE);
        }
        
        $this->redis->hMSet('subdomains', $redisSubdomain);

        $this->view->disable();
    }

    public function productAction()
    {
        $products = Product::find();
        $redisProduct = [];
        foreach ($products as $key => $product) {
            $id = $product->id;
            $redisProduct[$id] = json_encode($product->toArray(), JSON_UNESCAPED_UNICODE);
        }

        $this->redis->hMSet('products', $redisProduct);

        $this->view->disable();
    }

    public function hsetAction()
    {
        $this->view->disable();
        $subdomains = Subdomain::find([
            "conditions" => "active = 'Y'"
        ]);
        $this->redis->delete('h');
        foreach ($subdomains as $subdomain) {
            $this->redis->hSet('h', 'key1', $subdomain->id); /* 0, value was replaced. */
            
        }
        $aaa =  $this->redis->hGet('h', 'key1'); /* returns "plop" */
        var_dump($aaa);exit;
        echo '<pre>'; print_r($aaa); echo '</pre>';
        foreach (json_decode($aaa) as $key => $value) {
            echo $value->id;
        }
    }
}
