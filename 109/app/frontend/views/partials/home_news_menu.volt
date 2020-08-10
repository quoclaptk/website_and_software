{% set categories = news_service.getNewsMenuShowHome(cf['_number_news_menu_home']) %}
{% if categories|length > 0 %}
	{% if layout == 2 %}
        {% set class = "col-md-4" %}
    {% elseif layout == 1 %}
        {% set class = "col-md-3" %}
    {% else %}
        {% set class = "col-md-4" %}
    {% endif %}
    {% if demo_router is defined %}
        {% set router = demo_router %}
    {% else %}
        {% set router = '' %}
    {% endif %}
    <div class="box_news_hot_index">
        {% if position == "header" or position == "footer" %}<div class="container">{% endif %}
        {% for i in categories %}
        	{% set title = i['name'] %}
        	{% if languageCode == 'vi' %}
                {% set cate_link = tag.site_url(i['slug']) %}
            {% else %}
                {% set cate_link = tag.site_url(languageCode ~ '/' ~ i['slug']) %}
            {% endif %}
            {% if i['news'] is defined and i['news']|length > 0 %}
	            {% set news = i['news'] %}
		        <div class="box_product_home_categtory">
			        <div class="title_bar_center bar_web_bgr text-uppercase"><h2>{{ link_to(cate_link, title) }}</h2></div>
			        <div class="box_news_grid_index">
			           <div class="row">
                            {% for j in news %}
                                {% set id = j['id'] %}
                                {% set name = j['name'] %}
                                {% set name_show = tag.cut_string(j['name'], 70) %}
                                {% set summary = tag.cut_string(j['summary'], 120) %}
                                {% if languageCode == 'vi' %}
                                    {% set link = tag.site_url(j['slug']) %}
                                {% else %}
                                    {% set link = tag.site_url(languageCode ~ '/' ~ j['slug']) %}
                                {% endif %}
                                {% set photo = news_helper.getNewsPhoto(j['photo'], subdomain.folder, j['folder']) %}
                                {% set created_at = date("d/m/Y", strtotime(j['created_at'])) %}
                                <div class="{{ class }} col-sm-4 col-xs-6 col-ss-12">
                                    <div class="news_grid">
                                        <a href="{{ link }}" class="news_grid_photo">
                                            <div class="box_img_news">
                                                <img src="{{ photo }}" alt="{{ name }}" class="img-responsive">
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
			        </div>
                    <div class="home_news_menu_view_more clearfix">
                        <a href="{{ cate_link }}" class="pull-right btn btn-primary bar_web_bgr btn-view-more-newsmenu">{{ word['_xem_them'] }} <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                    </div>
				</div>
			{% endif %}
		{% endfor %}
        {% if position == "header" and position == "footer" %}</div>{% endif %}
    </div>
{% endif %}