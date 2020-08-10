<?php

namespace Modules\PhalconVn;

use Modules\PhalconVn\ConfigService;
use Modules\PhalconVn\WordTranslateService;

class Tag extends \Phalcon\Tag
{
    public static function number_format($input)
    {
        return number_format($input, 0, ",", ".");
    }

    public static function cms_price($sale, $price, $unit = 'đ', $option = null)
    {
        $priceHtml = null;
        $configService = new ConfigService();
        $wordTranslateService = new WordTranslateService();
        $cf =  $configService->getConfigItem();
        $word =  $wordTranslateService->getWordTranslation();
        $unitConfig = $cf['_cf_text_price_text'];
        $unitPosition = $cf['_cf_radio_price_unit_position'];
        if (isset($option['note']) && $option['note'] == true) {
            $notPice = $cf['_cf_text_note_price_product'];
            $priceNote = ($notPice != '') ? ' <span class="price-pr-detail-note">('. $notPice .')</span>' : '';
        } else {
            $priceNote = '';
        }
        
        if ($sale > 0 && $price > 0) {
            $saleFormat = number_format($sale, 0, ',', '.')  . " " . $unit;
            $priceFormat = number_format($price, 0, ',', '.') . " " . $unit;
            if (!empty($unitConfig)) {
                $saleFormat = $unitPosition == 1 ? '<span class="unit_price">'. $unitConfig .'</span><span class="text_price">' . number_format($sale, 0, ',', '.') . '</span>' : '<span class="text_price">' . number_format($sale, 0, ',', '.') . '</span><span class="unit_price">'. $unitConfig .'</span>';
                $priceFormat = $unitPosition == 1 ? '<span class="unit_price">'. $unitConfig . '</span><span class="text_price">' . number_format($price, 0, ',', '.') . '</span>' : '<span class="text_price">' . number_format($price, 0, ',', '.') . '</span><span class="unit_price">'. $unitConfig .'</span>';
            }

            if (!empty($option["class"])) {
                $priceHtml = sprintf('<div class="'. $option["class"] .'">
                                <div class="product_price_old">%s%s</div>
                                <div class="product_price_new">%s</div>
                            </div>', $priceFormat, $priceNote, $saleFormat);
            } else {
                $priceHtml = sprintf(
                    '<div class="product_price_old">%s%s</div>
                            <div class="product_price_new">%s</div>',
                    $priceFormat,
                    $priceNote,
                    $saleFormat
                );
            }
        }

        if ($sale == 0 && $price > 0) {
            $priceFormat = number_format($price, 0, ',', '.') . " " . $unit;
            if (!empty($unitConfig)) {
                $priceFormat = $unitPosition == 1 ? '<span class="unit_price">'. $unitConfig . '</span><span class="text_price">' . number_format($price, 0, ',', '.') . '</span>' : '<span class="text_price">' . number_format($price, 0, ',', '.') . '</span><span class="unit_price">'. $unitConfig .'</span>';
            }

            if (!empty($option["class"])) {
                $priceHtml = sprintf('<div class="'. $option["class"] .'">
			                        <div class="product_price_new product_price">%s%s</div>
			                    </div>', $priceFormat, $priceNote);
            } else {
                $priceHtml = sprintf('<div class="product_price_new product_price">%s%s</div>', $priceFormat, $priceNote);
            }
        }

        if ($price == 0) {
            $priceFormat = $word->_('_lien_he');
            if (!empty($option["class"])) {
                $priceHtml = sprintf('<div class="'. $option["class"] .' product_price">
			                        <div class="product_price_new">%s</div>
			                    </div>', $priceFormat);
            } else {
                $priceHtml = sprintf('<div class="product_price_new">%s</div>', $priceFormat);
            }
        }

        return $priceHtml;
    }

    public static function percen_calculate($x, $divisor, $option = null)
    {
        $percentHtml = null;
        if (is_numeric($x) && is_numeric($divisor)) {
            if ($x > 0 && $x < $divisor) {
                $percent = ($x / $divisor) * 100;
                $percent = round($percent, 0, PHP_ROUND_HALF_UP);
                $percent = round($percent, 0);
                $percent = 100 - $percent;

                if ($percent > 0) {
                    $percentHtml = sprintf('<p class="%s">%s</p>', "price_percent", '-' . $percent . '%');
                }
            } else {
                $percentHtml = null;
            }
        }


        return $percentHtml;
    }

    public static function replacenumber($str)
    {
        $html = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $val = substr($str, $i, 1);
            $html .= '<b>' . $val . '</b>';
        }
        return $html;
    }

    public static function cut_string($str, $limit)
    {
        if (strlen($str) > $limit) {
            $re = substr($str, 0, $limit);
            $re = substr($re, 0, strrpos($re, " "));
            $re .= "...";
            return $re;
        } else {
            return $str;
        }
    }

    public function site_url($link = "")
    {
        if ($link != "") {
            $url =  "/" . $link . "/";
        } else {
            $url = "/";
        }
        return $url;
    }

    public function site_url_full($link = '')
    {
        $baseUrl =  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ?  "https" : "http");
        $url = ($link != '') ? $baseUrl . '://' . HOST . '/' . $link . '/' : $baseUrl . '://' . HOST . '/';

        return $url;
    }
}
