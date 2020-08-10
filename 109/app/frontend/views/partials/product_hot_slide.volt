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
{% if productHot|length > 0 %}
{% if type == 'hot' %}
{% set title = word['_san_pham_noi_bat'] %}
{% elseif type == 'new' %}
{% set title = word['_san_pham_moi'] %}
{% endif %}
<div class="md_product_hot_slide">
{% if position == 'header' or position == 'footer' %}
<div class="container">
	<div class="title_bar txt_web_color"><h2>{{ title }}</h2></div>
{% else %}
	<div class="title_bar_center bar_web_bgr text-uppercase"><h2>{{ title }}</h2></div>
{% endif %}
	<div class="box_product_hot_slide owl-carousel">
	{% for i in productHot %}
		<div class="item">{{ product_helper.productListViewHtmlSlide('col-product-hot-slide', router, i, subdomain, setting, cf, word)}}</div>
    {% endfor %}
	</div>
{% if position == 'header' or position == 'footer' %}</div>{% endif %}
</div>
{% endif %}