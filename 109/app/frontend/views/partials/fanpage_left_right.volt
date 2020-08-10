{% if position == "right" %}
    {% set box_class = "box_right_element" %}
{% else %}
    {% set box_class = "box_left_element" %}
{% endif %}
{% if setting.facebook != "" %}
<div id="{{ html_id ~ "_" ~ id }}" class="{{ box_class }}">
    <div class="title_bar_right bar_web_bgr text-uppercase">Fanpage</div>
    <div class="box_fanpage_lr">
	    <div class="fb-page" data-href="{{ setting.facebook }}" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
	</div>
</div>
{% endif %}