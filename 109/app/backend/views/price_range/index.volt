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
                            {% for item in page.items %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                       <th width="6%" style="vertical-align: top" class="text-center"><input type="checkbox" name="select-all-item" class="flat-red"></th>
                                        <th>Tên</th>
                                        <th width="6%">Thứ tự</th>
                                        <th>Giá bắt đầu</th>
                                        <th>Giá kết thúc</th>
                                        <th width="5%">Ẩn/Hiện</th>
                                        <th width="5%">Sửa</th>
                                        <th width="5%">Xóa</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                <tr>
                                    <td class="text-center"><input type="checkbox" name="select_all" value="{{ item.id }}"></td>
                                    <td>{{ item.name }}</td>
                                    <td><input type="text" name="sort_{{ item.id }}" value="{{ item.sort }}" class="form-control" style="width:50px"></td>
                                    <td>{{ number_format(item.from_price, 0, ',', '.') }}</td>
                                    <td>{{ number_format(item.to_price, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        {% if item.active == 'Y' %}
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hide/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                        {% else %}
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/show/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
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
