<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Link</th>
                <th width="12%">Số lượt</th>
            </tr>
        </thead>
        <tbody>
        	{% set count = 1 %}
        	{% for key,i in url %}
            <tr>
                <th scope="row">{{ count }}</th>
                <td>{{ key }}</td>
                <td>{{ i }}</td>
            </tr>
            {% set count = count + 1 %}
			{% endfor %}
        </tbody>
    </table>
</div>