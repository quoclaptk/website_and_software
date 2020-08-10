<div class="cart-box-container" style="position: relative">
    <div id="form-subdomain-loading" class="ajax_loadding"></div>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ title_bar }}</h4>
    </div>
    <div class="col-md-12">
        <ul id="info-balance">
            <li>Số dư hiện tại: <strong class="text-danger">{{ number_format(userCurrent.balance, 0, ',', '.') }}</strong> đ</li>
            <li>Số tiền bị trừ khi tạo web mới: <strong class="text-danger">{{ number_format(money_minus, 0, ',', '.') }}</strong> đ</li>
            {% if sharePrice is defined %}
            {#<li>Phí copy website: <strong class="text-danger">{{ number_format(sharePrice, 0, ',', '.') }}</strong> đ</li>#}
            {% endif %}
        </ul>
    </div>
    <div class="clear"></div>
    {% if userCurrent.balance - money_minus > min_balance %}
    {{ form("role":"form", "action":ACP_NAME ~ "/subdomain/" ~ action, "id":"form-create-domain") }}
    <div class="modal-body">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Tên domain</label>
                            <div class="row">
                                <div class="col-md-8">
                                    {{ form.render("name",{'class':'form-control sub-domain','id':'name'}) }}
                                </div>
                                <div class="col-md-4"><label style="margin-top: 7px;color: #f00">{{ mainGlobal.getRootDomain() }}</label></div>
                            </div>
                            {{ form.messages('name') }}
                        </div>

                        <div class="form-group">
                            <label for="name">Tên đăng nhập</label>
                            {{ form.render("username",{'class':'form-control sub-username','id':'username', 'readonly':'true'}) }}
                            {{ form.messages('username') }}
                        </div>

                        <div class="form-group">
                            <label for="name">Mật khẩu</label>
                            {{ form.render("password",{'class':'form-control sub-password','id':'password', 'value':password}) }}
                            {{ form.messages('password') }}
                        </div>

                        {#<div class="form-group">
                            <label for="confirmPassword">Xác nhận mật khẩu</label>
                            {{ form.render("confirmPassword",{'class':'form-control','id':'confirmPassword'}) }}
                            {{ form.messages('confirmPassword') }}
                        </div>#}
                    </div>
                </div>


            </div><!-- /.box -->

        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="btn-create-website" name="ok">Tạo web</button>
        {{ link_to("javascript:;", "Đóng", "class": "btn btn-danger", "data-dismiss":"modal") }}
    </div>
    {{ endform() }}
    {% else %}
    <div class="alert alert-warning">Bạn không đủ số dư để tạo web mới. Vui lòng nạp thêm tiền vào tài khoản hoặc liên hệ nhà phát triển.</div>
    {% endif %}
</div>