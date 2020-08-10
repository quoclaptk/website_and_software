<?php

namespace Modules\PhalconVn;

use Phalcon\Image\Adapter\GD;
use Phalcon\Text;

class UploadService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * upload file
     * @param  object $file     
     * @param  string $subfolderUrl
     * @param  null|string $folder   
     * @param  null|string $type   
     * @return array           
     */
    public function upload($file, $subfolderUrl, $folder = null, $type = null)
    {
        $data = [
            'message' => 'success',
            'file_name' => null,
        ];
        $ext = $file->getType();
        $data['ext'] = $ext;
        if (!$this->extImageCheck($ext)) {
            $data['message'] = 'Định dạng file không cho phép. Hãy upload một hình ảnh.';
        } elseif($file->getSize() > $this->config->media->maxSize) {
            $data['message'] = 'Dung lượng quá lớn. Vui lòng upload hình ảnh có dung lượng nhỏ hơn 500kb!';
        } else {
            $fileName = basename($file->getName(), "." . $file->getExtension());
            $fileName = $this->general->create_slug($fileName);
            $subCode = Text::random(Text::RANDOM_ALNUM);
            $fileFullName = $fileName . '_' . $subCode . '.' . $file->getExtension();
            if (!is_dir($subfolderUrl)) {
                mkdir( $subfolderUrl, 0777);
            }

            if ($folder != null) {
                if (!is_dir($subfolderUrl . "/" . $folder)) {
                    mkdir($subfolderUrl .  "/" . $folder, 0777);
                }
            }
            
            $fullFolder = ($folder != null) ? $subfolderUrl . "/" . $folder : $subfolderUrl;
            if ($file->moveTo($fullFolder . "/" . $fileFullName)) {
                $fileNameFolder = $fullFolder . "/" . $fileFullName;
                
                // if type not null resize image
                if ($type != null) {
                    if ($file->getSize() >= $this->config->media->minResize) {
                        $image = new GD($fileNameFolder);
                        $image->resize($this->config->media->thumb->width, $this->config->media->thumb->height);
                        $image->save($fullFolder . '/' . $fileFullName);
                    }
                }
            }

            $data['file_name'] = $fileFullName;
        }
            
        return $data;
    }

    /**
     * check ext image allow upload
     * @param  array $extension
     * @return bolean          
     */
    protected function extImageCheck($extension)
    {
        $allowedTypes = [
            'image/gif',
            'image/png',
            'image/bmp',
            'image/jpeg',
            'image/webp',
            'image/vnd.microsoft.icon',
        ];

        return in_array($extension, $allowedTypes);
    }
}
