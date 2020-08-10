{#{{ partial('partials/nav_setting') }}#}
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}
            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            <div>
                <div class="content-page">
                    <div class="row form-group">
                        <div class="col-md-4">
                            <input type="text" name="txt_config" class="form-control" placeholder="Tìm cấu hình">
                        </div>
                        <div class="col-md-4">{{ link_to(ACP_NAME ~ '/config_item/updateNewConfig', 'Cập nhật cấu hình mới', 'style':'text-decoration:underline;margin-top:5px;display:inline-block') }}</div>
                    </div>
                    <div class="panel panel-default" id="configSetting">                            
                        <div class="panel-body">
                            <div class="row">
                                {% for i in list_config_item_arr %}
                                    {% set name = i['name'] %}
                                    {% set type = i['type'] %}
                                    {% set field = i['field'] %}
                                    {% set value = i['value'] %}
                                    {% set min_value = i['min_value'] %}
                                    {% set max_value = i['max_value'] %}
                                    <div class="col-md-4" style="display:flex">
                                        <div class="form-group clearfix" style="width:100%">
                                            <div class="box_config_setting well clearfix">
                                                <div class="row">
                                                    <label class="col-md-6 text-right text-sm"{% if type == 'text' %} style="margin-top: 5px"{% endif %}>{{ name }}</label>
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
                                                                {% if field != '_cf_radio_banner_ads_left' and field != '_cf_radio_banner_ads_right' and i['list_value'] is not null %}
                                                                {% for j in i['list_value'] %}
                                                                    <div class="radio pull-left" style="margin-top: 0;{% if i['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                        <label>
                                                                            <input type="radio" name="{{ field }}[]" value="{{ j['name'] }}"{% if j['select'] == 1 %} checked{% endif %} class="flat-red"><span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ j['name'] }}</span>
                                                                        </label>
                                                                    </div>
                                                                {% endfor %}
                                                                {% endif %}
                                                                {% if field == '_cf_radio_banner_ads_left' or field == '_cf_radio_banner_ads_right' %}
                                                                <a href="/{{ ACP_NAME }}/banner" style="color:#f00;text-decoration:underline;float:left;padding-left:20px;margin-bottom:5px;font-size:12px"><i>Click chọn hình</i></a>
                                                                {% endif %}
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
                                {% endfor %}
                            </div>
                            <div class="row">
                                <div class="col-md-4" style="display:flex">
                                    <div class="form-group">
                                        <div class="panel panel-default" style="border:none">                            
                                            <div class="panel-body" style="padding:0px">
                                                <div class="box_new_interface" style="font-size:13px">
                                                    <div class="box_new_interface_header">
                                                        <div class="panel panel-default">                            
                                                            <div class="panel-body">
                                                                <div class="panel-group clearfix" style="margin-bottom:0">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_header" style="display: block;">Thêm module hiện dưới trang <span style="color:#f00">Chi tiết sản phẩm</span></a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_header" class="panel-collapse collapse in">
                                                                            <div class="panel-body">
                                                                                <div class="module_position_header">
                                                                                    <div class="table-responsive mailbox-messages">
                                                                                        <table class="table table-bordered table-striped table-hover table-newinterface">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th>Tên</th>
                                                                                                <th style="width:20%;" class="text-center">Thứ tự</th>
                                                                                                <th width="15%" class="text-center">Ẩn/Hiện</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            {% for j in modules %}
                                                                                                <tr>
                                                                                                    <td>{{ j['module_name'] }}
                                                                                                        {% if j['url'] != '' %}
                                                                                                        <a href="/{{ j['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                                                                        {% endif %}
                                                                                                    </td>
                                                                                                    
                                                                                                    <td class="text-center">
                                                                                                        <input type="text" name="sort_module[{{ j['module_id'] }}]" class="form-control" value="{% if j['sort'] != '' %}{{ j['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                                                                    </td>
                                                                                                    <td align="center">
                                                                                                        <input type="checkbox" name="active_module[{{ j['module_id'] }}]" value="Y" {% if j['active'] == 'Y' and j['type'] == 'product' %}checked{% endif %}>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                {% if j['child'] is defined %}
                                                                                                {% for k in j['child'] %}
                                                                                                <tr>
                                                                                                    <td>|---{{ k['module_name'] }}</td>
                                                                                                    <td class="text-center">
                                                                                                        <input type="text" name="sort_module[{{ k['module_id'] }}]"  class="form-control" value="{% if k['sort'] != '' %}{{ k['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                                                                    </td>
                                                                                                    <td align="center">
                                                                                                        <input type="checkbox" name="active_module[{{ k['module_id'] }}]" value="Y" {% if j['active'] == 'Y' and k['active'] == 'Y' and k['type'] == 'product' %}checked{% endif %}>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                {% endfor %}
                                                                                                {% endif %}
                                                                                            {% endfor %}
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" style="display:flex">
                                    <div class="form-group">
                                        <div class="panel panel-default" style="border:none">                            
                                            <div class="panel-body" style="padding:0px">
                                                <div class="box_new_interface" style="font-size:13px">
                                                    <div class="box_new_interface_header">
                                                        <div class="panel panel-default">                            
                                                            <div class="panel-body">
                                                                <div class="panel-group clearfix" style="margin-bottom:0">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_header1" style="display: block;">Thêm module hiện dưới trang <span style="color:#f00">Menu danh mục sản phẩm</span></a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_header1" class="panel-collapse collapse in">
                                                                            <div class="panel-body">
                                                                                <div class="module_position_header">
                                                                                    <div class="table-responsive mailbox-messages">
                                                                                        <table class="table table-bordered table-striped table-hover table-newinterface">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th>Tên</th>
                                                                                                <th style="width:20%;" class="text-center">Thứ tự</th>
                                                                                                <th width="15%" class="text-center">Ẩn/Hiện</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            {% for j in modules %}
                                                                                                <tr>
                                                                                                    <td>{{ j['module_name'] }}
                                                                                                        {% if j['url'] != '' %}
                                                                                                        <a href="/{{ j['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                                                                        {% endif %}
                                                                                                    </td>
                                                                                                    
                                                                                                    <td class="text-center">
                                                                                                        <input type="text" name="sort_module_category[{{ j['module_id'] }}]" class="form-control" value="{% if j['sort'] != '' %}{{ j['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                                                                    </td>
                                                                                                    <td align="center">
                                                                                                        <input type="checkbox" name="active_module_category[{{ j['module_id'] }}]" value="Y" {% if j['active'] == 'Y' and j['type'] == 'category' %}checked{% endif %}>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                {% if j['child'] is defined %}
                                                                                                {% for k in j['child'] %}
                                                                                                <tr>
                                                                                                    <td>|---{{ k['module_name'] }}</td>
                                                                                                    <td class="text-center">
                                                                                                        <input type="text" name="sort_module_category[{{ k['module_id'] }}]"  class="form-control" value="{% if k['sort'] != '' %}{{ k['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                                                                    </td>
                                                                                                    <td align="center">
                                                                                                        <input type="checkbox" name="active_module_category[{{ k['module_id'] }}]" value="Y" {% if j['active'] == 'Y' and k['active'] == 'Y' and k['type'] == 'category' %}checked{% endif %}>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                {% endfor %}
                                                                                                {% endif %}
                                                                                            {% endfor %}
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" style="display:flex">
                                    <div class="form-group">
                                        <div class="panel panel-default" style="border:none">                            
                                            <div class="panel-body" style="padding:0px">
                                                <div class="box_new_interface" style="font-size:13px">
                                                    <div class="box_new_interface_header">
                                                        <div class="panel panel-default">                            
                                                            <div class="panel-body">
                                                                <div class="panel-group clearfix" style="margin-bottom:0">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_header2" style="display: block;">Thêm module hiện dưới trang <span style="color:#f00">Menu danh mục tin tức</span></a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_header2" class="panel-collapse collapse in">
                                                                            <div class="panel-body">
                                                                                <div class="module_position_header">
                                                                                    <div class="table-responsive mailbox-messages">
                                                                                        <table class="table table-bordered table-striped table-hover table-newinterface">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th>Tên</th>
                                                                                                <th style="width:20%;" class="text-center">Thứ tự</th>
                                                                                                <th width="15%" class="text-center">Ẩn/Hiện</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            {% for j in modules %}
                                                                                                <tr>
                                                                                                    <td>{{ j['module_name'] }}
                                                                                                        {% if j['url'] != '' %}
                                                                                                        <a href="/{{ j['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                                                                        {% endif %}
                                                                                                    </td>
                                                                                                    
                                                                                                    <td class="text-center">
                                                                                                        <input type="text" name="sort_module_news_menu[{{ j['module_id'] }}]" class="form-control" value="{% if j['sort'] != '' %}{{ j['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                                                                    </td>
                                                                                                    <td align="center">
                                                                                                        <input type="checkbox" name="active_module_news_menu[{{ j['module_id'] }}]" value="Y" {% if j['active'] == 'Y' and j['type'] == 'news_menu' %}checked{% endif %}>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                {% if j['child'] is defined %}
                                                                                                {% for k in j['child'] %}
                                                                                                <tr>
                                                                                                    <td>|---{{ k['module_name'] }}</td>
                                                                                                    <td class="text-center">
                                                                                                        <input type="text" name="sort_module_news_menu[{{ k['module_id'] }}]"  class="form-control" value="{% if k['sort'] != '' %}{{ k['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                                                                    </td>
                                                                                                    <td align="center">
                                                                                                        <input type="checkbox" name="active_module_news_menu[{{ k['module_id'] }}]" value="Y" {% if j['active'] == 'Y' and k['active'] == 'Y' and k['type'] == 'news_menu' %}checked{% endif %}>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                {% endfor %}
                                                                                                {% endif %}
                                                                                            {% endfor %}
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

