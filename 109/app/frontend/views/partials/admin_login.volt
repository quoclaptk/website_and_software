{% if position == "right" %}
    {% set box_class = "box_right_element" %}
{% elseif position == "left" %}
    {% set box_class = "box_left_element" %}
{% else %}
    {% set box_class = "box_inline_element" %}
{% endif %}
<div id="{{ html_id ~ "_" ~ id }}" class="{{ box_class }} hidden-sm hidden-xs">
    {% if position == 'left' and position == 'right'  %}
    <div class="title_bar_right bar_web_bgr text-uppercase form-group">Đăng nhập hệ thống</div>
    {% endif %}
    <div class="box_login_adm">
        {% if session.get('auth-identity') == '' %}
            {% if position == 'left' or position == 'right'  %}
            {{ form('role':'form', 'id':'frm_adm_login_' ~ position) }}
            <div class="form-group clearfix">
                <input type="text" class="form-control" placeholder="Username" name="adm_username_{{ position }}">
            </div>
            <div class="form-group clearfix">
                <input type="password" class="form-control" placeholder="Password" name="adm_password_{{ position }}">
            </div>
            <div id="error_adm_login_{{ position }}"></div>
            <div class="form-group clearfix text-right">
                <input type="hidden" name="ok_{{ position }}" value="ok">
                <input type="submit" id="adm_btn_{{ position }}" class="btn btn-sm btn-success" value="{{ word['_dang_nhap'] }}">
            </div>
            {{ endform() }}
            {% endif %}
            {% if position == 'header' or position == 'footer' %}
            <div class="container frm_adm_login_inline">
                <div class="pull-right">
                    {{ form('role':'form', 'id':'frm_adm_login_' ~ position, 'class':'form-inline') }}
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Username" name="adm_username_{{ position }}">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="adm_password_{{ position }}">
                    </div>
                    <input type="hidden" name="ok_{{ position }}" value="ok">
                    <input type="submit" id="adm_btn_{{ position }}" class="btn btn-sm btn-success" value="{{ word['_dang_nhap'] }}">
                    <div id="error_adm_login_{{ position }}"></div>
                    {{ endform() }}
                </div>
            </div>
            {% endif %}
            {% if position == 'center' %}
            <div class="frm_adm_login_inline clearfix">
                <div class="pull-right">
                    {{ form('role':'form', 'id':'frm_adm_login_' ~ position, 'class':'form-inline') }}
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Username" name="adm_username_{{ position }}">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="adm_password_{{ position }}">
                    </div>
                    <input type="hidden" name="ok_{{ position }}" value="ok">
                    <input type="submit" id="adm_btn_{{ position }}" class="btn btn-sm btn-success" value="{{ word['_dang_nhap'] }}">
                    <div id="error_adm_login_{{ position }}"></div>
                    {{ endform() }}
                </div>
            </div>
            {% endif %}
        {% else %}
        <div class="text-success{% if position != 'left' or position != 'right' %} text-center{% endif %}">Bạn đang đăng nhập. Click <a href="/hi" style="text-decoration: underline">vào đây</a> để vào trang quản trị</div>
        {% endif %}
    </div>
</div>