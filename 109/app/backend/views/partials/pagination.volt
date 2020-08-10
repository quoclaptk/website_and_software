{% if page.total_pages > 1 %}
    {% set acp_name = ACP_NAME | trim  %}
    {% set controller = this.view.getControllerName() | lower  %}
    {% set action = this.view.getActionName() | lower %}
    {#{% set url = acp_name ~ '/' ~ controller ~ '/' ~ action %}#}
    {% if url_page is defined %}
        {% if qParam is defined and qParam == false %}
        {% set url = url_page %}
        {% else %}
        {% set url = url_page ~ '?' %}
        {% endif %}
    {% elseif type is defined and type != '' %}
        {% set url = acp_name ~ '/' ~ controller ~ '/' ~ type ~ '/' %}
    {% else %}
        {% if action == 'index' %}
            {% set url = acp_name ~ '/' ~ controller ~ '?' %}
        {% else %}
            {% set url = acp_name ~ '/' ~ controller ~ '/' ~ action ~ '?' %}
        {% endif %}
    {% endif %}
    {% set startIndex = 1 %}
    {% if page.total_pages > 10 %}
        {% if page.current > 8 %}
            {% set startIndex = startIndex + page.current - 8 %}
        {% endif %}
        {% if page.total_pages - page.current < 10 %}
            {% set startIndex = page.total_pages - 9 %}
        {% endif %}
    {% endif %}

    <ul class="pagination pagination-sm m-t-none m-b-none">
        {% if page.current > 1 %}
            <li>{{ link_to(url, '<i class="fa fa-angle-double-left"></i>', 'title' : 'Go to page ' ~ page.next) }}</li>
            <li class="prev">{{ link_to(url ~ 'page=' ~ page.before, 'data-page' : page.before, '<i class="fa fa-angle-left"></i>', 'title' : 'Go to page ' ~ page.last) }}</li>
        {% endif %}
        {% for pageIndex in startIndex..page.total_pages %}
            {% if pageIndex is startIndex + 10 %}
                {% break %}
            {% endif %}

            <li {% if pageIndex is page.current %}class="active"{% endif %}>
                {{ link_to(url ~ 'page=' ~ pageIndex, pageIndex, 'data-page' : pageIndex, 'title' : 'Go to page ' ~ pageIndex) }}
            </li>
        {% endfor %}

        {% if page.current < page.total_pages %}
            <li class="next">{{ link_to(url ~ 'page=' ~ page.next, 'data-page' : page.next, '<i class="fa fa-angle-right"></i>', 'title' : 'Go to page ' ~ page.next)}}</li>

            <li>{{ link_to(url ~ 'page=' ~ page.last, 'data-page' : page.last, '<i class="fa fa-angle-double-right"></i>', 'title' : 'Go to page ' ~ page.last) }}</li>
        {% endif %}
    </ul>
{% endif %}