{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            <div class="panel panel-default">
                <div class="panel-heading">{{ title_bar }}</div>
                <div class="panel-body">
                    <div class="row">
                        {% for i in list_config_kernel_arr %}
                        {% set name = i['name'] %}
                        {% set type = i['type'] %}
                        {% set field = i['field'] %}
                        {% set value = i['value'] %}
                        <div class="form-group clearfix">
                            <label class="col-md-3 text-right"{% if type == 'text' %} style="margin-top: 5px"{% endif %}>{{ name }}</label>
                            <div class="col-md-7">
                                {% if type == 'text' %}
                                <div style="padding-left: 20px">
                                    <input type="text" name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ i['place_holder'] }}">
                                </div>
                                {% endif %}

                                {% if type == 'textarea' %}
                                    <div style="padding-left: 20px">
                                        <textarea name="{{ field }}" class="form-control" rows="3" placeholder="{{ i['place_holder'] }}">{{ value }}</textarea>
                                    </div>
                                {% endif %}

                                {% if type == 'radio' %}
                                {% for j in i['list_value'] %}
                                <div class="radio" style="margin-top: 0">
                                    <label>
                                        <input type="radio" name="{{ field }}[]" value="{{ j['name'] }}"{% if j['select'] == 1 %} checked{% endif %}><span style="margin-left: 7px;vertical-align: middle">{{ j['name'] }}</span>
                                    </label>
                                </div>
                                {% endfor %}
                                {% endif %}

                                {% if type == 'checkbox' %}
                                    {% for j in i['list_value'] %}
                                        <div class="checkbox" style="margin-top: 0">
                                            <label>
                                                <input type="checkbox" name="{{ field }}[]" value="{{ j['name'] }}"{% if j['select'] == 1 %} checked{% endif %}>
                                                <span style="margin-left: 7px;vertical-align: middle">{{ j['name'] }}</span>
                                            </label>
                                        </div>
                                    {% endfor %}
                                {% endif %}

                                {% if type == 'select' %}
                                    <div class="checkbox" style="margin-top: 0">
                                        <label>
                                            <select class="form-control" name="{{ field }}">
                                                {% for j in i['list_value'] %}
                                                <option value="{{ j['name'] }}"{% if j['select'] == 1 %} selected{% endif %}>{{ j['name'] }}</option>
                                                {% endfor %}
                                            </select>
                                        </label>
                                    </div>
                                {% endif %}
                            </div>
                        </div>
                        {% endfor %}
                    </div>

                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ link_to(ACP_NAME, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>