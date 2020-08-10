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
                        <div class="pull-right">
                            <a href="javascript:;" class="btn btn-primary" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                        </div>
                        <p class="clear"></p>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            {% for key,item in page.items %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                                                <th width="7%">Thứ tự</th>
                                                <th>Họ Tên</th>
                                                <th>SĐT</th>
                                                <th>Email</th>
                                                <th>Địa chỉ</th>
                                                <th>Chủ đề</th>
                                                <th>Nội dung</th>
                                                <th width="5%">Xem</th>
                                                <th width="5%">Xóa</th>
                                            </tr>
                                        </thead>

                                    <tbody>
                                        {% endif %}
                                        <tr>
                                            <td><input type="checkbox" name="select_all" value="{{ item.id }}"></td>
                                            <td>{{ key + 1 }}</td>
                                            <td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, ''~ item.name ~'' ) }}</td>
                                            <td>{{ item.phone }}</td>
                                            <td>{{ item.email }}</td>
                                            <td>{{ item.address }}</td>
                                            <td>{{ item.subject }}</td>
                                            <td>{{ tag.cut_string(item.comment, 100) }}</td>
                                            <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-eye"></i>' ) }}</td>
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
        </div>
    </div>
</section>
