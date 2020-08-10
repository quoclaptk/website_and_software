{% set category = category_service.getCategoryParent()  %}
{% if category != "" %}
    {% if position == "right" %}
        {% set box_class = "box_right_element" %}
    {% else %}
        {% set box_class = "box_left_element" %}
    {% endif %}
<div id="{{ html_id ~ "_" ~ id }}" class="{{ box_class }}">
    <div class="title_bar_left text-uppercase bar_web_bgr">{{ word['_danh_muc_san_pham'] }}</div>
    <div class="box_cate_product">
        <ul>
        {% for i in category %}
            {% set name = i.name %}
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
            <li>
                <a href="{{ link }}" title="{{ name }}">{{ name }}</a>
            </li>
        {% endfor %}
        </ul>
    </div>
</div>
{% endif %}