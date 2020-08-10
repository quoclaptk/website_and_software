{% set tmpLayoutModuleRights = module_item_service.getTmpLayoutModulePosition(setting.layout_id, 'right') %}
{% if tmpLayoutModuleRights is not null %}
    {% if cf['_turn_off_column_left_right_mobile'] == false %}
        {% set hide_class = ' hidden-xs hidden-sm' %}
    {% else %}
        {% set hide_class = '' %}
    {% endif %}
    {% if setting.layout_id == 2 %}
        {% set right_class = "col-md-3 clearfix" ~ hide_class ~""  %}
    {% else %}
        {% set right_class = "col-md-3 clearfix" ~ hide_class ~"" %}
    {% endif %}
    {% if dispatcher.getControllerName() is not "landing_page" %}
        {% if setting.layout_id == 2 %}
            {% if dispatcher.getControllerName() == "index" %}
                {% if layout_config.hide_left == 'N' and layout_config.hide_right == 'N' %}
                    {% set rightId = 'col-right-2' %}
                {% elseif layout_config.hide_left == 'Y' and layout_config.hide_right == 'N' %}
                    {% set rightId = 'col-right-3' %}
                {% endif %}
            {% else %}
                {% if layout_config.show_left_inner == 'Y' and layout_config.show_right_inner == 'Y' %}
                    {% set rightId = 'col-right-2' %}
                {% elseif layout_config.show_left_inner == 'N' and layout_config.show_right_inner == 'Y' %}
                    {% set rightId = 'col-right-3' %}
                {% endif %}
            {% endif %}
        {% else %}
            {% set rightId = ''  %}
        {% endif %}
    {% else %}
        {% if landingPage.hide_left == 'N' and landingPage.hide_right == 'N' %}
            {% set rightId = 'col-right-2' %}
        {% elseif landingPage.hide_left == 'Y' and landingPage.hide_right == 'N' %}
            {% set rightId = 'col-right-3' %}
        {% endif %}
    {% endif %}
    <div class="{{ right_class }}" {% if rightId is defined and rightId != '' %}id="{{ rightId }}"{% endif %}>
    <div id="box_right_element">
    {% for i in tmpLayoutModuleRights %}
        {% set id = i["id"] %}
        {% set html_id = i["type"] %}
        {% set module_id = i["module_id"] %}
        {{ partial("partials/load_module_left_right", ["layout":layout, "id":id, "html_id":html_id, "module_id":module_id, "position":"right"]) }}
    {% endfor %}
    </div>
</div>
{% endif %}