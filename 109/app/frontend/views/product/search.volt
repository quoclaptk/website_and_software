{% if layout == 2 %}
    {% set product_class = "col-md-4" %}
{% elseif layout == 1 %}
    {% set product_class = "col-md-3" %}
{% else %}
    {% set product_class = "col-md-4" %}
{% endif %}

{% if demo_router is defined %}
    {% set router = demo_router %}
{% else %}
    {% set router = '' %}
{% endif %}
<div class="{% if layout == 1 %}box_category_inner{% endif %} box_page">
    {{ partial("partials/breadcrumb") }}
    <div class="box_category_inner">
        <div class="title_cate clearfix">
            <div class="title_bar_center bar_web_bgr text-uppercase"><h1>{{ title_bar }}</h1></div>
        </div>
    </div>
    <div class="box_list_product">
        {% if page.items|length > 0 %}
        <div class="row">
            {% for i in page.items %}
                {{ product_helper.productListViewHtml(product_class, router, i, subdomain, setting, cf, word)}}
            {% endfor %}
        </div>
        {% endif %}
    </div>
	{% if news is defined and news|length > 0 %}
	<div class="box_search_news">
		<div class="row">
	        {% for i in news %}
	            {% set id = i.id %}
	            {% set name = i.name %}
	            {% set name_show = tag.cut_string(i.name, 70) %}
	            {% set summary = tag.cut_string(i.summary, 120) %}
	            {% if languageCode == 'vi' %}
	                {% set link = tag.site_url(i.slug) %}
	            {% else %}
	                {% set link = tag.site_url(languageCode ~ '/' ~ i.slug) %}
	            {% endif %}
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
	            {% set created_at = date("d/m/Y", strtotime(i.created_at)) %}
		        <div class="{{ product_class }} col-sm-4 col-xs-6 col-ss-12 col-news">
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
	{% endif %}
	{% if page.items|length == 0 and news|length == 0 %}
	<div class="alert alert-info">{{ word._('_khong_co_ket_qua_nao') }}</div>
	{% endif %}
</div>