{#{{ partial('partials/nav_setting') }}#}
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}
            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
                <input type="hidden" name="active" value="language">
            <div>
                <!-- Nav tabs -->
                <ul id="tab-setting" class="nav nav-tabs" role="tablist" style="margin-bottom: 10px">
                    <li role="presentation" class="active"><a href="#language" aria-controls="language" role="tab" data-toggle="tab">Đa ngôn ngữ</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="language">
                        <div class="panel panel-default"> 
                            <div class="panel-heading" style="display: block;">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse_header" style="display: block;">Ngôn ngữ nhập thủ công</a>
                                </h4>
                            </div>                           
                            <div class="panel-body">
                                <div class="row">
                                    {% for node,i in list_config_item_arr %}
                                        {% if node == 1 %}
                                            {% set name = i['name'] %}
                                            {% set type = i['type'] %}
                                            {% set field = i['field'] %}
                                            {% set value = i['value'] %}
                                            {% set min_value = i['min_value'] %}
                                            {% set max_value = i['max_value'] %}
                                            <div class="col-md-6" style="display:flex">
                                                <div class="form-group clearfix" style="width:100%">
                                                    <div class="box_config_setting clearfix">
                                                        <div class="row">
                                                            <label class="col-md-6 text-right"{% if type == 'text' %} style="margin-top: 5px"{% endif %}>{{ name }}</label>
                                                            <div class="col-md-6">
                                                                <div class="clearfix">
                                                                    {% if type == 'text' %}
                                                                        <div>
                                                                            <input {% if (min_value != null and min_value != 0) or (max_value != null and max_value != 0) %}type="number"{% else %}type="text"{% endif %} name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ i['place_holder'] }}" {% if min_value != null and min_value != 0 %}min="{{ min_value }}"{% endif %} {% if max_value != null and max_value != 0 %}max="{{ max_value }}"{% endif %}>
                                                                        </div>
                                                                    {% endif %}

                                                                    {% if type == 'email' %}
                                                                        <div>
                                                                            <input type="email" name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ i['place_holder'] }}">
                                                                        </div>
                                                                    {% endif %}

                                                                    {% if type == 'textarea' %}
                                                                        <div style="padding-left: 20px">
                                                                            <textarea name="{{ field }}" class="form-control" rows="3" placeholder="{{ i['place_holder'] }}">{{ value }}</textarea>
                                                                        </div>
                                                                    {% endif %}

                                                                    {% if type == 'radio' %}
                                                                        {% for j in i['list_value'] %}
                                                                            <div class="radio pull-left" style="margin-top: 0;{% if i['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                <label>
                                                                                    <input type="radio" name="{{ field }}[]" value="{{ j['name'] }}"{% if j['select'] == 1 %} checked{% endif %} class="flat-red"><span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ j['name'] }}</span>
                                                                                </label>
                                                                            </div>
                                                                        {% endfor %}
                                                                    {% endif %}

                                                                    {% if type == 'checkbox' %}
                                                                        {% for j in i['list_value'] %}
                                                                            <div class="checkbox" style="margin-top: 0;{% if i['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                <label>
                                                                                    <input type="checkbox" name="{{ field }}[]" value="{{ j['name'] }}"{% if j['select'] == 1 %} checked{% endif %} class="flat-red">
                                                                                    <span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ j['name'] }}</span>
                                                                                </label>
                                                                            </div>
                                                                        {% endfor %}
                                                                    {% endif %}

                                                                    {% if type == 'select' %}
                                                                        <div class="checkbox" style="margin-top: 0;{% if i['guide'] is defined %}margin-bottom:0{% endif %}">
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

                                                                {% if i['guide'] is defined %}
                                                                    <p><a href="javascript:;" class="view-config-guide" data-id="{{ i['config_core_id'] }}" style="color: #f00;font-size: 12px;text-decoration: underline"><i>Click để xem hướng dẫn</i></a></p>
                                                                {% endif %}
                                                            </div>
                                                        </div>
                                                        {% if i['child'] is defined and i['child']|length > 0 %}
                                                            {% set child = i['child'] %}
                                                            {% for k in child %}
                                                                {% set name = k['name'] %}
                                                                {% set type = k['type'] %}
                                                                {% set field = k['field'] %}
                                                                {% set value = k['value'] %}
                                                                {% set min_value = k['min_value'] %}
                                                                {% set max_value = k['max_value'] %}
                                                                <div class="row">
                                                                    <label class="col-md-6 text-right text-sm"{% if type == 'text' %} style="margin-top: 5px"{% endif %}>{{ name }}</label>
                                                                    <div class="col-md-6">
                                                                        <div class="clearfix">
                                                                            {% if type == 'text' %}
                                                                                <div>
                                                                                    <input {% if (min_value != null and min_value != 0) or (max_value != null and max_value != 0) %}type="number"{% else %}type="text"{% endif %} name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ k['place_holder'] }}" {% if min_value != null and min_value != 0 %}min="{{ min_value }}"{% endif %} {% if max_value != null and max_value != 0 %}max="{{ max_value }}"{% endif %} style="margin-bottom:10px">
                                                                                </div>
                                                                            {% endif %}

                                                                            {% if type == 'email' %}
                                                                                <div>
                                                                                    <input type="email" name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ k['place_holder'] }}">
                                                                                </div>
                                                                            {% endif %}

                                                                            {% if type == 'textarea' %}
                                                                                <div style="padding-left: 20px">
                                                                                    <textarea name="{{ field }}" class="form-control" rows="3" placeholder="{{ k['place_holder'] }}">{{ value }}</textarea>
                                                                                </div>
                                                                            {% endif %}

                                                                            {% if type == 'radio' %}
                                                                                {% for l in k['list_value'] %}
                                                                                    <div class="radio pull-left" style="margin-top: 0;{% if k['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                        <label>
                                                                                            <input type="radio" name="{{ field }}[]" value="{{ l['name'] }}"{% if l['select'] == 1 %} checked{% endif %} class="flat-red"><span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ l['name'] }}</span>
                                                                                        </label>
                                                                                    </div>
                                                                                {% endfor %}
                                                                            {% endif %}

                                                                            {% if type == 'checkbox' %}
                                                                                {% for l in k['list_value'] %}
                                                                                    <div class="checkbox" style="margin-top: 0;{% if k['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                        <label>
                                                                                            <input type="checkbox" name="{{ field }}[]" value="{{ l['name'] }}"{% if l['select'] == 1 %} checked{% endif %} class="flat-red">
                                                                                            <span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ l['name'] }}</span>
                                                                                        </label>
                                                                                    </div>
                                                                                {% endfor %}
                                                                                
                                                                            {% endif %}

                                                                            {% if type == 'select' %}
                                                                                <div class="checkbox" style="margin-top: 0;{% if k['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                    <label>
                                                                                        <select class="form-control" name="{{ field }}">
                                                                                            {% for l in k['list_value'] %}
                                                                                                <option value="{{ l['name'] }}"{% if l['select'] == 1 %} selected{% endif %}>{{ l['name'] }}</option>
                                                                                            {% endfor %}
                                                                                        </select>
                                                                                    </label>
                                                                                </div>
                                                                            {% endif %}
                                                                        </div>

                                                                        {% if k['guide'] is defined %}
                                                                        <p><a href="javascript:;" class="view-config-guide" data-id="{{ k['config_core_id'] }}" style="color: #f00;font-size: 12px;text-decoration: underline"><i>Click để xem hướng dẫn</i></a></p>
                                                                        {% endif %}
                                                                    </div>
                                                                </div>
                                                            {% endfor %}
                                                        {% endif %}
                                                    </div>
                                                </div>
                                            </div>
                                        {% endif %}
                                    {% endfor %}
                                </div>
                                <div class="row">
                                    {% for language in languages %}
                                    {% if language.code == 'vi' %}
                                        {% set checked = ' checked' %}
                                        {% set disabled = ' disabled' %}
                                    {% elseif language.code != 'vi' %}
                                        {% if language.id in arrayTmp %}
                                            {% set checked = ' checked' %}
                                            {% set disabled = '' %}
                                        {% else %}
                                            {% set checked = '' %}
                                            {% set disabled = '' %}
                                        {% endif %}
                                    {% endif %}
                                    <div class="checkbox" style="display:inline-block">
                                        <label>
                                            <input type="checkbox" name="language[]" value="{{ language.id }}" class="flat-red"{{ checked ~ disabled }}>
                                            <span>{{ language.name }}</span>
                                        </label>
                                    </div>
                                    {% endfor %}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default"> 
                            <div class="panel-heading" style="display: block;">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse_header" style="display: block;">Ngôn ngữ tự dịch (google dịch)</a>
                                </h4>
                            </div>                           
                            <div class="panel-body">
                                <div class="row">
                                    {% for node,i in list_config_item_arr %}
                                        {% if node == 0 %}
                                            {% set name = i['name'] %}
                                            {% set type = i['type'] %}
                                            {% set field = i['field'] %}
                                            {% set value = i['value'] %}
                                            {% set min_value = i['min_value'] %}
                                            {% set max_value = i['max_value'] %}
                                            <div class="col-md-6" style="display:flex">
                                                <div class="form-group clearfix" style="width:100%">
                                                    <div class="box_config_setting clearfix">
                                                        <div class="row">
                                                            <label class="col-md-6 text-right"{% if type == 'text' %} style="margin-top: 5px"{% endif %}>{{ name }}</label>
                                                            <div class="col-md-6">
                                                                <div class="clearfix">
                                                                    {% if type == 'text' %}
                                                                        <div>
                                                                            <input {% if (min_value != null and min_value != 0) or (max_value != null and max_value != 0) %}type="number"{% else %}type="text"{% endif %} name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ i['place_holder'] }}" {% if min_value != null and min_value != 0 %}min="{{ min_value }}"{% endif %} {% if max_value != null and max_value != 0 %}max="{{ max_value }}"{% endif %}>
                                                                        </div>
                                                                    {% endif %}

                                                                    {% if type == 'email' %}
                                                                        <div>
                                                                            <input type="email" name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ i['place_holder'] }}">
                                                                        </div>
                                                                    {% endif %}

                                                                    {% if type == 'textarea' %}
                                                                        <div style="padding-left: 20px">
                                                                            <textarea name="{{ field }}" class="form-control" rows="3" placeholder="{{ i['place_holder'] }}">{{ value }}</textarea>
                                                                        </div>
                                                                    {% endif %}

                                                                    {% if type == 'radio' %}
                                                                        {% for j in i['list_value'] %}
                                                                            <div class="radio pull-left" style="margin-top: 0;{% if i['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                <label>
                                                                                    <input type="radio" name="{{ field }}[]" value="{{ j['name'] }}"{% if j['select'] == 1 %} checked{% endif %} class="flat-red"><span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ j['name'] }}</span>
                                                                                </label>
                                                                            </div>
                                                                        {% endfor %}
                                                                    {% endif %}

                                                                    {% if type == 'checkbox' %}
                                                                        {% for j in i['list_value'] %}
                                                                            <div class="checkbox" style="margin-top: 0;{% if i['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                <label>
                                                                                    <input type="checkbox" name="{{ field }}[]" value="{{ j['name'] }}"{% if j['select'] == 1 %} checked{% endif %} class="flat-red">
                                                                                    <span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ j['name'] }}</span>
                                                                                </label>
                                                                            </div>
                                                                        {% endfor %}
                                                                    {% endif %}

                                                                    {% if type == 'select' %}
                                                                        <div class="checkbox" style="margin-top: 0;{% if i['guide'] is defined %}margin-bottom:0{% endif %}">
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

                                                                {% if i['guide'] is defined %}
                                                                    <p><a href="javascript:;" class="view-config-guide" data-id="{{ i['config_core_id'] }}" style="color: #f00;font-size: 12px;text-decoration: underline"><i>Click để xem hướng dẫn</i></a></p>
                                                                {% endif %}
                                                            </div>
                                                        </div>
                                                        {% if i['child'] is defined and i['child']|length > 0 %}
                                                            {% set child = i['child'] %}
                                                            {% for k in child %}
                                                                {% set name = k['name'] %}
                                                                {% set type = k['type'] %}
                                                                {% set field = k['field'] %}
                                                                {% set value = k['value'] %}
                                                                {% set min_value = k['min_value'] %}
                                                                {% set max_value = k['max_value'] %}
                                                                <div class="row">
                                                                    <label class="col-md-6 text-right"{% if type == 'text' %} style="margin-top: 5px"{% endif %}>{{ name }}</label>
                                                                    <div class="col-md-6">
                                                                        <div class="clearfix">
                                                                            {% if type == 'text' %}
                                                                                <div>
                                                                                    <input {% if (min_value != null and min_value != 0) or (max_value != null and max_value != 0) %}type="number"{% else %}type="text"{% endif %} name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ k['place_holder'] }}" {% if min_value != null and min_value != 0 %}min="{{ min_value }}"{% endif %} {% if max_value != null and max_value != 0 %}max="{{ max_value }}"{% endif %} style="margin-bottom:10px">
                                                                                </div>
                                                                            {% endif %}

                                                                            {% if type == 'email' %}
                                                                                <div>
                                                                                    <input type="email" name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ k['place_holder'] }}">
                                                                                </div>
                                                                            {% endif %}

                                                                            {% if type == 'textarea' %}
                                                                                <div style="padding-left: 20px">
                                                                                    <textarea name="{{ field }}" class="form-control" rows="3" placeholder="{{ k['place_holder'] }}">{{ value }}</textarea>
                                                                                </div>
                                                                            {% endif %}

                                                                            {% if type == 'radio' %}
                                                                                {% for l in k['list_value'] %}
                                                                                    <div class="radio pull-left" style="margin-top: 0;{% if k['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                        <label>
                                                                                            <input type="radio" name="{{ field }}[]" value="{{ l['name'] }}"{% if l['select'] == 1 %} checked{% endif %} class="flat-red"><span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ l['name'] }}</span>
                                                                                        </label>
                                                                                    </div>
                                                                                {% endfor %}
                                                                            {% endif %}

                                                                            {% if type == 'checkbox' %}
                                                                                {% set italiaLang = false %}
                                                                                {% for l in k['list_value'] %}
                                                                                    {% if l['name'] == 'Italia' %}
                                                                                    {% set italiaLang = true %}
                                                                                    {% endif %}
                                                                                    <div class="checkbox" style="margin-top: 0;{% if k['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                        <label>
                                                                                            <input type="checkbox" name="{{ field }}[]" value="{{ l['name'] }}"{% if l['select'] == 1 %} checked{% endif %} class="flat-red">
                                                                                            <span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ l['name'] }}</span>
                                                                                        </label>
                                                                                    </div>
                                                                                {% endfor %}
                                                                                {% if field == '_cf_checkbox_select_language' and italiaLang == false %}
                                                                                <div class="checkbox" style="margin-top: 0;">
                                                                                    <label>
                                                                                        <input type="checkbox" name="_cf_checkbox_select_language[]" value="Italia" class="flat-red">
                                                                                        <span class="text-sm" style="margin-left: 7px;vertical-align: middle">Italia</span>
                                                                                    </label>
                                                                                </div>
                                                                                {% endif %}
                                                                            {% endif %}

                                                                            {% if type == 'select' %}
                                                                                <div class="checkbox" style="margin-top: 0;{% if k['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                    <label>
                                                                                        <select class="form-control" name="{{ field }}">
                                                                                            {% for l in k['list_value'] %}
                                                                                                <option value="{{ l['name'] }}"{% if l['select'] == 1 %} selected{% endif %}>{{ l['name'] }}</option>
                                                                                            {% endfor %}
                                                                                        </select>
                                                                                    </label>
                                                                                </div>
                                                                            {% endif %}
                                                                        </div>

                                                                        {% if k['guide'] is defined %}
                                                                        <p><a href="javascript:;" class="view-config-guide" data-id="{{ k['config_core_id'] }}" style="color: #f00;font-size: 12px;text-decoration: underline"><i>Click để xem hướng dẫn</i></a></p>
                                                                        {% endif %}
                                                                    </div>
                                                                </div>
                                                            {% endfor %}
                                                        {% endif %}
                                                    </div>
                                                </div>
                                            </div>
                                        {% endif %}
                                    {% endfor %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Save", "class": "btn btn-primary","data-type":"save") }}
                {#{{ submit_button("Save & New", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}#}
                {#{{ submit_button("Save & Close", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}
                {{ link_to('/' ~ ACP_NAME ~ , "Exit", "class": "btn btn-danger") }}#}
            </div>
            {{ endform() }}

        </div>
    </div>
</section>
<div class="modal modal fade" id="modalViewConfigGuide" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Hướng dẫn cấu hình</h4>
        </div>
        <div class="modal-body">
        </div>
      </div>
    </div>
</div>

