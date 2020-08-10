{% set languages = config_service.getLanguagesTranslate() %}
{% set menu = menu_service.getMenuInfoMain() %}
{% if menu != '' %}
    {% set menu_item = menu_service.getMenuItem(menu.id)  %}
    {% set categories = category_service.recursive(0, 0) %}
{% else %}
    {% set menu_item = '' %}
{% endif %}
{% set categoryParents = category_service.recursiveCategoryCombo() %}
{% set sliders = banner_service.getBannerInType(2) %}
<div class="menu_combo bar_web_bgr hidden-xs">
    <div class="container">
        <div class="row">
            <div class="col-md-3 hidden-xs hidden-sm">
                <div class="categories-content-title">
                    <i class="fa fa-list"></i>
                    <span>{{ word['_danh_muc_san_pham'] }}</span>
                    <nav{% if router.getControllerName() != 'index' %} id="left_cate"{% endif %}>
                        <ul class="nav-main">
                            {% for categoryParent in categoryParents %}
                                {% set name = categoryParent['name'] %}
                                {% if languageCode == 'vi' %}
                                    {% set link = tag.site_url(categoryParent['slug']) %}
                                {% else %}
                                    {% set link = tag.site_url(languageCode ~ '/' ~ categoryParent['slug']) %}
                                {% endif %}
                                {% if categoryParent['child'] is defined %}
                                    {% set categoryChilds = categoryParent['child'] %}
                                {% else %}
                                    {% set categoryChilds = '' %}
                                {% endif %}
                            <li class="menuItem">
                                <div class="menuItem-box"><a href="{{ link }}">
                                    {% if categoryParent['icon_type'] == 1 and categoryParent['font_class'] != '' %}<i class="fa fa-{{ categoryParent['font_class'] }}" aria-hidden="true"></i>{% endif %}
                                    {% if categoryParent['icon_type'] == 2 and categoryParent['icon'] != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ categoryParent['icon']) %}
                                    {{ image('files/icon/' ~  subdomain.folder ~ '/' ~ categoryParent['icon'], 'class':'img-main-menu-icon') }}
                                    {% else %}
                                    <i class="fa fa-caret-right" aria-hidden="true"></i> 
                                    {% endif %}
                                    <span>{{ name }}</span>
                                </a>
                                    {% if categoryChilds != '' %}<i class="fa fa-angle-right fa-has-child" aria-hidden="true"></i>{% endif %}
                                </div>
                                {% if categoryChilds != '' %}
                                <div class="sub-cate">
                                    <div class="sub-cate-inner">
                                        {% for categoryChild in categoryChilds  %}
                                        {% set nameCh = categoryChild['name'] %}
                                        {% if languageCode == 'vi' %}
                                            {% set linkCh = tag.site_url(categoryChild['slug']) %}
                                        {% else %}
                                            {% set linkCh = tag.site_url(languageCode ~ '/' ~ categoryChild['slug']) %}
                                        {% endif %}
                                        {% set categoryChs = category_service.getCategoryChild(categoryChild['id']) %}
                                        <ul>
                                            <li><a class="title" href="{{ linkCh }}">{{ nameCh }}</a></li>
                                            {% if categoryChs|length > 0 %}
                                                {% for categoryCh in categoryChs %}
                                                    {% set linkChLast = tag.site_url(categoryCh.slug) %}
                                                    <li><a href="{{ linkChLast }}">{{ categoryCh.name }}</a></li>
                                                {% endfor %}
                                            {% endif %}
                                        </ul>
                                        {% endfor %}
                                    </div>
                                </div>
                                {% endif %}
                            </li>
                            {% endfor %}
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-9">
                <div id="group_menu" class="clearfix">
                    <div class="menu_cb_desktop hidden-xs hidden-sm">
                        <ul>
                            {% for i in menu_item %}
                            {% set name = i.name %}
                            {% set module_name = i.module_name %}
                            {% set moduleId = i.module_id %}
                            {% set active = "" %}
                            {% if module_name == "index" %}
                                {% if i.other_url != '' %}
                                    {% set url = i.other_url %}
                                {% else %}
                                    {% if languageCode == 'vi' %}
                                        {% set url = tag.site_url() %}
                                    {% else %}
                                        {% set url = tag.site_url(languageCode) %}
                                    {% endif %}
                                {% endif %}
                            {% elseif module_name == 'link' %}
                                {% set url = i.url %}
                            {% else %}
                                {% if i.other_url != '' %}
                                    {% set url = i.other_url %}
                                {% else %}
                                     {% set url = tag.site_url(i.url) %}
                                {% endif %}
                            {% endif %}
                            {% set active = "" %}
                            {% if module_name == "index" %}
                                {% if dispatcher.getControllerName() == "index" %}
                                    {% set active = " active" %}
                                {% endif %}
                            {% else %}
                                {% set active = "" %}
                            {% endif %}
                            {% if module_name == "product" or module_name == "subdomain_list" or module_name == "contact" or module_name == "customer_comment" or module_name == "clip" %}
                                {% if i.other_url != '' %}
                                    {% set url = i.other_url %}
                                {% else %}
                                    {% if languageCode == 'vi' %}
                                        {% set url = tag.site_url(i.url) %}
                                    {% else %}
                                        {% set url = tag.site_url(languageCode ~ '/' ~ i.url) %}
                                    {% endif %}
                                {% endif %}
                            {% endif %}
                            {% if module_name == "category" %}
                                {% set categories_one = category_service.recursive(i.module_id, 0) %}
                                {% if languageCode == 'vi' %}
                                    {% set url = tag.site_url(i.url) %}
                                {% else %}
                                    {% set url = tag.site_url(languageCode ~ '/' ~ i.url) %}
                                {% endif %}
                                {% if activeMenu is defined and activeMenu['type'] == module_name and activeMenu['id'] == moduleId %}
                                    {% set active = " active" %}
                                {% endif %}
                            {% endif %}
                            {% if module_name == "news_type" %}
                                 {% set categories_news_type = news_category.recursive(i.module_id, 0, 0) %}
                            {% endif %}
                            {% if module_name == "news_menu" %}
                                {% set news_menu_one = news_menu_service.recursive(i.module_id, 0) %}
                                {% if languageCode == 'vi' %}
                                    {% set url = tag.site_url(i.url) %}
                                {% else %}
                                    {% set url = tag.site_url(languageCode ~ '/' ~ i.url) %}
                                {% endif %}
                                {% if activeMenu is defined and activeMenu['type'] == module_name and activeMenu['id'] == moduleId %}
                                    {% set active = " active" %}
                                {% endif %}
                            {% endif %}
                            {% if module_name == 'product' %}
                                {% if dispatcher.getControllerName() == "product" %}
                                    {% set active = " active" %}
                                {% endif %}
                            {% endif %}
                            {% if module_name == 'clip' %}
                                {% if dispatcher.getControllerName() == "video" %}
                                    {% set active = " active" %}
                                {% endif %}
                            {% endif %}
                            {% if module_name == 'contact' %}
                                {% if dispatcher.getControllerName() == "contact" %}
                                    {% set active = " active" %}
                                {% endif %}
                            {% endif %}
                            {% if module_name == 'subdomain_list' %}
                                {% if dispatcher.getControllerName() == "project" %}
                                    {% set active = " active" %}
                                {% endif %}
                            {% endif %}
                            {% if module_name == 'customer_comment' %}
                                {% if dispatcher.getControllerName() == "customer_comment" %}
                                    {% set active = " active" %}
                                {% endif %}
                            {% endif %}
                            <li class="parent_li"><a class="parent{{ active }}" href="{{ url }}">
                                {% if i.icon_type == 1 and i.font_class != '' %}<i class="fa fa-{{ i.font_class }}" aria-hidden="true"></i>{% endif %}
                                {% if i.icon_type == 2 and i.photo != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ i.photo) %}{{ image('files/icon/' ~  subdomain.folder ~ '/' ~ i.photo, 'class':'img-main-menu-icon') }}{% endif %}
                                {% if i.icon_type_category is defined  and i.icon_type_category == 1 and i.font_class_category != '' %}<i class="fa fa-{{ i.font_class_category }}" aria-hidden="true"></i>{% endif %}
                                {% if i.icon_type_category is defined  and i.icon_type_category == 2 and i.icon_category != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ i.icon_category) %}{{ image('files/icon/' ~  subdomain.folder ~ '/' ~ i.icon_category, 'class':'img-main-menu-icon') }}{% endif %}
                                <span>{{ name }}</span></a>
                            {% if module_name == "product" %}{{ categories  }}{% endif %}{% if module_name == "category" %}{{categories_one }}{% endif %}{% if module_name == "news_type" %}{{ categories_news_type }}{% endif %}{% if module_name == "news_menu" %}{{ news_menu_one }}{% endif %}</li>
                        {% endfor %}
                        </ul>
                        {% if cf['_cf_radio_menu_google_translate'] == true and languages|length > 0 %}
                        <div class="google_translate_menu">
                            <nav>
                                {% for key,language in languages %}
                                <a href="javscript:;" onclick="doGoogleLanguageTranslator('vi|{{ key }}'); return false;" >
                                    <img src="/assets/images/flag/{{ key }}.png" alt="{{ language }}">
                                </a>
                                {% endfor %}
                            </nav>
                        </div>
                        {% endif %}
                        {% if cf['_cf_radio_menu_language_database'] == true and tmpSubdomainLanguages|length > 0 %}
                        <div class="google_translate_menu">
                            <nav>
                                {% for key,tmp in tmpSubdomainLanguages %}
                                <a href="{{ languageUrls[tmp.language.code] }}" >
                                    <img src="/assets/images/flag/{{ tmp.language.code }}.png" alt="{{ tmp.language.name }}">
                                </a>
                                {% endfor %}
                            </nav>
                        </div>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% if router.getControllerName() == 'index' %}
