{% set categories = news_menu_service.recursiveVerticalMenu(0) %}
{% if position == "right" %}
    {% set box_class = "box_right_element" %}
{% else %}
    {% set box_class = "box_left_element" %}
{% endif %}
<div class="{{ box_class }}">
    <div class="title_bar_right bar_web_bgr text-uppercase">{{ word['_danh_muc_tin_tuc'] }}</div>
    <div class="box-content box-category">
        {{ categories  }}
    </div>
</div>