{% if page.total_pages > 1 %}
    {% set controller = this.view.getControllerName() | lower  %}
    {% set action = this.view.getActionName() | lower %}
    {% set url = url_page %}
    {% set startIndex = 1 %}
    {% if page.total_pages > 5 %}
        {% if page.current > 3 %}
            {% set startIndex = startIndex + page.current - 3 %}
        {% endif %}
        {% if page.total_pages - page.current < 5 %}
            {% set startIndex = page.total_pages - 4 %}
        {% endif %}
    {% endif %}

    <ul class="pagination pagination-sm m-t-none m-b-none pagination-ajax" data-id="{{ html_id }}">
        {% if page.current > 1 %}
            {% if page.before == 1 %}
                {% set page_before = "" %}
            {% else %}
                {% set page_before = page.before %}
            {% endif %}
            <li>
                <a href="javascript:;" data-url="{{ url }}" data-page="{{ page.first }}"><i class="fa fa-angle-double-left"></i></a>
            </li>
            <li class="prev">
                <a href="javascript:;" data-url="{{ url }}" data-page="{{ page.before }}"><i class="fa fa-angle-left"></i></a>
            </li>
        {% endif %}
        {% for pageIndex in startIndex..page.total_pages %}
            {% if pageIndex is startIndex + 5 %}
                {% break %}
            {% endif %}

            {% if pageIndex == 1 %}
                {% set page_index = "" %}
            {% else %}
                {% set page_index = pageIndex %}
            {% endif %}

            <li {% if pageIndex is page.current %}class="active"{% endif %}>
                <a href="javascript:;" data-url="{{ url }}" data-page="{{ pageIndex }}">{{ pageIndex }}</a>
            </li>
        {% endfor %}

        {% if page.current < page.total_pages %}
            <li class="next">
                <a href="javascript:;" data-url="{{ url }}" data-page="{{ page.next }}"><i class="fa fa-angle-right"></i></a>
            </li>
            <li>
                <a href="javascript:;" data-url="{{ url }}" data-page="{{ page.last }}"><i class="fa fa-angle-double-right"></i></a>
            </li>
        {% endif %}
    </ul>
{% endif %}