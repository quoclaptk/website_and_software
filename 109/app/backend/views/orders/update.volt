{{ partial('partials/content_header') }}

{% set currencyOdr = (item.currency is not empty) ? item.currency : 'VNĐ' %}
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            {#{{ form('role':'form','enctype':'multipart/form-data') }}#}
            <div class="panel panel-default">
                <div class="panel-heading">{{ title_bar }}</div>
                <div class="panel-body">
                    <div class="form-group row">
                        <div class="col-md-2"><b>Phương thức thanh toán</b></div>
                        <div class="col-md-10">
                            {% if item.payment_method == 1 %}
                                {{ word._('_thanh_toan_khi_nhan_hang') }}
                            {% else %}
                                {{ word._('_thanh_toan_chuyen_khoan_qua_ngan_hang') }}
                            {% endif %}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Mã đơn hàng</b></div>
                        <div class="col-md-10">{{ item.code }}</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Họ tên</b></div>
                        <div class="col-md-10">{{ item.name }}</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Số điện thoại</b></div>
                        <div class="col-md-10">{{ item.phone }}</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Email</b></div>
                        <div class="col-md-10">{{ item.email }}</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Địa chỉ</b></div>
                        <div class="col-md-10">{{ item.address }}</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Ghi chú khách hàng</b></div>
                        <div class="col-md-10">{{ item.comment }}</div>
                    </div>
                </div>
            </div>
            {% set order_info = json_decode(item.order_info) %}
            <div class="panel panel-default">
                <div class="panel-heading">Danh sách sản phẩm</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên</th>
                                <th>Hình ảnh</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng giá</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for key,value in order_info %}
                                {% set currency = (value.currency is not empty) ? value.currency : 'VNĐ' %}
                                {% if cf['_cf_text_price_text'] != '' %}
                                    {% if cf['_cf_radio_price_unit_position'] == 1 %}
                                        {% set price = cf['_cf_text_price_text'] ~ tag.number_format(value.price) %}
                                        {% set total = cf['_cf_text_price_text'] ~ tag.number_format(value.total) %}
                                    {% else %}
                                        {% set price = tag.number_format(value.price) ~ cf['_cf_text_price_text'] %}
                                        {% set total = tag.number_format(value.total) ~ cf['_cf_text_price_text'] %}
                                    {% endif %}
                                {% else %}
                                    {% set price = tag.number_format(value.price) ~ " " ~ currency %}
                                    {% set total = tag.number_format(value.total) ~ " " ~ currency %}
                                {% endif %}
                                <tr>
                                    <th scope="row">{{ key + 1 }}</th>
                                    <td>
                                        <p><a href="{{ value.link }}" target="_blank">{{ value.name }}</a></p>
                                        {% if value.options is defined %}
                                        {% set options = value.options %}
                                        <div class="optionsCart text-left">
                                            {% for k,option in options %}
                                            <div class="form-group">
                                                - <span>{{ k }}:</span> <b class="text-danger">{{ option }}</b>
                                            </div>
                                            {% endfor %}
                                        </div>
                                        {% endif %}
                                     </td>
                                    <td class="text-center">{{ image(value.photo, "width":100) }}</td>
                                    <td>{{ price }}</td>
                                    <td>{{ value.qty }}</td>
                                    <td>{{ total }}</td>
                                </tr>
                            {% endfor %}
                                <tr>
                                    <td colspan="5" class="text-right">Tổng tiền</td>
                                    {% if cf['_cf_text_price_text'] != '' %}
                                        {% if cf['_cf_radio_price_unit_position'] == 1 %}
                                            {% set totalCart = cf['_cf_text_price_text'] ~ tag.number_format(item.total) %}
                                        {% else %}
                                            {% set totalCart = tag.number_format(item.total) ~ cf['_cf_text_price_text'] %}
                                        {% endif %}
                                    {% else %}
                                        {% set totalCart = tag.number_format(item.total) ~ " " ~ currencyOdr %}
                                    {% endif %}
                                    <td>{{ totalCart }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{ form('role':'form') }}
            <div class="panel panel-default">
                <div class="panel-heading">Thiết lập</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Ghi chú</label>
                                {{ form.render("note",{'class':'form-control'}) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Tình trạng</label>
                                {{ form.render("order_status",{'class':'form-control'}) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ submit_button("Lưu & Đóng", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}
                {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "?active=order", "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>