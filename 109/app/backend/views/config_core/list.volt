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
                            <a href="javascript:;" class="btn btn-primary" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
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
                                        <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                                        <th width="7%">Thứ tự</th>
                                        <th>Cấu hình</th>
                                        <th width="20%" class="text-center">Cập nhật hướng dẫn</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                <tr>
                                    <td><input type="checkbox" name="select_all" value="{{ item.id }}"></td>
                                    <td>{{ item.sort }}</td>
                                    <td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/updateGuide/' ~ item.id, ''~ item.name ~'' ) }}</td>
                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/updateGuide/' ~ item.id, '<i class="fa fa-edit"></i>' ) }}</td>
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
                {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary") }}
            </div>

        </div>
    </div>
</section>
