<?php namespace Modules\Helpers;

class BannerHelper extends BaseHelper
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
    public function getBannerPhoto($photo, $subFolder, $options = null)
    {
        $photoUrl = '/assets/images/no-image.png';
        if (!empty($photo) && file_exists('files/ads/' . $subFolder . '/' . $photo)) {
            $cfEnableThumbnail = $this->config_service->getConfigItemDetail('_cf_radio_enable_thumbnail_' . $options['type']);
            if ($cfEnableThumbnail) {
                $photoUrl = $this->image_service->createThumb('files/ads/' . $subFolder . '/' . $photo, $options['type']);
            } else {
                $sizeFolder = isset($options['folder']) ? $options['folder'] : '320x320';
                if (file_exists('files/ads/' . $subFolder . '/thumb/' . $sizeFolder . '/' . $photo)) {
                    $photoUrl = '/files/ads/' . $subFolder . '/thumb/' . $sizeFolder . '/' . $photo;
                } else {
                    $photoUrl = '/files/ads/' . $subFolder . '/' . $photo;
                }
            }
        }

        return $photoUrl;
    }
}
