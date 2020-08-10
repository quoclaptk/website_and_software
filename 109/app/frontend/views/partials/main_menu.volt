{% set languages = config_service.getLanguagesTranslate() %}
{% set menu = menu_service.getMenuInfo(module_id) %}
{% if menu != '' %}
	{% set menu_item = menu_service.getMenuItem(menu.id)  %}
	{% set categories = category_service.recursive(0, 0) %}
{% if menu.style == 'horizontal' and menu_item != '' %}
<div class="main_menu bar_web_bgr hidden-xs">
    <div class="container">
        <div class="box_main_menu clearfix">
            <nav class="main_menu_nav hidden-xs">
                <ul class="clearfix">
                {% for i in menu_item %}
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
                        {% set categories_news_type = news_category.recursive(i.module_id, 0, 0) %}
                    {% endif %}
                    {% if module_name == "news_menu" %}
                        {% set news_menu_one = news_menu_service.recursive(i.module_id, 0, {'level0':true}) %}
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
                    <li><a href="{{ url }}"{{ target }} class="text-uppercase{{ active }}">
                    	{% if i.icon_type == 1 and i.font_class != '' %}<i class="fa fa-{{ i.font_class }}" aria-hidden="true"></i>{% endif %}
                    	{% if i.icon_type == 2 and i.photo != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ i.photo) %}{{ image('files/icon/' ~  subdomain.folder ~ '/' ~ i.photo, 'class':'img-main-menu-icon') }}{% endif %}
                    	{% if i.icon_type_category is defined  and i.icon_type_category == 1 and i.font_class_category != '' %}<i class="fa fa-{{ i.font_class_category }}" aria-hidden="true"></i>{% endif %}
                    	{% if i.icon_type_category is defined  and i.icon_type_category == 2 and i.icon_category != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ i.icon_category) %}{{ image('files/icon/' ~  subdomain.folder ~ '/' ~ i.icon_category, 'class':'img-main-menu-icon') }}{% endif %}
                    	<span>{{ name }}</span></a>
                        {% if module_name == "product" %}{{ categories  }}{% endif %}{% if module_name == "category" %}{{categories_one }}{% endif %}
                        {% if module_name == "news_type" %}{{ categories_news_type }}{% endif %}
                        {% if module_name == "news_menu" %}{{ news_menu_one }}{% endif %}
                    </li>
                {% endfor %}
                </ul>
            </nav>
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
{% endif %}
{% if menu.style == 'vertical' and menu_item != '' %}
<div class="menu-vertical box_left_element">
    <div class="title_bar_left text-uppercase bar_web_bgr">{{ word['_menu'] }}</div>
    <div class="box-content box-category">
        <ul>
        {% for i in menu_item %}
            {% set name = i.name %}
            {% set module_name = i.module_name %}
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
            {% endif %}
            {% if module_name == "news_type" %}
                {% set categories_news_type = news_category.recursive(i.module_id, 0, 0) %}
            {% endif %}
            {% if module_name == "news_menu" %}
                {% set news_menu_one = news_menu_service.recursive(i.module_id, 0, {'level0':true}) %}
                {% if languageCode == 'vi' %}
                    {% set url = tag.site_url(i.url) %}
                {% else %}
                    {% set url = tag.site_url(languageCode ~ '/' ~ i.url) %}
                {% endif %}
            {% endif %}
            <li><a href="{{ url }}"{{ target }} title="{{ name }}">
            	{% if i.icon_type == 2 and i.photo != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ i.photo) %}
            		{{ image('files/icon/' ~  subdomain.folder ~ '/' ~ i.photo, 'class':'img-main-menu-icon') }}
            	{% elseif i.icon_type == 1 and i.font_class != '' %}
            		<i class="fa fa-{{ i.font_class }}" aria-hidden="true"></i>
        		{% elseif i.icon_type_category is defined %}
					{% if i.icon_type_category == 1 and i.font_class_category != '' %}
						<i class="fa fa-{{ i.font_class_category }}" aria-hidden="true"></i>
					{% elseif i.icon_type_category == 2 and i.icon_category != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ i.icon_category) %}
						{{ image('files/icon/' ~  subdomain.folder ~ '/' ~ i.photo, 'class':'img-main-menu-icon') }}
					{% endif %}
        		{% else %}
	            	<i class="fa fa-caret-right"></i>
            	{% endif %}
            	<span>{{ name }}</span></a>
                {% if module_name == "product" %}{{ categories }}{% endif %}
                {% if module_name == "category" %}{{ categories_one }}{% endif %}
                {% if module_name == "news_menu" %}{{ news_menu_one }}{% endif %}
            </li>
        {% endfor %}
        </ul>
    </div>
</div>
{% endif %}
{% endif %}