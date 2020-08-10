{% set categories = category_service.getCategoryType('hot') %}
{% if categories|length > 0 %}
<div class="module_category_group_sole">
	{% if position == 'header' or position == 'footer' %}
	<div class="container">
	{% endif %}
	<div class="box_module_category_group_sole">
		<div class="boxgroupHome">
	    <ul class="listBoxContent">
	    	{% for category in categories  %}
	    		{% set id = category.id %}
                {% set name = category.name %}
                {% set content = category.content %}
                {% if languageCode == 'vi' %}
                    {% set link = tag.site_url(category.slug) %}
                {% else %}
                    {% set link = tag.site_url(languageCode ~ '/' ~ category.slug) %}
                {% endif %}
                {% if category.banner_md_sole != '' %}
                    {% if file_exists("files/category/" ~ subdomain.folder ~ "/" ~ category.banner_md_sole) %}
                        {% set photo = "/files/category/" ~ subdomain.folder ~ "/" ~ category.banner_md_sole %}
                    {% else %}
                        {% set photo = "/assets/images/no-image.png" %}
                    {% endif %}
                {% else %}
                    {% set photo = "/assets/images/no-image.png" %}
                {% endif %}
	        <li>
	            <div class="bxflex flexBet">
	                <div class="bx_topy bxTopy1">
	                    <div class="textContent topic">
	                        <h2>{{ name }}</h2>
	                        {% if content is not null %}
	                        <p>{{ content }}</p>
	                        {% endif %}
	                        <a class="hxn" href="{{ link }}">{{ word._('_xem_ngay') }}</a>
	                    </div>
	                </div>
	                <div class="bx_topy bxTopy2">
	                    {% if cf['_cf_radio_enable_lazyload_image'] %}
                        <img class="imgContentTp lazy" data-src="{{ photo }}" data-alt="{{ name }}">
                        {% else %}
                        <img class="mimgContentTp" src="{{ photo }}" alt="{{ name }}">
                        {% endif %}
	                </div>
	            </div>
	        </li>
	        {% endfor %}
	    </ul>
	</div>
	</div>
    {% if position == 'header' or position == 'footer' %}
	</div>
	{% endif %}
</div>
{% endif %}