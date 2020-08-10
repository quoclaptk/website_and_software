{% set news = news_service.getNewsHot('introduct', 90) %}
<div class="module_news_introduct">
	{% if position == 'header' or position == 'footer' %}
		<div class="container">
	{% endif %}
        <div class="title_bar_center bar_web_bgr text-uppercase"><h2>{{ word['_gioi_thieu_chung'] }}</h2></div>
		<div class="box_module_news_introduct row clearfix">
			{% for i in news %}
				{% set id = i.id %}
                {% set name = i.name %}
                {% set name_show = tag.cut_string(i.name, 70) %}
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
                {% set summary = tag.cut_string(i.summary, 200) %}
                {% if i.photo != '' %}
                    {% if file_exists("files/news/" ~ subdomain.folder ~ "/thumb/320x320/" ~ i.folder ~ "/" ~ i.photo) %}
                        {% set photo = "/files/news/" ~ subdomain.folder ~ "/thumb/320x320/" ~ i.folder ~ "/" ~ i.photo %}
                    {% elseif file_exists("files/news/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo) %}
                        {% set photo = "/files/news/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo %}
                    {% else %}
                        {% set photo = "/assets/images/no-image.png" %}
                    {% endif %}
                {% else %}
                    {% set photo = "/assets/images/no-image.png" %}
                {% endif %}
                {% set day = date("d", strtotime(i.created_at)) %}
                {% set month = date("m/Y", strtotime(i.created_at)) %}
				<div class="col-md-4 col-sm-6 col-news-introduct">
					<div class="news_introduct_item">
						<div class="news_introduct_item_image">
                            <a href="{{ link }}">
                                <div class="box_introduct_item_image">
                                    {% if cf['_cf_radio_enable_lazyload_image'] %}
                                    <img class="media-object lazy" data-src="{{ photo }}" data-alt="{{ name }}">
                                    {% else %}
                                    <img class="media-object" src="{{ photo }}" alt="{{ name }}">
                                    {% endif %}
                                </div>
                            </a>
                        </div>
						<div class="news_introduct_name"><a href="{{ link }}" title="{{ name }}">{{ name_show }}</a></div>
                        <div class="news_introduct_description">{{ summary }}</div>
						<div class="news_introduct_view text-center"><a href="{{ link }}" class="btn btn-sm btn-theme-colored">{{ word['_xem_tiep'] }}</a></div>
					</div>
				</div>
			{% endfor %}
		</div>
	{% if position == 'header' or position == 'footer' %}
		</div>
	{% endif %}
</div>