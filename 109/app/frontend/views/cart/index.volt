{% if demo_router is defined %}
    {% if languageCode == 'vi' %}
        {% set linkContinued = tag.site_url(demo_router) %}
        {% set linkCheckout = tag.site_url(demo_router ~ '/thanh-toan') %}
    {% else %}
        {% set linkContinued = tag.site_url(demo_router ~ '/' ~ languageCode) %}
        {% set linkCheckout = tag.site_url(demo_router ~ '/' ~ languageCode ~ '/checkout') %}
    {% endif %}
{% else %}
    {% if languageCode == 'vi' %}
        {% set linkContinued = tag.site_url() %}
        {% set linkCheckout = tag.site_url('thanh-toan') %}
    {% else %}
        {% set linkContinued = tag.site_url(languageCode) %}
        {% set linkCheckout = tag.site_url(languageCode ~ '/checkout') %}
    {% endif %}
{% endif %}
<div class="content_main">
    {{ partial("partials/breadcrumb") }}
    <div class="node-content">
        <div class="text-uppercase title txt_web_color"><h1>{{ title_bar }}</h1></div>
        {% if cart|length > 0 %}
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
                        <th rowspan="1" class="a-center">{{ word['_xoa'] }}</th>
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
                        <td class="a-center">
                            <input type="number" name="cart_qty" value="{{ qty }}" maxlength="3" min="1" data-id="{{ id }}" data-currency="{{ currency }}" data-price="{{ i["price"] }}" class="input_qty">
                        </td>
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
                        </td>
                        <td class="a-center"><a class="red event-remove-item" href="javascript:;" data-rowid="{{ rowId }}"><i class="fa fa-times" aria-hidden="true"></i></a></td>
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
        <div class="box_btn_cart">
            <div class="row">
                <a href="{{ linkContinued }}" class="button-green" data-dismiss="modal">
                    <span class="group-icon bar_web_bgr"><i class="fa fa-shopping-cart"></i></span>
                    <span class="group-title bar_web_bgr">{{ word['_tiep_tuc_mua_sam'] }}</span>
                </a>
                <a href="{{ linkCheckout }}" class="button-red button-right">
                    <span class="group-icon"><i class="fa fa-chevron-right"></i></span>
                    <span class="group-title">{{ word['_thanh_toan'] }}</span>
                </a>
            </div>
        </div>
        {% else %}
        <div class="alert alert-success">{{ word['_hien_chua_co_san_pham_nao_trong_gio_hang'] }}</div>
        <div class="text-left">
            <a href="{{ linkContinued }}" class="button-green" data-dismiss="modal">
                <span class="group-icon bar_web_bgr"><i class="fa fa-shopping-cart"></i></span>
                <span class="group-title bar_web_bgr">{{ word['_tiep_tuc_mua_sam'] }}</span>
            </a>
        </div>
        {% endif %}
    </div>
</div>