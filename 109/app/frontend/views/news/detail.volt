<div class="{% if layout == 1 %}content_main{% endif %} box_page">
    {{ partial("partials/breadcrumb") }}
    <div class="node-content">
        <div class="title txt_web_color">
            <h1>{{ detail.name }}</h1>
        </div>
        <p class="time">{{ word._('_dang_luc') }} {{ created_at }}</p>
        <div class="like_button hidden-xs">
            <div class="fb-like" data-href="{{ mainGlobal.getCurrentUrl() }}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
        </div>
        <div class="summary_detail">{{ detail.summary }}</div>
        <div class="article-content">{{ htmlDisplayShortCode(htmlspecialchars_decode(detail.content)) }}</div>
        {% if cf['_cf_radio_frm_ycbg_news'] == true %}
        {{ partial("partials/form_item_module", ["layout":layout, "position":"center"]) }}
        {% endif %}
        {% if cf['_turn_off_comment_facebook'] == true %}
        <div class="hidden-xs fb-comment-area">
            <div class="fb-comments" data-href="{{ mainGlobal.getCurrentUrl() }}" data-numposts="5"></div>
        </div>
        {% endif %}
        {% if other_news|length > 0 %}
            <div class="block-related-news">
                <div class="header"><h2 class="title">{{ word['_tin_tuc_khac'] }}</h2></div>
                <ul class="list-news">
                    {% for news in other_news %}
                        {% set name = news.name %}
                        {% if languageCode == 'vi' %}
                            {% set link = tag.site_url(news.slug) %}
                        {% else %}
                            {% set link = tag.site_url(languageCode ~ '/' ~ news.slug) %}
                        {% endif %}
                        <li><a href="{{ link }}" title="{{ name }}">{{ name }}</a></li>
                    {% endfor %}
                </ul>
            </div>
        {% endif %}
    </div>
</div>