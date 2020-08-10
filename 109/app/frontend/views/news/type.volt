{% if layout == 2 %}
    {% set class = "col-md-4" %}
{% elseif layout == 1 %}
    {% set class = "col-md-3" %}
{% else %}
    {% set class = "col-md-4" %}
{% endif %}

<div class="{% if layout == 1 %}content_main{% endif %} box_page">
    {{ partial("partials/breadcrumb") }}
    <div class="box_category_inner">
        <div class="title_bar_center bar_web_bgr text-uppercase"><h1>{{ title_bar }}</h1></div>
    </div>
    <div class="box_news_grid_page">
        {% if type.content != "" %}
        <div class="post_static">
            {{ type.content }}
            {% if cf['_turn_off_comment_facebook'] == true %}
            <div class="hidden-xs fb-comment-area">
                <div class="fb-comments" data-href="{{ mainGlobal.getCurrentUrl() }}" data-numposts="5"></div>
            </div>
            {% endif %}
        </div>
        {% endif %}
        {% if news|length > 0 %}
        <div class="row">
            {% for i in page.items %}
                {% set id = i.id %}
                {% set name = i.name %}
                {% set name_show = tag.cut_string(i.name, 70) %}
                {% set summary = tag.cut_string(i.summary, 120) %}
                {% set link = tag.site_url(i.slug) %}
                {% set photo = news_helper.getNewsPhoto(i.photo, subdomain.folder, i.folder) %}
                {% set created_at = date("d/m/Y", strtotime(i.created_at)) %}
            <div class="{{ class }} col-sm-4 col-xs-6 col-ss-12 col-news">
                <div class="news_grid">
                    <a href="{{ link }}" class="news_grid_photo">
                        <div class="box_img_news">
                            {% if cf['_cf_radio_enable_lazyload_image'] %}
                            <img data-src="{{ photo }}" data-alt="{{ name }}" class="lazy">
                            {% else %}
                            <img src="{{ photo }}" alt="{{ name }}" class="img-responsive">
                            {% endif %}
                        </div>
                    </a>
                    <h4><a href="{{ link }}" title="{{ name }}">{{ name_show }}</a></h4>
                    <p>{{ summary }}</p>
                    <div class="clearfix news_grid_date_view">
                        <i class="pull-left">({{ created_at }})</i>
                        <a href="{{ link }}" class="pull-right"><img src="/assets/images/arrow_news.png" alt=""></a>
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