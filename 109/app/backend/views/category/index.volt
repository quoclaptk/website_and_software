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
                                    <table id="example" class="table table-bordered table-striped table-hover table-{{ router.getControllerName() }}" style="font-size: 13px">
                                    <thead>
                                    <tr>
                                        <th width="6%" style="vertical-align: top" class="text-center"><input type="checkbox" name="select-all-item" class="flat-red"></th>
                                        
                                        <th width="15%" class="text-center">Tên</th>
                                        <th width="6%" class="text-center">Thứ tự</th>
                                        <th width="17%" class="text-center"><p>Nổi bật</p><div class="text-center"><input type="checkbox" name="select-all-hot" class="flat-red"></div></th>
                                        <th width="17%" class="text-center"><p>Module sản phẩm theo danh mục</p><p class="text-danger">Chú ý: Hiển thị tối đa 10 danh mục</p><div class="text-center"><input type="checkbox" name="select-all-new" class="flat-red"></div></th>
                                        <th width="17%" class="text-center"><p>Hiện banner trong DM cha</p><div class="text-center"><input type="checkbox" name="select-all-selling" class="flat-red"></div></th>
                                        <th width="10%" class="text-center"><p>Main Menu</p><div class="text-center"><input type="checkbox" name="select-all-promotion" class="flat-red"></div></th>
                                        <th width="7%" class="text-center"><p>Ẩn/Hiện</p><div class="text-center"><input type="checkbox" name="select-all-active" class="flat-red"></div></th>
                                        <th width="15%" class="text-center"><p>Copy shortcode</p></th>
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
                                        <input type="checkbox" name="hot_{{ item.id }}" value="Y" class="hot-item" {% if item.hot == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <div class="row">
                                            <div class="col-md-6 text-right"><input type="text" name="sort_home_{{ item.id }}" value="{{ item.sort_home }}" class="form-control" style="width:50px"></div>
                                            <div class="col-md-6 text-left">
                                                <input type="checkbox" name="show_home_{{ item.id }}" value="Y" class="new-item" {% if item.show_home == 'Y' %}checked{% endif %}>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="picture_{{ item.id }}" value="Y" class="selling-item" {% if item.picture == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="menu_{{ item.id }}" value="Y" class="promotion-item" {% if item.menu == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="active_{{ item.id }}" value="Y" class="active-item" {% if item.active == 'Y' %}checked{% endif %}>
                                        {% if item.active == 'Y' %}
                                        <span class="badge badge-success">Hiện</span>
                                        {% else %}
                                        <span class="badge badge-danger">Ẩn</span>
                                        {% endif %}
                                    </td>
                                    <td>
                                        <p id="product_category_{{ item.id }}" class="red shortcode_txt">[product_category category="{{ item.slug }}"]</p>
                                        <div class="text-center"><button type="button" class="btn btn-sm btn-success" onclick="copyToClipboard(product_category_{{ item.id }})">Copy</button></div>
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
