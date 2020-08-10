{#{{ partial('partials/nav_setting') }}#}
<section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      {{ content() }}
      {{ flashSession.output() }}
      {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
      <input type="hidden" name="active" value="{% if request.get('active') != '' %}{{ request.get('active') }}{% else %}css{% endif %}">
      <div>
        <!-- Nav tabs -->
                {#<ul id="tab-setting" class="nav nav-tabs" role="tablist" style="margin-bottom: 10px">
                    <li role="presentation" {% if request.get('active') == 'css' or request.get('active') == '' %}class="active"{% endif %}><a href="#css" aria-controls="css" role="tab" data-toggle="tab">Css & giao diện</a></li>
                    <li role="presentation" {% if request.get('active') == 'header' %}class="active"{% endif %}><a href="#header" aria-controls="header" role="tab" data-toggle="tab">Header</a></li>
                    <li role="presentation" {% if request.get('active') == 'left' %}class="active"{% endif %}><a href="#left" aria-controls="left" role="tab" data-toggle="tab">Cột trái</a></li>
                    <li role="presentation" {% if request.get('active') == 'center' %}class="active"{% endif %}><a href="#center" aria-controls="center" role="tab" data-toggle="tab">Cột giữa</a></li>
                    <li role="presentation" {% if request.get('active') == 'right' %}class="active"{% endif %}><a href="#right" aria-controls="right" role="tab" data-toggle="tab">Cột phải</a></li>
                    <li role="presentation" {% if request.get('active') == 'footer1' %}class="active"{% endif %}><a href="#footer1" aria-controls="footer1" role="tab" data-toggle="tab">Footer</a></li>
                  </ul>#}

                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane{% if request.get('active') == 'css' or request.get('active') == '' %} active{% endif %}" id="css">
                      <div class="panel panel-default" style="border:none">                            
                        <div class="panel-body" style="padding:0px">
                          <div class="box_new_interface" style="font-size:13px">
                            <div class="box_new_interface_header">
                              {% set key = 'header' %}
                              <div class="panel panel-default">                            
                                <div class="panel-body">
                                  <div class="panel-group clearfix" style="margin-bottom:0">
                                    <div class="panel panel-default">
                                      <div class="panel-heading">
                                        <h4 class="panel-title">
                                          <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse_header" style="display: block;">Header</a>
                                        </h4>
                                      </div>
                                      {% if layout_id == 2 %}
                                      <div class="form-group clearfix" id="show_hide_header">
                                        <label>
                                          <input type="radio" name="hide_header" value="N"{% if (layout_config.hide_header == 'N') %} checked{% endif %}> Hiện
                                        </label>
                                        <label>
                                          <input type="radio" name="hide_header" value="Y"{% if (layout_config.hide_header == 'Y') %} checked{% endif %}> Ẩn
                                        </label>
                                      </div>
                                      {% endif %}
                                      <div id="collapse_header" class="panel-collapse collapse">
                                        <div class="panel-body">
                                          <div id="module_position_header">
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
                                                  {% for j in position_module_array['header'] %}
                                                  <tr>
                                                    <td>{{ j['module_name'] }}
                                                      {% if j['url'] != '' %}
                                                      <a href="/{{ j['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                      {% endif %}
                                                    </td>

                                                    <td class="text-center">
                                                      <input type="text" name="sort_module_{{ key }}_{{ layout_id }}[{{ j['module_id'] }}]" id="sort_{{ j['id'] }}" class="form-control" value="{% if j['sort'] != '' %}{{ j['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                    </td>
                                                    <td align="center">
                                                      <input type="checkbox" name="active_module_{{ key }}_{{ layout_id }}[{{ j['module_id'] }}]" value="Y" {% if j['active'] == 'Y' %}checked{% endif %}>
                                                    </td>
                                                  </tr>
                                                  {% if j['child'] is defined %}
                                                  {% for k in j['child'] %}
                                                  <tr>
                                                    <td>|---{{ k['module_name'] }}
                                                      {% if k['url'] != '' %}
                                                      <a href="/{{ k['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                      {% endif %}
                                                    </td>
                                                    <td class="text-center">
                                                      <input type="text" name="sort_module_{{ key }}_{{ layout_id }}[{{ k['module_id'] }}]" id="sort_child_{{ k['module_id'] }}" class="form-control" value="{% if k['sort'] != '' %}{{ k['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                    </td>
                                                    <td align="center">
                                                      <input type="checkbox" name="active_module_{{ key }}_{{ layout_id }}[{{ k['module_id'] }}]" value="Y" {% if j['active'] == 'Y' AND k['active'] == 'Y' %}checked{% endif %}>
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
                            <div class="box_new_interface_content">
                              <div class="row">
                                <div class="col-md-4" style="padding-right:0">
                                  {% set key = 'left' %}
                                  <div class="panel panel-default">                           
                                    <div class="panel-body">
                                      <div class="panel-group clearfix" style="margin-bottom:0">
                                        <div class="panel panel-default">
                                          <div class="panel-heading">
                                            <h4 class="panel-title">
                                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_left" style="display: block;">Cột trái</a>
                                            </h4>
                                          </div>
                                          {% if layout_id == 2 %}
                                          <div class="form-group clearfix" style="padding-top:10px;margin-bottom:0">
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <div class="row">
                                                  <div class="col-md-4">
                                                    <b style="color:#f00">Cột trái trang chủ</b>
                                                  </div>
                                                  <div class="col-md-8">
                                                    <label style="padding-left: 20px;color: #f00">
                                                      <input type="radio" name="hide_left" value="N"{% if (layout_config.hide_left == 'N') %} checked{% endif %}> Hiện
                                                    </label>
                                                    <label style="padding-left: 20px;color: #f00">
                                                      <input type="radio" name="hide_left" value="Y"{% if (layout_config.hide_left == 'Y') %} checked{% endif %}> Ẩn
                                                    </label>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="form-group">
                                                <div class="row">
                                                  <div class="col-md-4">
                                                    <b style="color:#f00">Cột trái trang trong</b>
                                                  </div>
                                                  <div class="col-md-8">
                                                    <label style="padding-left: 20px;color: #f00">
                                                      <input type="radio" name="show_left_inner" value="Y"{% if (layout_config.show_left_inner == 'Y') %} checked{% endif %}> Hiện
                                                    </label>
                                                    <label style="padding-left: 20px;color: #f00">
                                                      <input type="radio" name="show_left_inner" value="N"{% if (layout_config.show_left_inner == 'N') %} checked{% endif %}> Ẩn
                                                    </label>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          {% endif %}
                                          <div id="collapse_left" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                              <div id="module_position_left">
                                                <div class="table-responsive mailbox-messages">
                                                  <table class="table table-bordered table-striped table-hover table-newinterface">
                                                    <thead>
                                                      <tr>
                                                        <th>Tên</th>
                                                        <th style="width:10%;" class="text-center">STT</th>
                                                        <th width="10%" class="text-center">A/H</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                      {% for j in position_module_array['left'] %}
                                                      <tr>
                                                        <td>{{ j['module_name'] }}
                                                          {% if j['url'] != '' %}
                                                          <a href="/{{ j['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                          {% endif %}
                                                        </td>

                                                        <td class="text-center">
                                                          <input type="text" name="sort_module_{{ key }}_{{ layout_id }}[{{ j['module_id'] }}]" id="sort_{{ j['id'] }}" class="form-control" value="{% if j['sort'] != '' %}{{ j['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                        </td>
                                                        <td align="center">
                                                          <input type="checkbox" name="active_module_{{ key }}_{{ layout_id }}[{{ j['module_id'] }}]" value="Y" {% if j['active'] == 'Y' %}checked{% endif %}>
                                                        </td>
                                                      </tr>
                                                      {% if j['child'] is defined %}
                                                      {% for k in j['child'] %}
                                                      <tr>
                                                        <td>|---{{ k['module_name'] }}
                                                          {% if k['url'] != '' %}
                                                          <a href="/{{ k['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                          {% endif %}
                                                        </td>
                                                        <td class="text-center">
                                                          <input type="text" name="sort_module_{{ key }}_{{ layout_id }}[{{ k['module_id'] }}]" id="sort_child_{{ k['module_id'] }}" class="form-control" value="{% if k['sort'] != '' %}{{ k['sort'] }}{% else %}1{% endif %}" style="margin-right:5px;display: inline-block">
                                                        </td>
                                                        <td align="center">
                                                          <input type="checkbox" name="active_module_{{ key }}_{{ layout_id }}[{{ k['module_id'] }}]" value="Y" {% if j['active'] == 'Y' AND k['active'] == 'Y' %}checked{% endif %}>
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
                                <div class="col-md-4" style="padding-right:0">
                                  {% set key = 'center' %}
                                  <div class="panel panel-default">                            
                                    <div class="panel-body">
                                      <div class="panel-group clearfix" style="margin-bottom:0">
                                        <div class="panel panel-default">
                                          <div class="panel-heading">
                                            <h4 class="panel-title">
                                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_center" style="display: block;">Cột giữa</a>
                                            </h4>
                                          </div>
                                          <div id="collapse_center" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                              <div id="module_position_center">
                                                <div class="table-responsive mailbox-messages">
                                                  <table class="table table-bordered table-striped table-hover table-newinterface">
                                                    <thead>
                                                      <tr>
                                                        <th>Tên</th>
                                                        <th style="width:20%;" class="text-center">STT</th>
                                                        <th width="15%" class="text-center">A/H</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                      {% for j in position_module_array['center'] %}
                                                      <tr>
                                                        <td>{{ j['module_name'] }}
                                                          {% if j['url'] != '' %}
                                                          <a href="/{{ j['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                          {% endif %}
                                                        </td>

                                                        <td class="text-center">
                                                          <input type="text" name="sort_module_{{ key }}_{{ layout_id }}[{{ j['module_id'] }}]" id="sort_{{ j['id'] }}" class="form-control" value="{% if j['sort'] != '' %}{{ j['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                        </td>
                                                        <td align="center">
                                                          <input type="checkbox" name="active_module_{{ key }}_{{ layout_id }}[{{ j['module_id'] }}]" value="Y" {% if j['active'] == 'Y' %}checked{% endif %}>
                                                        </td>
                                                      </tr>
                                                      {% if j['child'] is defined %}
                                                      {% for k in j['child'] %}
                                                      <tr>
                                                        <td>|---{{ k['module_name'] }}
                                                          {% if k['url'] != '' %}
                                                          <a href="/{{ k['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                          {% endif %}
                                                        </td>
                                                        <td class="text-center">
                                                          <input type="text" name="sort_module_{{ key }}_{{ layout_id }}[{{ k['module_id'] }}]" id="sort_child_{{ k['module_id'] }}" class="form-control" value="{% if k['sort'] != '' %}{{ k['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                        </td>
                                                        <td align="center">
                                                          <input type="checkbox" name="active_module_{{ key }}_{{ layout_id }}[{{ k['module_id'] }}]" value="Y" {% if j['active'] == 'Y' AND k['active'] == 'Y' %}checked{% endif %}>
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
                                <div class="col-md-4">
                                  {% set key = 'right' %}
                                  <div class="panel panel-default">                            
                                    <div class="panel-body">
                                      <div class="panel-group clearfix" style="margin-bottom:0">
                                        <div class="panel panel-default">
                                          <div class="panel-heading">
                                            <h4 class="panel-title">
                                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_right" style="display: block;">Cột phải</a>
                                            </h4>
                                          </div>
                                          {% if layout_id == 2 %}
                                          <div class="form-group clearfix" style="padding-top:10px;margin-bottom:0">
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <div class="row">
                                                  <div class="col-md-4">
                                                    <b style="color:#f00">Cột phải trang chủ</b>
                                                  </div>
                                                  <div class="col-md-8">
                                                    <label style="padding-left: 20px;color: #f00">
                                                      <input type="radio" name="hide_right" value="N"{% if (layout_config.hide_right == 'N') %} checked{% endif %}> Hiện
                                                    </label>
                                                    <label style="padding-left: 20px;color: #f00">
                                                      <input type="radio" name="hide_right" value="Y"{% if (layout_config.hide_right == 'Y') %} checked{% endif %}> Ẩn
                                                    </label>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="form-group">
                                                <div class="row">
                                                  <div class="col-md-4">
                                                    <b style="color:#f00">Cột phải trang trong</b>
                                                  </div>
                                                  <div class="col-md-8">
                                                    <label style="padding-left: 20px;color: #f00">
                                                      <input type="radio" name="show_right_inner" value="Y"{% if (layout_config.show_right_inner == 'Y') %} checked{% endif %}> Hiện
                                                    </label>
                                                    <label style="padding-left: 20px;color: #f00">
                                                      <input type="radio" name="show_right_inner" value="N"{% if (layout_config.show_right_inner == 'N') %} checked{% endif %}> Ẩn
                                                    </label>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          {% endif %}
                                          <div id="collapse_right" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                              <div id="module_position_right">
                                                <div class="table-responsive mailbox-messages">
                                                  <table class="table table-bordered table-striped table-hover table-newinterface">
                                                    <thead>
                                                      <tr>
                                                        <th>Tên</th>
                                                        <th style="width:20%;" class="text-center">STT</th>
                                                        <th width="15%" class="text-center">A/H</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                      {% for j in position_module_array['right'] %}
                                                      <tr>
                                                        <td>{{ j['module_name'] }}
                                                          {% if j['url'] != '' %}
                                                          <a href="/{{ j['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                          {% endif %}
                                                        </td>

                                                        <td class="text-center">
                                                          <input type="text" name="sort_module_{{ key }}_{{ layout_id }}[{{ j['module_id'] }}]" id="sort_{{ j['id'] }}" class="form-control" value="{% if j['sort'] != '' %}{{ j['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                        </td>
                                                        <td align="center">
                                                          <input type="checkbox" name="active_module_{{ key }}_{{ layout_id }}[{{ j['module_id'] }}]" value="Y" {% if j['active'] == 'Y' %}checked{% endif %}>
                                                        </td>
                                                      </tr>
                                                      {% if j['child'] is defined %}
                                                      {% for k in j['child'] %}
                                                      <tr>
                                                        <td>|---{{ k['module_name'] }}
                                                          {% if k['url'] != '' %}
                                                          <a href="/{{ k['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                          {% endif %}
                                                        </td>
                                                        <td class="text-center">
                                                          <input type="text" name="sort_module_{{ key }}_{{ layout_id }}[{{ k['module_id'] }}]" id="sort_child_{{ k['module_id'] }}" class="form-control" value="{% if k['sort'] != '' %}{{ k['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                        </td>
                                                        <td align="center">
                                                          <input type="checkbox" name="active_module_{{ key }}_{{ layout_id }}[{{ k['module_id'] }}]" value="Y" {% if j['active'] == 'Y' AND k['active'] == 'Y' %}checked{% endif %}>
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
                            <div class="box_new_interface_footer">
                              {% set key = 'footer' %}
                              <div class="panel panel-default">                            
                                <div class="panel-body">
                                  <div class="panel-group clearfix">
                                    <div class="panel panel-default">
                                      <div class="panel-heading">
                                        <h4 class="panel-title">
                                          <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse_footer" style="display: block;">Footer</a>
                                        </h4>
                                      </div>
                                      {% if layout_id == 2 %}
                                      <div class="form-group clearfix" id="show_hide_footer">
                                        <label>
                                          <input type="radio" name="hide_footer" value="N"{% if (layout_config.hide_footer == 'N') %} checked{% endif %}> Hiện
                                        </label>
                                        <label>
                                          <input type="radio" name="hide_footer" value="Y"{% if (layout_config.hide_footer == 'Y') %} checked{% endif %}> Ẩn
                                        </label>
                                      </div>
                                      {% endif %}
                                      <div id="collapse_footer" class="panel-collapse collapse">
                                        <div class="panel-body">
                                          <div id="module_position_footer">
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
                                                  {% for j in position_module_array['footer'] %}
                                                  <tr>
                                                    <td>{{ j['module_name'] }}
                                                      {% if j['url'] != '' %}
                                                      <a href="/{{ j['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                      {% endif %}
                                                    </td>

                                                    <td class="text-center">
                                                      <input type="text" name="sort_module_{{ key }}_{{ layout_id }}[{{ j['module_id'] }}]" id="sort_{{ j['id'] }}" class="form-control" value="{% if j['sort'] != '' %}{{ j['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                    </td>
                                                    <td align="center">
                                                      <input type="checkbox" name="active_module_{{ key }}_{{ layout_id }}[{{ j['module_id'] }}]" value="Y" {% if j['active'] == 'Y' %}checked{% endif %}>
                                                    </td>
                                                  </tr>
                                                  {% if j['child'] is defined %}
                                                  {% for k in j['child'] %}
                                                  <tr>
                                                    <td>|---{{ k['module_name'] }}
                                                      {% if k['url'] != '' %}
                                                      <a href="/{{ k['url'] }}" style="color:#f00;font-weight:700">| Sửa</a>
                                                      {% endif %}
                                                    </td>
                                                    <td class="text-center">
                                                      <input type="text" name="sort_module_{{ key }}_{{ layout_id }}[{{ k['module_id'] }}]" id="sort_child_{{ k['module_id'] }}" class="form-control" value="{% if k['sort'] != '' %}{{ k['sort'] }}{% else %}1{% endif %}" style="display: inline-block">
                                                    </td>
                                                    <td align="center">
                                                      <input type="checkbox" name="active_module_{{ key }}_{{ layout_id }}[{{ k['module_id'] }}]" value="Y" {% if j['active'] == 'Y' AND k['active'] == 'Y' %}checked{% endif %}>
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

                          <div class="row">
                            <div class="col-md-12">
                              <div class="panel panel-default" id="background" style="margin-bottom:0">
                                <div class="panel-heading">Tùy chỉnh giao diện Layout {{ layout_id }}</div>
                                <div class="panel-body">

                                  <legend class="text-danger" style="font-size: 18px">Font chữ</legend>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="radio">
                                        <label>
                                          <input type="radio" name="font_web" value="Arial" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Arial') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Arial', sans-serif;margin-left: 7px">Arial</span>
                                        </label>
                                      </div>
                                      <div class="radio">
                                        <label>
                                          <input type="radio" name="font_web" value="Tahoma" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Tahoma') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Tahoma', sans-serif;margin-left: 7px">Tahoma</span>
                                        </label>
                                      </div>
                                      <div class="radio">
                                        <label>
                                          <input type="radio" name="font_web" value="Times New Roman" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Times New Roman') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Times New Roman', sans-serif;margin-left: 7px">Times New Roman</span>
                                        </label>
                                      </div>
                                      <div class="radio">
                                        <label>
                                          <input type="radio" name="font_web" value="Roboto" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Roboto') %}checked{% elseif css_item == '' %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Roboto', sans-serif;margin-left: 7px">Roboto</span>
                                        </label>
                                      </div>
                                      <div class="radio">
                                        <label>
                                          <input type="radio" name="font_web" value="Roboto Condensed" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Roboto Condensed') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Roboto Condensed', sans-serif;margin-left: 7px">Roboto Condensed</span>
                                        </label>
                                      </div>
                                      <div class="radio">
                                        <label>
                                          <input type="radio" name="font_web" value="Open Sans" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Open Sans') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Open Sans', sans-serif;margin-left: 7px">Open Sans</span>
                                        </label>
                                      </div>
                                      <div class="radio">
                                        <label>
                                          <input type="radio" name="font_web" value="Cormorant Upright" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Cormorant Upright') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Cormorant Upright', serif;margin-left: 7px">Cormorant Upright</span>
                                        </label>
                                      </div>
                                      <div class="radio">
                                        <label>
                                          <input type="radio" name="font_web" value="Chakra Petch" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Chakra Petch') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Chakra Petch', serif;margin-left: 7px">Chakra Petch</span>
                                        </label>
                                      </div>

                                    </div>
                                  </div>
                                  <legend class="text-danger" style="font-size: 18px">Background</legend>
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="color">Màu nền</label>
                                        <input type="text" name="color" class="form-control my-colorpicker1 colorpicker-element" placeholder="Màu nền" value="{% if background != "" %}{{ background.color }}{% endif %}">
                                      </div>
                                      <div class="form-group">
                                        <ul class="list_color_default">
                                          {% for i in list_color %}
                                          <li style="display:inline-block">
                                            <a href="javascript:;" data-color="{{ i }}" class="set_bgr_color{% if background.color == i %} active{% endif %}" style="display:inline-block;width:20px;height:20px;background:{{ i }}"></a>
                                          </li>
                                          {% endfor %}
                                        </ul>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="color">Màu chữ</label>
                                        <input type="text" name="text_color" class="form-control my-colorpicker1 colorpicker-element" placeholder="Màu chữ" value="{% if background != "" %}{{ background.text_color }}{% endif %}">
                                      </div>
                                      <div class="form-group">
                                        <ul class="list_color_default">
                                          {% for i in list_color %}
                                          <li style="display:inline-block">
                                            <a href="javascript:;" data-color="{{ i }}" class="set_bgr_text_color{% if background.text_color == i %} active{% endif %}" style="display:inline-block;width:20px;height:20px;background:{{ i }}"></a>
                                          </li>
                                          {% endfor %}
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label for="file">Hình nền</label>
                                        <input type="file" id="photo" name="photo">
                                        <p></p>
                                        <label class="text-danger">- Ghi chú: Kích thước ảnh upload tối đa là: 500kb</label>
                                        {% if background != "" and background.photo != '' %}
                                        <div id="imageBackgorund">
                                          <div class="form-group">{{ image('files/default/' ~ SUB_FOLDER ~ '/' ~ background.photo , 'width':'150', 'style':'margin-top:10px') }}
                                          </div>
                                          <div class="checkbox">
                                            <label>
                                              <input type="checkbox" name="delete_background" value="1" class="form-control" /> Xóa hình nền
                                            </label>
                                          </div>
                                                                    {#
                                                                    <a href="javascript:;" class="deletebackgroundPhoto" data-layout="{{ layout_id }}" data-id="{{ background.id }}"><i class="fa fa-times"></i><p class="text-danger">Xóa hình</p></a>
                                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/deletebackgroundphoto/' ~ layout_id ~ '/' ~ background.id, '<i class="fa fa-times"></i><p class="text-danger">Xóa hình</p>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}#}
                                                                  </div>
                                                                  {% endif %}
                                                                </div>
                                                              </div>
                                                              <div class="col-md-9">
                                                                <div style="margin-top:30px">
                                                                  <label for="file" class="text-danger" style="color:#f00">Chọn hình nền có sẵn</label>
                                                                  <input type="hidden" name="brg_select">
                                                                  <nav class="bgr_select">
                                                                    {% for i in 1..10 %}
                                                                    {% set brg = 'assets/source/background/' ~ 'background-' ~ i ~ '.jpg' %}
                                                                    <a href="javascript:;" data-bgr="{{ 'background-' ~ i ~ '.jpg' }}" {% if background != '' and background.photo == 'background-' ~ i ~ '.jpg' %} class="active"{% endif %}>
                                                                      {{ image(brgDefaults[i - 1], 'style':'width:70px;height:70px') }}
                                                                    </a>
                                                                    {% endfor %}
                                                                  </nav>
                                                                </div>
                                                              </div>
                                                            </div>
                                                            <div class="row">
                                                              <div class="col-md-12">
                                                                <div class="form-group">
                                                                  <label>Css hình nền</label>
                                                                  <p class="text-primary"><strong>- Lặp:</strong></p>
                                                                  <div class="row">
                                                                    <div class="col-md-3">
                                                                      <input type="radio" name="type" value="no-repeat"{% if background != "" and background.type == "no-repeat" %} checked{% endif %}><span style="margin-left: 5px">Không lặp(no-repeat)</span>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                      <input type="radio" name="type" value="repeat"{% if background != "" and background.type == "repeat" %} checked{% elseif !background %} checked{% endif %}><span style="margin-left: 5px">Lặp(repeat)</span>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                      <input type="radio" name="type" value="repeat-x"{% if background != "" and background.type == "repeat-x" %} checked{% endif %}><span style="margin-left: 5px">Lặp chiều ngang(repeat-x)</span>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                      <input type="radio" name="type" value="repeat-y"{% if background != "" and background.type == "repeat-y" %} checked{% endif %}><span style="margin-left: 5px">Lặp chiều dọc(repeat-y)</span>
                                                                    </div>
                                                                  </div>
                                                                  <p class="text-primary"><strong>- Cố định:</strong></p>
                                                                  <div class="row">
                                                                    <div class="col-md-3">
                                                                      <input type="radio" name="attachment" value="fixed"{% if background and background.attachment == "fixed" %} checked{% elseif !background %} checked{% endif %}><span style="margin-left: 5px">Có(fixed)</span>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                      <input type="radio" name="attachment" value="initial"{% if background and background.attachment == "initial" %} checked{% endif %}><span style="margin-left: 5px">Không(initial)</span>
                                                                    </div>
                                                                  </div>
                                                                </div>

                                                                <div class="form-group">
                                                                  <label for="active">Hiển thị hình nền</label>
                                                                  <select id="bgr_active" name="bgr_active" class="form-control" style="width:90px">
                                                                    <option {% if background != '' and background.active == "Y" %}selected="selected"{% endif %}value="Y">Có</option>
                                                                    <option {% if background != '' and background.active == "N" %}selected="selected"{% endif %}value="N">Không</option>
                                                                  </select>
                                                                </div>
                                                              </div>
                                                            </div>
                                                            <legend class="text-primary" style="font-size: 18px">Màu chủ đạo</legend>
                                                            <input type="hidden" value="1" name="enable_css">
                                                            <div class="row form-group">
                                                              <div class="col-md-12">
                                                                <label style="padding-right: 20px">
                                                                  <input type="radio" name="enable_color" value="1"{% if (layout_config.enable_color == 1) %} checked{% endif %}> Sử dụng màu chỉnh sửa
                                                                </label>
                                                                <label>
                                                                  <input type="radio" name="enable_color" value="0"{% if (layout_config.enable_color == 0) %} checked{% endif %}> Sử dụng màu mặc định
                                                                </label>
                                                              </div>
                                                            </div>
                                                            <div class="row form-group">
                                                              <div class="col-md-3">
                                                                <label for="color">Màu nền khung website</label>
                                                                <input type="text" name="container" class="form-control my-colorpicker1 colorpicker-element" placeholder="Màu nền khung website" {% if(css_item != "" and css_item.container is defined) %}value="{{ css_item.container }}"{% endif %}>
                                                                <div style="margin-top: 15px">
                                                                  <ul class="list_color_default">
                                                                    {% for i in list_color %}
                                                                    <li style="display:inline-block">
                                                                      <a href="javascript:;" data-color="{{ i }}" class="set_bgr_container{% if css_item.container is defined and css_item.container == i %} active{% endif %}" style="display:inline-block;width:20px;height:20px;background:{{ i }}"></a>
                                                                    </li>
                                                                    {% endfor %}
                                                                  </ul>
                                                                </div>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <label for="color">Màu nền thanh menu</label>
                                                                <input type="text" name="bar_web_bgr" class="form-control my-colorpicker1 colorpicker-element" placeholder="Màu nền" {% if(css_item != "") %}value="{{ css_item.bar_web_bgr }}"{% endif %}>
                                                                <div style="margin-top: 15px">
                                                                  <ul class="list_color_default">
                                                                    {% for i in list_color %}
                                                                    <li style="display:inline-block">
                                                                      <a href="javascript:;" data-color="{{ i }}" class="set_bgr_bar_web_bgr{% if css_item.bar_web_bgr == i %} active{% endif %}" style="display:inline-block;width:20px;height:20px;background:{{ i }}"></a>
                                                                    </li>
                                                                    {% endfor %}
                                                                  </ul>
                                                                </div>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <label for="color">Màu chữ thanh menu</label>
                                                                <input type="text" name="bar_web_color" class="form-control my-colorpicker1 colorpicker-element" placeholder="Màu chữ" {% if(css_item != "") %}value="{{ css_item.bar_web_color }}"{% endif %}>
                                                                <div style="margin-top: 15px">
                                                                  <ul class="list_color_default">
                                                                    {% for i in list_color %}
                                                                    <li style="display:inline-block">
                                                                      <a href="javascript:;" data-color="{{ i }}" class="set_bgr_bar_web_color{% if css_item.bar_web_color == i %} active{% endif %}" style="display:inline-block;width:20px;height:20px;background:{{ i }}"></a>
                                                                    </li>
                                                                    {% endfor %}
                                                                  </ul>
                                                                </div>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <label for="color">Màu chữ thanh menu top</label>
                                                                <input type="text" name="menu_top_color" class="form-control my-colorpicker1 colorpicker-element" placeholder="Màu chữ" {% if css_item != "" and css_item.menu_top_color is defined %}value="{{ css_item.menu_top_color }}"{% endif %}>
                                                                <div style="margin-top: 15px">
                                                                  <ul class="list_color_default">
                                                                    {% for i in list_color %}
                                                                    <li style="display:inline-block">
                                                                      <a href="javascript:;" data-color="{{ i }}" class="set_bgr_menu_top_color{% if css_item.menu_top_color is defined and css_item.menu_top_color == i %} active{% endif %}" style="display:inline-block;width:20px;height:20px;background:{{ i }}"></a>
                                                                    </li>
                                                                    {% endfor %}
                                                                  </ul>
                                                                </div>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <label for="color">Màu chữ chủ đạo</label>
                                                                <input type="text" name="txt_web_color" class="form-control my-colorpicker1 colorpicker-element" placeholder="" {% if(css_item != "") %}value="{{ css_item.txt_web_color }}"{% endif %}>
                                                                <div style="margin-top: 15px">
                                                                  <ul class="list_color_default">
                                                                    {% for i in list_color %}
                                                                    <li style="display:inline-block">
                                                                      <a href="javascript:;" data-color="{{ i }}" class="set_bgr_txt_web_color{% if css_item.txt_web_color == i %} active{% endif %}" style="display:inline-block;width:20px;height:20px;background:{{ i }}"></a>
                                                                    </li>
                                                                    {% endfor %}
                                                                  </ul>
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
        <div class="modal modal fade" id="myModalEditCss" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            {{ form('role':'form','action':'', 'id':'form-edit-css') }}
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sửa css banner</h4>
              </div>
              <div class="modal-body">
                <p>Some text in the modal.</p>
              </div>
              <div class="modal-footer">
                <button type="button" id="save-css" class="btn btn-primary">Lưu</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
            {{ endform() }}

          </div>
        </div>
        <script type="text/javascript">
        $(document).ready(function(){
          $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
          var target = $(e.target).attr("href") // activated tab
          $('input[name=active]').val(target.replace('#', ''));
        });
        })
        </script>
