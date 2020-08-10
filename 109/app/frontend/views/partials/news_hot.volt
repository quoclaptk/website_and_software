{% set newsHot = news_service.getNewsHot(type, limit) %}
{% if type == 'hot' %}
    {% set title = word['_tin_noi_bat'] %}
{% elseif type == 'new' %}
    {% set title = word['_tin_moi'] %}
{% elseif type == 'most_view' %}
    {% set title = word['_tin_xem_nhieu'] %}
{% endif %}
{% if position == 'header' or position == 'footer' or position == 'center' %}
    <div class="news_index news_index_{{ type }}">
        {% if position == "footer" or position == "header" %}<div class="container">{% endif %}
            <div class="title_bar txt_web_color">
                <h2>{{ title }}</h2>
            </div>
        {% if position == "footer" or position == "header" %}</div>{% endif %}
        {% if position == "footer" or position == "header" %}
            {% set news_hot_class = "container" %}
            {% set news_hot_col_class = " col-md-6" %}
        {% else %}
            {% set news_hot_class = "container-center" %}
            {% set news_hot_col_class = "" %}
        {% endif %}
        {% if newsHot is not null %}
        <div class="{{ news_hot_class }}">
            <div class="row">
            {% for i in newsHot %}
                {% set id = i.id %}
                {% set name = i.name %}
                {% set name_show = tag.cut_string(i.name, 70) %}
                {% if languageCode == 'vi' %}
                    {% set link = tag.site_url(i.slug) %}
                {% else %}
                    {% set link = tag.site_url(languageCode ~ '/' ~ i.slug) %}
                {% endif %}
                {% set summary = tag.cut_string(i.summary, 100) %}
                {% set photo = news_helper.getNewsPhoto(i.photo, subdomain.folder, i.folder) %}
                {% set day = date("d", strtotime(i.created_at)) %}
                {% set month = date("m/Y", strtotime(i.created_at)) %}
                <div class="col-sm-12 col-xs-12{{ news_hot_col_class }} col-module-news-hot">
                    <div class="media media_news">
                        <div class="media-left">
                            <a href="{{ link }}">
                                <div class="box_media_img">
                                    {% if cf['_cf_radio_enable_lazyload_image'] %}
                                    <img class="lazy media-object" data-src="{{ photo }}" data-alt="{{ name }}">
                                    {% else %}
                                    <img class="media-object" src="{{ photo }}" alt="{{ name }}">
                                    {% endif %}
                                </div>
                            </a>
                        </div>
                        <div class="media-body">
                            <div class="media-calendar pull-left">
                                <div class="media-calendar-box">
                                    <div class="media-calendar-date txt_web_color text-center">{{ day }}</div>
                                    <div class="media-calendar-month txt_web_color text-center">{{ month }}</div>
                                </div>
                            </div>
                            <div class="media-content pull-left">
                                <p class="media-heading"><a href="{{ link }}">{{ name }}</a></p>
                                <p class="media-summary">{{ summary }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
            </div>
        </div>
        {% endif %}

        {#<div class="text-center">
            <a href="#" class="btn btn-default text-uppercase btn_view_all_news">Xem thêm</a>
        </div>#}
    </div>
{% else %}
    {% if position == "right" %}
        {% set box_class = "box_right_element" %}
    {% elseif position == "left" %}
        {% set box_class = "box_left_element" %}
    {% else %}
        {% set box_class = "box_center_element" %}
    {% endif %}
    <div class="{{ box_class }}">
        <div class="title_bar_right bar_web_bgr text-uppercase">{{ title }}</div>
        <ul class="box_news_tab ">
            {% for i in newsHot %}
                {% set id = i.id %}
                {% set name = i.name %}
                {% set name_show = tag.cut_string(i.name, 70) %}
                {% if languageCode == 'vi' %}
                    {% set link = tag.site_url(i.slug) %}
                {% else %}
                    {% set link = tag.site_url(languageCode ~ '/' ~ i.slug) %}
                {% endif %}
                {% set summary = tag.cut_string(i.summary, 100) %}
                {% if i.photo != '' %}
                    {% if file_exists("files/news/" ~ subdomain.folder ~ "/thumb/320x320/" ~ i.folder ~ "/" ~ i.photo) %}
                        {% set photo = "/files/news/" ~ subdomain.folder ~ "/thumb/320x320/" ~ i.folder ~ "/" ~ i.photo %}
                    {% elseif file_exists("files/news/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo) %}
                        {% set photo = "/files/news/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo %}
                    {% else %}
                        {% set photo = "/assets/images/no-image.png" %}
                    {% endif %}
                {% else %}
                    {% set photo = "/assets/images/no-image.png" %}
                {% endif %}
                {% set day = date("d", strtotime(i.created_at)) %}
                {% set month = date("m/Y", strtotime(i.created_at)) %}
            <li class="clearfix">
                <a href="{{ link }}">
                    {% if cf['_cf_radio_enable_lazyload_image'] %}
                    <img class="lazy" data-src="{{ photo }}">
                    {% else %}
                    <img src="{{ photo }}" alt="{{ name }}">
                    {% endif %}
                </a>
                <h4><a href="{{ link }}">{{ name }}</a></h4>
                <span>{{ mainGlobal.sw_human_time_diff(strtotime(i.created_at)) }}</span>
            </li>
            {% endfor %}
        </ul>
    </div>
{% endif %}
