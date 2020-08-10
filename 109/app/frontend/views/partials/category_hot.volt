{% set categories = category_service.getCategoryType('hot') %}
{% if categories|length > 0 %}
<div class="module_category_hot">
	{% if position == 'header' or position == 'footer' %}
		<div class="container">
	{% endif %}
        <div class="title_bar_center bar_web_bgr text-uppercase"><h2>{{ word['_danh_muc_san_pham_noi_bat'] }}</h2></div>
		<div class="box_module_category_hot row clearfix">
			{% for i in categories %}
				{% set id = i.id %}
                {% set name = i.name %}
                {% set name_show = tag.cut_string(i.name, 70) %}
                {% if languageCode == 'vi' %}
                    {% set link = tag.site_url(i.slug) %}
                {% else %}
                    {% set link = tag.site_url(languageCode ~ '/' ~ i.slug) %}
                {% endif %}
                {% if i.banner != '' %}
                    {% if file_exists("files/category/" ~ subdomain.folder ~ "/" ~ i.banner) %}
                        {% set photo = "/files/category/" ~ subdomain.folder ~ "/" ~ i.banner %}
                    {% else %}
                        {% set photo = "/assets/images/no-image.png" %}
                    {% endif %}
                {% else %}
                    {% set photo = "/assets/images/no-image.png" %}
                {% endif %}
				<div class="col-md-3 col-sm-6 col-xs-6 col-ss-12 col-category-hot">
					<div class="category_hot_item">
						<div class="category_hot_item_image">
                            <a href="{{ link }}">
                                <div class="box_category_hot_item_image">
                                    {% if cf['_cf_radio_enable_lazyload_image'] %}
                                    <img class="media-object lazy" data-src="{{ photo }}" data-alt="{{ name }}">
                                    {% else %}
                                    <img class="media-object" src="{{ photo }}" alt="{{ name }}">
                                    {% endif %}
                                    {% if cf['_cf_radio_category_hot_effect'] %}
                                    <div class="box-hover">
                                        <div class="text-center">
                                            <span>+</span><span>{{ cf['_cf_text_number_product_category_effect'] }}</span>
                                            <p>{{ word._('_san_pham') }}</p>
                                        </div>
                                    </div>
                                    {% endif %}
                                </div>
                            </a>
                        </div>
						<div class="category_hot_name"><a href="{{ link }}" title="{{ name }}">{{ name_show }}</a></div>
                        {% if cf['_cf_radio_category_hot_btn_view_more'] == true %}
						<div class="category_hot_view text-center"><a href="{{ link }}" class="btn btn-sm btn-theme-colored">{{ word['_xem_tiep'] }}</a></div>
                        {% endif %}
					</div>
				</div>
			{% endfor %}
		</div>
    {% if position == 'header' or position == 'footer' %}
		</div>
	{% endif %}
</div>
{% endif %}