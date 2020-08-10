{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}

            <div class="panel panel-default">
                <div class="panel-heading" style="display:block">{{ module_name }}</div>
                <div class="panel-body">
                    <div class="clearfix">
                        <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create/" ~ type, "Thêm mới", "class": "btn btn-primary") }}</div>
                        <div class="pull-right">
                            <a href="javascript:;" class="btn btn-primary" onclick="delete_all_type('{{ controller_name }}', {{ type }}, {{ page_current }})">Xóa nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="show_all_type('{{ controller_name }}', {{ type }}, {{ page_current }})">Hiển thị nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="hide_all_type('{{ controller_name }}', {{ type }}, {{ page_current }})">Ẩn nhiều mục</a>
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
                                                <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                                                <th width="7%">Thứ tự</th>
                                                <th>Tên</th>
                                                {% if productElement.is_color == 'Y' %}
                                                <th width="25%">Chọn màu</th>
                                                {% endif %}
                                                <th width="5%">Ẩn/Hiện</th>
                                                <th width="5%">Sửa</th>
                                                <th width="5%">Xóa</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                        {% endif %}
                                        <tr>
                                            <td>
                                                {% if item.id not in arr_list_j_product_element_product %}
                                                <input type="checkbox" name="select_all" value="{{ item.id }}">
                                                {% endif %}
                                            </td>
                                            <td>{{ item.sort }}</td>
                                            
                                            <td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ type ~ '/' ~ item.id ~ '/' ~ page_current, item.name ) }}</td>
                                            {% if productElement.is_color == 'Y' %}
                                            <td>
                                                <input type="text" name="pelm_color" data-id="{{ item.id }}" class="my-colorpicker1 form-control" style="width:80px;display:inline-block">
                                                <span class="pelm_color_{{ item.id }}" style="{% if item.color is not null %}background: {{ item.color }}{% endif %};display:inline-block;width:30px;height:30px;vertical-align:middle"></span>
                                            </td>
                                            {% endif %}
                                            <td align="center">
                                                {% if item.active == 'Y' %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hide/' ~ type ~ '/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                {% else %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/show/' ~ type ~ '/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                {% endif %}
                                            </td>
                                            <td align="center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ type ~ '/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-edit"></i>' ) }}</td>
                                            <td class="text-center">
                                                {% if item.id not in arr_list_j_product_element_product %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_delete/' ~ type ~ '/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}
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
                {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create/" ~ type, "Thêm mới", "class": "btn btn-primary") }}
            </div>

        </div>
    </div>
</section>
