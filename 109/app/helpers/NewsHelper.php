<?php 

namespace Modules\Helpers;

class NewsHelper extends BaseHelper
{

    /**
     * Get news image display
     *
     * @param string $photo
     * @param string $subFolder
     * @param string $folder
     * @param array $options
     * @return string $photoUrl
     */
    public function getNewsPhoto($photo, $subFolder, $folder, $options = null)
    {
        $photoUrl = '/assets/images/no-image.png';
        if (!empty($photo) && file_exists('files/news/' . $subFolder . '/' . $folder . '/' . $photo)) {
            $cfEnableThumbnail = $this->config_service->getConfigItemDetail('_cf_radio_enable_thumbnail_news');
            if ($cfEnableThumbnail) {
                $photoUrl = $this->image_service->createThumb('files/news/' . $subFolder . '/' . $folder . '/' . $photo, 'news');
            } else {
                $sizeFolder = isset($options['folder']) ? $options['folder'] : '320x320';
                if (file_exists('files/news/' . $subFolder . '/thumb/' . $sizeFolder . '/' . $folder . '/' . $photo)) {
                    $photoUrl = '/files/news/' . $subFolder . '/thumb/' . $sizeFolder . '/' . $folder . '/' . $photo;
                } else {
                    $photoUrl = '/files/news/' . $subFolder . '/' . $folder . '/' . $photo;
                }
            }
        }

        return $photoUrl;
    }
}
