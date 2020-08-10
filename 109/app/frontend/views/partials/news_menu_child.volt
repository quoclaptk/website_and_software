{% set categories = news_menu_service.getCategoryChild(parent_id) %}
{% if categories|length > 0 %}
<div class="box_news_menu_child box_cate_news">
    <ul>
        {% for i in categories %}
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
            <a href="{{ link }}" title="{{ i.name }}">{{ i.name }}</a>
        </li>
        {% endfor %}
    </ul>
</div>
{% endif %}