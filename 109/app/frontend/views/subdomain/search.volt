{% if list|length > 0 %}
<div class="row">
    <div class="col-xs-12">
        {% for key,item in list %}
            {% if loop.first %}
                <div class="table-responsive mailbox-messages">
                <table id="example" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th width="7%">Thứ tự</th>
                    <th>Tên</th>
                </tr>
                </thead>

                <tbody>
            {% endif %}
            <tr>
                <td>{{ key + 1 }}</td>
                <td>
                    <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank">{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a>
                </td>
            </tr>
            {% if loop.last %}
                </tbody>

                </table>
                </div>
            {% endif %}
        {% endfor %}
    </div>
</div>
{% endif %}