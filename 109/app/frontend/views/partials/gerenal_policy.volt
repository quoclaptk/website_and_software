{% set policy = news_service.getNewsTypeInfoType("policy") %}
{% if policy != "" %}
    {% set news = news_service.getlistNews(policy.id, 10) %}

    {% if news != "" %}
        <div id="{{ html_id }}_{{ id }}">
            <div class="title_footer text-uppercase"><h4>{{ word['_chinh_sach_chung'] }}</h4></div>
            <div class="content_footer_middle" id="company_fanpage">
                <ul>
                    {% for i in news %}
                        {% set name = i.name %}
                        {% if demo_router is defined %}
                            {% set link = tag.site_url(demo_router ~ '/' ~ i.slug) %}
                        {% else %}
                            {% set link = tag.site_url(i.slug) %}
                        {% endif %}
                        <li>
                            <a href="{{ link }}" title="{{ name }}">{{ name }}</a>
                        </li>
                    {% endfor %}
                </ul>
            </div>
        </div>
    {% endif %}
{% endif %}

{% set news_menu = news_service.getNewsMenuFooter() %}
{% if news_menu|length > 0 %}
    {% for i in news_menu %}
        {% if i['news'] is defined %}
            {% set news = i['news'] %}
                <div class="box_news_footer">
                    <div class="title_footer text-uppercase"><h4>{{ i['name'] }}</h4></div>
                    {% if news|length > 0 %}
                    <div class="content_footer_right">
                        <ul>
                            {% for j in news %}
                                {% set name = j['name'] %}
                                {% if demo_router is defined %}
                                    {% set link = tag.site_url(demo_router ~ '/' ~ j['slug']) %}
                                {% else %}
                                    {% set link = tag.site_url(j['slug']) %}
                                {% endif %}
                                <li>
                                    <a href="{{ link }}" title="{{ name }}">{{ name }}</a>
                                </li>
                            {% endfor %}
                        </ul>
                    </div>
                    {% endif %}
                </div>
        {% endif %}
    {% endfor %}
{% endif %}
