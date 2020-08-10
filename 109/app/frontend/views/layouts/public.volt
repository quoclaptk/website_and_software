{% block header %}
    {{ partial('partials/header') }}
{% endblock %}
{% block menu %}
    {{ partial('partials/menu') }}
{% endblock %}

<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8" id="wrap-content">
                {{ content() }}
            </div>
            <div class="col-md-4 hidden-xs hidden-sm" id="wrap-right">
                {{ partial('partials/right') }}
            </div>
        </div>
    </div>
</div>


{% block footer %}
    {{ partial('partials/footer') }}
{% endblock %}