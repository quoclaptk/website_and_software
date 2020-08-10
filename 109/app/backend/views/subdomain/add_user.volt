<h4>Tên miền: <strong class="text-danger">{{ subdomain.name~ '.' ~ ROOT_DOMAIN }}</strong></h4>
<ul class="list row" style="max-height: 400px;overflow: auto">
    {% for i in users %}
        {% set username = i.username %}
        {% set name = i.name %}
    <li class="form-group col-md-4">
        <div class="checkbox">
	        <label>
	            <input type="checkbox" class="user-item-checkbox" name="username[]" value="{{ i.id }}" {% if i.id in tmp_selected %}checked{% endif %}><span style="vertical-align: middle;sans-serif;margin-left: 3px">{{ name }}</span>
	        </label>
	    </div>
    </li>
    {% endfor %}
</ul>