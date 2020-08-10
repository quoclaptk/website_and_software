{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}
            {{ form('role':'form','enctype':'multipart/form-data') }}
            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">
                    <div class="clearfix">
                        <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary") }}</div>
                        <div class="pull-right">
                            {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                            <a href="javascript:;" class="btn btn-danger" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                            <a href="javascript:;" class="btn btn-success" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                            <a href="javascript:;" class="btn btn-warning" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
                        </div>
                        <p class="clear"></p>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            {% for item in items %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="6%" style="vertical-align: top" class="text-center"><input type="checkbox" name="select-all-item" class="flat-red"></th>
                                        <th>Tên</th>
                                        <th width="6%">Thứ tự</th>
                                        <th width="10%">Module Tin tức theo danh mục</th>
                                        <th width="10%">Main Menu</th>
                                        <th width="10%">Footer</th>
                                        <th width="10%">Vận chuyển</th>
                                        {#<th width="10%">Popup đăng ký</th>#}
                                        <th width="10%">Bảng đăng ký</th>
                                        <th width="7%">Ẩn/Hiển</th>
                                        <th width="5%">Sửa</th>
                                        <th width="5%">Xóa</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                <tr>
                                    <td class="text-center"><input type="checkbox" name="select_all" value="{{ item.id }}"></td>
                                    <td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, ''~ item.name ~'' ) }}</td>
                                    <td><input type="text" name="sort_{{ item.id }}" value="{{ item.sort }}" class="form-control" style="width:50px"></td>
                                    <td class="text-center">
                                        <input type="checkbox" name="home_{{ item.id }}" value="1" class="active-item" {% if item.home == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="menu_{{ item.id }}" value="1" class="active-item" {% if item.menu == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="footer_{{ item.id }}" value="1" class="active-item" {% if item.footer == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="policy_{{ item.id }}" value="1" class="active-item" {% if item.policy == 'Y' %}checked{% endif %}>
                                    </td>
                                    {#<td class="text-center">
                                        <input type="checkbox" name="popup_{{ item.id }}" value="1" class="active-item" {% if item.popup == 'Y' %}checked{% endif %}>
                                    </td>#}
                                    <td class="text-center">
                                        <input type="checkbox" name="reg_form_{{ item.id }}" value="1" class="active-item" {% if item.reg_form == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="active_{{ item.id }}" value="1" class="active-item" {% if item.active == 'Y' %}checked{% endif %}>
                                        {% if item.active == 'Y' %}
                                        <span class="badge badge-success">Hiện</span>
                                        {% else %}
                                        <span class="badge badge-danger">Ẩn</span>
                                        {% endif %}
                                    </td>
                                    <td align="center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~  '/update/' ~ item.id, '<i class="fa fa-edit"></i>' ) }}</td>
                                    <td align="center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~  '/_delete/' ~ item.id, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                </tr>
                                {% if loop.last %}
                                    </tbody>

                                    </table>
                                    </div>
                                {% endif %}
                            {% endfor %}
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary") }}</div>
                <div class="pull-right">
                    {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                    <a href="javascript:;" class="btn btn-danger" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                    <a href="javascript:;" class="btn btn-success" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                    <a href="javascript:;" class="btn btn-warning" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
                </div>
            </div>
            {{ endform() }}

        </div>
    </div>
</section>
