<?php

use Phalcon\Mvc\User\Component;
use Modules\PhalconVn\MainGlobal;

require_once('lib/php-shortcode.php');
include '../app/library/PhalconVn/MainGlobal.php';

$mainGlobal = MainGlobal::getInstance();
$subdomain = $mainGlobal->checkDomain();
if (!$subdomain) {
    die;
}
include '../app/library/PhalconVn/ShortcodeService.php';
include '../app/helpers/ProductShortcodeHelper.php';
global $shortcodeService;
global $productShortcodeHelper;
$shortcodeService = new Modules\PhalconVn\ShortcodeService;
$productShortcodeHelper = new Modules\Helpers\ProductShortcodeHelper;

/**
 *
 * Shortcode list product category
 *
 */
function productCategoryShortcode($args, $content)
{
    global $shortcodeService;
    global $productShortcodeHelper;
    $categorySlug = $args['category'];
    $products = $shortcodeService->getProductsInListCategory($categorySlug, 20);
    $content = '';
    if (count($products) > 0) {
        $content .= '<div id="shortcode-'. $categorySlug .'" class="box_product_category_shortcode"><div class="row">';
        foreach ($products as $product) {
            $content .= $productShortcodeHelper->productListViewHtmlShortcode($product);
        }

        $content .= '</div></div>';
    }

    return $content;
}

add_shortcode('product_category', 'productCategoryShortcode');

// display on html
function htmlDisplayShortCode($content)
{
    preg_match_all("/\[[^\]]*\]/", $content, $matches);
    if ($matches[0]) {
        $find = $matches[0];

        $replace = [];
        foreach ($matches[0] as $matche) {
            $replace[] = do_shortcode($matche);
        }

        $html = str_replace($find, $replace, $content);
    } else {
        $html = $content;
    }

    return $html;
}
