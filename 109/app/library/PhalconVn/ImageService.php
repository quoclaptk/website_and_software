<?php

namespace Modules\PhalconVn;

use Phalcon\Image\Adapter\GD;

class ImageService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create thumb nail
     * 
     * @param string $fileUrl
     * @return string $imageThumb
     */
    public function createThumb($fileUrl, $type = 'product')
    {
        $w = 0;
        $h = 0;
        switch ($type) {
            case 'news':
                $w = $this->config_service->getConfigItemDetail('_cf_text_thumbnail_width_news');
                $h = $this->config_service->getConfigItemDetail('_cf_text_thumbnail_height_news');
                break;

            case 'slider':
                $w = $this->config_service->getConfigItemDetail('_cf_text_thumbnail_width_slider');
                $h = $this->config_service->getConfigItemDetail('_cf_text_thumbnail_height_slider');
                break;

            case 'partner':
                $w = $this->config_service->getConfigItemDetail('_cf_text_thumbnail_width_partner');
                $h = $this->config_service->getConfigItemDetail('_cf_text_thumbnail_height_partner');
                break;
            
            default:
                $w = $this->config_service->getConfigItemDetail('_cf_text_thumbnail_width');
                $h = $this->config_service->getConfigItemDetail('_cf_text_thumbnail_height');
                break;
        }
        
        if ($w == 0 || $h == 0) {
            return $fileUrl;
        }

        $imageThumb = $fileUrl;
        $imageArr = explode('/', $fileUrl);
        $imageName = $imageArr[count($imageArr) - 1];
        unset($imageArr[count($imageArr) - 1]);
        $folder = implode('/', $imageArr);
        $pathParts = pathinfo($imageName);
        $extension = $pathParts['extension'];
        $fileName = $pathParts['filename'] . '_' . $w . 'x' . $h . '.' . $extension;
        $imageThumb = $folder . '/thumb/' . $imageName . '/' . $fileName;
        if ($extension != 'webp') {
	        if (!file_exists($imageThumb)) {
	            // create thumb folder
	            if (is_dir($folder)) {
	                if (!is_dir($folder . '/thumb')) {
	                    mkdir($folder . '/thumb');
	                }

	                if (!is_dir($folder . '/thumb/' . $imageName)) {
	                    mkdir($folder . '/thumb/' . $imageName);
	                }

	                $image = new GD($fileUrl);
	                $wOrigin = $image->getWidth();
	                $hOrigin = $image->getHeight();
	                
	                if ($w < $wOrigin && $h < $hOrigin) {
	                    $image->resize($w, $h);
	                    $image->save($imageThumb);
	                } else {
	                    $imageThumb = $fileUrl;
	                }
	            }
	        }
        }

        return '/' . $imageThumb;
    }

}
