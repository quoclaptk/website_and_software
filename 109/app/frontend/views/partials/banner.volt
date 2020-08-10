{% set banner_type = banner_service.getBannerTypeInfo(module_id) %}
{% if banner_type != '' %}
    {% set banner = banner_service.getListBanner(banner_type.id) %}
    {% if banner|length > 0 %}
        {% if banner_type.type == 2 and position != 'left' and position != 'right' %}
        {% if position != 'center' %}<div class="container container-banner-{{ position }}">{% endif %}
            <div id="carousel-slider-{{ id }}" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    {% for key,value in banner %}
                    <li data-target="#carousel-slider-{{ id }}" data-slide-to="{{ key }}"{% if key == 0 %} class="active"{% endif %}></li>
                    {% endfor %}
                </ol>
                <div class="carousel-inner" role="listbox">
                {% for key,value in banner %}
                {% set photo = banner_helper.getBannerPhoto(value.photo, subdomain.folder, {'type':'slider'}) %}
                    <div class="item {% if key == 0 %} active{% endif %}">
                        <a href="{{ value.link }}"><img src="{{ photo }}" alt="{{ value.name }}"></a>
                    </div>
                {% endfor %}
                </div>
                <a class="left carousel-control" href="#carousel-slider-{{ id }}" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-slider-{{ id }}" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        {% if position != 'center' %}</div>{% endif %}

        {% elseif banner_type.type == 3 %}
            {% if position == "footer" %}
                {% set container_class = "container" %}
            {% else %}
                {% set container_class = "container-fluid" %}
            {% endif %}
        <div id="{{ html_id ~ "_" ~ id }}" class="banner_partner">
            <!-- <div class="text-center text-uppercase new_company_txt">Đối tác</div> -->
            <div class="container">
                <div class="title_bar txt_web_color">
                    <h2>{{ word['_doi_tac'] }}</h2>
                </div>
            </div>
            <div class="box_new_company">
                <div class="{{ container_class }}">
                    <div class="box_logo_company box_logo_company_{{ position }} owl-carousel">
                        {% for key,value in banner %}
                            {% set photo = banner_helper.getBannerPhoto(value.photo, subdomain.folder, {'type':'partner'}) %}
                        <div class="item">
                            <a href="{{ value.link }}" target="_blank" class="logo_company">
                                <div class="box_img_partner">
                                    <img src="{{ photo }}" alt="{{ value.name }}">
                                </div>
                            </a>
                        </div>
                        {% endfor %}
                    </div>
                </div>
            </div>
        </div>
        {% else %}
        <div id="{{ html_id ~ "_" ~ id }}" class="banner_static">
            {% for key,value in banner %}
                {% set photo = "/files/ads/" ~ subdomain.folder ~ "/" ~ value.photo %}
            <div class="text-center banner_static_elm">
                <a href="{{ value.link }}" target="_blank">
                    {% if cf['_cf_radio_enable_lazyload_image'] %}
                        <img class="lazy" data-src="{{ photo }}" data-alt="{{ value.name }}">
                    {% else %}
                        <img src="{{ photo }}" alt="{{ value.name }}">
                    {% endif %}
                </a>
            </div>
            {% endfor %}
        </div>
        {% endif %}
    {% endif %}
{% endif %}
