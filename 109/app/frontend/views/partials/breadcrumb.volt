{% if full is defined and full == true %}
<div id="breadcrumb_full">
{% endif %}
{% if demo_router is defined %}
{% set link = tag.site_url(demo_router) %}
{% else %}
{% set link = tag.site_url() %}
{% endif %}
<ol class="breadcrumb txt_web_color">
    <li><a href="{{ link }}">{{ word['_trang_chu'] }}</a></li>
    {{ breadcrumb }}
</ol>
{% if full is defined and full == true %}
</div>
{% endif %}