<div class="box_combo_menu_cate_slider">
    <div class="container">
        <div class="row">
            <div class="col-md-3 hidden-xs hidden-sm">
                {% if categoryParents != '' %}
                <nav class="menu-cb-categories-list-box for-home">
                    <ul class="nav-main">
                        {% for categoryParent in categoryParents %}
                            {% set name = categoryParent['name'] %}
                            {% set link = tag.site_url(categoryParent['slug']) %}
                            {% if categoryParent['child'] is defined %}
                                {% set categoryChilds = categoryParent['child'] %}
                            {% else %}
                                {% set categoryChilds = '' %}
                            {% endif %}
                        <li class="menuItem">
                            <div class="menuItem-box"><a href="{{ link }}">
                                {% if categoryParent['icon_type'] == 1 and categoryParent['font_class'] != '' %}<i class="fa fa-{{ categoryParent['font_class'] }}" aria-hidden="true"></i>{% endif %}
                                {% if categoryParent['icon_type'] == 2 and categoryParent['icon'] != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ categoryParent['icon']) %}
                                {{ image('files/icon/' ~  subdomain.folder ~ '/' ~ categoryParent['icon'], 'class':'img-main-menu-icon') }}
                                {% else %}
                                <i class="fa fa-caret-right" aria-hidden="true"></i> 
                                {% endif %}
                                <span>{{ name }}</span>
                            </a>
                                {% if categoryChilds != '' %}<i class="fa fa-angle-right fa-has-child" aria-hidden="true"></i>{% endif %}
                            </div>
                            {% if categoryChilds != '' %}
                            <div class="sub-cate">
                                <div class="sub-cate-inner">
                                    {% for categoryChild in categoryChilds  %}
                                    {% set nameCh = categoryChild['name'] %}
                                    {% set linkCh = tag.site_url(categoryChild['slug']) %}
                                    {% set categoryChs = category_service.getCategoryChild(categoryChild['id']) %}
                                    <ul>
                                        <li><a class="title" href="{{ linkCh }}">{{ nameCh }}</a></li>
                                        {% if categoryChs|length > 0 %}
                                            {% for categoryCh in categoryChs %}
                                                {% set linkChLast = tag.site_url(categoryCh.slug) %}
                                                <li><a href="{{ linkChLast }}">{{ categoryCh.name }}</a></li>
                                            {% endfor %}
                                        {% endif %}
                                    </ul>
                                    {% endfor %}
                                </div>
                            </div>
                            {% endif %}
                        </li>
                        {% endfor %}
                    </ul>
                    {#<div id="show_more_cate"><span>{{ word['_xem_them'] }}</span> <i class="fa fa-plus-circle" aria-hidden="true"></i></div>#}
                </nav>
                {% endif %}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-9 md-pa-0">
                {% if sliders|length > 0 %}
                <div class="box_carousel_combo">
                    <div id="carousel-slider-{{ id }}" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            {% for key,value in sliders %}
                            <li data-target="#carousel-slider-{{ id }}" data-slide-to="{{ key }}"{% if key == 0 %} class="active"{% endif %}></li>
                            {% endfor %}
                        </ol>

                        <div class="carousel-inner" role="listbox">
                        {% for key,value in sliders %}
                            {% set photo = banner_helper.getBannerPhoto(value.photo, subdomain.folder, {'type':'slider'}) %}
                            <div class="item {% if key == 0 %} active{% endif %}">
                                <a href="{{ value.link }}"><img src="{{ photo }}" alt="{{ value.name }}"></a>
                            </div>
                        {% endfor %}
                        </div>

                        <a class="left carousel-control" href="#carousel-slider-{{ id }}" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-slider-{{ id }}" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                {% endif %}
            </div>

        </div>
    </div>
</div>
{% endif %}