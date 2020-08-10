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
            <h1>{{ title_bar }}</h1>
        </div>
        <div class="box_news_grid_page">
        {% if(clip|length > 0) %}
        <div class="row">
        {% for i in page.items %}
            {% set id = i.id %}
            {% set name = i.name %}
            {% if languageCode == 'vi' %}
                {% set link = tag.site_url(i.slug) %}
            {% else %}
                {% set link = tag.site_url(languageCode ~ '/' ~ i.slug) %}
            {% endif %}
            {% set photo = "/files/news/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo %}
            {% set photo = image("files/youtube/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo, 'alt':''~ name ~'') %}
            <div class="col-xs-12 col-sm-4 {{ class }}">
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

            <div class="text-center box_pagination">
                {{ partial('partials/pagination') }}
            </div>
        {% endif %}
        </div>
    </div>
</div>
