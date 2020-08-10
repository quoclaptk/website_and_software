{% set newsSliders = news_service.getNewsHot('slider') %}
<div class="box_carousel_slider_news">
	{% if word._('_we_are_here_for_you') is not null or word._('_what_we_offer') is not null %}
	<div class="carousel_slider_news_title text-center">
		{% if word._('_we_are_here_for_you') is not null %}
		<h3>{{ word._('_we_are_here_for_you') }}</h3>
		{% endif %}
		{% if word._('_what_we_offer') is not null %}
		<h2>{{ word._('_what_we_offer') }}</h2>
		{% endif %}
	</div>
	{% endif %}
	{% if newsSliders|length > 0 %}
	<div class="carousel slide" id="carousel-slider-news" data-ride="carousel">
	    <ol class="carousel-indicators">
	    	{% for key,newsSlider in newsSliders %}
	        <li data-target="#carousel-slider-news" data-slide-to="{{ key }}"{% if key == 0 %} class="active"{% endif %}></li>
	        {% endfor %}
	    </ol>
	    <div class="carousel-inner" role="listbox">
	    	{% for key,newsSlider in newsSliders %}
		    	{% set name = newsSlider.name %}
		    	{% set summary = newsSlider.summary %}
		    	{% set slogan = newsSlider.slogan %}
		    	{% if languageCode == 'vi' %}
                    {% set link = tag.site_url(newsSlider.slug) %}
                {% else %}
                    {% set link = tag.site_url(languageCode ~ '/' ~ newsSlider.slug) %}
                {% endif %}
	            {% set photo = news_helper.getNewsPhoto(newsSlider.photo, subdomain.folder, newsSlider.folder) %}
		        <div class="item{% if key == 0 %} active{% endif %}">
		        	<img src="{{ photo }}" alt="{{ name }}">
		            <div class="carousel-caption">
		            	{% if name is not null %}
		                <h3>{{ name }}</h3>
		                {% endif %}
		                {% if slogan is not null %}
		                <h4>{{ slogan }}</h4>
		                {% endif %}
		                {% if summary is not null %}
		                <p>{{ summary }}</p>
		                {% endif %}
		                {% if word._('_get_in_touch') is not null %}
		                <div class="carousel-caption-btn"><span><a href="{{ link }}">{{ word._('_get_in_touch') }}</a></span></div>
		                {% endif %}
		            </div>
		        </div>
	        {% endfor %}
	    </div> <a href="#carousel-slider-news" class="left carousel-control" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a href="#carousel-slider-news" class="right carousel-control" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>
	</div>
	{% endif %}
</div>