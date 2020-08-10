<div class="box_page">        
    <div class="node-content">
        <div class="title txt_web_color">
            <h1>{{ detail.name }}</h1>
        </div>
        <p class="time">Đăng lúc {{ created_at }}</p>
        <iframe width="100%" height="480" src="https://www.youtube.com/embed/{{ detail.code }}?autoplay=true" frameborder="0" allowfullscreen></iframe>
        <div class="like_button">
            <div class="fb-like" data-href="{{ router.getRewriteUri() }}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
        </div>
        {% if(not(other_clip is empty)) %}
        <div class="block-related-news">
            <div class="header"><h2 class="title">Video khác</h2></div>
                <div class="row">
                {% for key, value in other_clip %}
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
        </div>
        {% endif %}
    </div>
</div>

