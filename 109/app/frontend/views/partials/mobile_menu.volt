{% set languages = config_service.getLanguagesTranslate() %}
{% set menu = menu_service.getMenuInfoMain() %}
{% if menu != '' %}
{% set menu_item = menu_service.getMenuItem(menu.id)  %}
{% if demo_router is defined %}
    {% set categories = category_service.recursive(0, 0, ['router':demo_router]) %}
{% else %}
    {% set categories = category_service.recursive(0, 0) %}
{% endif %}
<div id="mobile-menu">
    <ul>
        <li>
            <div class="mm-search bar_web_bgr">
                <form action="/{% if languageCode == 'vi' %}tim-kiem{% else %}{{ languageCode ~ '/search' }}{% endif %}" id="search" name="search" method="get" class="navbar-form form_search_index">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="icon-search"></i></button>
                        </div>
                        <input type="text" value="{{request.get('q')}}" name="q" class="form-control simple" placeholder="{{ word['_nhap_tu_khoa'] }}" id="srch-term">
                    </div>
                </form>
            </div>
        </li>
        {% if cf['_cf_radio_menu_google_translate'] == true and languages|length > 0 %}
        <li>
            <div class="google_translate_menu_mobile">
                <nav>
                    {% for key,language in languages %}
                    <a href="javscript:;" onclick="doGoogleLanguageTranslator('vi|{{ key }}'); return false;" >
                        <img src="/assets/images/flag/{{ key }}.png" alt="{{ language }}">
                    </a>
                    {% endfor %}
                </nav>
            </div>
        </li>
        {% endif %}
        {% if cf['_cf_radio_menu_language_database'] == true and tmpSubdomainLanguages|length > 0 %}
        <li>
            <div class="google_translate_menu_mobile">
                <nav>
                    {% for key,tmp in tmpSubdomainLanguages %}
                    <a href="{{ languageUrls[tmp.language.code] }}" >
                        <img src="/assets/images/flag/{{ tmp.language.code }}.png" alt="{{ tmp.language.name }}">
                    </a>
                    {% endfor %}
                </nav>
            </div>
        </li>
        {% endif %}
        {% if menu_item != '' %}
        {% for i in menu_item %}
            {% set name = i.name %}
            {% set module_name = i.module_name %}
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
                    {% if demo_router is defined %}
                        {% set url = tag.site_url(demo_router ~ '/' ~ i.url) %}
                    {% else %}
                        {% set url = tag.site_url(i.url) %}
                    {% endif %}
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
                {% set news_menu_one = news_menu_service.recursive(i.module_id, 0) %}
                {% if languageCode == 'vi' %}
                    {% set url = tag.site_url(i.url) %}
                {% else %}
                    {% set url = tag.site_url(languageCode ~ '/' ~ i.url) %}
                {% endif %}
            {% endif %}
        <li><a href="{{ url }}">{{ name }}</a>{% if module_name == "product" %}{{ categories  }}{% endif %}{% if module_name == "category" %}{{categories_one }}{% endif %}{% if module_name == "news_type" %}{{ categories_news_type }}{% endif %}{% if module_name == "news_menu" %}{{ news_menu_one }}{% endif %}</li>
        {% endfor %}
        {% endif %}
    </ul>
</div>
{% endif %}