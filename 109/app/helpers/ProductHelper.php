<?php 

namespace Modules\Helpers;

class ProductHelper extends BaseHelper
{
    public function __construct()
    {
        parent::__construct();
    }

    public function productListViewHtml($product_class, $demo_router, $product, $subdomain, $setting, $cf, $word, $options = null)
    {
        $id = (isset($options['array']) && $options['array'] == true) ? $product['id'] : $product->id;
        $name = (isset($options['array']) && $options['array'] == true) ? $product['name'] : $product->name;
        $slug = (isset($options['array']) && $options['array'] == true) ? $product['slug'] : $product->slug;
        $folder = (isset($options['array']) && $options['array'] == true) ? $product['folder'] : $product->folder;
        $photo = (isset($options['array']) && $options['array'] == true) ? $product['photo'] : $product->photo;
        $photoSecondary = (isset($options['array']) && $options['array'] == true) ? $product['photo_secondary'] : $product->photo_secondary;
        $prPrice = (isset($options['array']) && $options['array'] == true) ? $product['price'] : $product->price;
        $cost = (isset($options['array']) && $options['array'] == true) ? $product['cost'] : $product->cost;
        $summary = (isset($options['array']) && $options['array'] == true) ? $product['summary'] : $product->summary;
        $link = (isset($options['array']) && $options['array'] == true) ? $product['link'] : $product->link;
        $cartLink = (isset($options['array']) && $options['array'] == true) ? $product['cart_link'] : $product->cart_link;
        $enable_link = (isset($options['array']) && $options['array'] == true) ? $product['enable_link'] : $product->enable_link;
        $name_show = $this->tag->cut_string($name, 70);
        $outStock = (isset($options['array']) && $options['array'] == true) ? $product['out_stock'] : $product->out_stock;
        $purchaseNumber = (isset($options['array']) && $options['array'] == true) ? $product['purchase_number'] : $product->purchase_number;
        $hits = (isset($options['array']) && $options['array'] == true) ? $product['hits'] : $product->hits;
        $target = '';
        if ($cf['_cf_text_all_product_url'] != '' && filter_var($cf['_cf_text_all_product_url'], FILTER_VALIDATE_URL)) {
            $url = $cf['_cf_text_all_product_url'];
        } else {
            if ($enable_link == 1 && !empty($link) && filter_var($link, FILTER_VALIDATE_URL)) {
                $target = ' target="blank"';
                $url = $link;
            } else {
                $target = '';
                $url = ($this->_lang_code == 'vi') ? $this->tag->site_url($slug) : $this->tag->site_url($this->_lang_code . '/' . $slug);
            }
        }

        $photoUrl = $this->getProductPhoto($photo, $subdomain->folder, $folder);
        $photoHtml =  ($cf['_cf_radio_enable_lazyload_image']) ? sprintf('<img class="lazy" data-src="%s" data-alt="%s">', $photoUrl, $name) : sprintf('<img src="%s" alt="%s">', $photoUrl, $name);
        
        // image hover display
        $photoSecondaryUrl = null;
        $photoSecondaryUrlHtml = null;
        $boxProductImgClass = null;
        if (!empty($photoSecondary)) {
            $photoSecondaryUrl = $this->getProductPhoto($photoSecondary, $subdomain->folder, $folder);
            $photoSecondaryUrlHtml = sprintf('<img src="%s" alt="%s" class="img-hover">', $photoSecondaryUrl, $name);
            $boxProductImgClass = ' box_product_img_hover';
        }

        if ($cf['_turn_off_product_price'] == true) {
            $price = $this->tag->cms_price($prPrice, $cost, "đ", ["class" => "box_product_price"]);
            $percent = $this->tag->percen_calculate($prPrice, $cost);
        } else {
            $price = '';
            $percent = '';
        }

        $outStockHtml = $outStock == 'Y' ? '<span class="status-hot hot">'. $word->_('_het_hang') .'</span>' : '';
        $class =  (isset($options['detail']) && $options['detail'] == true) ? $product_class : $product_class . ' col-sm-4 col-xs-6 col-ss-12 col-product';
        $html = sprintf('<div class="%s"><div class="box_product"><a href="%s"%s>%s<div class="box_product_img%s">%s%s</div>%s</a><div class="box_product_name"><a href="%s"%s>%s</a></div>', $class, $url, $target, $outStockHtml, $boxProductImgClass, $photoHtml, $photoSecondaryUrlHtml, $percent, $url, $target, $name_show);
        if ($cf['_cf_radio_show_summary_to_product'] == true) {
            $html .= '<div class="box_product_summary">'. $summary .'</div>';
        }

        if ($price != '' || $percent != '' || $cf['_turn_off_cart_btn'] == true) {
            $html .= '<div class="box_product_price_cart clearfix">' . $price;
            if ($cf['_turn_off_cart_btn'] == true) {
                $html .= '<div class="btn_cart text-uppercase">';
                if (!empty($cartLink) && filter_var($cartLink, FILTER_VALIDATE_URL)) {
                    $html .= '<a href="'. $cartLink .'"><span class="btn btn-sm btn-warning">'. $word['_mua_ngay'] .'</span><i class="icon-giohang"></i></a>';
                } elseif ($cf['_cf_text_cart_other_url'] != '' && filter_var($cf['_cf_text_cart_other_url'], FILTER_VALIDATE_URL)) {
                    $html .= '<a href="'. $cf['_cf_text_cart_other_url'] .'"><span class="btn btn-sm btn-warning">'. $word['_mua_ngay'] .'</span><i class="icon-giohang"></i></a>';
                } else {
                    $html .= sprintf('<span class="btn btn-sm btn-warning add-to-cart" data-id="%s" data-cart-type="%s" data-name="%s">%s</span><i class="icon-giohang" data-id="%s" data-cart-type="%s" data-name="%s"></i>', $id, $cf['_cf_select_cart_type'], $name, $word['_mua_ngay'], $id, $cf['_cf_select_cart_type'], $name);
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        if ($cf['_show_btn_view_product_detail'] == true) {
            $html .= sprintf('<div class="btn_view_product_detail text-center"><a href="%s" %s class="btn btn-blue bar_web_bgr">%s</a></div>', $url, $target, $word['_chi_tiet']);
        }

        if ($cf['_show_company_to_product'] == true || $cf['_show_hotline_to_product'] == true || $cf['_show_email_to_product'] == true) {
            $html .= '<div class="box_product_company_info"><ul class="text-center">';
            if ($cf['_show_company_to_product'] == true) {
                $html .= '<li class="product_company_name"><strong>'. $setting->name .'</strong></li>';
            }

            if ($cf['_show_hotline_to_product'] == true && $setting->hotline != '') {
                $html .= sprintf('<li class="product_company_hotline"><span>%s: </span><strong><a href="tel:%s">%s</a></strong></li>', $word['_hotline'], $setting->hotline, $setting->hotline);
            }

            if ($cf['_show_email_to_product'] == true && $setting->email != '') {
                $html .= sprintf('<li class="product_company_email"><span>%s: </span><strong><a href="mailto:%s">%s</a></strong></li>', $word['_email'], $setting->email, $setting->email);
            }

            $html .= '</ul></div>';
        }

        if ($cf['_cf_radio_show_purchase_number_to_product'] == true || $cf['_cf_radio_show_views_to_product'] == true) {
            $html .= '<div class="box_product_purchase_hits clearfix">';
            if ($cf['_cf_radio_show_purchase_number_to_product'] == true) {
                $html .= '<div class="box_product_purchase pull-left text-left"><i class="fa fa-user-o"></i> '. $word->_('_luot_mua') .': <span class="box_product_purchase_number">'. $purchaseNumber .'</span></div>';
            }

            if ($cf['_cf_radio_show_views_to_product'] == true) {
                $html .= '<div class="box_product_hits pull-left"><i class="fa fa-eye"></i> '. $word->_('_luot_xem') .': <span class="box_product_hits_number">'. $hits .'</span></div>';
            }

            $html .= '</div>';
        }

        $html .= '</div></div>';

        return $html;
    }

    public function productListViewHtmlSlide($product_class, $demo_router, $product, $subdomain, $setting, $cf, $word, $options = null)
    {
        $id = (isset($options['array']) && $options['array'] == true) ? $product['id'] : $product->id;
        $name = (isset($options['array']) && $options['array'] == true) ? $product['name'] : $product->name;
        $slug = (isset($options['array']) && $options['array'] == true) ? $product['slug'] : $product->slug;
        $folder = (isset($options['array']) && $options['array'] == true) ? $product['folder'] : $product->folder;
        $photo = (isset($options['array']) && $options['array'] == true) ? $product['photo'] : $product->photo;
        $photoSecondary = (isset($options['array']) && $options['array'] == true) ? $product['photo_secondary'] : $product->photo_secondary;
        $prPrice = (isset($options['array']) && $options['array'] == true) ? $product['price'] : $product->price;
        $cost = (isset($options['array']) && $options['array'] == true) ? $product['cost'] : $product->cost;
        $summary = (isset($options['array']) && $options['array'] == true) ? $product['summary'] : $product->summary;
        $link = (isset($options['array']) && $options['array'] == true) ? $product['link'] : $product->link;
        $cartLink = (isset($options['array']) && $options['array'] == true) ? $product['cart_link'] : $product->cart_link;
        $enable_link = (isset($options['array']) && $options['array'] == true) ? $product['enable_link'] : $product->enable_link;
        $name_show = $this->tag->cut_string($name, 70);
        $outStock = (isset($options['array']) && $options['array'] == true) ? $product['out_stock'] : $product->out_stock;
        $purchaseNumber = (isset($options['array']) && $options['array'] == true) ? $product['purchase_number'] : $product->purchase_number;
        $hits = (isset($options['array']) && $options['array'] == true) ? $product['hits'] : $product->hits;
        $target = '';
        if ($cf['_cf_text_all_product_url'] != '' && filter_var($cf['_cf_text_all_product_url'], FILTER_VALIDATE_URL)) {
            $url = $cf['_cf_text_all_product_url'];
        } else {
            if ($enable_link == 1 && !empty($link) && filter_var($link, FILTER_VALIDATE_URL)) {
                $target = ' target="blank"';
                $url = $link;
            } else {
                $target = '';
                $url = ($this->_lang_code == 'vi') ? $this->tag->site_url($slug) : $this->tag->site_url($this->_lang_code . '/' . $slug);
            }
        }
        
        $photoUrl = $this->getProductPhoto($photo, $subdomain->folder, $folder);
        $photoHtml =  ($cf['_cf_radio_enable_lazyload_image']) ? sprintf('<img class="lazy" data-src="%s" data-alt="%s">', $photoUrl, $name) : sprintf('<img src="%s" alt="%s">', $photoUrl, $name);
        // $photoHtml = sprintf('<img src="%s" alt="%s">', $photoUrl, $name);

        // image hover display
        $photoSecondaryUrl = null;
        $photoSecondaryUrlHtml = null;
        $boxProductImgClass = null;
        if (!empty($photoSecondary)) {
            $photoSecondaryUrl = $this->getProductPhoto($photoSecondary, $subdomain->folder, $folder);
            $photoSecondaryUrlHtml = sprintf('<img src="%s" alt="%s" class="img-hover">', $photoSecondaryUrl, $name);
            $boxProductImgClass = ' box_product_img_hover';
        }

        if ($cf['_turn_off_product_price'] == true) {
            $price = $this->tag->cms_price($prPrice, $cost, "đ", ["class" => "box_product_price"]);
            $percent = $this->tag->percen_calculate($prPrice, $cost);
        } else {
            $price = '';
            $percent = '';
        }

        $outStockHtml = $outStock == 'Y' ? '<span class="status-hot hot">'. $word->_('_het_hang') .'</span>' : '';
        $class =  (isset($options['detail']) && $options['detail'] == true) ? $product_class : $product_class . ' col-sm-4 col-xs-6 col-ss-12 col-product';

        $html = sprintf('<div class="box_product box_product_carousel"><a href="%s"%s>%s<div class="box_product_img%s">%s%s</div>%s</a><div class="box_product_name"><a href="%s"%s>%s</a></div>', $url, $target, $outStockHtml, $boxProductImgClass, $photoHtml, $photoSecondaryUrlHtml, $percent, $url, $target, $name_show);

        if ($cf['_cf_radio_show_summary_to_product'] == true) {
            $html .= '<div class="box_product_summary">'. $summary .'</div>';
        }

        if ($price != '' || $percent != '' || $cf['_turn_off_cart_btn'] == true) {
            $html .= '<div class="box_product_price_cart clearfix">' . $price;
            if ($cf['_turn_off_cart_btn'] == true) {
                $html .= '<div class="btn_cart text-uppercase">';
                if (!empty($cartLink) && filter_var($cartLink, FILTER_VALIDATE_URL)) {
                    $html .= '<a href="'. $cartLink .'"><span class="btn btn-sm btn-warning">'. $word['_mua_ngay'] .'</span><i class="icon-giohang"></i></a>';
                } elseif ($cf['_cf_text_cart_other_url'] != '' && filter_var($cf['_cf_text_cart_other_url'], FILTER_VALIDATE_URL)) {
                    $html .= '<a href="'. $cf['_cf_text_cart_other_url'] .'"><span class="btn btn-sm btn-warning">'. $word['_mua_ngay'] .'</span><i class="icon-giohang"></i></a>';
                } else {
                    $html .= sprintf('<span class="btn btn-sm btn-warning add-to-cart" data-id="%s" data-cart-type="%s" data-name="%s">%s</span><i class="icon-giohang" data-id="%s" data-cart-type="%s" data-name="%s"></i>', $id, $cf['_cf_select_cart_type'], $name, $word['_mua_ngay'], $id, $cf['_cf_select_cart_type'], $name);
                }
                $html .= '</div>';
            }

            $html .= '</div>';
        }

        if ($cf['_show_btn_view_product_detail'] == true) {
            $html .= sprintf('<div class="btn_view_product_detail text-center"><a href="%s" %s class="btn btn-blue bar_web_bgr">%s</a></div>', $url, $target, $word['_chi_tiet']);
        }

        if ($cf['_show_company_to_product'] == true || $cf['_show_hotline_to_product'] == true || $cf['_show_email_to_product'] == true) {
            $html .= '<div class="box_product_company_info"><ul class="text-center">';
            if ($cf['_show_company_to_product'] == true) {
                $html .= '<li class="product_company_name"><strong>'. $setting->name .'</strong></li>';
            }

            if ($cf['_show_hotline_to_product'] == true && $setting->hotline != '') {
                $html .= sprintf('<li class="product_company_hotline"><span>%s: </span><strong><a href="tel:%s">%s</a></strong></li>', $word['_hotline'], $setting->hotline, $setting->hotline);
            }

            if ($cf['_show_email_to_product'] == true && $setting->email != '') {
                $html .= sprintf('<li class="product_company_email"><span>%s: </span><strong><a href="mailto:%s">%s</a></strong></li>', $word['_email'], $setting->email, $setting->email);
            }

            $html .= '</ul></div>';
        }

        if ($cf['_cf_radio_show_purchase_number_to_product'] == true || $cf['_cf_radio_show_views_to_product'] == true) {
            $html .= '<div class="box_product_purchase_hits clearfix">';
            if ($cf['_cf_radio_show_purchase_number_to_product'] == true) {
                $html .= '<div class="box_product_purchase pull-left text-left"><i class="fa fa-user-o"></i> '. $word->_('_luot_mua') .': <span class="box_product_purchase_number">'. $purchaseNumber .'</span></div>';
            }

            if ($cf['_cf_radio_show_views_to_product'] == true) {
                $html .= '<div class="box_product_hits pull-left"><i class="fa fa-eye"></i> '. $word->_('_luot_xem') .': <span class="box_product_hits_number">'. $hits .'</span></div>';
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    public function productHotAboutHtml($product, $subdomain, $setting, $cf, $word, $key, $options = null)
    {
        $id = (isset($options['array']) && $options['array'] == true) ? $product['id'] : $product->id;
        $name = (isset($options['array']) && $options['array'] == true) ? $product['name'] : $product->name;
        $slug = (isset($options['array']) && $options['array'] == true) ? $product['slug'] : $product->slug;
        $folder = (isset($options['array']) && $options['array'] == true) ? $product['folder'] : $product->folder;
        $photo = (isset($options['array']) && $options['array'] == true) ? $product['photo'] : $product->photo;
        $prPrice = (isset($options['array']) && $options['array'] == true) ? $product['price'] : $product->price;
        $cost = (isset($options['array']) && $options['array'] == true) ? $product['cost'] : $product->cost;
        $summary = (isset($options['array']) && $options['array'] == true) ? $product['summary'] : $product->summary;
        $link = (isset($options['array']) && $options['array'] == true) ? $product['link'] : $product->link;
        $cartLink = (isset($options['array']) && $options['array'] == true) ? $product['cart_link'] : $product->cart_link;
        $enable_link = (isset($options['array']) && $options['array'] == true) ? $product['enable_link'] : $product->enable_link;
        $name_show = $this->tag->cut_string($name, 70);
        $outStock = (isset($options['array']) && $options['array'] == true) ? $product['out_stock'] : $product->out_stock;
        $purchaseNumber = (isset($options['array']) && $options['array'] == true) ? $product['purchase_number'] : $product->purchase_number;
        $hits = (isset($options['array']) && $options['array'] == true) ? $product['hits'] : $product->hits;
        $target = '';
        if ($cf['_cf_text_all_product_url'] != '' && filter_var($cf['_cf_text_all_product_url'], FILTER_VALIDATE_URL)) {
            $url = $cf['_cf_text_all_product_url'];
        } else {
            if ($enable_link == 1 && !empty($link) && filter_var($link, FILTER_VALIDATE_URL)) {
                $target = ' target="blank"';
                $url = $link;
            } else {
                $target = '';
                $url = ($this->_lang_code == 'vi') ? $this->tag->site_url($slug) : $this->tag->site_url($this->_lang_code . '/' . $slug);
            }
        }
        
        $photoUrl = $this->getProductPhoto($photo, $subdomain->folder, $folder);

        if ($cf['_turn_off_product_price'] == true) {
            $price = $this->tag->cms_price($prPrice, $cost, "VNĐ");
            $percent = $this->tag->percen_calculate($prPrice, $cost);
        } else {
            $price = '';
            $percent = '';
        }

        $html = '';
        $html .= sprintf('<div class="list list-%s col-md-12 col-sm-6">
                <div class="list-photo col-xs-12 col-sm-12 col-md-7">
                    <a href="%s"><img src="%s" alt="%s"></a>
                </div>
                <div class="list-item col-xs-12 col-sm-12 col-md-5">
                    <h5>
                        <a href="%s" title="%s">%s</a>
                        <span class="list-number">%s</span>
                    </h5>
                    <div class="list-content">
                        %s
                        <div class="price-chain">%s</div>
                    </div>
                    <a href="%s" class="btn btn-detail">%s</a>
                </div>
            </div>', $key, $url, $photoUrl, $name, $url, $name, $name, $key + 1, $summary, $price, $url, $word['_chi_tiet']);

        return $html;
    }

    /**
     * Get product image display
     *
     * @param string $photo
     * @param string $subFolder
     * @param string $folder
     * @param array $options
     * @return string $photoUrl
     */
    public function getProductPhoto($photo, $subFolder, $folder, $options = null)
    {
        $photoUrl = '/assets/images/no-image.png';
        if (!empty($photo) && file_exists('files/product/' . $subFolder . '/' . $folder . '/' . $photo)) {
            $cfEnableThumbnail = $this->config_service->getConfigItemDetail('_cf_radio_enable_thumbnail');
            if ($cfEnableThumbnail) {
                $photoUrl = $this->image_service->createThumb('files/product/' . $subFolder . '/' . $folder . '/' . $photo);
            } else {
                $sizeFolder = isset($options['folder']) ? $options['folder'] : '360x360';
                if (file_exists('files/product/' . $subFolder . '/thumb/' . $sizeFolder . '/' . $folder . '/' . $photo)) {
                    $photoUrl = '/files/product/' . $subFolder . '/thumb/' . $sizeFolder . '/' . $folder . '/' . $photo;
                } else {
                    $photoUrl = '/files/product/' . $subFolder . '/' . $folder . '/' . $photo;
                }
            }
        }

        return $photoUrl;
    }
}
