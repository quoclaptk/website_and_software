{% if config_service.getLanguagesTranslate()|length > 0 or (config_service.getLanguagesTranslate()|length > 0 and cf['_cf_radio_menu_google_translate'] == true) %}
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
{{javascript_include('assets/js/google_translate.js')}}
<div id="google_translate_element"></div>
{% endif %}
{% if demo_router is defined %}
{% set linkCart = tag.site_url(demo_router ~ '/gio-hang') %}
{% set linkSearch = tag.site_url(demo_router ~ '/p-search') %}
{% else %}
{% set linkCart = tag.site_url('gio-hang') %}
    {% if languageCode == 'vi' %}
        {% set linkSearch = '/tim-kiem' %}
    {% else %}
        {% set linkSearch =  '/' ~ languageCode ~ '/search' %}
    {% endif %}
{% endif %}
{% set tmpLayoutModuleHeades = module_item_service.getTmpLayoutModulePosition(setting.layout_id, 'header') %}
{% if tmpLayoutModuleHeades is not null %}
<header>
    {% if dayRemain is defined and dayRemain < 20 %}
    <div style="padding:25px 0!important;background:#f00!important;color:#fff!important;text-align:center!important;font-weight:500!important;display:block!important;height: 70px!important">Thông báo tự động: Hiện tại website cần được nâng cấp & bảo trì, vui lòng liên hệ đơn vị thiết kế website để được hỗ trợ | <a href="https://docs.google.com/document/d/1wdi1D9fvRGu9K8tQ41A9mY_SwibcbRvO2HwqBwXpCws/edit" target="_blank" style="color:#0f1b2d!important;text-decoration:underline!important">Xem thêm</a></div>
    {% endif %}
    {% if cf['_cf_radio_menu_mobile'] == true %}
    <div id="header_mobile" class="bar_web_bgr hidden-sm hidden-md hidden-lg">
        <div class="container">
            <div class="row">
                <div class="mm-toggle-wrap col-xs-2">
                    <div class="mm-toggle"><i class="fa fa-bars"></i></div>
                </div>
                <div class="{% if cf['_turn_off_cart_banner'] == true %}col-xs-7{% else %}col-xs-10{% endif %} col-logo-mobile logo-header text-center">
                    <a href="{{ tag.site_url() }}">
                    {% if setting.enable_logo_text == 0 %}
                        <img src="/files/default/{{ subdomain.folder ~ "/" ~ setting.logo }}" alt="Logo" class="company_logo_img">
                    {% else %}
                        {% if setting.text_logo != '' %}
                        {% set txt_logo = json_decode(setting.text_logo, true) %}
                        <div class="text-center" id="logo_text_type_mobile">
                          {% if count(txt_logo) > 0 %}<div class="text1_logo_mobile">{{ txt_logo[0] }}</div>{% endif %}
                          {% if count(txt_logo) > 1 %}
                          <div class="text2_logo_mobile">{{ txt_logo[1] }}</div>
                          {% endif %}
                        </div>
                        {% endif %}
                    {% endif %}
                    </a>  
                </div>
                {% if cf['_turn_off_cart_banner'] == true %}
                <div class="col-xs-3 col-mobile-cart">
                    <div class="header_cart clearfix text-center">
                        <div class="s_cart text-center">
                            <a href="{{ linkCart }}">
                                <i class="icon-giohang"></i>
                            </a>
                            <span class="item_count txt_web_color">{{ cart_service.getTotalItems() }}</span>
                        </div>
                    </div>
                </div>
                {% endif %}
            </div>
        </div>
    </div>
    {% endif %}
    {% for i in tmpLayoutModuleHeades %}
        {% set id = i["id"] %}
        {% set module_id = i["module_id"] %}
        {% set html_id = i["type"] %}
        {% if html_id == "_header_top" %}
            <div class="header_top hidden-xs">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-5">
                            <div class="company_name txt_web_color text-uppercase">{{ setting.name }}</div>
                        </div>
                        <div class="col-md-6 col-sm-7">
                            <ul id="header_top_info">
                                <li><i class="fa fa-phone-square txt_web_color" aria-hidden="true"></i><span>{{ word['_hotline'] }}: </span><strong><a href="tel:{{ hotline }}">{{ hotline }}</a></strong></li> |
                                <li><i class="icon-mail txt_web_color"></i><span><a href="mailto:{{ setting.email }}">{{ setting.email }}</a></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        {% endif %}
        {% if html_id == "_header_logo_search_cart" %}
            {% set moduleChilds = module_item_service.getModuleChild(module_id) %}
            {% if moduleChilds|length > 0 %}
            <div class="header_logo_search_cart hidden-xs">
                <div class="container">
                    <div class="row">
                    {% for moduleChild in moduleChilds %}
                        {% if moduleChild.module_type == '_header_logo' and moduleChild.active ==  'Y' %}
                        <div class="col-sm-3 col-header-logo">
                            <div>                               
                                <a href="{{ tag.site_url() }}">
                                {% if setting.enable_logo_text == 0 %}
                                    <img src="/files/default/{{ subdomain.folder ~ "/" ~ setting.logo }}" alt="Logo">
                                    {% else %}
                                    {% if setting.text_logo != '' %}
                                        {% set txt_logo = json_decode(setting.text_logo, true) %}
                                    <div class="text-center" id="logo_text_type_{{ setting.enable_logo_text }}">
                                      {% if count(txt_logo) > 0 %}<p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ txt_logo[0] }}</p>{% endif %}
                                      {% if count(txt_logo) > 1 %}
                                      <p class="text2_logo">{{ txt_logo[1] }}</p>
                                      {% endif %}
                                    </div>
                                    {% endif %}
                                {% endif %}
                                </a>  
                            </div>
                        </div>
                        {% endif %}
                        {% if moduleChild.module_type == '_header_search' and moduleChild.active ==  'Y' %}
                        {% set category = category_service.getCategoryParent()  %}
                        <div class="{% if cf['_turn_off_cart_banner'] == true %}col-sm-7{% else %}col-sm-9{% endif %} col-header-search">
                            <div class="group_banner_center clearfix{% if cf['_cf_radio_banner_hotline'] == true %} group_banner_center_info{% endif %}">
                                {% if cf['_cf_radio_banner_search'] == true %}
                                <div class="input-group group_search">
                                    <form class="clearfix" action="{{ linkSearch }}">
                                        {% if cf['_cf_radio_banner_search_category'] == true and category|length > 0 %}
                                        <select class="form-control pull-left group_search_category" name="catID">
                                            <option value="0">{{ word['_danh_muc'] }}</option>
                                            {% for k in category %}
                                            <option value="{{ k.id }}"{% if request.get('catID') == k.id %} selected {% endif %}>{{ k.name }}</option>
                                            {% endfor %}
                                        </select>
                                        {% endif %}
                                        {% if cf['_cf_radio_banner_search_input'] == true %}
                                        <div class="pull-left group_search_input">
                                            <input type="text" name="q" value="{{ request.get('q') }}" class="form-control" placeholder="{{ word['_nhap_tu_khoa'] }}" required>
                                        </div>
                                        {% endif %}
                                        <button type="submit" id="btn_search">
                                            <i class="icon-search"></i>
                                        </button>
                                    </form>
                                </div>
                                {% endif %}
                                {% if cf['_cf_radio_banner_hotline'] == true %}
                                <div class="department_address_banner">
                                    <ul>
                                        {% if setting.email != "" %}
                                        <li class="clearfix">
                                            <i class="icon-mail pull-left"></i>
                                            <span class="pull-left">Email: <a href="mailto:{{ setting.email }}">{{ setting.email }}</a></span>
                                        </li>
                                        {% endif %}
                                        {% if hotline is not null %}
                                        <li class="clearfix">
                                            <i class="icon-sodienthoai pull-left"></i>
                                            <span class="pull-left">Điện thoại: <a href="tel:{{ hotline }}">{{ hotline }}</a></span>
                                        </li>
                                        {% endif %}
                                        {% if setting.address != "" %}
                                        <li class="clearfix">
                                            <i class="icon-dia-chi pull-left"></i>
                                            <span class="pull-left">{{ setting.address }}</span>
                                        </li>
                                        {% endif %}
                                    </ul>
                                </div>
                                {% endif %}
                            </div>
                        </div>
                        {% endif %}
                        {% if moduleChild.module_type == '_header_cart' and moduleChild.active ==  'Y' %}
                            {% if cf['_turn_off_cart_banner'] == true %}
                            <div class="col-sm-2 col-header-cart">
                                <div class="header_cart pull-right clearfix">
                                    <a href="{{ linkCart }}">
                                        <div class="s_cart pull-left text-center">
                                            <i class="icon-giohang"></i>
                                        </div>
                                    </a>
                                    <div class="pull-left txt_cart">
                                        <p class="bold"><a href="{{ linkCart }}">{{ word['_gio_hang'] }}</a></p>
                                        <p class="text-center txt_web_color bold">(<span class="number_cart_item">{{ cart_service.getTotalItems() }}</span>)</p>
                                    </div>
                                </div>
                            </div>
                            {% endif %}
                        {% endif %}
                    {% endfor %}
                    </div>
                </div>
            </div>
            {% endif %}
        {% endif %}
        {% if html_id == "menu" %}
        {{ partial("partials/main_menu", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "banner" %}
        {{ partial("partials/banner", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "post" %}
        {{ partial("partials/post", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_comment_facebook" %}
        {{ partial("partials/facebook_comment", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_admin_login" %}
        {{ partial("partials/admin_login", ["layout":layout, "id":id, "html_id":html_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_banner_top" %}
        {{ partial("partials/banner_html", ["layout":layout, "id":id, "html_id":html_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_newsletter" %}
        {{ partial("partials/newsletter", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_multi_language_google" %}
        {{ partial("partials/multi_language_google", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_multi_language_database" %}
        {{ partial("partials/multi_language_database", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_search" %}
        {{ partial("partials/search", ["layout":layout, "id":id, "html_id":html_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_customer_message" %}
        {{ partial("partials/customer_message_module", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_frm_ycbg" %}
        {{ partial("partials/form_item_module", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_customer_comment" %}
        {{ partial("partials/customer_comment", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_home_news_hot_index" %}
        {{ partial("partials/news_hot", ["position":"header", "layout":layout, "html_id":html_id, "id": id, "news_type":"news", "type":"hot", "limit":4]) }}
        {% endif %}
        {% if html_id == "_news_new" %}
        {{ partial("partials/news_hot", ["position":"header", "layout":layout, "html_id":html_id, "id":id, "news_ty
            pe":2, "type":"new", "limit":4]) }}
        {% endif %}
        {% if html_id == "_news_most_view" %}
        {{ partial("partials/news_hot", ["position":"header", "layout":layout, "html_id":html_id, "id":id, "news_type":2, "type":"most_view", "limit":4]) }}
        {% endif %}
        {% if html_id == "_module_news_introduct" %}
        {{ partial("partials/module_news_introduct", ["layout":layout, "id":id, "html_id":html_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_home_product_hot" %}
        {{ partial("partials/product_hot", ["layout":layout, "id":id, "html_id":html_id, "type":"hot", "limit":8, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_home_product_new" %}
        {{ partial("partials/product_hot", ["layout":layout, "id":id, "html_id":html_id, "type":"new", "limit":8, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_policy_service" %}
        {{ partial("partials/policy_service", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_combo_menu_slider" %}
        {{ partial("partials/combo_menu_slider", ["layout":layout, "position":"header", "id":id, "module_id":module_id]) }}
        {% endif %}
        {% if html_id == "_md_search_advanced" %}
        {{ partial("partials/search_advanced", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_menu_top_2" %}
        {{ partial("partials/main_menu_2", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_category_hot" %}
        {{ partial("partials/category_hot", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_register_login" %}
        {{ partial("partials/member_account", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_product_hot_slide" %}
        {{ partial("partials/product_hot_slide", ["layout":layout, "position":"header", "type":"hot"]) }}
        {% endif %}
        {% if html_id == "_text_marquee_horizontal" %}
        {{ partial("partials/text_marquee_horizontal", ["layout":layout, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_contact" %}
        {{ partial("partials/contact", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_banner_2" %}
        {{ partial("partials/md_banner_2", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_banner_3" %}
        {{ partial("partials/md_banner_3", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_google_map" %}
        {{ partial("partials/md_google_map", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_usually_question" %}
        {{ partial("partials/usually_question", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_date_time" %}
        {{ partial("partials/md_date_time", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_subdomain_implement" %}
        {{ partial("partials/subdomain_implement", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_module_create_website" %}
        {{ partial("partials/module_create_website", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_slider_news" %}
        {{ partial("partials/slider_news", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_news_hot_effect" %}
        {{ partial("partials/news_hot_effect", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_home_product_category" %}
        {{ partial("partials/home_product_category", ["position":"header", "layout":layout, "id":id, "html_id":html_id, "limit":8]) }}
        {% endif %}
        {% if html_id == "_home_news_menu" %}
        {{ partial("partials/home_news_menu", ["layout":layout, "id":id, "html_id":html_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_product_hot_about" %}
        {{ partial("partials/product_hot_about", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_news_statistical" %}
        {{ partial("partials/news_statistical", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_md_slider_news" %}
        {{ partial("partials/md_slider_news", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
        {% if html_id == "_category_group_sole" %}
        {{ partial("partials/category_group_sole", ["module_id":module_id, "position":"header"]) }}
        {% endif %}
    {% endfor %}
</header>
{% endif %}