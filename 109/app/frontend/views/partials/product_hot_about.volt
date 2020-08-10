{% set type = 'hot' %}
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
<div class="md_product_hot_about">
{% if position == 'header' or position == 'footer' %}
<div class="container">
{% endif %}
	<div class="box_product_hot_about list-container">
		<div class="row">
			{% for key,product in productHot %}
				{{ product_helper.productHotAboutHtml(product, subdomain, setting, cf, word, key)}}
			{% endfor %}
		</div>
	</div>
{% if position == 'header' or position == 'footer' %}</div>{% endif %}
</div>
{% endif %}