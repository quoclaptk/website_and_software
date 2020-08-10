<div class="cart-box-container">
    {{ flashSession.output() }}
    {% if message == "success" %}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Đã thêm <strong style="color: #f00">{{ domain }}</strong> cho website <strong style="color:#f00">{{ item.name ~ '.' ~ ROOT_DOMAIN }}</strong></h4>
    </div>
    <div class="modal-body">
        <div class="help-block" style="margin-bottom: 0">
            <ul>
                <li>
                    <div class="form-group">Đăng nhập quản lý tên miền {{ domain }} và cấu hình DNS như sau ( Bạn cần chờ 15 phút hoặc tối đa 4h để tên miền hoạt động):</div>
                    <div class="alert" role="alert" style="margin:0;border-top: 1px solid #ccc">
                        <div>Recored Type: <strong style="color: #0000FF">A</strong></div>
                        <div>Host: <strong style="color: #0000FF">@</strong></div>
                        <div>Value: <strong style="color: #0000FF">{{ ipAdress }}</strong></div>
                    </div>
                    <div class="alert" role="alert" style="margin-top:0;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc">
                        <div>Recored Type: <strong style="color: #0000FF">CNAME</strong></div>
                        <div>Host: <strong style="color: #0000FF">www</strong></div>
                        <div>Value: <strong style="color: #0000FF">{{ domain }}</strong></div>
                    </div>
                    {#<div class="alert alert-info" role="alert">
                        <div>Recored Type: TXT</div>
                        <div>Host: @</div>
                        <div>Value: bfb3e6dcc3c1eff8ed6a0044ad7415a8</div>
                    </div>#}
                    <label>Hình minh họa</label>
                    <div class="text-center">{{ image('backend/dist/img/add-domain.png') }}</div>
                </li>
            </ul>
        </div>
    </div>
    <div class="modal-footer" style="text-align: center">
        {{ link_to("javascript:;", "Đóng", "class": "btn btn-danger", "data-dismiss":"modal") }}
    </div>
    {% else %}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ title_bar }}</h4>
    </div>
    <div class="col-md-12">
        <ul id="info-balance">
            <li>Số dư hiện tại: <strong class="text-danger">{{ number_format(userCurrent.balance, 0, ',', '.') }}</strong> đ</li>
            <li>Số tiền bị trừ khi thêm tên miền: <strong class="text-danger">{{ number_format(money_minus, 0, ',', '.') }}</strong> đ</li>
        </ul>
    </div>
    <div class="clear"></div>
    {% if userCurrent.balance - money_minus > min_balance %}
    {{ form('role':'form','action':ACP_NAME ~ '/subdomain/addDomain/' ~ item.id, 'id':'form-add-domain') }}
    <div class="modal-body">
        <div class="form-group">
            <label for="name">Tên miền<span class="text-danger">(*)</span></label>
            <p class="text-danger bold">Chú ý: Hãy nhập tenmien.com thay vì http://tenmien.com</p>
            {{ form.render("name",{'class':'form-control'}) }}
            {{ form.messages('name') }}
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-add-domain ladda-button" data-style="slide-left" name="ok">Thêm</button>
        {{ link_to("javascript:;", "Đóng", "class": "btn btn-danger btn-close-form-domain", "data-dismiss":"modal") }}
    </div>
    {{ endform() }}
    {% else %}
    <div class="alert alert-warning">Bạn không đủ số dư để thêm tên miền mới. Vui lòng nạp thêm tiền vào tài khoản hoặc liên hệ nhà phát triển.</div>
    {% endif %}
    {% endif %}
</div>
