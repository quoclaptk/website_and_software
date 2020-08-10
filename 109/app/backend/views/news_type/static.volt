{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}

            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">
                    {#<div class="clearfix">
                        <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/createStatic", "Thêm mới", "class": "btn btn-primary") }}</div>
                        <div class="pull-right">
                            <a href="javascript:;" class="btn btn-primary" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
                        </div>
                        <p class="clear"></p>
                    </div>#}
                    <div class="row">
                        <div class="col-xs-12">
                            {% for item in page.items %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                                        <th width="7%">Thứ tự</th>
                                        <th>Tên</th>
                                        <th width="8%" class="text-center">View site</th>
                                        <th width="8%" class="text-center">Lượt xem</th>
                                        {#<th width="8%">Trang tĩnh</th>#}
                                        <th width="5%">Ẩn/Hiện</th>
                                        <th width="5%">Sửa</th>
                                        <th width="5%">Xóa</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                <tr>
                                    <td>
                                        {% if item.id not in arr_list_j_category or item.id not in arr_list_j_news %}
                                        <input type="checkbox" name="select_all" value="{{ item.id }}">
                                        {% endif %}
                                    </td>
                                    <td>{{ item.sort }}</td>
                                    <td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/updateStatic/' ~ item.id ~ '/' ~ page_current, ''~ item.name ~'' ) }}</td>
                                    
                                    <td class="text-center">{{ link_to('video/' ~ item.slug ~ '/', 'target':'_blank', 'view' ) }}</td>
                                    <td class="text-center">{{ item.hits }}</td>
                                    {#<td class="text-center">
                                        {% if item.static == 'Y' %}
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hidestatic/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                        {% else %}
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/showstatic/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                        {% endif %}
                                    </td>
                                    #}
                                    <td class="text-center">
                                        {% if item.active == 'Y' %}
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hideStatic/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                        {% else %}
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/showStatic/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                        {% endif %}
                                    </td>
                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/updateStatic/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-edit"></i>' ) }}</td>
                                    <td class="text-center">
                                        {% if item.id not in arr_list_j_category or item.id not in arr_list_j_news %}
                                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/deleteStatic/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}
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
                {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/createStatic", "Thêm mới", "class": "btn btn-primary") }}
            </div>

        </div>
    </div>
</section>
