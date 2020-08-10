{% for cate in category %}
    {% set name = cate['name'] %}
    {% set cat_link = url('tin-tuc/cat/' ~ cate['slug'] ~ '/' ) %}
    {% set news_cate = cate['news'] %}

<div class="box_category">
    <div class="header">
        <h2 class="title"><a href="{{ cat_link }}" title="{{ name }}">{{ name }}</a></h2>
        <a href="{{ cat_link }}" class="view_all">
            Xem tất cả
        </a>
    </div>
    <div class="content">
        <div class="view-aside-news">
            <div class="row">
                <div class="col-md-7">
                    {% for n in news_cate %}
                        {% if loop.first %}
                            {% set first_link = url('tin-tuc/' ~ n['slug'] ~ '/') %}
                            {% set first_name = n['name'] %}
                            {% set first_sum = n['summary'] %}
                            {% set first_photo = image('files/news/thumb/500x300/' ~ n['folder'] ~ '/' ~ n['photo'], 'alt':''~ first_name ~'') %}
                            <div class="first-news">
                                <a href="{{ first_link }}" title="{{ first_name }}">{{ first_photo }}</a>
                                <h4 class="title"><a href="{{ first_link }}" title="{{ first_name }}">{{ first_name }}</a></h4>
                                <p class="sumary">{{ first_sum }}</p>
                            </div>
                        {% endif %}
                    {% endfor %}

                </div>
                <div class="col-md-5">
                    <div class="list-new">
                        {% for n in news_cate %}
                            {% set link = url('tin-tuc/' ~ n['slug'] ~ '/') %}
                            {% set name =  n['name'] %}
                            {% set sum = n['summary'] %}
                            {% set photo = image('files/news/thumb/114x80/' ~ n['folder'] ~ '/' ~ n['photo'], 'alt':''~ name ~'') %}
                            {% if !loop.first %}
                            <div class="item-n"><a class="img-tb" href="{{ link }}" title="{{ name }}">{{ photo }}</a>
                                <div class="info">
                                    <a href="{{ link }}" class="title" title="{{ name }}">{{ name }}</a>
                                </div>
                            </div>
                            {% endif %}
                        {% endfor %}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
{% endfor %}