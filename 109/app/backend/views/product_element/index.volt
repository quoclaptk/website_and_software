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
                            {% for item in page.items %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                        <table id="example" class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                                                <th width="7%">Thứ tự</th>
                                                <th width="20%">Tên</th>
                                                <th width="15%">Combo giá(<span class="text-danger">check 2 thuộc tính</span>)</th>
                                                <th width="15%">Màu sắc(<span class="text-danger">Nhập màu cho thuộc tính con</span>)</th>
                                                 <th width="15%">Hình ảnh sản phẩm kèm theo</span>)</th>
                                                <th width="15%">Tìm kiếm nâng cao</th>
                                                <th class="text-center">Danh sách(click để sửa)</th>
                                                {#
                                                <th class="text-center">Thêm danh sách</th>
                                                #}
                                                <th width="5%">Ẩn/Hiện</th>
                                                <th width="5%">Sửa</th>
                                                <th width="5%">Xóa</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                        {% endif %}
                                        <tr>
                                            <td>
                                                {% if item.id not in arr_list_j_product_element_detail %}
                                                <input type="checkbox" name="select_all" value="{{ item.id }}">
                                                {% endif %}
                                            </td>
                                            <td>{{ item.sort }}</td>

                                            <td>
                                                <div>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, ''~ item.name ~'' ) }}</div>
                                                <div class="checkbox" style="display:inline-block">
                                                    <label>
                                                        {% if item.show_price == 'Y' %}
                                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hideShowPrice/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                        {% else %}
                                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/showShowPrice/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                        {% endif %}
                                                        <span class="red bold">Cho phép các thuộc tính: Có giá khác nhau và 1 sản phẩm tích chọn nhiều thuộc tính</span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                {% if item.combo == 'Y' %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/isCombo/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                {% else %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/isCombo/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                {% endif %}
                                            </td>
                                            <td class="text-center">
                                                {% if item.is_color == 'Y' %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/isColor/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                {% else %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/isColor/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                {% endif %}
                                            </td>
                                            <td class="text-center">
                                                {% if item.is_product_photo == 'Y' %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/isProductPhoto/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                {% else %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/isProductPhoto/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                {% endif %}
                                            </td>
                                            <td class="text-center">
                                                {% if item.search == 'Y' %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hideSearch/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                {% else %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/showSearch/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                {% endif %}
                                            </td>
                                            <td>
                                                {{ link_to(ACP_NAME ~ '/product_element_detail/index/' ~ item.id, 'class':'btn btn-success', 'target':'blank', '<i class="fa fa-list"></i> Danh sách</a>' ) }}
                                                <ol style="margin-top:10px">
                                                    {% for ped in item.productElementDetails %}
                                                    <li>{{ link_to(ACP_NAME ~ '/product_element_detail/index/' ~ item.id, ''~ ped.name ~'', 'target':'blank', 'class':'text-danger', 'style':'font-weight:bold' ) }}</li>
                                                    {% endfor %}
                                                </ol>
                                            </td>
                                            {#
                                            <td class="text-center">{{ link_to(ACP_NAME ~ '/product_element_detail/index/' ~ item.id, 'class':'btn btn-link', '<i class="fa fa-plus"></i> Thêm</a>' ) }}</td>
                                            #}
                                            <td class="text-center">
                                                {% if item.active == 'Y' %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hide/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                {% else %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/show/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                {% endif %}
                                            </td>
                                            <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-edit"></i>' ) }}</td>
                                            <td class="text-center">
                                                {#{% if item.id not in arr_list_j_product_element_detail %}#}
                                                {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}
                                                {#{% endif %}#}
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
