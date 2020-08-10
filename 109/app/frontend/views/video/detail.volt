{% if layout == 2 %}
    {% set class = "col-md-4" %}
{% elseif layout == 1 %}
    {% set class = "col-md-3" %}
{% else %}
    {% set class = "col-md-4" %}
{% endif %}

<div class="box_page">
    {{ partial("partials/breadcrumb") }}
    <div class="node-content">
        <div class="title txt_web_color">
            <h1>{{ detail.name }}</h1>
        </div>
        <p class="time">Đăng lúc {{ created_at }}</p>
        {% if detail.summary != "" %}
            <div class="post_static">{{ detail.summary }}</div>
        {% endif %}
        <iframe width="100%" height="500" src="https://www.youtube.com/embed/{{ detail.code }}" frameborder="0" allowfullscreen></iframe>
        <div class="like_button">
            <div class="fb-like" data-href="{{ router.getRewriteUri() }}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
        </div>
        {% if (other_clip|length > 0) %}
            <div class="block-related-news">
                <div class="header"><h2 class="title">Video khác</h2></div>
                <div class="row">
                    {% for i in other_clip %}
                        {% set id = i.id %}
                        {% set name = i.name %}
                        {% if languageCode == 'vi' %}
                            {% set link = tag.site_url(i.slug) %}
                        {% else %}
                            {% set link = tag.site_url(languageCode ~ '/' ~ i.slug) %}
                        {% endif %}
                        {% set photo = "/files/news/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo %}
                        {% set photo = image("files/youtube/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo, 'alt':''~ name ~'') %}
                        <div class="col-xs-12 col-sm-4 {{ class }} col-video">
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
                    {% endfor %}
                </div>
            </div>
        {% endif %}
    </div>
</div>

