{% if layout == 2 %}
    {% set class = "col-md-4" %}
{% elseif layout == 1 %}
    {% set class = "col-md-3" %}
{% else %}
    {% set class = "col-md-4" %}
{% endif %}
<div class="content_main">
    {{ partial("partials/breadcrumb") }}
    <div class="header"><h1 class="title">{{ title_bar }}</h1></div>
        <div class="box_news_grid_page">
            {{ partial("partials/news_menu_child", ["parent_id":category.id]) }}
            {% if cf['_cf_radio_show_header_news_menu'] == true or category.content != "" or cf['_cf_radio_show_footer_news_menu'] == true or category.reg_form == 'Y' or category.messenger_form == 'Y' %}
            <div class="post_static">
                {% if cf['_cf_radio_show_header_news_menu'] == true %}
                {{ partial('partials/mic_support_page', {'form_name':'frm_mic_support_header'}) }}
                {% endif %}
                {% if category.content != "" %}
                    {{ htmlDisplayShortCode(htmlspecialchars_decode(category.content)) }}
                {% endif %}
                {% if cf['_cf_radio_show_footer_news_menu'] == true %}
                {{ partial('partials/mic_support_page', {'form_name':'frm_mic_support_footer'}) }}
                {% endif %}
                {% if category.reg_form == 'Y' %}
                    {{ partial("partials/form_item_module", ["layout":layout, "position":"center"]) }}
                {% endif %}
                {% if category.messenger_form == 'Y' %}
                    {{ partial("partials/customer_message_module", ["layout":layout, "position":"center"]) }}
                {% endif %}
            </div>
             {% endif %}
            {% if page.items|length > 0 %}
            <div class="row">
                {% for i in page.items %}
                    {% set id = i.id %}
                    {% set name = i.name %}
                    {% set name_show = tag.cut_string(i.name, 70) %}
                    {% set summary = tag.cut_string(i.summary, 120) %}
                    {% if languageCode == 'vi' %}
                        {% set link = tag.site_url(i.slug) %}
                    {% else %}
                        {% set link = tag.site_url(languageCode ~ '/' ~ i.slug) %}
                    {% endif %}
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
            {% endif %}
        </div>
        {% if page.items|length > 0 %}
            <div class="text-center box_pagination">{{ partial('partials/pagination') }}</div>
        {% endif %}
        {% if tmpTypeModules|length > 0 %}
        <div class="box_product_modules">
            {% for i in tmpTypeModules %}
                {% set id = i.id %}
                {% set html_id = i.type %}
                {% set module_id = i.module_id %}
                {{ partial("partials/home", ["layout":setting.layout_id, "id":id, "html_id":html_id, "module_id":module_id]) }}
            {% endfor %}
        </div>
        {% endif %}
        {% if cf['_turn_off_comment_facebook'] == true %}
        <div class="hidden-xs fb-comment-area">
            <div class="fb-comments" data-href="{{ mainGlobal.getCurrentUrl() }}" data-numposts="5"></div>
        </div>
        {% endif %}
</div>
