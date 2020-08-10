{% if position == "right" %}
    {% set box_class = "box_right_element" %}
{% else %}
    {% set box_class = "box_left_element" %}
{% endif %}
<div id="{{ html_id ~ "_" ~ id }}" class="{{ box_class }} hidden-sm hidden-xs">
    <div class="title_bar_right bar_web_bgr text-uppercase">{{ word['_thong_tin_doanh_nghiep'] }}</div>
    <div class="department_info">
        <div class="department_name clearfix">
            {% if setting.logo != "" %}
            <a href="/">
                <img src="/files/default/{{ subdomain.folder ~ "/" ~ setting.logo }}" alt="{{ setting.name }}">
            </a>
            {% endif %}
            <span><a href="{{ tag.site_url() }}" target="_blank">{{ setting.name }}</a></span>
        </div>

        <div class="department_address">
            <ul>
                {% if setting.address != "" %}
                <li class="clearfix">
                    <i class="icon-dia-chi pull-left"></i>
                    <span class="pull-left"><a href="https://www.google.com/maps/search/?api=1&query={{ setting.address }}" target="_blank">{{ setting.address }}</a></span>
                </li>
                {% endif %}
                {% if hotline is not null %}
                    <li class="clearfix">
                        <i class="icon-sodienthoai pull-left"></i>
                        <span class="pull-left"><a href="tel:{{ hotline }}">{{ hotline }}</a></span>
                    </li>
                {% endif %}
                {% if setting.email != "" %}
                <li class="clearfix">
                    <i class="icon-mail pull-left"></i>
                    <span class="pull-left"><a href="mailto:{{ setting.email }}">{{ setting.email }}</a></span>
                </li>
                {% endif %}
                {% if setting.tax_code != "" %}
                <li class="clearfix">
                    <i class="fa fa-barcode pull-left"></i>
                    <span class="pull-left">{{ setting.tax_code }}</span>
                </li>
                {% endif %}
                {% if setting.website != "" %}
                <li class="clearfix">
                    <i class="fa fa-globe pull-left" aria-hidden="true"></i>
                    <span class="pull-left"><a href="//{{ setting.website }}" target="_blank">{{ setting.website }}</a></span>
                </li>
                {% endif %}
            </ul>
        </div>
    </div>
</div>