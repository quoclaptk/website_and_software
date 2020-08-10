{% set tmpLayoutModuleLefts = module_item_service.getTmpLayoutModulePosition(setting.layout_id, 'left') %}
{% if tmpLayoutModuleLefts is not null %}
{% if cf['_turn_off_column_left_right_mobile'] == false %}
    {% set hide_class = ' hidden-xs hidden-sm' %}
{% else %}
    {% set hide_class = '' %}
{% endif %}
{% if setting.layout_id == 2 %}
    {% set leftClass = "col-md-3 clearfix" ~ hide_class ~""  %}
{% else %}
    {% set leftClass = "col-md-3 clearfix" ~ hide_class ~"" %}
{% endif %}
{% if dispatcher.getControllerName() is not "landing_page" %}
    {% if setting.layout_id == 2 %}
        {% if dispatcher.getControllerName() == "index" %}
            {% if layout_config.hide_left == 'N' and layout_config.hide_right == 'N' %}
                {% set leftId = 'col-left-2' %}
            {% elseif layout_config.hide_left == 'N' and layout_config.hide_right == 'Y' %}
                {% set leftId = 'col-left-3' %}
            {% endif %}
        {% else %}
            {% if layout_config.show_left_inner == 'Y' and layout_config.show_right_inner == 'Y' %}
                {% set leftId = 'col-left-2' %}
            {% elseif layout_config.show_left_inner == 'Y' and layout_config.show_right_inner == 'N' %}
                {% set leftId = 'col-left-3' %}
            {% endif %}
        {% endif %}
    {% else %}
        {% set leftId = ''  %}
    {% endif %}
{% else %}
    {% if landingPage.hide_left == 'N' and landingPage.hide_right == 'N' %}
        {% set leftId = 'col-left-2' %}
    {% elseif landingPage.hide_left == 'N' and landingPage.hide_right == 'Y' %}
        {% set leftId = 'col-left-3' %}
    {% endif %}
{% endif %}
<div class="{{ leftClass }}{% if leftPull is defined and leftPull == true %} col-md-pull-9{% endif %}" {% if leftId is defined and leftId != '' %}id="{{ leftId }}"{% endif %}>
    <div id="box_left_element">
        {% for i in tmpLayoutModuleLefts %}
            {% set id = i["id"] %}
            {% set html_id = i["type"] %}
            {% set module_id = i["module_id"] %}
            {{ partial("partials/load_module_left_right", ["layout":layout, "id":id, "html_id":html_id, "module_id":module_id, "position":"left"]) }}
        {% endfor %}
    </div>
</div>
{% endif %}