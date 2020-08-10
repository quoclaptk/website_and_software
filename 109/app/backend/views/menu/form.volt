{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}
            <div class="row">
                <div class="col-md-3">
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse1" style="display: block">Trang</a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse in">
                                {{ form('role':'form','enctype':'multipart/form-data','action':HTTP_HOST ~ '/' ~ ACP_NAME ~ '/menu/addmenu/' ~ item.id) }}
                                <div class="panel-body">
                                    <ul class="list" style="max-height: 300px;overflow: auto">
                                        {% for i in menuArray %}
                                            {% set child = i['child'] %}
                                        <li class="form-group">
                                            <input type="checkbox" class="menu-item-checkbox" name="{{ i['module_name'] }}[{{ i['module_id'] }}][module_id]" value="{{ i['module_id'] }}"><span style="margin-left: 5px;vertical-align: middle">{{ i['name'] }}</span>
                                            <input type="hidden" name="{{ i['module_name'] }}[{{ i['module_id'] }}][module_name]" value="{{ i['module_name'] }}">
                                            <input type="hidden" name="{{ i['module_name'] }}[{{ i['module_id'] }}][parent_id]" value="{{ i['parent_id'] }}">
                                            <input type="hidden" name="{{ i['module_name'] }}[{{ i['module_id'] }}][level]" value="{{ i['level'] }}">
                                            <input type="hidden" name="{{ i['module_name'] }}[{{ i['module_id'] }}][name]" value="{{ i['name'] }}">
                                            <input type="hidden" name="{{ i['module_name'] }}[{{ i['module_id'] }}][url]" value="{{ i['url'] }}">
                                            {% if child != '' %}
                                            <ul style="margin-top: 15px;margin-left: 15px">
                                                {% for j in child %}
                                                <li class="form-group">
                                                    <input type="checkbox" class="menu-item-checkbox" name="{{ j['module_name'] }}[{{ j['module_id'] }}][module_id]" value="{{ j['module_id'] }}"><span style="margin-left: 5px;vertical-align: middle">{{ j['space_name'] }}</span>
                                                    <input type="hidden" name="{{ j['module_name'] }}[{{ j['module_id'] }}][module_name]" value="{{ j['module_name'] }}">
                                                    <input type="hidden" name="{{ j['module_name'] }}[{{ j['module_id'] }}][parent_id]" value="{{ j['parent_id'] }}">
                                                    <input type="hidden" name="{{ j['module_name'] }}[{{ j['module_id'] }}][level]" value="{{ j['level'] }}">
                                                    <input type="hidden" name="{{ j['module_name'] }}[{{ j['module_id'] }}][name]" value="{{ j['name'] }}">
                                                    <input type="hidden" name="{{ j['module_name'] }}[{{ j['module_id'] }}][url]" value="{{ j['url'] }}">
                                                    <input type="hidden" name="{{ j['module_name'] }}[{{ j['module_id'] }}][sort]" value="{{ j['sort'] }}">
                                                </li>
                                                {% endfor %}
                                            </ul>
                                            {% endif %}
                                        </li>
                                        {% endfor %}
                                    </ul>
                                    <div class="button-controls">
                                        <span class="list-controls" style="vertical-align: middle">
                                            <a href="javascript:void(0)" onclick="selectAll(this)" class="select-all text-primary" style="margin-top:5px">Chọn tất cả</a>
                                        </span>

                                        <span class="add-to-menu pull-right">
                                            <input type="submit" class="btn btn-default button-secondary submit-add-to-menu right" value="Thêm vào menu" name="add-post-type-menu-item" id="submit-posttype-page">
                                            <span class="spinner"></span>
                                        </span>
                                    </div>
                                    {{ endform() }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse2" style="display: block">Links</a>
                                </h4>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse in">
                                {{ form('role':'form','enctype':'multipart/form-data','action':HTTP_HOST ~ '/' ~ ACP_NAME ~ '/menu/addmenustatic/' ~ item.id) }}
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-3"><label>Tên</label></div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input type="text" name="name" id="static_name" class="form-control" placeholder="Tiêu đề" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3"><label>Link</label></div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input type="text" name="url" id="static_url" class="form-control" placeholder="Link" value="//">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><label>Mở tab mới</label></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="checkbox" name="static_new_blank" value="Y">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-controls">
                                        <span class="add-to-menu pull-right">
                                            <input type="submit" class="btn btn-default button-secondary submit-add-to-menu right" value="Thêm vào menu" name="add-post-type-menu-item" id="submit-posttype-page-static">
                                            <span class="spinner"></span>
                                        </span>
                                    </div>
                                </div>
                                {{ endform() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    {{ form('role':'form','enctype':'multipart/form-data') }}
                    {#<div class="panel panel-default">
                        <div class="panel-heading">{{ title_bar }}</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3"><label for="name">Tiêu đề</label></div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                        {{ form.messages('name') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="name">Vị trí</label></div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <ul class="list_category">
                                            {% for i in position %}
                                                <li>
                                                    <input type="checkbox" name="position[]" value="{{ i.id }}"{% if tmp_position_module_item_arr is defined %}{% if i.id in tmp_position_module_item_arr %}checked="checked"{% endif %}{% endif %}>
                                                    <span>{{ i.name }}</span>
                                                </li>
                                            {% endfor %}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="name">Loại menu</label></div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ form.render("style", {"class":"form-control", "style":"width:120px"}) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="name">Thứ tự</label></div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                        {{ form.messages('sort') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label>Main menu</label></div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ form.render("main", {"class":"form-control", "style":"width:90px"}) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="name">Hiển thị</label></div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ form.render("active", {"class":"form-control", "style":"width:90px"}) }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>#}

                    <div class="panel panel-default">
                        <div class="panel-heading">Danh sách menu</div>
                        <div class="panel-body">
                            {% for i in menu_item %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="10%">Sort</th>
                                        <th>Tên</th>
                                        <th>Icon (Font icon)</th>
                                        <th>Icon (Hình ảnh)</th>
                                        <th width="15%">Kiểu icon</th>
                                        <th width="10%">Thứ tự</th>
                                        <th width="14%">Target New</th>
                                        <th width="5%">Ẩn/Hiển</th>
                                        <th width="5%">Sửa</th>
                                        <th width="5%">Xóa</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                <tr>
                                    <td class="text-center">{{ i.sort }}</td>
                                    <td>{{ link_to(ACP_NAME ~ '/menu_item/update/' ~ i.id, ''~ i.space_name ~'' ) }}</td>
                                    <td class="text-center">{% if i.font_class != '' %}<i class="fa fa-{{ i.font_class }}" aria-hidden="true" style="color: #f00;font-size: 20px"></i>{% endif %}</td>
                                    <td class="text-center">{% if i.photo != '' %}{{ image('files/icon/' ~ folder ~ '/' ~ i.photo, 'style':'max-width:20px') }}{% endif %}</td>
                                    <td>
                                        <select class="form-control" name="icon_type[]">
                                            <option value="1" {% if i.icon_type == 1 %}selected{% endif %}>Font icon</option>
                                            <option value="2" {% if i.icon_type == 2 %}selected{% endif %}>Hình ảnh</option>
                                        </select>
                                    </td>
                                    <td class="center">
                                        <input type="text" name="sort[]" value="{{ i.sort }}" class="form-control">
                                    </td>
                                    <td align="center">
                                        {% if i.new_blank == 'Y' %}
                                            {{ link_to(ACP_NAME ~ '/menu_item/hideNewBank/' ~ i.id, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                        {% else %}
                                            {{ link_to(ACP_NAME ~ '/menu_item/showNewBank/' ~ i.id, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                        {% endif %}
                                    </td>
                                    <td align="center">
                                        {% if i.active == 'Y' %}
                                            {{ link_to(ACP_NAME ~ '/menu_item/hide/' ~ i.id, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                        {% else %}
                                            {{ link_to(ACP_NAME ~ '/menu_item/show/' ~ i.id, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                        {% endif %}
                                    </td>
                                    <td align="center">{{ link_to(ACP_NAME ~ '/menu_item/update/' ~ i.id, '<i class="fa fa-edit"></i>' ) }}</td>
                                    <td align="center">{{ link_to(ACP_NAME ~ '/menu_item/_delete/' ~ i.id, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                </tr>
                                {% if loop.last %}
                                    </tbody>

                                    </table>
                                    </div>
                                {% endif %}
                            {% endfor %}
                            {#
                            {% if item.style == 'vertical' %}
                            <div class="form-enable form-group" style="margin-top:20px">
                                <label>
                                    <input type="radio" name="display_type" value="1"{% if item.display_type == 1 %} checked{% endif %}> Hiện trực quan menu con
                                </label>
                            </div>
                            <div class="form-enable">
                                <label>
                                    <input type="radio" name="display_type" value="2"{% if item.display_type == 2 %} checked{% endif %}> Rê chuột hiện menu con
                                </label>
                            </div>
                            {% endif %}
                            #}
                        </div>

                    </div>

                    <div class="box-footer">
                        {{ submit_button("Lưu", "class": "btn btn-success","data-type":"save") }}
                        {#{{ link_to(ACP_NAME ~ "/" ~ controller_name, "Thoát", "class": "btn btn-danger") }}#}
                    </div>
                    {{ endform() }}
                </div>
            </div>
        </div>
    </div>
</section>