{% set categoryPictures = category_service.getCategoryChild(parent_id, {'picture':true}) %}
{% set categories = category_service.getCategoryChild(parent_id) %}
{% if layout == 2 %}
    {% set class = "col-md-4" %}
{% elseif layout == 1 %}
    {% set class = "col-md-3" %}
{% else %}
    {% set class = "col-md-4" %}
{% endif %}
{% if categories|length > 0 or categoryPictures|length > 0 %}
    <div class="text-center">
        <div class="title_category text-uppercase"><i class="fa fa-dot-circle-o" aria-hidden="true"></i><span>{{ title_bar }}</span></div>
    </div>
    <div class="text-center"><p class="title_category_line"></p></div>
    {% if categoryPictures|length > 0 %}
    <div class="list_category_child_picture">
        <div class="row">
            {% for i in categoryPictures %}
                {% if demo_router is defined %}
                    {% set link = tag.site_url(demo_router ~ '/' ~ i.slug) %}
                {% else %}
                    {% set link = tag.site_url(i.slug) %}
                {% endif %}
                <div class="{{ class }} col-sm-4 col-xs-6 col-ss-12 col-category-child-picture">
                    <div class="category_bar_picture">
                        {% if i.banner != '' and file_exists("files/category/" ~ subdomain.folder ~ "/" ~ i.banner) %}
                        <a href="{{ link }}" title="{{ i.name }}">
                            <div class="box_category_bar_picture_image">
                                {{ image("files/category/" ~ subdomain.folder ~ "/" ~ i.banner, 'alt':i.name) }}
                            </div>
                        </a>
                        {% endif %}
                        <div class="category_bar">
                            <a href="{{ link }}" title="{{ i.name }}">{{ i.name }}</a>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {% endif %}
    {% if categoryPictures|length == 0 and categories|length > 0 %}
    <div class="list_category_child">
        <div class="row">
            {% for i in categories %}
                {% if demo_router is defined %}
                    {% if languageCode == 'vi' %}
                        {% set link = tag.site_url(demo_router ~ '/' ~ i.slug) %}
                    {% else %}
                        {% set link = tag.site_url(demo_router ~ '/' ~ languageCode ~ '/' ~ i.slug) %}
                    {% endif %}
                {% else %}
                    {% if languageCode == 'vi' %}
                        {% set link = tag.site_url(i.slug) %}
                    {% else %}
                        {% set link = tag.site_url(languageCode ~ '/' ~ i.slug) %}
                    {% endif %}
                {% endif %}
                <div class="{{ class }} col-sm-4 col-xs-6 col-ss-12 col-category-child-txt">
                    <div class="category_bar">
                        <a href="{{ link }}" title="{{ i.name }}">{{ i.name }}</a>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {% endif %}
{% endif %}