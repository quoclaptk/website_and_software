{% set newsHots = news_service.getNewsHot('hot_effect', 4) %}
<div class="box_news_hot_effect">
    {% if position == "header" or position == "footer" %}<div class="container">{% endif %}
    	<div class="vc_row wpb_row vc_row-fluid vc_row-no-padding">
    	    <div class="wpb_column vc_column_container vc_col-sm-12">
    	        <div class="vc_column-inner">
    	            <div class="wpb_wrapper">
    	                <div class="vi-category-panel">
                            {% for newsHot in newsHots %}
                                {% set id = newsHot.id %}
                                {% set name = newsHot.name %}
                                {% set name_show = tag.cut_string(newsHot.name, 70) %}
                                {% if languageCode == 'vi' %}
                                    {% set link = tag.site_url(newsHot.slug) %}
                                {% else %}
                                    {% set link = tag.site_url(languageCode ~ '/' ~ newsHot.slug) %}
                                {% endif %}
                                {% set summary = tag.cut_string(newsHot.summary, 200) %}
                                {% set photo = news_helper.getNewsPhoto(newsHot.photo, subdomain.folder, newsHot.folder) %}
        	                    <div class="vi-category-panel-item" onclick="return true">
        	                        <div class="vi-category-panel-bg" style="display:block;background-image: url('{{ photo }}')">
                                        <img src="{{ photo }}" title="{{ name }}" alt="{{ name }}">
                                    </div>
        	                        <h3 class="vi-category-panel-heading">{{ name }}</h3>
        	                        <div class="vi-category-panel-content">
        	                            <h3 class="vi-category-heading">{{ name }}</h3>
        	                            <div class="vi-category-content">{{ summary }}</div>
        	                            <div class="vi-category-btn"><a class="btn-pink" href="{{ link }}" title="{{ word._('_xem_them') }}">{{ word._('_xem_them') }}</a></div>
        	                        </div>
        	                    </div>
                            {% endfor %}
    	                </div>
    	            </div>
    	        </div>
    	    </div>
    	</div>
    {% if position == "header" or position == "footer" %}</div>{% endif %}
</div>