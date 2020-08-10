{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}
            {{ form('role':'form','enctype':'multipart/form-data','id':'formNotSubmit') }}
            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-7">
                            {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary btn-sm") }}
                        </div>
                        <div class="col-md-5 text-right">
                            {{ submit_button("Lưu", "class": "btn btn-primary btn-sm","data-type":"save") }}
                            <a href="javascript:;" class="btn btn-danger btn-sm" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                            <a href="javascript:;" class="btn btn-success btn-sm" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                            <a href="javascript:;" class="btn btn-warning btn-sm" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
                        </div>
                        <p class="clear"></p>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            {% for item in page.items %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="6%" style="vertical-align: top" class="text-center"><input type="checkbox" name="select-all-item" class="flat-red"></th>
                                        <th width="25%" class="text-center" style="vertical-align: top">Tên</th>
                                        <th style="vertical-align: top">Xem</th>
                                        <th width="8%" style="vertical-align: top">Lượt xem</th>
                                        <th width="7%" style="vertical-align: top">Thứ tự</th>
                                        <th width="10%">Main Menu</th>
                                        <th width="7%" class="text-center"><p>Ẩn/Hiện</p><div class="text-center"><input type="checkbox" name="select-all-active" class="flat-red"></div></th>
                                        <th width="5%" style="vertical-align: top">Sửa</th>
                                        <th width="5%" style="vertical-align: top">Xóa</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                <tr>
                                    <td align="center"><input type="checkbox" name="select_all" value="{{ item.id }}"></td>
                                    <td>
                                        <p>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, ''~ item.name ~'' ) }}</p>
                                    </td>
                                    <td class="text-center">{{ link_to(item.slug ~ '/', 'target':'_blank', 'view' ) }}</td>
                                    <td class="text-center">{{ item.hits }}</td>
                                    <td><input type="text" name="sort_{{ item.id }}" value="{{ item.sort }}" class="form-control" style="width:50px"></td>
                                    
                                    <td class="text-center">
                                        <input type="checkbox" name="menu_{{ item.id }}" value="1" class="active-item" {% if item.menu == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="active_{{ item.id }}" value="1" class="active-item" {% if item.active == 'Y' %}checked{% endif %}>
                                        {% if item.active == 'Y' %}
                                        <span class="badge badge-success">Hiện</span>
                                        {% else %}
                                        <span class="badge badge-danger">Ẩn</span>
                                        {% endif %}
                                    </td>
                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-edit"></i>' ) }}</td>
                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
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
                <div class="text-center">{{ partial('partials/pagination') }}</div>
            </div>

            <div class="box-footer">
                <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary btn-sm") }}</div>
                <div class="pull-right">
                    {{ submit_button("Lưu", "class": "btn btn-primary btn-sm","data-type":"save") }}
                    <a href="javascript:;" class="btn btn-danger btn-sm" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                    <a href="javascript:;" class="btn btn-success btn-sm" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                    <a href="javascript:;" class="btn btn-warning btn-sm" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
                </div>
            </div>
             {{ endform() }}
        </div>
    </div>
</section>
