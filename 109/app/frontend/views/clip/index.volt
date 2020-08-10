<div class="box_page">
    <div class="header"><h1 class="title">{{ title_bar }}</h1></div>
    <div class="row">
        {% for key, value in page.items %}
            {% set item_class = '' %}
            {% if (key+1) % 3 == 0 %}
                {% set item_class = '<div class="last"></div>' %}
            {% endif %}
            {% set link = url('clip-bong-da/' ~ value['slug'] ~ '/' ) %}
            {% set name =  value['name'] %}
            {% set photo = image('files/youtube/thumb/320x240/' ~ value['folder'] ~ '/' ~ value['photo'], 'alt':''~ name ~'') %}
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="item_list_video">
                    <div class="item_video">
                        <a href="{{ link }}">
                            {{ photo }}
                            <span class="play"></span>
                        </a>
                    </div>
                    <div class="info_clip">
                        <a href="{{ link }}" title="{{ name }}" class="video_name">{{ name }}</a>
                    </div>
                </div>
            </div>
            {{ item_class }}
        {% endfor %}
    </div>
    {{ partial('partials/pagination') }}
</div>
