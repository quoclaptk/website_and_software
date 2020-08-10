{% if position == "right" %}
    {% set box_class = "box_right_element" %}
{% elseif position == "left" %}
    {% set box_class = "box_left_element" %}
{% else %}
    {% set box_class = "box_inline_element" %}
{% endif %}
<div id="{{ html_id ~ "_" ~ id }}" class="{{ box_class }}">
    <div class="title_bar_right bar_web_bgr text-uppercase">{{ word['_thong_ke_truy_cap'] }}</div>
    <div class="box_access_online">
        <div class="user_online">
            {{ partial('partials/access_online_block') }}
        </div>
    </div>
</div>