<div id="page">
    {% block header %}
        {% if layout_config.hide_header == 'N' %}
            {{ partial('partials/header') }}
        {% endif %}
    {% endblock %}
    {% block content %}
    {% if dispatcher.getControllerName() != "index" %}
        <section id="content_{{ dispatcher.getControllerName() }}_{{ dispatcher.getActionName() }}" class="clearfix">
    {% endif %}
        <section id="content" class="clearfix">
            <div id="box_content_inner">
                <div class="container">
                    {% if dispatcher.getControllerName() == "index" %}
                    <div class="row">
                        <div class="col-md-9 clearfix col-center">
                            {{ partial("partials/home") }}
                        </div>
                        {{ partial("partials/right", ["layout": 4]) }}
                    </div>
                    {% else %}
                        {% if setting.layout_id == 2  %}
                            {% if layout_config.show_left_inner == 'Y' and layout_config.show_right_inner == 'Y' %}
                                <div class="row">
                                    {{ partial('partials/left', ["layout": 2]) }}
                                    <div class="col-md-6 col-sm-12 clearfix col-center">
                                        {{ content() }}
                                    </div>
                                    {{ partial("partials/right", ["layout": 2]) }}
                                </div>
                            {% elseif layout_config.show_left_inner == 'Y' and layout_config.show_right_inner == 'N'  %}
                                <div class="row">
                                    {{ partial('partials/left', ["layout": 2]) }}
                                    <div class="col-md-9 clearfix col-center">{{ content() }}</div>
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
                                <div class="col-md-9 clearfix col-center">
                                    {{ content() }}
                                </div>
                                {{ partial("partials/right", ["layout": 4]) }}
                            </div>
                        {% endif %}
                    {% endif %}
                </div>
            </div>
        </section>
    {% if dispatcher.getControllerName() != "index" %}</section>{% endif %}
    {% endblock %}
    {% block footer %}
        {% if layout_config.hide_footer == 'N' %}
            {{ partial("partials/footer") }}
        {% endif %}
    {% endblock %}
</div>
{{ partial('partials/outsite', ['layout':4]) }}