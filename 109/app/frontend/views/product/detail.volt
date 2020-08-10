{% if detail.photo is not null %}
    {% if file_exists("files/product/" ~ subdomain.folder ~ "/" ~ detail.folder ~ "/" ~ detail.photo) %}
        {% set photo = "/files/product/" ~ subdomain.folder ~ "/" ~ detail.folder ~ "/" ~ detail.photo %}
    {% else %}
        {% set photo = "/assets/images/no-image.png" %}
    {% endif %}
    {% if file_exists("files/product/" ~ subdomain.folder ~ "/thumb/120x120/" ~ detail.folder ~ "/" ~ detail.photo) %}
        {% set thumb = "/files/product/" ~ subdomain.folder ~ "/thumb/120x120/" ~ detail.folder ~ "/" ~ detail.photo %}
    {% elseif file_exists("files/product/" ~ subdomain.folder ~ "/" ~ detail.folder ~ "/" ~ detail.photo)  %}
        {% set thumb = "/files/product/" ~ subdomain.folder ~ "/" ~ detail.folder ~ "/" ~ detail.photo %}
    {% else %}
        {% set thumb = "/assets/images/no-image.png" %}
    {% endif %}
{% endif %}

{% if demo_router is defined %}
    {% set router = demo_router %}
{% else %}
    {% set router = '' %}
{% endif %}
{% set priceUsd = null %}
{% if productElementComboPrices is not null %}
    {% set price = tag.cms_price(productElementComboPrices['price'], productElementComboPrices['cost'], "đ", ["class":"box_product_detail_price clearfix", "note":true]) %}
    {% if cf['_cf_text_price_usd_currency'] is defined %}
        {% set priceUsd = tag.cms_price(productElementComboPrices['priceUsd'], productElementComboPrices['costUsd'], cf['_cf_text_price_usd_currency'], ["class":"box_product_detail_price clearfix", "note":true]) %}
    {% endif %}
{% else %}
    {% set price = tag.cms_price(detail.price, detail.cost, "đ", ["class":"box_product_detail_price clearfix", "note":true]) %}
    {% if cf['_cf_text_price_usd_currency'] is defined %}
        {% set priceUsd = tag.cms_price(detail.price_usd, detail.cost_usd, cf['_cf_text_price_usd_currency'], ["class":"box_product_detail_price clearfix", "note":true]) %}
    {% endif %}
{% endif %}
{% set currency = cf['_cf_text_price_text'] is not empty ? cf['_cf_text_price_text'] : 'VNĐ' %}
<div class="box_page">
    {{ partial("partials/breadcrumb") }}
    <div id="box_product_info">
        <div class="row">
            <div class="product-view clearfix">
                {% if detail.photo != '' %}
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div id="sliderFlex" class="flexslider">
                      <ul class="slides">
                        {% if productElementComboPrices is null or (productElementComboPrices is not null and productElementComboPrices['productPhotoElementId'] is null) %}
                        <li data-thumb="{{ photo }}">
                            <a href="{{ photo }}" rel="fancybox-thumb" class="fancybox-thumb">
                                <img src="{{ photo }}" alt="{{ detail.name }}">
                            </a>
                        </li>
                        {% endif %}
                        {% if productPhotos|length > 0 %}
                            {% for i in productPhotos %}
                                {% set pr_photo = "/files/product/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo   %}
                                {% if file_exists("files/product/" ~ subdomain.folder ~ "/thumb/120x120/" ~ i.folder ~ "/" ~ i.photo) %}
                                    {% set pr_thumb = "/files/product/" ~ subdomain.folder ~ "/thumb/120x120/" ~ i.folder ~ "/" ~ i.photo %}
                                {% else %}
                                    {% set pr_thumb = "/files/product/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo %}
                                {% endif %}
                                <li data-thumb="{{ pr_thumb }}">
                                    <a href="{{ pr_photo }}" rel="fancybox-thumb" class="fancybox-thumb">
                                        <img src="{{ pr_photo }}" alt="{{ detail.name }}">
                                    </a>
                                </li>
                            {% endfor %}
                        {% endif %}
                      </ul>
                    </div>
                </div>
                {% endif %}

                <div class="product-shop {% if detail.photo != '' %}col-lg-6 col-sm-6 col-md-6{% else %}col-lg-12 col-sm-12 col-md-12{% endif %} col-xs-12">
                    <div class="product-name">
                        <h1>{{ detail.name }}{% if productElementComboPrices is not null and productElementComboPrices['productElmSelectedName'] is not null %}(<span id="product-combo-name">{{ productElementComboPrices['productElmSelectedName'] }}</span>){% endif %}</h1>
                    </div>
                    {% if detail.summary != "" %}
                    <div class="summary-box">{{ detail.summary }}</div>
                    {% endif %}
                    <ul class="product_info">
                        {% if detail.code != "" %}
                        <li>
                            <label>{{ word['_ma_san_pham'] }}:</label>
                            <span>{{ detail.code }}</span>
                        </li>
                        {% endif %}
                        {% if detail.out_stock == 'Y' %}
                        <li>
                            <label>{{ word['_tinh_trang'] }}:</label>
                            <span class="status-hot-txt">{{ word._('_het_hang') }}</span>
                        </li>
                        {% endif %}
                        {% if productElements is not null %}
                            {% for key,productElement in productElements %}
                            <li>
                                <label>{{ key }}:</label>
                                <span>{{ productElement['name'] }}</span>
                            </li>
                            {% endfor %}
                        {% endif %}
                        {% if productElementPrices is not null %}
                            {% for key,productElementPrice in productElementPrices %}
                            <li>
                                <label>{{ key }}:</label>
                                <div id="attribute-element">
                                    {% for k,prElm in productElementPrice %}
                                    <div class="element-input">
                                        <label class="option-element" data-tmpid="{{ prElm['id'] }}" data-cost="{{ number_format(prElm['cost'], 0, ',', '.') }}" data-price="{{ number_format(prElm['price'], 0, ',', '.') }}"{% if cf['_cf_text_price_usd_currency'] is defined %} data-costusd="{{ number_format(prElm['cost_usd'], 0, ',', '.') }}" data-priceusd="{{ number_format(prElm['price_usd'], 0, ',', '.') }}" data-usdcurrency={{ cf['_cf_text_price_usd_currency'] }}{% endif %}>{{ prElm['name'] }}</label>
                                    </div>
                                    {% endfor %}
                                </div>
                            </li>
                            {% endfor %}
                        {% endif %}
                        {% if cf['_cf_text_price_usd_currency'] is not empty %}
                        <li>
                            <label>{{ word._('Thanh toán theo') }}:</label>
                            <div class="attribute-element">
                                <div class="element-input">
                                    <label class="option-element-pi option-element-select"  data-cost="{{ number_format(detail.cost, 0, ',', '.') }}" data-price="{{ number_format(detail.price, 0, ',', '.') }}" data-currency="{{ currency }}">VNĐ</label>
                                </div>
                                <div class="element-input">
                                    <label class="option-element-pi"  data-cost="{{ number_format(detail.cost_usd, 0, ',', '.') }}" data-price="{{ number_format(detail.price_usd, 0, ',', '.') }}" data-currency="{{ cf['_cf_text_price_usd_currency'] }}">PI Network ({{ cf['_cf_text_price_usd_currency'] }})</label>
                                </div>
                            </div>
                        </li>
                        {% endif %}
                        {% if productElementComboPrices is not null %}
                            {% for objProductElement in productElementComboPrices['objProductElements'] %}
                                {% set isColor = objProductElement['is_color'] %}
                                {% set name = objProductElement['name'] %}
                                {% set productElementDetails = objProductElement['productElementDetails'] %}
                                {% if isColor == 'Y' %}
                                    {% set class = 'color-single-product' %}
                                {% else %}
                                    {% set class = 'capacity-single-product' %}
                                {% endif %}
                                <li>
                                    <div class="box_combo_product">
                                        <div class="{{ class }} full-width clearfix"> <span class="sdo-label-cm text-uppercase">{{ name }}: </span>
                                            {% if productElementDetails is not null %}
                                            <ul>
                                                {% for productElementDetail in productElementDetails %}
                                                    {% set color = productElementDetail['color'] %} 
                                                    {% set prmElmName = productElementDetail['name'] %} 
                                                    {% set active = null %}
                                                    {% if productElementDetail['id'] in productElementComboPrices['peSelected'] %}
                                                        {% set active = ' active' %}
                                                    {% endif %}
                                                    {% if isColor == 'Y' %}
                                                        <li style="color: #fff; background: {{ color }}" data-action="color" data-color-id="{{ productElementDetail['id'] }}" data-product-id="{{ detail.id }}" class="item-color tfs-item-attribute{{active}}"></li>
                                                    {% else %}
                                                        <li data-action="capacity" data-capacity-id="{{ productElementDetail['id'] }}" data-product-id="{{ detail.id }}" class="item-capacity tfs-item-attribute{{active}}">{{ prmElmName }}</li>
                                                    {% endif %}
                                                {% endfor %}
                                            </ul>
                                            {% endif %}
                                        </div>
                                    </div>
                                </li>
                            {% endfor %}
                            <input type="hidden" name="tmpid" value="{{ productElementComboPrices['tmpProductElementSelected'] }}">
                        {% endif %}
                        
                        {% if cf['_turn_off_product_price'] == true %}
                        <li>
                            <label>{{ word['_gia'] }}:</label>
                            <div class="price_detail" id="price_detail">{{ price }}</div>
                        </li>
                        {% endif %}
                        
                        {% if cf['_turn_off_cart_btn'] == true and cf['_cf_radio_cart_product_detail'] == true %}
                        <li class="box_product_detail_cart">
                            <div class="product-detail__order addtocart-component ">
                                {% if cf['_cf_text_cart_other_url'] is null or detail.cart_link is null %}
                                <div class="d-quantiy-changer p-b-12">
                                    <div class="txt-level-count-stock"> {{ word._('_so_luong') }}: </div>
                                    <div class="qty-cart-product-box ">
                                        <span class="qty-cat-product-decrease qty-cart-product-amount js-cart-product-decrease-quantity disabled" aria-hidden="true"></span>
                                        <input id="pdpAddtoCartSelect" type="text" name="product_qty" value="1" min="1" class="input-qty js-cart-product-qty-input-quantity d-quantiy-changer__input">
                                        <span class="qty-cat-product-increase qty-cart-product-amount js-cart-product-increase-quantity" aria-hidden="true"></span>
                                    </div>
                                    {% if detail.in_stock > 0 %}
                                    <div class="txt-total-stock"> ({{ word._('_san_pham_con_lai') }} {{ number_format(detail.in_stock, 0, ',', '.') }} {{ lcfirst(word._('_san_pham')) }}) </div>
                                    {% endif %}
                                </div>
                                {% endif %}
                                {% if detail.cart_link is not null %}
                                    {% set urlCart = detail.cart_link %}
                                {% elseif cf['_cf_text_cart_other_url'] is not null %}
                                    {% set urlCart = cf['_cf_text_cart_other_url'] %}
                                {% else %}
                                    {% set urlCart = 'javascript:;' %}
                                {% endif %}
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="d-combo-sale-buttons d-combo-sale-buttons--add-to-cart">
                                            <div class="d-combo-sale-buttons__btn-item d-combo-sale-buttons__btn-item--buy-now"><a href="{{ urlCart }}" id="btn-1click-checkout" data-id="{{ detail.id }}" data-type="checkout" data-cart-type="{{ cf['_cf_select_cart_type'] }}" data-name="{{ detail.name }}" data-currency="{{ currency }}" data-options=""><button class="d-button d-button--sale d-button--buy-now"><span class="d-button__text h3-rbt-re">{{ word._('_mua_ngay') }}</span></button></a></div>
                                            <div class="d-combo-sale-buttons__btn-item d-combo-sale-buttons__btn-item--add-to-cart">
                                                <div class="AddToCart-AddToCartAction">
                                                    <a href="{{ urlCart }}" id="btn-1click-cart" data-id="{{ detail.id }}" data-type="cart" data-cart-type="{{ cf['_cf_select_cart_type'] }}" data-name="{{ detail.name }}" data-currency="{{ currency }}" data-options=""><button id="addToCartButton" type="button" class="d-button d-button--sale d-button--add-to-cart"><span class="d-button__text h3-rbt-re text-add-to-cart">{{ word._('_them_vao_gio_hang') }}</span></button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {% endif %}
                    </ul>
                    
                    <div class="share_face hidden-xs">
                        <div class="addthis_toolbox addthis_default_style"  style="overflow: hidden">
                            <a class="addthis_button_facebook_like" fb:like:layout="button_count" ></a>
                            <a class="addthis_button_facebook_share" fb:share:layout="button_count"></a>
                        </div>
                        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-52142fca03ecd20e"></script>
                    </div>
                    {% if cf['_cf_radio_fast_register_product'] == true %}
                    {{ partial('partials/box_fast_register') }}
                    {% endif %}
                </div>
            </div>
        </div>
    </div>
    {% if product_detail|length > 0 %}
    <div class="box_product_detail">
        <ul id="pr_detail_tab" class="nav nav-tabs text-uppercase" role="tablist">
            {% for key,value in product_detail %}
                {% if key == 0 %} {% set class = "active" %} {% else %} {% set class = "" %} {% endif %}
                <li role="presentation" class="{{ class }}"><a href="#tab-pr-detail-{{ value.id }}" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">{{ value.name }}</a></li>
            {% endfor %}
        </ul>
        <div class="tab-content" id="pr_detail_content">
            {% for key,value in product_detail %}
                {% if key == 0 %} {% set class = " active in" %} {% else %} {% set class = "" %} {% endif %}
                <div class="tab-pane fade{{ class }}" role="tabpanel" id="tab-pr-detail-{{ value.id }}">{{ htmlDisplayShortCode(htmlspecialchars_decode(value.content)) }}</div>
            {% endfor %}
        </div>
    </div>
    {% endif %}
    {% if tmpTypeModules|length > 0 %}
    <div class="box_product_modules">
        {% for i in tmpTypeModules %}
            {% set id = i.id %}
            {% set html_id = i.type %}
            {% set module_id = i.module_id %}
            {{ partial("partials/home", ["layout":setting.layout_id, "id":id, "html_id":html_id, "module_id":module_id]) }}
        {% endfor %}
    </div>
    {% endif %}
    {% if cf['_turn_off_comment_facebook'] == true %}
    <div class="hidden-xs fb-comment-area">
        <div class="fb-comments" data-href="{{ mainGlobal.getCurrentUrl() }}" data-numposts="5"></div>
    </div>
    {% endif %}
    {% if other_product|length > 0 %}
    <div class="box_product_other">
        <div class="title_pr_care text-uppercase"><span>{{ word['_san_pham_khac'] }}</span><p></p></div>

        <div class="list_product_care owl-carousel">
        {% for i in other_product %}
            {{ product_helper.productListViewHtml('item', router, i, subdomain, setting, cf, word, ['detail':true])}}
        {% endfor %}
        </div>
    </div>
    {% endif %}
</div>