<div id="page">
    {% block header %}
        {% if landingPage.hide_header == 'N' %}
            {{ partial('partials/header') }}
        {% endif %}
    {% endblock %}
    {% block content %}
        <section id="content" class="clearfix">
            <div id="box_content_inner">
                <div class="container">
                    <div class="row">
                        {% if landingPage.hide_left == 'N' %}
                            {{ partial('partials/left', ["layout": setting.layout_id]) }}
                        {% endif %}
                        <div class="{% if landingPage.hide_left == 'Y' and landingPage.hide_right == 'Y' %}col-md-12 {% elseif (landingPage.hide_left == 'Y' and landingPage.hide_right == 'N') or (landingPage.hide_left == 'N' and landingPage.hide_right == 'Y') %}col-md-9 {% else %}col-md-6 {% endif %}col-sm-12 clearfix col-center">
                            {% if tmpLandingModules != '' AND tmpLandingModules is defined %}
                                {% for i in tmpLandingModules %}
                                    {% set id = i.id %}
                                    {% set html_id = i.type %}
                                    {% set module_id = i.module_id %}
                                    {{ partial("partials/home", ["layout":setting.layout_id, "id":id, "html_id":html_id, "module_id":module_id]) }}
                                {% endfor %}
                            {% endif %}
                            {{ content() }}
                        </div>
                        {% if landingPage.hide_right == 'N' %}
                            {{ partial('partials/right', ["layout": setting.layout_id]) }}
                        {% endif %}
                    </div>
                </div>
            </div>
        </section>
    {% endblock %}
    {% block footer %}
        {% if landingPage.hide_footer == 'N' %}
            {{ partial("partials/footer") }}
        {% endif %}
    {% endblock %}
</div>
{{ partial('partials/outsite', ['layout':setting.layout_id]) }}