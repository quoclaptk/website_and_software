<?php

namespace Modules\PhalconVn;

use Phalcon\Text;

class General
{
    public function stripUnicode($str)
    {
        if (!$str) {
            return false;
        }
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'd' => 'đ',
            'D' => 'Đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|€',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            '-' => '!|@|\$|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\'| |\"|\&|\#|\[|\]|~|',
            '-' => '-+-',
            '' => '^\-+|\-+$'
        );
        foreach ($unicode as $khongdau=>$codau) {
            $arr=explode("|", $codau);
            $str = str_replace($arr, $khongdau, $str);
        }
        return $str;
    }

    public function create_slug($str)
    {
        $str = $this->stripUnicode($str);
        $str = strtolower($str);
        $str = trim($str);
        $str = preg_replace('/[^a-zA-Z0-9\ ]/', '', $str);
        $str = str_replace("  ", " ", $str);
        $str = str_replace(" ", "-", $str);
        return $str;
    }

    public function create_slug_second($str)
    {
        $str = $this->stripUnicode($str);
        $str = strtolower($str);
        $str = trim($str);
        $str= preg_replace('/[^a-zA-Z0-9\ ]/', '-', $str);
        $str = str_replace("  ", " ", $str);
        $str = str_replace(" ", "-", $str);
        return $str;
    }

    public function create_slug_three($str)
    {
        $str = $this->stripUnicode($str);
        $str = strtolower($str);
        $str = trim($str);
        $str= preg_replace('/[^a-zA-Z0-9\ ]/', '-', $str);
        $str = str_replace("-", " ", $str);
        $str = str_replace("    ", " ", $str);
        $str = str_replace("   ", " ", $str);
        $str = str_replace("  ", " ", $str);
        return $str;
    }

    public function upload_file_content($save_to, $url, $subCode = null)
    {
        $name=basename($url);
        if ($subCode != null) {
            $path_parts = pathinfo($url);
            if (!empty($path_parts['filename'])) {
                $file_name = self::create_slug($path_parts['filename']) . '_' . $subCode;
            } else {
                $file_name = $subCode;
            }
            $extension = $path_parts['extension'];
            $name= $file_name . '.' . $extension;
        }
        $fn=$save_to . '/' . $name;
        $fp=fopen($fn, "w");
        if ($fn) {
            $content=file_get_contents($url);
            if (fwrite($fp, $content, strlen($content))) {
                fclose($fp);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public function sw_human_time_diff($date)
    {
        $langs = array('giây', 'phút', 'giờ', 'ngày', 'tuần', 'tháng', 'năm');
        $chunks = array(
            array( 60 * 60 * 24 * 365 ,  $langs[6], $langs[6] ),
            array( 60 * 60 * 24 * 30 ,$langs[5], $langs[5] ),
            array( 60 * 60 * 24 * 7, $langs[4],$langs[4] ),
            array( 60 * 60 * 24 , $langs[3],$langs[3] ),
            array( 60 * 60 , $langs[2], $langs[2] ),
            array( 60 , $langs[1],$langs[1] ),
            array( 1,  $langs[0],$langs[0] )
        );

        $newer_date = time();


        $since = $newer_date - $date;
        //if ( 0 > $since )
        //return __( 'Gần đây', 'swhtd' );
        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }
        $output = (1 == $count) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
        if (!(int)trim($output)) {
            $output = '0 ' .  $langs[0];
        }
        $output .= ' trước';
        return $output;
    }

    public function translate_day($day = '')
    {
        switch ($day) {
            case 'Monday':
                $day_vi = 'Thứ hai';
                break;

            case 'Tuesday':
                $day_vi = 'Thứ ba';
                break;

            case 'Wednesday':
                $day_vi = 'Thứ tư';
                break;

            case 'Thursday':
                $day_vi = 'Thứ năm';
                break;

            case 'Friday':
                $day_vi = 'Thứ sáu';
                break;

            case 'Saturday':
                $day_vi = 'Thứ bẩy';
                break;

            case 'Sunday':
                $day_vi = 'Chủ nhật';
                break;
            
            default:
                $day_vi = '';
                break;
        }

        return $day_vi;
    }

    public function change_timezone($date_time)
    {
        $user_tz = 'Europe/London';
        $schedule_date = new \DateTime($date_time, new \DateTimeZone($user_tz));
        $schedule_date->setTimeZone(new \DateTimeZone('Asia/Ho_Chi_Minh'));
        $triggerOn =  $schedule_date->format('Y-m-d H:i');

        return $triggerOn;
    }

    public function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }

    /**
     * array_slice data
     *
     * @param array $data
     * @param interger $from
     * @param interger $to
     * 
     * @return array
     */
    public function arraySlice($data, $from, $to)
    {
        $items = [];
        if(!empty($data)){
            if (count($data) > 0) {
                $itemTotal = ceil(count($data) / $to);
                for($i = 0; $i < $itemTotal; $i++){
                    array_push($items, array_slice($data, $from, $to));
                    $from += $to;
                }
            }
        }

        return $items;
    }

    public function deleteImgInFolder($imgUploadUrls, $contentHtml)
    {
        if (!empty($contentHtml)) {
            preg_match_all('/<img[^>]+>/i', $contentHtml, $imgResult);

            $imgUrlFolder = array();
            if (!empty($imgResult)) {
                foreach ($imgResult[0] as $img_tag) {
                    $doc = new \DOMDocument();
                    $doc->loadHTML($img_tag);
                    $imageTags = $doc->getElementsByTagName('img');
                    foreach ($imageTags as $tag) {
                        $img_src = $tag->getAttribute('src');
                        $imgUrlFolder[] = $img_src;
                    }
                }

                foreach ($imgUploadUrls as $img) {
                    if (!empty($imgUrlFolder) && !in_array($img, $imgUrlFolder)) {
                        unlink(str_replace(HTTP_HOST . '/', '', $img));
                    }
                }
            } else {
                foreach ($imgUploadUrls as $img) {
                    unlink(str_replace(HTTP_HOST . '/', '', $img));
                }
            }
        } else {
            foreach ($imgUploadUrls as $img) {
                unlink(str_replace(HTTP_HOST . '/', '', $img));
            }
        }
    }

    public function deleteAllFileInFolder($directory)
    {
        $files = glob($directory . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
          if (is_file($file)) {
              unlink($file);
          } // delete file
        }
    }

    //delete all file and sub folder
    public function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") {
                        $this->rrmdir($dir."/".$object);
                    } else {
                        unlink($dir."/".$object);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * Check json format string
     *
     * @param string $string
     * @return bolean
     */
    public function isJSON($string){
       return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}
