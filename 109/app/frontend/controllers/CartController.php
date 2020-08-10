<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Subdomain;
use Modules\Models\Product;
use Modules\Models\Languages;
use Modules\Models\TmpProductProductElementDetail;
use Modules\Models\ProductElementDetail;

class CartController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
    }

    public function indexAction()
    {
        $titleBar = $this->_word['_gio_hang'];
        $breadcrumb = "<li class='active'>$titleBar</li>";
        $this->view->cart = $this->cart_service->getContent();
        $this->view->title = $titleBar;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function insertAction()
    {
        if ($this->request->isPost()) {
            $requestOptions = $this->request->getPost('options');
            $id = $this->request->getPost("id");
            $qty = ($this->request->getPost("qty") != "") ? $this->request->getPost("qty") : 1;
            $currency = ($this->request->getPost("currency")) ? $this->request->getPost("currency") : 'VNĐ';
            $product = Product::findfirst([
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND id = $id AND active = 'Y' AND deleted = 'N'"
            ]);

            // $this->cart_service->destroy();

            $data = array();
            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domain = $this->mainGlobal->getDomainCustomerInfo();
            if ($product) {
                $language = Languages::findFirstById($product->language_id);
                $subdomain = Subdomain::findFirstById($this->_subdomain_id);
                $options = [];
                if (!empty($requestOptions)) {
                    $tmp = TmpProductProductElementDetail::findFirstById($requestOptions['tmpid']);
                    if ($tmp) {
                        if ($currency == 'VNĐ' || $currency == $this->_config['_cf_text_price_text']) {
                            if ($tmp->cost > 0 && $tmp->price > 0) {
                                $price = $tmp->price;
                            }
                            if ($tmp->cost > 0 && $tmp->price == 0) {
                                $price = $tmp->cost;
                            }
                            if ($tmp->cost == 0) {
                                $price = $tmp->cost;
                            }
                        }

                        if (isset($this->_config['_cf_text_price_usd_currency']) && $currency == $this->_config['_cf_text_price_usd_currency']) {
                            if ($tmp->cost_usd > 0 && $tmp->price_usd > 0) {
                                $price = $tmp->price_usd;
                            }
                            if ($tmp->cost_usd > 0 && $tmp->price_usd == 0) {
                                $price = $tmp->cost;
                            }
                            if ($tmp->cost_usd == 0) {
                                $price = $tmp->cost_usd;
                            }
                        }
                        
                        if ($tmp->product_element_detail_id != null) {
                            $productElementDetail = ProductElementDetail::findFirstById($tmp->product_element_detail_id);
                            $options[$productElementDetail->product_element->name] = $productElementDetail->name;
                        }

                        if ($tmp->combo_id != null) {
                            $productElementDetails = ProductElementDetail::find([
                                "conditions" => "id IN (". $tmp->combo_id .")"
                            ]);

                            if (count($productElementDetails) > 0) {
                                foreach ($productElementDetails as $key => $productElementDetail) {
                                    $options[$productElementDetail->product_element->name] = $productElementDetail->name;
                                }
                            }
                        }
                    }
                } else {
                    if ($currency == 'VNĐ' || $currency == $this->_config['_cf_text_price_text']) {
                        if ($product->cost > 0 && $product->price > 0) {
                            $price = $product->price;
                        }
                        if ($product->cost > 0 && $product->price == 0) {
                            $price = $product->cost;
                        }
                        if ($product->cost == 0) {
                            $price = $product->cost;
                        }
                    }

                    if (isset($this->_config['_cf_text_price_usd_currency']) && $currency == $this->_config['_cf_text_price_usd_currency']) {
                        if ($product->cost_usd > 0 && $product->price_usd > 0) {
                            $price = $product->price_usd;
                        }
                        if ($product->cost_usd > 0 && $product->price_usd == 0) {
                            $price = $product->cost_usd;
                        }
                        if ($product->cost_usd == 0) {
                            $price = $product->cost_usd;
                        }
                    }
                }

                $photo = !empty($product->photo) ? "/files/product/" . $subdomain->folder . "/" . $product->folder . "/" . $product->photo : '/assets/images/no-image.png';
                $langUrl = $language->code == 'vi' ? '' : $language->code . '/';
               
                $link = $domain ? $protocol . $domain->name . $this->tag->site_url($langUrl . $product->slug) : $protocol . $subdomain->name . '.' . ROOT_DOMAIN . $this->tag->site_url($langUrl . $product->slug);
                $data = [
                    "subdomain_id" => $this->_subdomain_id,
                    "id" => $id,
                    "name" => $product->name,
                    "link" => $link,
                    "qty" => $qty,
                    "price" => $price,
                    "currency" => $currency,
                    "photo" => $photo
                ];

                if (!empty($options)) {
                    $data['options'] = $options;
                }

                if ($this->cart_service->add($data) != false) {
                    echo "success";
                } else {
                    echo "unsuccess";
                }
            }
        }
        $this->view->disable();
    }

    public function updateAction()
    {
        if ($this->request->isPost()) {
            $id = $this->request->getPost("id");
            $qty = $this->request->getPost("qty");
            $currency = $this->request->getPost("currency");
            $price = $this->request->getPost("price");
            $product = Product::findfirst([
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND id = $id AND active = 'Y' AND deleted = 'N'"
            ]);

            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domain = $this->mainGlobal->getDomainCustomerInfo();

            // $this->cart_service->destroy();

            $data = array();
            if ($product) {
                $language = Languages::findFirstById($product->language_id);
                $subdomain = Subdomain::findFirstById($this->_subdomain_id);

                $photo = !empty($product->photo) ? "/files/product/" . $subdomain->folder . "/" . $product->folder . "/" . $product->photo : '/assets/images/no-image.png';

                $langUrl = $language->code == 'vi' ? '' : $language->code . '/';
               
                $link = $domain ? $protocol . $domain->name . $this->tag->site_url($langUrl . $product->slug) : $protocol . $subdomain->name . '.' . ROOT_DOMAIN . $this->tag->site_url($langUrl . $product->slug);

                $data = [
                    "subdomain_id" => $this->_subdomain_id,
                    "id" => $id,
                    "name" => $product->name,
                    "link" => $link,
                    "qty" => $qty,
                    "currency" => $currency,
                    "price" => $price,
                    "photo" => $photo
                ];

                if ($this->cart_service->update($data) != false) {
                    echo "success";
                } else {
                    return "unsuccess";
                }
            }
        }
        $this->view->disable();
    }

    public function deleteAction()
    {
        if ($this->request->isPost()) {
            $rowId = $this->request->getPost("rowid");
            if ($this->cart_service->removeProduct($rowId) != false) {
                echo "success";
            } else {
                echo "unsuccess";
            }
        }
        $this->view->disable();
    }
}
