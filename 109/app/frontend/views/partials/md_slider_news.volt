{% set sliders = banner_service.getBannerInType(2) %}
{% set newses = news_service.getNewsLastest(5) %}
<div class="box_md_slider_news">
	{% if position != 'center' %}<div class="container">{% endif %}
		<div class="row">
			{% if sliders|length > 0 %}
			<div class="col-md-8 col-md-slider">
				<div class="box_md_slider">
					 <div class="carousel slide" id="carousel-slider-and-news" data-ride="carousel">
	                    <ol class="carousel-indicators">
	                        {% for key,value in sliders %}
	                        <li data-target="#carousel-slider-and-news" data-slide-to="{{ key }}"{% if key == 0 %} class="active"{% endif %}></li>
	                        {% endfor %}
	                    </ol>

	                    <div class="carousel-inner" role="listbox">
	                    {% for key,value in sliders %}
	                        {% set photo = "/files/ads/" ~ subdomain.folder ~ "/" ~ value.photo %}
	                        <div class="item {% if key == 0 %} active{% endif %}">
	                            <a href="{{ value.link }}"><img src="{{ photo }}" alt="{{ value.name }}"></a>
	                        </div>
	                    {% endfor %}
	                    </div>

	                    <a class="left carousel-control" href="#carousel-slider-and-news" role="button" data-slide="prev">
	                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	                        <span class="sr-only">Previous</span>
	                    </a>
	                    <a class="right carousel-control" href="#carousel-slider-and-news" role="button" data-slide="next">
	                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	                        <span class="sr-only">Next</span>
	                    </a>
	                </div>
				</div>
			</div>
			{% endif %}
			{% if newses|length > 0 %}
			<div class="col-md-4 col-md-slider-news">
				<div class="block-article clearfix">
				    <figure>
				        <h2>Tin tức & sự kiện</h2>
				        <a href="/tin-tuc">{{ word._('_xem_them') }} ⇉</a>
				    </figure>
				    <ul>
				    	{% for i in newses %}
			                {% set id = i.id %}
			                {% set name = i.name %}
			                {% set name_show = tag.cut_string(i.name, 70) %}
			                {% if languageCode == 'vi' %}
			                    {% set link = tag.site_url(i.slug) %}
			                {% else %}
			                    {% set link = tag.site_url(languageCode ~ '/' ~ i.slug) %}
			                {% endif %}
			                {% set summary = tag.cut_string(i.summary, 100) %}
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
				        <li>
				            <a href="{{ link }}">
				            	{% if cf['_cf_radio_enable_lazyload_image'] %}
				                <img class="lazy" data-src="{{ photo }}" data-alt="{{ name }}">
				                {% else %}
				                <img src="{{ photo }}" alt="{{ name }}">
				                {% endif %}
				                <h3>{{ name }}</h3>
				            </a>
				        </li>
				        {% endfor %}
				    </ul>
				</div>
			</div>
			{% endif %}
		</div>
	{% if position != 'center' %}</div>{% endif %}
</div>