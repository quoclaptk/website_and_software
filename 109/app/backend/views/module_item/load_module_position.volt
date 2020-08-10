<div class="panel-group clearfix">
    {% for key,value in position_module_array %}
        <div class="col-md-12">
            <div class="panel panel-default" style="margin-bottom: 20px">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ key }}" style="display: block;">
                            {% if key == 'header' %}Header{% elseif key == 'left' %}Cột trái{% elseif key == 'right' %}Cột phải{% elseif key == 'center' %}Cột giữa{% else %}Footer{% endif %} Layout {{ layout_id }}
                        </a>
                    </h4>
                </div>
                <div id="collapse_{{ key }}" class="panel-collapse collapse in{#{% if key == 'header' %}in{% endif %}#}">
                    <div class="panel-body">
                        <div class="col-md-8">
                            <div id="module_position_{{ key }}">
                                <div class="table-responsive mailbox-messages">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Tên</th>
                                            <th width="10%">Sửa</th>
                                            <th style="width:20%;" class="text-center">Thứ tự</th>
                                            {#<th width="10%">Ẩn/Hiện</th>#}
                                            <th width="10%" class="text-center">Xóa</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {% for j in position_module_array[key] %}
                                            <tr>
                                                <td>{{ j['module_name'] }}</td>
                                                <td class="text-center">{% if j['url'] != '' %}{{ link_to(j['url'], '<i class="fa fa-edit"></i>' ) }}{% endif %}</td>
                                                <td class="text-center">
                                                    <input type="text" name="sort_module_key_{{ key }}[]" id="sort_{{ j['id'] }}" class="form-control" value="{{ j['sort'] }}" style="width:70px;margin-right:5px;display: inline-block">
                                                </td>
                                                {#<td align="center">
                                                    <input type="checkbox" name="input_module_key_{{ key }}[]" value="{{ j['id'] }}" {% if j['active'] == 'Y' %}checked{% endif %}>
                                                </td>#}
                                                <td align="center">
                                                    <input type="checkbox" name="input_module_key_{{ key }}[]" value="{{ j['id'] }}">
                                                </td>
                                            </tr>
                                            {% if j['child'] != '' %}
                                                {% for k in j['child'] %}
                                                    <tr>
                                                        <td>|--- {{ k['module_name'] }}</td>
                                                        <td class="text-center">{{ link_to(ACP_NAME ~ '/module_item/update/' ~ k['module_type'], '<i class="fa fa-edit"></i>' ) }}</td>
                                                        <td class="text-center">
                                                            <input type="text" name="sort" id="sort_{{ k['id'] }}" class="form-control" value="{{ k['sort'] }}" style="width:70px;margin-right:5px;display: inline-block">
                                                            <a data-id="{{ k['id'] }}" onclick="update_layout_module_sort({{ k['id']}})" href="javascript:void(0)" style="display: inline-block"><i class="fa fa-refresh"></i></a>
                                                        </td>

                                                        <td align="center">
                                                            {% if k['active'] == 'Y' %}
                                                                <a href="javascript:;" data-layout="{{ layout_id }}" data-id="{{ k['id'] }}" class="tmp_layout_hide"><i class="fa fa-check-square-o fa-lg"></i></a>
                                                            {% else %}
                                                                <a href="javascript:;" data-layout="{{ layout_id }}" data-id="{{ k['id'] }}" class="tmp_layout_show"><i class="fa fa-square-o fa-lg"></i></a>
                                                            {% endif %}
                                                        </td>
                                                    </tr>
                                                {% endfor %}
                                            {% endif %}
                                        {% endfor %}
                                        </tbody>
                                    </table>
                                </div>
                                <p></p>
                                <div class="clearfix">
                                    <div class="pull-right" style="margin-left: 10px">
                                        <a href="javascript:;" data-layout="{{ layout_id }}" data-position="{{ key }}" class="btn btn-primary btn-delete-position">Xóa</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="javascript:;" data-layout="{{ layout_id }}" data-position="{{ key }}" class="btn btn-primary btn-save-position">Cập nhật thứ tự</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="list" style="max-height: 460px;overflow: auto">
                                {% for i in module_array[key] %}
                                <li class="form-group">
                                    <label>
                                        <input type="checkbox" name="module_{{ key }}[]" value="{{ i['id'] }}" class="minimal module_checkbox_{{ key }}"><span style="padding-left: 10px;font-weight: normal">{{ i['name'] }}</span>
                                    </label>
                                </li>
                                {% endfor %}
                            </ul>
                            <span class="add-to-menu">
                                <a href="javascript:;" class="btn btn-primary button-secondary add-to-module" data-layout="{{ layout_id }}" data-position="{{ key }}">Thêm module</a>
                                <span class="spinner"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {% endfor %}
    </div>
    <div class="panel-group">
        <div class="col-md-12">
            <div class="panel panel-default" id="background">
                <div class="panel-heading">Tùy chỉnh giao diện Layout {{ layout_id }}</div>
                <div class="panel-body">
                    <div class="text-center">
                        {{ link_to(ACP_NAME ~ '/layout/css/' ~ layout_id , 'Chỉnh CSS giao diện', 'target':'blank', 'class':'text-success', 'style':'text-decoration:underline;color:#f00' ) }}
                    </div>
                    <div class="text-center" style="margin-bottom: 20px">
                        <a href="javascript:;" id="resetPageCss" data-layout="{{ layout_id }}" data-id="{{ layout_config.id }}" style="text-decoration: underline;">Khôi phục thiết lập gốc</a>
                        {#{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/resetPageCss/' ~ item.l.id ~ '/' ~ item.lc_id , 'Khôi phục thiết lập gốc(bao gồm cả Background)', 'style':'text-decoration:underline' ) }}#}
                    </div>
                    <legend class="text-primary" style="font-size: 18px">Font chữ</legend>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="font_web" value="Arial" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Arial') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Arial', sans-serif;">Arial</span>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="font_web" value="Tahoma" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Tahoma') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Tahoma', sans-serif;">Tahoma</span>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="font_web" value="Times New Roman" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Times New Roman') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Times New Roman', sans-serif;">Times New Roman</span>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="font_web" value="Roboto" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Roboto') %}checked{% else %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Roboto', sans-serif;">Roboto</span>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="font_web" value="Roboto Condensed" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Roboto Condensed') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Roboto Condensed', sans-serif;">Roboto Condensed</span>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="font_web" value="Open Sans" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Open Sans') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Open Sans', sans-serif;">Open Sans</span>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="font_web" value="Cormorant Upright" {% if(css_item != "" and css_item.font_web is defined and css_item.font_web == 'Cormorant Upright') %}checked{% endif %}><span style="vertical-align: middle;font-family: 'Cormorant Upright', serif;">Cormorant Upright</span>
                                </label>
                            </div>
                            
                        </div>
                    </div>
                    <legend class="text-primary" style="font-size: 18px">Background</legend>
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
                                {% if background != "" and background.photo != '' %}
                                    {{ image('files/default/' ~ SUB_FOLDER ~ '/' ~ background.photo , 'width':'150', 'style':'margin-top:10px') }}
                                    <div class="text-center" style="width: 150px">
                                        <a href="javascript:;" id="deleteBackgroundPhoto" data-layout="{{ layout_id }}" data-id="{{ background.id }}"><i class="fa fa-times"></i><p class="text-danger">Xóa hình</p></a>
                                        {#{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/deletebackgroundphoto/' ~ layout_id ~ '/' ~ background.id, '<i class="fa fa-times"></i><p class="text-danger">Xóa hình</p>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}#}
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
                                        {{ image(brg, 'style':'width:70px;height:70px') }}
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
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="radio" name="type" value="no-repeat"{% if background != "" and background.type == "no-repeat" %} checked{% endif %}><span style="margin-left: 5px">Không lặp(no-repeat)</span>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="radio" name="type" value="repeat"{% if background != "" and background.type == "repeat" %} checked{% else %} checked{% endif %}><span style="margin-left: 5px">Lặp(repeat)</span>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="radio" name="type" value="repeat-x"{% if background != "" and background.type == "repeat-x" %} checked{% endif %}><span style="margin-left: 5px">Lặp chiều ngang(repeat-x)</span>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="radio" name="type" value="repeat-y"{% if background != "" and background.type == "repeat-y" %} checked{% endif %}><span style="margin-left: 5px">Lặp chiều dọc(repeat-y)</span>
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
                                <input type="radio" name="enable_color" value="1" class="minimal"{% if (layout_config.enable_color == 1) %} checked{% endif %}> Sử dụng màu chỉnh sửa
                            </label>
                            <label>
                                <input type="radio" name="enable_color" value="0" class="minimal"{% if (layout_config.enable_color == 0) %} checked{% endif %}> Sử dụng màu mặc định
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