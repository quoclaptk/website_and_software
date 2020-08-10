<div class="content_main">
{% for cate in channel_group %}
    {% set name = cate['name'] %}
    {% set tv = cate['tv'] %}
    {% if(not(tv is empty)) %}
    <div class="header">
        <h2 class="title">{{ name }}</h2>
    </div>
    <div class="container-fluid">
        <div class="row">
        {% for key, value in tv %}
            {% set link = url('live-tv/' ~ value['slug'] ~ '/' ) %}
            {% set name =  value['name'] %}
            {% set photo = image('files/tv/' ~ value['photo'], 'alt':''~ name ~'') %}
            <div class="col-xs-6 col-sm-4 col-md-2 list_tv">
                <div class="item_list_tv">
                    <div class="item_tv">
                        <a href="{{ link }}" title="{{ name }}">
                            {{ photo }}
                        </a>
                    </div>
                </div>
            </div>
        {% endfor %}
        </div>
    </div>
    {% endif %}
{% endfor %}
</div>
