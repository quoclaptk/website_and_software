{% if layout == 2 %}
    {% set product_class = "col-md-4" %}
{% elseif layout == 1 %}
    {% set product_class = "col-md-3" %}
{% else %}
    {% set product_class = "col-md-4" %}
{% endif %}

{% if demo_router is defined %}
    {% set router = demo_router %}
{% else %}
    {% set router = '' %}
{% endif %}
<div class="{% if layout == 1 %}box_category_inner{% endif %} box_page">
    {% if cf['_cf_radio_enable_list_category_in_product_page'] is not empty and cf['_cf_radio_enable_list_category_in_product_page'] == true %}
    {{ partial("partials/breadcrumb") }}
    {% if this.view.getActionName() != 'search' and this.view.getActionName() != 'searchAdvanced' %}
    {{ partial("partials/product_category_child", ["layout":layout, "parent_id":0]) }}
    {% endif %}
    {% endif  %}
    <div class="box_category_inner">
        <div class="title_cate clearfix">
            <div class="title_bar_center bar_web_bgr text-uppercase"><h1>{{ title_bar }}</h1></div>
            {#{{ partial("partials/product_sort") }}#}
        </div>
    </div>
    
    <div class="box_list_product">
        {% if page.items|length > 0 %}
        <div class="row">
            {% for i in page.items %}
                {{ product_helper.productListViewHtml(product_class, router, i, subdomain, setting, cf, word)}}
            {% endfor %}
        </div>
        {% elseif this.view.getActionName() == 'search' or this.view.getActionName() == 'searchAdvanced'  %}
        <div class="alert alert-info">{{ word._('_khong_co_ket_qua_nao') }}</div>
        {% endif %}
    </div>

    <div class="text-center box_pagination">
        {{ partial('partials/pagination') }}
    </div>

</div>