<script type="text/javascript">
    $(document).ready(function() {
        $('form').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) { 
            e.preventDefault();
            return false;
          }
        });
        filterConfig();
    })

    function filterConfig() {
        var typingTimer;                //timer identifier
        var doneTypingInterval = 300;  //time in ms, 5 second for example
        var $input = $('input[name=txt_config]');

        //on keyup, start the countdown
        $input.on('keyup', function () {
            clearTimeout(typingTimer);
            // replace tag b in html
            $('#configSetting label.text-right').each(function() {
                var text = $(this).text();
                $(this).text(text.replace('<b>', '')); 
                $(this).text(text.replace('</b>', '')); 
            });

            var strvalue = $input.val();
            var words = [];
            words.push(strvalue);

            typingTimer = setTimeout(function() {
                wrapTagHtml(words);
           }, doneTypingInterval);
        });

        //on keydown, clear the countdown 
        $input.on('keydown', function () {
          clearTimeout(typingTimer);
        });
    }

    function wrapTagHtml(words) {
        $('#configSetting label.text-right').wrapInTag(words);
    }

    $.fn.wrapInTag = function(words) {
      var tag = 'b',
          words = words,
          regex = RegExp(words.join('|'), 'gi'),
          replacement = '<'+ tag +'>$&</'+ tag +'>';
      
      return this.html(function() {
        return $(this).html().replace(regex, replacement);
      });
    };
</script>
<style type="text/css">
    .text-right b{background: #FFFF00;color: #000}
</style>
