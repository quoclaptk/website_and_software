{% set categories = category_service.recursiveVerticalMenu(0) %}
{% if position == "right" %}
    {% set box_class = "box_right_element" %}
{% else %}
    {% set box_class = "box_left_element" %}
{% endif %}
<div class="{{ box_class }}">
    <div class="title_bar_right bar_web_bgr text-uppercase">{{ word['_danh_muc_san_pham'] }}</div>
    <div class="box-content box-category{% if cf['_cf_select_display_menu_category_left'] == 2 %} box-category-hover{% endif %}">
        {{ categories  }}
    </div>
</div>