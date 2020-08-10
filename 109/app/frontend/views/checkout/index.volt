{% set layout = setting.layout_id %}

{% if layout == 2 %}
    {% set class = "col-md-4" %}
{% elseif layout == 1 %}
    {% set class = "col-md-3" %}
{% else %}
    {% set class = "col-md-4" %}
{% endif %}

<div class="content_main">
    {{ partial("partials/breadcrumb") }}
    <div class="node-content">
        <div class="text-uppercase title"><h1>{{ word['_thong_tin_don_hang'] }}</h1></div>
        <div class="cart table-responsive" id="box_cart_inner">
            <fieldset>
                <table id="shopping-cart-table" class="data-table cart-table">
                    <colgroup>
                        <col width="1">
                        <col>
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                    </colgroup>
                    <thead>
                    <tr>
                        <th rowspan="1">&nbsp;</th>
                        <th rowspan="1" style="text-align:left"><span class="nobr">{{ word['_san_pham'] }}</span></th>
                        <th rowspan="1" class="a-center" width="10%">{{ word['_so_luong'] }}</th>
                        <th class="a-center" colspan="1" width="15%"><span class="nobr">{{ word['_gia'] }}</span></th>
                        <th class="a-center" colspan="1" width="15%">{{ word['_thanh_tien'] }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% for i in cart %}
                        {% set rowId = i["rowId"] %}
                        {% set id = i["id"] %}
                        {% set qty = i["qty"] %}
                        {% set name = i["name"] %}
                        {% set link = i["link"] %}
                        {% set currency = i["currency"] is not empty ? i["currency"] : 'VNĐ' %}
                        {% set photo = i["photo"] %}
                        {% if cf['_cf_text_price_text'] != '' %}
                            {% if cf['_cf_radio_price_unit_position'] == 1 %}
                                {% set price = cf['_cf_text_price_text'] ~ tag.number_format(i["price"]) %}
                                {% set total = cf['_cf_text_price_text'] ~ tag.number_format(i["total"]) %}
                            {% else %}
                                {% set price = tag.number_format(i["price"]) ~ cf['_cf_text_price_text'] %}
                                {% set total = tag.number_format(i["total"]) ~ cf['_cf_text_price_text'] %}
                            {% endif %}
                        {% else %}
                            {% set price = tag.number_format(i["price"]) ~ " " ~ currency %}
                            {% set total = tag.number_format(i["total"]) ~ " " ~ currency %}
                        {% endif %}
                        <tr>
                            <td>
                                <a href="{{ link }}" target="_blank" class="product-image"><img src="{{ photo }}" width="70" alt="{{ name }}"></a>
                            </td>
                            <td>
                                <h2 class="product-name">
                                    <a href="{{ link }}" target="_blank">{{ name }}</a>
                                </h2>
                                {% if i["options"] is defined %}
                                {% set options = i["options"] %}
                                <div class="optionsCart text-left">
                                    {% for key,option in options %}
                                    <div class="form-group">
                                        - <span>{{ key }}:</span> <b>{{ option }}</b>
                                    </div>
                                    {% endfor %}
                                </div>
                                {% endif %}
                            </td>
                            <td class="a-center">{{ qty }}</td>
                            <td class="a-right">
                                <span class="cart-price">
                                    <span class="price">{{ price }}</span>
                                </span>
                            </td>
                            <!-- inclusive price starts here -->
                            <!--Sub total starts here -->
                            <td class="a-right">
                            <span class="cart-price">
                                <span class="price">{{ total }}</span>
                            </span>
                        </tr>
                    {% endfor %}
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td class="sum_cart">
                            <strong>{{ word['_tong_tien'] }}</strong>
                        </td>
                        <td colspan="2" class="sum_cart">
                           {% if cf['_cf_text_price_text'] != '' %}
                                {% if cf['_cf_radio_price_unit_position'] == 1 %}
                                    {% set totalCart = cf['_cf_text_price_text'] ~ tag.number_format(cart_service.getTotal()) %}
                                {% else %}
                                    {% set totalCart = tag.number_format(cart_service.getTotal()) ~ cf['_cf_text_price_text'] %}
                                {% endif %}
                            {% else %}
                                {% set totalCart = tag.number_format(cart_service.getTotal()) ~ " " ~ currency %}
                            {% endif %}
                            <strong><span class="price" id="price_total">{{ totalCart }}</span></strong>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </fieldset>
        </div>
        <div class="box_checkout">
            <div class="text-uppercase title"><h1>{{ word['_thanh_toan'] }}</h1></div>
            <div class="form-checkout">
                {{ content() }}
                {{ form('role':'form', 'name':'frm_checkout') }}
                    <div class="form-group">
                        <div class="radio">
                          <label>
                            <input type="radio" name="payment_method" value="1" checked>
                            <span class="txt_payment_method">{{ word._('_thanh_toan_khi_nhan_hang') }}</span>
                          </label>
                        </div>
                        {% if word._('_ghi_chu_nhan_hang_tai_nha') is not null %}
                        <div class="note_payment_method note_payment_method_1">{{ word._('_ghi_chu_nhan_hang_tai_nha') }}</div>
                        {% endif %}
                        <div class="radio">
                          <label>
                            <input type="radio" name="payment_method" value="2">
                            <span class="txt_payment_method">{{ word._('_thanh_toan_chuyen_khoan_qua_ngan_hang') }}</span>
                          </label>
                        </div>
                        {% if setting.note_payment_method_2 is not null %}
                        <div class="note_payment_method note_payment_method_2">{{ setting.note_payment_method_2 }}</div>
                        {% endif %}
                    </div>
                    <div class="form-group">
                        <label for="inputFullName">{{ word['_ho_ten'] }}(<span class="red">*</span>):</label>
                        {{ form.render("name",{'class':'form-control', 'value':fullName, 'placeholder':word['_ho_ten']}) }}
                        {{ form.messages('name') }}
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Email:</label>
                        {{ form.render("email",{'class':'form-control', 'value':email}) }}
                    </div>

                    <div class="form-group">
                        <label for="inputPhone">{{ word['_dien_thoai'] }}(<span class="red">*</span>):</label>
                        {{ form.render("phone",{'class':'form-control', 'value':phone, 'placeholder':word['_dien_thoai']}) }}
                        {{ form.messages('phone') }}
                    </div>

                    <div class="form-group">
                        <label for="inputAddress">{{ word['_dia_chi'] }}(<span class="red">*</span>):</label>
                        {{ form.render("address",{'class':'form-control', 'value':address, 'placeholder':word['_dia_chi']}) }}
                        {{ form.messages('address') }}
                    </div>

                    <div class="form-group">
                        <label for="inputNote">{{ word['_ghi_chu'] }}:</label>
                        {{ form.render("comment",{'class':'form-control','placeholder':word['_ghi_chu']}) }}
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-success" value="{{ word['_gui_don_hang'] }}">
                    </div>
                {{ endform() }}
            </div>
            {% if setting.contact != '' %}
            <div id="contact_content">{{ setting.contact }}</div>
            {% endif %}
            {% if setting.enable_contact_default == 1 %}
            <div id="content_company_info_contact">
                <ul>
                    {% if setting.name != "" %}
                    <li class="text-uppercase">{{ setting.name }}</li>
                    {% endif %}
                    {% if setting.address != "" %}
                    <li class="clearfix">
                        <i class="icon-dia-chi"></i>
                        <span>{{ setting.address }}</span>
                    </li>
                    {% endif %}
                    {% if hotline != "" %}
                    <li class="clearfix">
                        <i class="icon-sodienthoai"></i>
                        <span><a href="tel:{{ hotline }}">{{ hotline }}</a></span>
                    </li>
                    {% endif %}
                    {% if setting.email != "" %}
                    <li class="clearfix">
                        <i class="icon-mail"></i>
                        <span><a href="mailto:{{ setting.email }}">{{ setting.email }}</a></span>
                    </li>
                    {% endif %}
                </ul>
            </div>
            {% endif %}
        </div>
    </div>
</div>