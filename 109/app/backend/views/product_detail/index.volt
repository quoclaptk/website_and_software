{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}

            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">
                    <div class="clearfix">
                        <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary") }}</div>
                        <div class="pull-right">
                            {#<a href="javascript:;" class="btn btn-primary" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>#}
                            <a href="javascript:;" class="btn btn-primary" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
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
                                                <th width="7%">Thứ tự</th>
                                                <th>Tên</th>
                                                <th width="5%">Ẩn/Hiện</th>
                                                <th width="5%">Sửa</th>
                                                <th width="5%">Xóa</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                        {% endif %}
                                        <tr>
                                            <td>{{ item.sort }}</td>
                                            <td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, ''~ item.name ~'' ) }}</td>
                                            <td class="text-center">
                                                {% if item.enable_delete == 'Y' %}
                                                    {% if item.active == 'Y' %}
                                                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hide/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                    {% else %}
                                                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/show/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                    {% endif %}
                                                {% endif %}
                                            </td>
                                            <td class="text-center">
                                                {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-edit"></i>' ) }}
                                            </td>
                                            <td class="text-center">
                                                {% if item.enable_delete == 'Y' %}
                                                {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','class':'prdConfirmDelete' ) }}
                                                {% endif %}
                                            </td>
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
                {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary") }}
            </div>

        </div>
    </div>
</section>
