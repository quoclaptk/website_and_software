{{ partial('partials/content_header') }}
{{ partial('partials/nav_layout') }}
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}


            <div class="panel panel-default" id="background">
                <div class="panel-heading">Background</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color">Màu nền</label>
                                <input type="text" name="color" class="form-control my-colorpicker1 colorpicker-element" placeholder="Hình nền" value="{% if item != "" %}{{ item.color }}{% endif %}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color">Màu chữ</label>
                                <input type="text" name="text_color" class="form-control my-colorpicker1 colorpicker-element" placeholder="Hình nền" value="{% if item != "" %}{{ item.bgr_text_color }}{% endif %}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="file">Hình nền</label>
                                <input type="file" id="photo" name="photo">
                                {% if item != "" and item.bgr_photo != '' %}
                                    {{ image('files/default/' ~ SUB_FOLDER ~ '/' ~ item.bgr_photo , 'width':'150', 'style':'margin-top:10px') }}
                                    <div class="text-center" style="width: 150px">
                                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/deletebackgroundphoto/' ~ item.l.id ~ '/' ~ item.bgr_id ~ '/' ~ page, '<i class="fa fa-times"></i><p class="text-danger">Xóa hình</p>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}
                                    </div>
                                {% endif %}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Css hình nền</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="radio" name="type" value="no-repeat"{% if item != "" and item.type == "no-repeat" %} checked{% else %} checked{% endif %}><span style="margin-left: 5px">Không lặp(no-repeat)</span>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="radio" name="type" value="repeat"{% if item != "" and item.type == "repeat" %} checked{% endif %}><span style="margin-left: 5px">Lặp(repeat)</span>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="radio" name="type" value="repeat-x"{% if item != "" and item.type == "repeat-x" %} checked{% endif %}><span style="margin-left: 5px">Lặp chiều ngang(repeat-x)</span>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="radio" name="type" value="repeat-y"{% if item != "" and item.type == "repeat-y" %} checked{% endif %}><span style="margin-left: 5px">Lặp chiều dọc(repeat-y)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="active">Hiển thị hình nền</label>
                                <select id="bgr_active" name="bgr_active" class="form-control" style="width:90px">
                                    <option {% if item.bgr_active == "Y" %}selected="selected"{% endif %}value="Y">Có</option>
                                    <option {% if item.bgr_active == "N" %}selected="selected"{% endif %}value="N">Không</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Thiết lập</div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        {#<div class="form-group">
                            <label class="col-md-3 text-right">Cho phép</label>
                            <div class="col-md-6">
                                <input type="checkbox" value="1" name="enable_css" class="form-control"{% if (item.enable_css == 1) %} checked{% endif %}>
                            </div>
                        </div>#}
                        <input type="hidden" value="1" name="enable_css">
                        <div class="text-center" style="margin-bottom: 20px">
                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/resetPageCss/' ~ item.l.id ~ '/' ~ item.lc_id , 'Khôi phục thiết lập gốc(bao gồm cả Background)', 'style':'text-decoration:underline' ) }}
                        </div>
                        <legend class="text-primary" style="font-size: 18px">Màu chủ đạo</legend>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label style="padding-right: 20px">
                                    <input type="radio" name="enable_color" value="1" class="minimal"{% if (item.enable_color == 1) %} checked{% endif %}> Sử dụng màu chỉnh sửa
                                </label>
                                <label>
                                    <input type="radio" name="enable_color" value="0" class="minimal"{% if (item.enable_color == 0) %} checked{% endif %}> Sử dụng màu mặc định
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label for="color">Màu nền thanh menu</label>
                                <input type="text" name="bar_web_bgr" class="form-control my-colorpicker1 colorpicker-element" placeholder="Màu nền" {% if(css_item != "") %}value="{{ css_item.bar_web_bgr }}"{% endif %}>
                            </div>
                            <div class="col-md-4">
                                <label for="color">Màu chữ thanh menu</label>
                                <input type="text" name="bar_web_color" class="form-control my-colorpicker1 colorpicker-element" placeholder="Màu chữ" {% if(css_item != "") %}value="{{ css_item.bar_web_color }}"{% endif %}>
                            </div>
                            <div class="col-md-4">
                                <label for="color">Màu chữ chủ đạo</label>
                                <input type="text" name="txt_web_color" class="form-control my-colorpicker1 colorpicker-element" placeholder="" {% if(css_item != "") %}value="{{ css_item.txt_web_color }}"{% endif %}>
                            </div>
                        </div>
                        <legend class="text-primary" style="font-size: 18px">Page</legend>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Page font size</label>
                            <div class="col-md-2">
                                <select name="page_font_size" class="form-control">
                                    <option value="">--- Chọn px ---</option>
                                    {% for i in 1..30 %}
                                    <option value="{{ i }}"{% if(css_item != "" AND css_item.page_font_size == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Chiều rộng page(mặc định full)</label>
                            <div class="col-md-6">
                                <input type="text" name="page_width" {% if(css_item != "") %}value="{{ css_item.page_width }}"{% endif %} class="form-control">
                            </div>
                            <div class="col-md-2" style="color:#f00">(px)</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Chiều rộng bao(mặc định 1170px)</label>
                            <div class="col-md-6">
                                <input type="text" name="page_container_width" {% if(css_item != "") %}value="{{ css_item.page_container_width }}"{% endif %} class="form-control">
                            </div>
                            <div class="col-md-2" style="color:#f00">(px)</div>
                        </div>
                        <legend class="text-primary" style="font-size: 18px">Header</legend>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Màu nền</label>
                            <div class="col-md-6">
                                <input type="text" name="header_background" {% if(css_item != "") %}value="{{ css_item.header_background }}"{% endif %} class="form-control my-colorpicker1 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Màu chữ</label>
                            <div class="col-md-6">
                                <input type="text" name="header_color" {% if(css_item != "") %}value="{{ css_item.header_color }}"{% endif %} class="form-control my-colorpicker1 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Cỡ chữ (px)</label>
                            <div class="col-md-3">
                                <select name="header_font_size" class="form-control">
                                    <option value="">--- Chọn px ---</option>
                                    {% for i in 1..30 %}
                                    <option value="{{ i }}" {% if(css_item != "" AND css_item.header_font_size == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Margin (px)</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="header_margin_top" class="form-control">
                                            <option value="">Top</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.header_margin_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="header_margin_bottom" class="form-control">
                                            <option value="">Bottom</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.header_margin_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="header_margin_left" class="form-control">
                                            <option value="">Left</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.header_margin_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="header_margin_right" class="form-control">
                                            <option value="">Right</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.header_margin_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Padding (px)</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="header_padding_top" class="form-control">
                                            <option value="">Top</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.header_padding_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="header_padding_bottom" class="form-control">
                                            <option value="">Bottom</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.header_padding_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="header_padding_left" class="form-control">
                                            <option value="">Left</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.header_padding_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="header_padding_right" class="form-control">
                                            <option value="">Right</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.header_padding_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <legend class="text-primary" style="font-size: 18px">Cột trái</legend>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Màu nền</label>
                            <div class="col-md-6">
                                <input type="text" name="left_background" {% if(css_item != "") %}value="{{ css_item.left_background }}"{% endif %} class="form-control my-colorpicker1 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Màu chữ</label>
                            <div class="col-md-6">
                                <input type="text" name="left_color" {% if(css_item != "") %}value="{{ css_item.left_color }}"{% endif %} class="form-control my-colorpicker1 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Cỡ chữ (px)</label>
                            <div class="col-md-3">
                                <select name="left_font_size" class="form-control">
                                    <option value="">--- Chọn px ---</option>
                                    {% for i in 1..30 %}
                                    <option value="{{ i }}"{% if(css_item != "" AND css_item.left_font_size == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Margin (px)</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="left_margin_top" class="form-control">
                                            <option value="">Top</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.left_margin_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="left_margin_bottom" class="form-control">
                                            <option value="">Bottom</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.left_margin_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="left_margin_left" class="form-control">
                                            <option value="">Left</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.left_margin_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="left_margin_right" class="form-control">
                                            <option value="">Right</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.left_margin_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Padding (px)</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="left_padding_top" class="form-control">
                                            <option value="">Top</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.left_padding_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="left_padding_bottom" class="form-control">
                                            <option value="">Bottom</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.left_padding_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="left_padding_left" class="form-control">
                                            <option value="">Left</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.left_padding_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="left_padding_right" class="form-control">
                                            <option value="">Right</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.left_padding_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <legend class="text-primary" style="font-size: 18px">Cột phải</legend>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Màu nền</label>
                            <div class="col-md-6">
                                <input type="text" name="right_background" {% if(css_item != "") %}value="{{ css_item.right_background }}"{% endif %} class="form-control my-colorpicker1 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Màu chữ</label>
                            <div class="col-md-6">
                                <input type="text" name="right_color" {% if(css_item != "") %}value="{{ css_item.right_color }}"{% endif %} class="form-control my-colorpicker1 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Cỡ chữ (px)</label>
                            <div class="col-md-3">
                                <select name="right_font_size" class="form-control">
                                    <option value="">--- Chọn px ---</option>
                                    {% for i in 1..30 %}
                                    <option value="{{ i }}"{% if(css_item != "" AND css_item.right_font_size == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Margin (px)</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="right_margin_top" class="form-control">
                                            <option value="">Top</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.right_margin_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="right_margin_bottom" class="form-control">
                                            <option value="">Bottom</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.right_margin_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="right_margin_left" class="form-control">
                                            <option value="">Left</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.right_margin_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="right_margin_right" class="form-control">
                                            <option value="">Right</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.right_margin_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Padding (px)</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="right_padding_top" class="form-control">
                                            <option value="">Top</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.right_padding_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="right_padding_bottom" class="form-control">
                                            <option value="">Bottom</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.right_padding_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="right_padding_left" class="form-control">
                                            <option value="">Left</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.right_padding_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="right_padding_right" class="form-control">
                                            <option value="">Right</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.right_padding_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <legend class="text-primary" style="font-size: 18px">Cột giữa</legend>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Màu nền</label>
                            <div class="col-md-6">
                                <input type="text" name="content_background" {% if(css_item != "") %}value="{{ css_item.content_background }}"{% endif %} class="form-control my-colorpicker1 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Màu chữ</label>
                            <div class="col-md-6">
                                <input type="text" name="content_color" {% if(css_item != "") %}value="{{ css_item.content_color }}"{% endif %} class="form-control my-colorpicker1 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Cỡ chữ (px)</label>
                            <div class="col-md-3">
                                <select name="content_font_size" class="form-control">
                                    <option value="">--- Chọn px ---</option>
                                    {% for i in 1..30 %}
                                    <option value="{{ i }}"{% if(css_item != "" AND css_item.content_font_size == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Margin (px)</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="content_margin_top" class="form-control">
                                            <option value="">Top</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.content_margin_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="content_margin_bottom" class="form-control">
                                            <option value="">Bottom</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.content_margin_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="content_margin_left" class="form-control">
                                            <option value="">Left</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.content_margin_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="content_margin_right" class="form-control">
                                            <option value="">Right</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.content_margin_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Padding (px)</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="content_padding_top" class="form-control">
                                            <option value="">Top</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.content_padding_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="content_padding_bottom" class="form-control">
                                            <option value="">Bottom</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.content_padding_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="content_padding_left" class="form-control">
                                            <option value="">Left</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.content_padding_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="content_padding_right" class="form-control">
                                            <option value="">Right</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.content_padding_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <legend class="text-primary" style="font-size: 18px">Footer</legend>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Màu nền</label>
                            <div class="col-md-6">
                                <input type="text" name="footer_background" {% if(css_item != "") %}value="{{ css_item.footer_background }}"{% endif %} class="form-control my-colorpicker1 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Màu chữ</label>
                            <div class="col-md-6">
                                <input type="text" name="footer_color" {% if(css_item != "") %}value="{{ css_item.footer_color }}"{% endif %} class="form-control my-colorpicker1 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Cỡ chữ (px)</label>
                            <div class="col-md-3">
                                <select name="footer_font_size" class="form-control">
                                    <option value="">--- Chọn px ---</option>
                                    {% for i in 1..30 %}
                                    <option value="{{ i }}"{% if(css_item != "" AND css_item.footer_font_size == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Margin (px)</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="footer_margin_top" class="form-control">
                                            <option value="">Top</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.footer_margin_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="footer_margin_bottom" class="form-control">
                                            <option value="">Bottom</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.footer_margin_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="footer_margin_left" class="form-control">
                                            <option value="">Left</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.footer_margin_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="footer_margin_right" class="form-control">
                                            <option value="">Right</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.footer_margin_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Padding (px)</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="footer_padding_top" class="form-control">
                                            <option value="">Top</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.footer_padding_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="footer_padding_bottom" class="form-control">
                                            <option value="">Bottom</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.footer_padding_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="footer_padding_left" class="form-control">
                                            <option value="">Left</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.footer_padding_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="footer_padding_right" class="form-control">
                                            <option value="">Right</option>
                                            {% for i in 1..30 %}
                                            <option value="{{ i }}"{% if(css_item != "" AND css_item.footer_padding_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ submit_button("Lưu & Đóng", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}
                {{ link_to(ACP_NAME ~ "/" ~ ACP_NAME ~ "/setting", "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
