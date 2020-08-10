<div class="cart-box-container">
    <div id="errorAddExpired"></div>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ title_bar }}</h4>
    </div>
    <div class="col-md-12">
        <ul id="info-balance">
            <li>Số dư hiện tại: <strong class="text-danger">{{ number_format(userCurrent.balance, 0, ',', '.') }}</strong> đ</li>
            <li>Số tiền bị trừ khi gia hạn website(số tiền x số năm): <strong class="text-danger">{{ number_format(money_minus, 0, ',', '.') }}</strong> đ</li>
        </ul>
    </div>
    <div class="clear"></div>
    {% if userCurrent.balance - money_minus > min_balance %}
    {{ form('role':'form','action':ACP_NAME ~ '/subdomain/addExpiredDate/' ~ item.id, 'id':'form-add-expired-domain') }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Thời gian</label>
                    <select name="year" class="form-control">
                        {% for i in 1..3 %}
                        <option value="{{ i }}">{{ i ~ ' năm' }}</option>
                        {% endfor %}
                    </select>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" name="ok">Lưu</button>
        {{ link_to("javascript:;", "Đóng", "class": "btn btn-danger", "data-dismiss":"modal") }}
    </div>
    {{ endform() }}
    {% else %}
    <div class="alert alert-warning">Bạn không đủ số dư để gia hạn website. Vui lòng nạp thêm tiền vào tài khoản hoặc liên hệ nhà phát triển.</div>
    {% endif %}
</div>
