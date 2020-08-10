{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flash.output() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed', 'name':'form_multilang') }}
            {{ form.render("id") }}

            {% if tmpSubdomainLanguages|length > 0 %}
            <ul id="langTab" class="nav nav-tabs">
                <li class="active">
                    <a href="#vi" data-toggle="tab" class="btn btn-info">Việt Nam</a>
                </li>
                {% for tmp in tmpSubdomainLanguages %}
                {% if tmp.language.code != 'vi' %}
                    <li>
                        <a href="#{{ tmp.language.code }}" data-toggle="tab" class="btn btn-info">{{ tmp.language.name }}</a>
                    </li>
                {% endif %}
                {% endfor %}
            </ul>
            {% endif %}
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade in active" id="vi">
                    <div class="panel panel-default">
                        <div class="panel-heading">{{ title_bar }}</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="name">Tên<span class="text-danger">(*)</span></label>
                                        {{ form.render("name",{'class':'form-control set_url','id':'name'}) }}
                                        {{ form.messages('name') }}
                                    </div>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-2"><label for="banner">Kiểu icon</label></div>
                                        <div class="col-md-8">
                                            <label>
                                                <input type="radio" name="icon_type" value="1" {% if item is defined and item.icon_type == 1 %}checked{% else %}checked{% endif %}> Font icon
                                            </label>
                                            <label>
                                                <input type="radio" name="icon_type" value="2" {% if item is defined and item.icon_type == 2 %}checked{% endif %}> Hình ảnh
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <div class="col-md-2"><label for="name">Font icon (<a href="https://fontawesome.com/v4.7.0/icons/" target="_blank">Chọn font</a>)</label></div>
                                        <div class="col-md-6">
                                            {{ form.render("font_class",{'class':'form-control'}) }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2"><label for="name">Hình icon</label></div>
                                        <div class="col-md-6">
                                            {{ form.render("icon") }}
                                            {% if item is defined and item.icon != '' %}
                                            {{ image('files/icon/' ~ SUB_FOLDER ~ '/' ~ item.icon ,  'style':'margin-top:10px;max-width:15px') }}
                                        {% endif %}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2"><label for="banner">Header</label></div>
                                        <div class="col-md-8">
                                            {% if layout_config is defined %}
                                            <label>
                                                <input type="radio" name="hide_header" value="N"{% if (layout_config.hide_header == 'N') %} checked{% endif %}> Hiện
                                            </label>
                                            <label>
                                                <input type="radio" name="hide_header" value="Y"{% if (layout_config.hide_header == 'Y') %} checked{% endif %}> Ẩn
                                            </label>
                                            {% endif %}
                                            {% if item is defined %}
                                            <label>
                                                <input type="radio" name="hide_header" value="N"{% if (item.hide_header == 'N') %} checked{% endif %}> Hiện
                                            </label>
                                            <label>
                                                <input type="radio" name="hide_header" value="Y"{% if (item.hide_header == 'Y') %} checked{% endif %}> Ẩn
                                            </label>
                                            {% endif %}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2"><label for="banner">Cột trái</label></div>
                                        <div class="col-md-8">
                                            {% if layout_config is defined %}
                                            <label>
                                                <input type="radio" name="hide_left" value="N"{% if (layout_config.hide_left == 'N') %} checked{% endif %}> Hiện
                                            </label>
                                            <label>
                                                <input type="radio" name="hide_left" value="Y"{% if (layout_config.hide_left == 'Y') %} checked{% endif %}> Ẩn
                                            </label>
                                            {% endif %}
                                            {% if item is defined %}
                                            <label>
                                                <input type="radio" name="hide_left" value="N"{% if (item.hide_left == 'N') %} checked{% endif %}> Hiện
                                            </label>
                                            <label>
                                                <input type="radio" name="hide_left" value="Y"{% if (item.hide_left == 'Y') %} checked{% endif %}> Ẩn
                                            </label>
                                            {% endif %}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2"><label for="banner">Cột phải</label></div>
                                        <div class="col-md-8">
                                            {% if layout_config is defined %}
                                            <label>
                                                <input type="radio" name="hide_right" value="N"{% if (layout_config.hide_right == 'N') %} checked{% endif %}> Hiện
                                            </label>
                                            <label>
                                                <input type="radio" name="hide_right" value="Y"{% if (layout_config.hide_right == 'Y') %} checked{% endif %}> Ẩn
                                            </label>
                                            {% endif %}
                                            {% if item is defined %}
                                            <label>
                                                <input type="radio" name="hide_right" value="N"{% if (item.hide_right == 'N') %} checked{% endif %}> Hiện
                                            </label>
                                            <label>
                                                <input type="radio" name="hide_right" value="Y"{% if (item.hide_right == 'Y') %} checked{% endif %}> Ẩn
                                            </label>
                                            {% endif %}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2"><label for="banner">Footer</label></div>
                                        <div class="col-md-8">
                                            {% if layout_config is defined %}
                                            <label>
                                                <input type="radio" name="hide_footer" value="N"{% if (layout_config.hide_footer == 'N') %} checked{% endif %}> Hiện
                                            </label>
                                            <label>
                                                <input type="radio" name="hide_footer" value="Y"{% if (layout_config.hide_footer == 'Y') %} checked{% endif %}> Ẩn
                                            </label>
                                            {% endif %}
                                            {% if item is defined %}
                                            <label>
                                                <input type="radio" name="hide_footer" value="N"{% if (item.hide_footer == 'N') %} checked{% endif %}> Hiện
                                            </label>
                                            <label>
                                                <input type="radio" name="hide_footer" value="Y"{% if (item.hide_footer == 'Y') %} checked{% endif %}> Ẩn
                                            </label>
                                            {% endif %}
                                        </div>
                                    </div>

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
                                                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_header" style="display: block;">Chọn module</a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_header" class="panel-collapse collapse in">
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
                                                                                            {% for j in moduleElements %}
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
                                                                                                        <input type="checkbox" name="active_module[{{ j['module_id'] }}]" value="Y" {% if j['active'] == 'Y' and j['landing_page_id'] == item.id %}checked{% endif %}>
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
                                                                                                        <input type="checkbox" name="active_module[{{ k['module_id'] }}]" value="Y" {% if j['active'] == 'Y' and k['active'] == 'Y' and k['landing_page_id'] == item.id %}checked{% endif %}>
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
                                    
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        {{ form.render("title",{'class':'form-control'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="keywords">Keywords</label>
                                        {{ form.render("keywords",{'class':'form-control','id':'keywords'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        {{ form.render("description",{'class':'form-control','id':'description'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="sort">Thứ tự</label>
                                        {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                        {{ form.messages('sort') }}
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label for="active">Hiển thị</label>
                                            {{ form.render("active",{'class':'form-control', 'style':'width:50%'}) }}
                                        </div>
                                        <div class="col-md-2">
                                            <label for="menu">Main Menu</label>
                                            {{ form.render("menu",{'class':'form-control', 'style':'width:50%'}) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% for tmp in tmpSubdomainLanguages %}
                {% if tmp.language.code != 'vi' %}
                    {% set langCode = tmp.language.code %}
                    {% set langName = tmp.language.name %}
                    <div class="tab-pane fade tab-other-lang-{{ langCode }}" id="{{ langCode }}">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ title_bar }}</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-8">

                                        <div class="form-group">
                                            <label>Tên<span class="text-danger">(*)</span></label>
                                            {{ form.render("name_" ~ langCode,{'class':'form-control set_url_' ~ langCode}) }}
                                            {{ form.messages('name_' ~ langCode) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            {{ form.render("title_" ~ langCode,{'class':'form-control'}) }}
                                        </div>

                                        <div class="form-group">
                                            <label for="keywords">Keywords</label>
                                            {{ form.render("keywords_" ~ langCode,{'class':'form-control'}) }}
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            {{ form.render("description_" ~ langCode,{'class':'form-control'}) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {% endif %}
                {% endfor %}
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ submit_button("Lưu & Thêm Mới", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}
                {{ submit_button("Lưu & Đóng", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}
                {{ link_to(ACP_NAME ~ "/" ~ controller_name, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        {% if tmpSubdomainLanguages|length > 0 %}
        $('form[name=form_multilang]').submit(function(e) {
            {% for tmp in tmpSubdomainLanguages %}
                {% set langCode  = tmp.language.code %}
                {% set langName  = tmp.language.name %}
                {% if langCode != 'vi' %}
                    if ($('input[name="name_{{ langCode }}"]').val() == '' || $('input[name="slug_{{ langCode }}"]').val() == '') {
                        toastr.error('Bạn chưa nhập đủ dữ liệu yêu cầu bên ngôn ngữ {{ langName }}');
                        e.preventDefault();
                        return false;
                    }
                {% endif %}
            {% endfor %}
        })
        {% endif %}
    });
</script>