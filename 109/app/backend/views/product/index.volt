{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {#<p><b class="text-danger">Chú ý: Website đa ngôn ngữ thì phải nhập dữ liệu đa ngôn ngữ bên Danh mục sản phẩm trước khi quản trị phần này để đồng bộ dữ liệu (VD: Website có 2 ngôn ngữ Việt Nam + English thì phải nhập cả phần tiếng Việt và tiếng Anh bên Danh mục sản phẩm trước)</b></p>#}
            {{ content() }}
            {{ flashSession.output() }}
            
            {{ form('role':'form','enctype':'multipart/form-data','id':'formNotSubmit') }}
            <!-- <input type="hidden" name="keyword" value="{{ request.get('keyword') }}"> -->
            <!-- <input type="hidden" name="category" value="{{ request.get('keyword') }}"> -->
            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-2">
                                    {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary btn-sm") }}
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
                                        <th width="25%" class="text-center" style="vertical-align: top">Tên + Mã sản phẩm</th>
                                        <th  class="text-center" style="vertical-align: top">Giá</th>
                                        <th class="text-center" width="8%" style="vertical-align: top">Hình ảnh</th>
                                        <th style="vertical-align: top">Xem</th>
                                        <th width="7%" style="vertical-align: top">Thứ tự</th>
                                        <th width="7%" class="text-center"><p>Ẩn/Hiện</p><div class="text-center"><input type="checkbox" name="select-all-active" class="flat-red"></div></th>
                                        <th width="7%"><p>Nổi bật</p><div class="text-center"><input type="checkbox" name="select-all-hot" class="flat-red"></th>
                                        <th width="7%" class="text-center"><p>Mới</p><div class="text-center"><input type="checkbox" name="select-all-new" class="flat-red"></div></th>
                                        <th width="7%" class="text-center"><p>Bán chạy</p><div class="text-center"><input type="checkbox" name="select-all-selling" class="flat-red"></div></th>
                                        <th width="9%" class="text-center"><p>Khuyến mãi</p><div class="text-center"><input type="checkbox" name="select-all-promotion" class="flat-red"></div></th>
                                        <th width="9%" class="text-center"><p>Hết hàng</p><div class="text-center"><input type="checkbox" name="select-all-out-stock" class="flat-red"></div></th>
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
                                        <p>
                                            <span style="display:inline-block" style="font-size:12px">Msp:</span>
                                            <span style="display:inline-block">
                                                <input type="text" class="form-control update-product-code" name="code_{{ item.id }}" data-id="{{ item.id }}" data-type="code" value="{{ item.code }}" placeholder="Mã sản phẩm" style="width:115px">
                                            </span>
                                        </p>
                                        <p>
                                            <span style="display:inline-block" style="font-size:12px">Lượt mua:</span>
                                            <span style="display:inline-block">
                                                <input type="text" class="form-control update-product-code" name="purchase_{{ item.id }}" data-id="{{ item.id }}" data-type="purchase_number" value="{{ item.purchase_number }}" placeholder="Lượt mua" style="width:80px">
                                            </span>
                                        </p>
                                        <p>
                                            <span style="display:inline-block" style="font-size:12px">Lượt xem:</span>
                                            <span style="display:inline-block">
                                                <input type="text" class="form-control update-product-code" name="hits_{{ item.id }}" data-id="{{ item.id }}" data-type="hits" value="{{ item.hits }}" placeholder="Lượt xem" style="width:80px">
                                            </span>
                                        </p>
                                        <p>
                                            <span style="display:inline-block" style="font-size:12px">Sp còn lại:</span>
                                            <span style="display:inline-block">
                                                <input type="text" class="form-control update-product-code" name="in_stock_{{ item.id }}" data-id="{{ item.id }}" data-type="in_stock" value="{{ item.in_stock }}" placeholder="Lượt xem" style="width:80px">
                                            </span>
                                        </p>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label class="text-danger">Giá cũ</label>
                                            <input type="text" class="form-control update-product-price" name="cost_{{ item.id }}" data-id="{{ item.id }}" data-type="1" value="{{ number_format(item.cost) }}" onkeyup="FormatNumber(this);" onKeyPress="return isNumberKey(event)" style="width: 120px">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-danger">Giá đang bán</label>
                                            <input type="text" class="form-control update-product-price" name="price_{{ item.id }}" data-id="{{ item.id }}" data-type="2" onkeyup="FormatNumber(this);" onKeyPress="return isNumberKey(event)" value="{{ number_format(item.price) }}" style="width: 120px">
                                        </div>
                                        {% if cf['_cf_text_price_usd_currency'] is defined %}
                                        <div class="form-group">
                                            <label class="text-danger">Giá cũ ({{ cf['_cf_text_price_usd_currency'] }})</label>
                                            <input type="text" class="form-control update-product-price" name="cost_usd_{{ item.id }}" data-id="{{ item.id }}" data-type="3" value="{{ number_format(item.cost_usd) }}" onkeyup="FormatNumber(this);" onKeyPress="return isNumberKey(event)" style="width: 120px">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-danger">Giá đang bán ({{ cf['_cf_text_price_usd_currency'] }})</label>
                                            <input type="text" class="form-control update-product-price" name="price_usd_{{ item.id }}" data-id="{{ item.id }}" data-type="4" onkeyup="FormatNumber(this);" onKeyPress="return isNumberKey(event)" value="{{ number_format(item.price_usd) }}" style="width: 120px">
                                        </div>
                                        {% endif %}
                                    </td>
                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, ''~ image('files/product/' ~ SUB_FOLDER ~ '/' ~ item.folder ~ '/' ~ item.photo, 'style':'width:60px') ) }}</td>
                                    <td class="text-center">{{ link_to(item.slug ~ '/', 'target':'_blank', 'view' ) }}</td>
                                    <td><input type="text" name="sort_{{ item.id }}" value="{{ item.sort }}" class="form-control" style="width:50px"></td>
                                    <td class="text-center">
                                        <input type="checkbox" name="active_{{ item.id }}" value="1" class="active-item" {% if item.active == 'Y' %}checked{% endif %}>
                                        {% if item.active == 'Y' %}
                                        <span class="badge badge-success">Hiện</span>
                                        {% else %}
                                        <span class="badge badge-danger">Ẩn</span>
                                        {% endif %}
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="hot_{{ item.id }}" value="1" class="hot-item" {% if item.hot == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="new_{{ item.id }}" value="1" class="new-item" {% if item.new == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="selling_{{ item.id }}" value="1" class="selling-item" {% if item.selling == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="promotion_{{ item.id }}" value="1" class="promotion-item" {% if item.promotion == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="out_stock_{{ item.id }}" value="1" class="out-stock-item" {% if item.out_stock == 'Y' %}checked{% endif %}>
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
