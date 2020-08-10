{% if type == 'hot' %}
{% set limit_item = cf['_number_product_hot'] %}
{% elseif type == 'new' %}
{% set limit_item = cf['_number_product_new'] %}
{% endif %}
{% if demo_router is defined %}
{% set router = demo_router %}
{% else %}
{% set router = '' %}
{% endif %}
{% set productHot = product_service.getProductHot(type, limit_item) %}

{% if type == 'hot' %}
{% set title = word['_san_pham_noi_bat'] %}
{% elseif type == 'new' %}
{% set title = word['_san_pham_moi'] %}
{% endif %}

{% if cf['_cf_select_number_product_hot_in_line'] == 2 %}
    {% if layout == 2 %}
        {% set product_class = "col-lg-6 col-md-4" %}
    {% elseif layout == 1 %}
        {% set product_class = "col-lg-6 col-md-3" %}
    {% else %}
        {% set product_class = "col-lg-6 col-md-4" %}
    {% endif %}
{% endif %}
{% if cf['_cf_select_number_product_hot_in_line'] == 3 %}
    {% if layout == 2 %}
        {% set product_class = "col-lg-4 col-md-4" %}
    {% elseif layout == 1 %}
        {% set product_class = "col-lg-4 col-md-3" %}
    {% else %}
        {% set product_class = "col-lg-4 col-md-4" %}
    {% endif %}
{% endif %}
{% if cf['_cf_select_number_product_hot_in_line'] == 4 %}
    {% if layout == 2 %}
        {% set product_class = "col-lg-3 col-md-4" %}
    {% elseif layout == 1 %}
        {% set product_class = "col-lg-3 col-md-3" %}
    {% else %}
        {% set product_class = "col-lg-3 col-md-4" %}
    {% endif %}
{% endif %}
{% if cf['_cf_select_number_product_hot_in_line'] == 5 %}
    {% if layout == 2 %}
        {% set product_class = "col-lg-25 col-md-4" %}
    {% elseif layout == 1 %}
        {% set product_class = "col-lg-25 col-md-3" %}
    {% else %}
        {% set product_class = "col-lg-25 col-md-4" %}
    {% endif %}
{% endif %}
{% if position == 'header' or position == 'footer' %}
{% set product_class = "col-md-3" %}
{% endif %}
<div id="{{ html_id ~ "_" ~ id }}" class="box_product_hot_index{% if layout == 1 %} content_main{% endif %}">
    {% if position == 'header' or position == 'footer' %}
    <div class="container">
    	<div class="title_bar txt_web_color"><h2>{{ title }}</h2></div>
	{% else %}
		<div class="title_bar_center bar_web_bgr text-uppercase"><h2>{{ title }}</h2></div>
    {% endif %}
    <div class="box_list_product">
        <div class="row">
        {% for i in productHot %}
            {{ product_helper.productListViewHtml(product_class, router, i, subdomain, setting, cf, word)}}
        {% endfor %}
        </div>
    </div>
{% if position == 'header' or position == 'footer' %}</div>{% endif %}
</div>