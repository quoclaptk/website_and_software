{% set layout = setting.layout_id %}

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
<div class="box_page">
    {% if cf['_cf_radio_enable_list_category_in_product_page'] is not empty and cf['_cf_radio_enable_list_category_in_product_page'] == true %}
    {{ partial("partials/breadcrumb") }}
    {{ partial("partials/product_category_child", ["layout":layout, "parent_id":category.id]) }}
    {% endif %}
    <div class="box_category_inner">
        <div class="title_cate clearfix">
            {# <div class="pull-left text-uppercase title border_none"><h1>{{ title_bar }}</h1></div> #}
            <div class="title_bar_center text-uppercase"><h1>{{ title_bar }}</h1></div>
            {#{{ partial("partials/product_sort") }}#}
        </div>
        {% if category is defined and category.content != '' %}
        <div class="box_category_summary">{{ htmlDisplayShortCode(htmlspecialchars_decode(category.content)) }}</div>
        {% endif %}
    </div>
    <div class="box_list_product">
        <div class="row">
            {% for i in page.items %}
                {{ product_helper.productListViewHtml(product_class, router, i, subdomain, setting, cf, word)}}
            {% endfor %}
        </div>
        <div class="text-center box_pagination">
            {{ partial('partials/pagination') }}
        </div>
    </div>
    {% if tmpTypeModules|length > 0 %}
    <div class="box_product_modules">
        {% for i in tmpTypeModules %}
            {% set id = i.id %}
            {% set html_id = i.type %}
            {% set module_id = i.module_id %}
            {{ partial("partials/home", ["layout":setting.layout_id, "id":id, "html_id":html_id, "module_id":module_id]) }}
        {% endfor %}
    </div>
    {% endif %}
</div>