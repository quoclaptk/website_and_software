{% set newsMenus = news_menu_service.getTypeData('policy') %}
{% if newsMenus|length > 0 %}
<div class="box_policy_service">
{% if position == 'header' or position == 'footer' %}<div class="container">{% endif %}
	<div class="row">
	{% for newsMenu in newsMenus %}
	<div class="col-md-3 col-policy-serive">
		<div class="md_policy_service clearfix">
			<div class="pull-left text-center md_policy_service_icon bar_web_bgr">
				{% if newsMenu.icon_type == 1 and newsMenu.font_class != '' %}
				<i class="fa fa-{{ newsMenu.font_class }}"></i>
				{% endif %}
				{% if newsMenu.icon_type == 2 and newsMenu.icon != '' and file_exists('files/icon/' ~  subdomain.folder ~ '/' ~ newsMenu.icon) %}{{ image('files/icon/' ~  subdomain.folder ~ '/' ~ newsMenu.icon, 'class':'img-policy-service-icon') }}{% endif %}
			</div>
			<div class="pull-left md_policy_service_name txt_web_color">
				<div class="md_policy_service_name_box"><div>{% if newsMenu.summary != '' %}<div class="policy_service_name">{{ newsMenu.name }}</div><span class="policy_service_summary">{{ newsMenu.summary }}{% else %}<span class="policy_service_name">{{ newsMenu.name }}</span>{% endif %}</div></div>
			</div>
		</div>
	</div>
	{% endfor %}
	</div>
{% if position == 'header' or position == 'footer' %}</div>{% endif %}
</div>
{% endif %}