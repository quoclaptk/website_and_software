
{{ content() }}

<ul class="pager">
    <li class="previous pull-left">
        {{ link_to("acp/users/index", "&larr; Go Back") }}
    </li>
    <li class="pull-right">
        {{ link_to("acp/users/create", "Create users", "class": "btn btn-primary") }}
    </li>
</ul>

{% for user in page.items %}
{% if loop.first %}
<table class="table table-bordered table-striped table-hover" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Email</th>
            <th>Profile</th>
            <th>Banned?</th>
            <th>Suspended?</th>
            <th>Confirmed?</th>
        </tr>
    </thead>
{% endif %}
    <tbody>
        <tr>
            <td>{{ user.id }}</td>
            <td>{{ user.username }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.profile.name }}</td>
            <td>{{ user.banned == 'Y' ? 'Yes' : 'No' }}</td>
            <td>{{ user.suspended == 'Y' ? 'Yes' : 'No' }}</td>
            <td>{{ user.active == 'Y' ? 'Yes' : 'No' }}</td>
            <td width="12%">{{ link_to("acp/users/edit/" ~ user.id, '<i class="fa fa-pencil-square-o"></i> Edit', "class": "btn") }}</td>
            <td width="12%">{{ link_to("acp/users/_delete/" ~ user.id, '<i class="fa fa-times"></i> Delete', "class": "btn") }}</td>
        </tr>
    </tbody>
{% if loop.last %}


</table>
{% if page.total_pages > 1 %}
    {% set controller = this.view.getControllerName() | lower  %}
    {% set action = this.view.getActionName() | lower %}
    {% set startIndex = 1, q = search_query | trim %}
    {% if page.total_pages > 5 %}
        {% if page.current > 3 %}
            {% set startIndex = startIndex + page.current - 3 %}
        {% endif %}
        {% if page.total_pages - page.current < 5 %}
            {% set startIndex = page.total_pages - 4 %}
        {% endif %}
    {% endif %}
    <ul class="pagination pagination-sm m-t-none m-b-none">
        {% if page.current > 1 %}
            <li>{{ link_to('acp/users/search', '<i class="fa fa-angle-double-left"></i>', 'title' : 'Go to page ' ~ page.next) }}</li>
            <li class="prev">{{ link_to('acp/users/search?page=' ~ page.before, 'data-page' : page.before, '<i class="fa fa-angle-left"></i>', 'title' : 'Go to page ' ~ page.last) }}</li>
        {% endif %}
        {% for pageIndex in startIndex..page.total_pages %}
            {% if pageIndex is startIndex + 5 %}
                {% break %}
            {% endif %}

            <li {% if pageIndex is page.current %}class="active"{% endif %}>
                {{ link_to('acp/users/search?page=' ~ pageIndex, pageIndex, 'data-page' : pageIndex, 'title' : 'Go to page ' ~ pageIndex) }}
            </li>
        {% endfor %}

        {% if page.current < page.total_pages %}
            <li class="next">{{ link_to('acp/users/search&page=' ~ page.next, 'data-page' : page.next, '<i class="fa fa-angle-right"></i>', 'title' : 'Go to page ' ~ page.next)}}</li>

            <li>{{ link_to('acp/users/search?page=' ~ page.last, 'data-page' : page.last, '<i class="fa fa-angle-double-right"></i>', 'title' : 'Go to page ' ~ page.last) }}</li>
        {% endif %}
    </ul>
{% endif %}
{% endif %}
{% else %}
    No users are recorded
{% endfor %}
