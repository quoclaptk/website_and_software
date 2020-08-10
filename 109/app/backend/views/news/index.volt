{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {#<p><b class="text-danger">Chú ý: Website đa ngôn ngữ thì phải nhập dữ liệu đa ngôn ngữ bên Danh mục bài viết trước khi quản trị phần này để đồng bộ dữ liệu (VD: Website có 2 ngôn ngữ Việt Nam + English thì phải nhập cả phần tiếng Việt và tiếng Anh bên Danh mục bài viết trước)</b></p>#}
            {{ content() }}
            {{ flashSession.output() }}
            {{ form('role':'form','enctype':'multipart/form-data','id':'formNotSubmit') }}
            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-2">
                                    {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create/" ~ type, "Thêm mới", "class": "btn btn-primary btn-sm") }}
                                </div>
                                <div class="col-md-4">
                                {% if category != '' %}
                                    <select class="form-control" name="category" onchange="elmFilter()">
                                        <option value="0">Lọc theo danh mục</option>
                                        {% for i in category %}
                                        <option value="{{ i.id }}"{% if i.id == categoryId %} selected{% endif %}>{{ i.name }}</option>
                                        {% endfor %}
                                    </select>
                                {% endif %}
                                </div>
                                <div class="col-md-6">
                                    <input type="hidden" name="controller" value="{{ controller_name }}">
                                    <div class="input-group">
                                      <input type="text" name="keyword" value="{{ keyword }}" placeholder="Nhập từ khóa ..." class="form-control">
                                      <span class="input-group-btn">
                                        <button id="btn-filter" class="btn btn-warning btn-flat ladda-button" data-style="slide-left"><span class="ladda-label"><i class="fa fa-search"></i></span></button>
                                      </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 text-right">
                            {{ submit_button("Lưu", "class": "btn btn-primary btn-sm","data-type":"save") }}
                            <a href="javascript:;" class="btn btn-success btn-sm" onclick="show_all_type('{{ controller_name }}', {{ type }}, {{ page_current }})">Hiển thị nhiều mục</a>
                            <a href="javascript:;" class="btn btn-warning btn-sm" onclick="hide_all_type('{{ controller_name }}', {{ type }}, {{ page_current }})">Ẩn nhiều mục</a>
                            <a href="javascript:;" class="btn btn-danger btn-sm" onclick="delete_all_type('{{ controller_name }}', {{ type }}, {{ page_current }})">Xóa nhiều mục</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            {% for item in page.items %}
                                {% if loop.first %}
                                <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr class="text-sm">
                                        <th width="5%" style="vertical-align: top" class="text-center"><input type="checkbox" name="select-all-item" class="flat-red"></th>
                                        <th style="vertical-align: top" class="text-center" width="25%">Tên</th>
                                        <th width="7%" class="text-center" style="vertical-align: top">View site</th>
                                        <th width="8%" style="vertical-align: top" class="text-center">Lượt xem</th>
                                        <th width="6%" class="text-center" style="vertical-align: top" class="text-center">Thứ tự</th>
                                        <th width="12%" class="text-center" style="vertical-align: top"><p>Giới thiệu chung</p><div class="text-center"><input type="checkbox" name="select-all-introduct" class="flat-red"></th>
                                        <th width="8%" class="text-center" style="vertical-align: top"><p>Xem nhiều</p><div class="text-center"><input type="checkbox" name="select-all-most-view" class="flat-red"></th>
                                        <th width="7%" class="text-center" style="vertical-align: top"><p>Tin mới</p><div class="text-center"><input type="checkbox" name="select-all-new" class="flat-red"></th>
                                        <th width="7%" class="text-center" style="vertical-align: top"><p>Nổi bật</p><div class="text-center"><input type="checkbox" name="select-all-hot" class="flat-red"></th>
                                        <th width="7%" class="text-center" style="vertical-align: top"><p>Tin slider</p><div class="text-center"><input type="checkbox" name="select-all-selling" class="flat-red"></th>
                                        <th width="7%" class="text-center" style="vertical-align: top"><p>Hiệu ứng nổi bật</p><div class="text-center"><input type="checkbox" name="select-all-out-stock" class="flat-red"></th>
                                        <th width="7%" class="text-center" style="vertical-align: top"><p>Tin thống kê</p><div class="text-center"><input type="checkbox" name="select-all-promotion" class="flat-red"></th> 
                                        <th width="5%" style="vertical-align: top"><p>Ẩn/Hiện</p><div class="text-center"><input type="checkbox" name="select-all-active" class="flat-red"></th>
                                        <th width="5%" style="vertical-align: top">Sửa</th>
                                        <th width="5%" style="vertical-align: top">Xóa</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}

                                <tr>
                                    <td class="text-center"><input type="checkbox" name="select_all" value="{{ item.id }}"></td>
                                    <td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ type ~ '/' ~ item.id ~ '/' ~ page_current, item.name ) }}</td>
                                    <td class="text-center">{{ link_to(item.slug ~ '/', 'target':'_blank', 'view' ) }}</td>
                                    <td class="text-center">{{ item.hits }}</td>
                                    <td><input type="text" name="sort_{{ item.id }}" value="{{ item.sort }}" class="form-control"></td>
                                    <td align="center">
                                        <input type="checkbox" name="introduct_{{ item.id }}" value="1" class="introduct-item" {% if item.introduct == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" name="most_view_{{ item.id }}" value="1" class="most-view-item" {% if item.most_view == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" name="new_{{ item.id }}" value="1" class="new-item" {% if item.new == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" name="hot_{{ item.id }}" value="1" class="hot-item" {% if item.hot == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" name="slider_{{ item.id }}" value="1" class="selling-item" {% if item.slider == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" name="hot_effect_{{ item.id }}" value="1" class="out-stock-item" {% if item.hot_effect == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="statistical_{{ item.id }}" value="1" class="promotion-item" {% if item.statistical == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" name="active_{{ item.id }}" value="1" class="active-item" {% if item.active == 'Y' %}checked{% endif %}>
                                        {% if item.active == 'Y' %}
                                        <span class="badge badge-success">Hiện</span>
                                        {% else %}
                                        <span class="badge badge-danger">Ẩn</span>
                                        {% endif %}
                                    </td>
                                    <td align="center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ type ~ '/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-edit"></i>' ) }}</td>
                                    <td align="center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_delete/' ~ type ~ '/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
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
                <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create/" ~ type, "Thêm mới", "class": "btn btn-primary btn-sm") }}</div>
                <div class="pull-right">
                    {{ submit_button("Lưu", "class": "btn btn-primary btn-sm","data-type":"save") }}
                    <a href="javascript:;" class="btn btn-success btn-sm" onclick="show_all_type('{{ controller_name }}', {{ type }}, {{ page_current }})">Hiển thị nhiều mục</a>
                    <a href="javascript:;" class="btn btn-warning btn-sm" onclick="hide_all_type('{{ controller_name }}', {{ type }}, {{ page_current }})">Ẩn nhiều mục</a>
                    <a href="javascript:;" class="btn btn-danger btn-sm" onclick="delete_all_type('{{ controller_name }}', {{ type }}, {{ page_current }})">Xóa nhiều mục</a>
                </div>
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
