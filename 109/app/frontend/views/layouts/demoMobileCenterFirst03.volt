<div id="page">
    {{ partial('partials/header') }}
    {% if dispatcher.getControllerName() != "index" %}
        <section id="content_{{ dispatcher.getControllerName() }}_{{ dispatcher.getActionName() }}" class="clearfix">
    {% endif %}
        <section id="content" class="clearfix">
            <div id="box_content_inner">
                <div class="container">
                    {% if dispatcher.getControllerName() == "index" %}
                    <div class="row">
                        <div class="col-md-9 col-md-push-3 clearfix col-center">
                            {% if tmpLayoutModule != '' AND tmpLayoutModule["center"] is defined AND tmpLayoutModule["center"] != "" %}
                                {% for i in tmpLayoutModule["center"] %}
                                    {% set id = i["id"] %}
                                    {% set html_id = i["type"] %}
                                    {% set module_id = i["module_id"] %}

                                    {{ partial("partials/home", ["layout":3, "id":id, "html_id":html_id, "module_id":module_id]) }}
                                {% endfor %}
                            {% endif %}
                        </div>
                        {{ partial('partials/left', ["layout": 3, "leftPull":true]) }}
                    </div>
                    {% else %}
                        {% if setting.layout_id == 2  %}
                            {% if layout_config.show_left_inner == 'Y' and layout_config.show_right_inner == 'Y' %}
                                <div class="row">
                                    {{ partial('partials/left', ["layout": 2]) }}
                                    <div class="col-md-6 col-sm-12 pull-left clearfix col-center">
                                        {{ content() }}
                                    </div>
                                    {{ partial("partials/right", ["layout": 2]) }}
                                </div>
                            {% elseif layout_config.show_left_inner == 'Y' and layout_config.show_right_inner == 'N'  %}
                                <div class="row">
                                    <div class="col-md-9 col-md-push-3 clearfix col-center">{{ content() }}</div>
                                    {{ partial('partials/left', ["layout": 2, "leftPull":true]) }}
                                </div>
                            {% elseif layout_config.show_left_inner == 'N' and layout_config.show_right_inner == 'Y' %}
                                <div class="row">
                                    <div class="col-md-9 clearfix col-center">{{ content() }}</div>
                                    {{ partial("partials/right", ["layout": 2]) }}
                                </div>
                            {% else %}
                                {{ content() }}
                            {% endif %}
                        {% else %}
                            <div class="row">
                                <div class="col-md-9 col-md-push-3 clearfix col-center">
                                    {{ content() }}
                                </div>
                                {{ partial('partials/left', ["layout": 3, "leftPull":true]) }}
                            </div>
                        {% endif %}
                    {% endif %}
                </div>
            </div>
        </section>
    {% if dispatcher.getControllerName() != "index" %}</section>{% endif %}
    {{ partial('partials/footer') }}
    
</div>
{{ partial('partials/outsite', ['layout':3]) }}