<?php namespace Modules\Backend\Controllers;


use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Image\Adapter\GD;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Glide\ServerFactory;
use League\Glide\Urls\UrlBuilderFactory;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureException;


class GlideImageController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Admin';
        $this->_message = $this->getMessage();
    }



    /**
     * simple example glide image php 
     * 
     * @return Image re-seize
     */
    public function reSizeAction()
    {
        // $this->view->disable();
        // try {
        //     // Set complicated sign key
        //     $signkey = 'v-LK4WCdhcfcc%jt*VC2cj%nVpu+xQKvLUA%H86kRVk_4bgG8&CWM#k*b_7MUJpmTc=4GFmKFp7=K%67je-skxC5vz+r#xT?62tT?Aw%FtQ4Y3gvnwHTwqhxUh89wCa_';
        //     // var_dump(md5($signkey));exit;
        //     // Validate HTTP signature
        //     $params = $this->request->get();
        //     $asd = $params['s'];
        //     unset($params['s']);
        //     ksort($params);
        //     $path = md5($signkey.':'.ltrim($asd, '/').'?'.http_build_query($params));
        //     $params['s'] = md5($signkey.':'.ltrim($path, '/').'?'.http_build_query($params));
        //     // var_dump($this->request->get());
        //     // var_dump($params);exit;
        //     $var = SignatureFactory::create($signkey)->validateRequest($path, $params);
            $server = ServerFactory::create([
            'source' => new Filesystem(new Local(PROJECT_PATH . '/public/assets/images/')),
            'cache' => new Filesystem(new Local(PROJECT_PATH . '/public/assets/images/cache')),
            ]);

            $width = $this->request->get('width');
            $height = $this->request->get('height');
            $fit = $this->request->get('fit');
            $server->outputImage('/23.jpg', [
                'w' => intval($width), 
                'h' => intval($height),
                'fit' => $fit
            ]);

            // return $server->getImageResponse($request);
            $server->outputImage($path, $_GET);


        // } catch (SignatureException $e) {
        //     var_dump(123);exit;
        // }
        
    }

    public function securityGlideAction()
    {
        // Set complicated sign key
        $signkey = 'v-LK4WCdhcfcc%jt*VC2cj%nVpu+xQKvLUA%H86kRVk_4bgG8&CWM#k*b_7MUJpmTc=4GFmKFp7=K%67je-skxC5vz+r#xT?62tT?Aw%FtQ4Y3gvnwHTwqhxUh89wCa_';

        // Create an instance of the URL builder

        $urlBuilder = UrlBuilderFactory::create('hi/glideImage/reSize', $signkey);
        // Generate a URL
        $url = $urlBuilder->getUrl('/23.jpg', ['w' => 500]);

        // Use the URL in your app
        echo '<img src="'.$url.'">';

        // Use the URL in your app

        // Prints out
        // <img src="/img/cat.jpg?w=500&s=af3dc18fc6bfb2afb521e587c348b904">
    }

    public function deleteCacheAction()
    {
        $server = ServerFactory::create([
            'source' => new Filesystem(new Local(PROJECT_PATH . '/public/assets/images/')),
            'cache' => new Filesystem(new Local(PROJECT_PATH . '/public/assets/images/cache')),
        ]);

        $server->deleteCache('23.jpg');
    }
}
