{% set productHot = product_service.getProductHot(type, limit) %}
{% if type == "hot" %}
    {% set title_product_hot = word['_san_pham_noi_bat'] %}
{% endif %}
{% if type == "selling" %}
    {% set title_product_hot = word['_san_pham_ban_chay'] %}
{% endif %}
{% if  type == "promotion" %}
    {% set title_product_hot = word['_san_pham_khuyen_mai'] %}
{% endif %}
{% if  type == "new" or type == "newest" %}
    {% set title_product_hot =  word['_san_pham_moi'] %}
{% endif %}
{% if position == "right" %}
    {% set box_class = "box_right_element" %}
{% else %}
    {% set box_class = "box_left_element" %}
{% endif %}

<div id="{{ html_id ~ "_" ~ id }}" class="{{ box_class }} hidden-sm hidden-xs">
    <div class="title_bar_left bar_web_bgr text-uppercase">{{ title_product_hot }}</div>
    <div class="product_sale_left">
        {% if productHot|length > 0 %}
            {% for i in productHot %}
                {% set id = i.id %}
                {% set name = i.name %}
                {% set name_show = tag.cut_string(i.name, 40) %}
                {% if demo_router is defined %}
                    {% if languageCode == 'vi' %}
                        {% set link = tag.site_url(demo_router ~ '/' ~ i.slug) %}
                    {% else %}
                        {% set link = tag.site_url(demo_router ~ '/' ~ languageCode ~ '/' ~ i.slug) %}
                    {% endif %}
                {% else %}
                    {% if languageCode == 'vi' %}
                        {% set link = tag.site_url(i.slug) %}
                    {% else %}
                        {% set link = tag.site_url(languageCode ~ '/' ~ i.slug) %}
                    {% endif %}
                {% endif %}
                {% set photo = product_helper.getProductPhoto(i.photo, subdomain.folder, i.folder, {'folder':'120x120'}) %}
                {% if cf['_turn_off_product_price'] == true %}
                    {% set price = tag.cms_price(i.price, i.cost, "đ", ["class":"product_price"]) %}
                {% else %}
                    {% set price = '' %}
                {% endif %}
                <div class="media">
                    <div class="media-left">
                        <a href="{{ link }}">
                            {% if cf['_cf_radio_enable_lazyload_image'] %}
                            <img class="media-object lazy" data-src="{{ photo }}" data-alt="{{ name }}">
                            {% else %}
                            <img class="media-object" src="{{ photo }}" alt="{{ name }}">
                            {% endif %}
                        </a>
                    </div>
                    <div class="media-body">
                        <p class="media-heading"><a href="{{ link }}" title="{{ name }}">{{ name_show }}</a></p>
                        {{ price }}
                    </div>
                </div>
            {% endfor %}
        {% endif %}
    </div>
</div>