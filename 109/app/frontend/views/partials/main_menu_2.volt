{% set languages = config_service.getLanguagesTranslate() %}
{% set menu = menu_service.getMenuInfoMain() %}
{% set categoryParents = category_service.getCategoryParent()  %}
{% if menu != '' %}
{% set menu_item = menu_service.getMenuItem(menu.id)  %}
{% set categories = category_service.recursive(0, 0, {'level0':true}) %}
<div class="main_menu_2 hidden-xs">
    {% if position == 'header' or position == 'footer' %}<div class="container">{% endif %}
        <div class="row">
            <div class="col-md-5 col-sm-12 col-menu-2-logo-search">
                <div class="main_menu_2_group_logo_search bar_web_bgr clearfix">
                    <div class="main_menu_2_logo pull-left">
                        <a href="{{ tag.site_url() }}">
                            {% if setting.enable_image_menu_2 == 1 and setting.image_menu_2 is not null %}
                                <img src="/files/default/{{ subdomain.folder ~ "/" ~ setting.image_menu_2 }}" alt="Logo" >
                            {% else %}
                                {% if setting.enable_logo_text == 0 %}
                                    <img src="/files/default/{{ subdomain.folder ~ "/" ~ setting.logo }}" alt="Logo" >
                                {% else %}
                                    {% if setting.text_logo != '' %}
                                        {% set txt_logo = json_decode(setting.text_logo, true) %}
                                    <div class="text-center main_menu_2_txt">
                                      <div class="main_menu_2_txt_text_1 text1_logo text-uppercase txt_web_color">{{ txt_logo[0] }}</div>
                                    </div>
                                    {% endif %}
                                {% endif %}
                            {% endif %}
                        </a> 
                    </div>
                    <div class="main_menu_2_group_search pull-left">
                        <div class="input-group group_search">
                            <form method="post" class="clearfix" action="/p-search/">
                                {% if categoryParents|length > 0 %}
                                <select class="form-control pull-left" name="catID">
                                    <option value="0">Danh mục</option>
                                    {% for categoryParent in categoryParents %}
                                    <option value="{{ categoryParent.id }}"{% if request.get('catID') == categoryParent.id %} selected {% endif %}>{{ categoryParent.name }}</option>
                                    {% endfor %}
                                </select>
                                {% endif %}
                                <div class="pull-left">
                                    <input type="text" name="keyword" value="" class="form-control" placeholder="{{ word['_nhap_tu_khoa'] }}" required="">
                                </div>
                                <button type="submit" id="btn_search">
                                    <i class="icon-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-sm-12 col-menu-2-menu-item">
                {% if menu_item != '' %}
                <div class="nav-container">
                    <ul id="nav-menu-top-2" class="clearfix pull-right">
                    {% for i in menu_item %}
                        {% set levelZeroClass = '' %}
                        {% set name = i.name %}
                        {% set module_name = i.module_name %}
                        {% set moduleId = i.module_id %}
                        {% set active = "" %}
                        {% if i.new_blank == 'Y' %}
                            {% set target = ' target="blank"' %}
                        {% else %}
                            {% set target = null %}
                        {% endif %}
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
                        {% if module_name == "index" %}
                            {% if dispatcher.getControllerName() == "index" %}
                                {% set active = " active" %}
                            {% endif %}
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
                            {% set categories_one = category_service.recursive(i.module_id, 0, {'level0':true}) %}
                                {% if languageCode == 'vi' %}
                                    {% set url = tag.site_url(i.url) %}
                                {% else %}
                                    {% set url = tag.site_url(languageCode ~ '/' ~ i.url) %}
                                {% endif %}
                            {% if categories_one != '' %}
                                {% set levelZeroClass = 'has-children' %}
                            {% endif %}
                            {% if activeMenu is defined and activeMenu['type'] == module_name and activeMenu['id'] == moduleId %}
                                {% set active = " active" %}
                            {% endif %}
                        {% endif %}
                        {% if module_name == "news_type" %}
                            {% if languageCode == 'vi' %}
                                {% set url = tag.site_url(i.url) %}
                            {% else %}
                                {% set url = tag.site_url(languageCode ~ '/' ~ i.url) %}
                            {% endif %}
                        {% endif %}
                        {% if module_name == "news_menu" %}
                            {% set news_menu_one = news_menu_service.recursive(i.module_id, 0, {'level0':true}) %}
                                {% if languageCode == 'vi' %}
                                    {% set url = tag.site_url(i.url) %}
                                {% else %}
                                    {% set url = tag.site_url(languageCode ~ '/' ~ i.url) %}
                                {% endif %}
                            {% if news_menu_one != '' %}
                                {% set levelZeroClass = 'has-children' %}
                            {% endif %}
                            {% if activeMenu is defined and activeMenu['type'] == module_name and activeMenu['id'] == moduleId %}
                                {% set active = " active" %}
                            {% endif %}
                        {% endif %}
                        {% if module_name == 'product' %}
                            {% if categories != '' %}
                            {% set levelZeroClass = 'has-children' %}
                            {% endif %}
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
                        <li class="level0"><a href="{{ url }}"{{ target }} class="level0{{ active }}{% if levelZeroClass is defined %} 
                        {{ levelZeroClass }}{% endif %}">{% if i.icon_type == 1 and i.font_class != '' %}<i class="fa fa-{{ i.font_class }}" aria-hidden="true"></i>{% endif %}
                        {% if i.icon_type == 2 and i.photo != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ i.photo) %}{{ image('files/icon/' ~  subdomain.folder ~ '/' ~ i.photo, 'class':'img-main-menu-icon') }}{% endif %}
                        {% if i.icon_type_category is defined  and i.icon_type_category == 1 and i.font_class_category != '' %}<i class="fa fa-{{ i.font_class_category }}" aria-hidden="true"></i>{% endif %}
                        {% if i.icon_type_category is defined  and i.icon_type_category == 2 and i.icon_category != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ i.icon_category) %}{{ image('files/icon/' ~  subdomain.folder ~ '/' ~ i.icon_category, 'class':'img-main-menu-icon') }}{% endif %}<span>{{ name }}</span></a>{% if module_name == "product" %}{{ categories }}{% endif %}{% if module_name == "category" %}{{categories_one }}{% endif %}{% if module_name == "news_menu" %}{{ news_menu_one }}{% endif %}
                        </li>
                        {% endfor %}
                    </ul>
                </div>
                {% endif %}
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
    {% if position == 'header' or position == 'footer' %}</div>{% endif %}
</div>
{% endif %}