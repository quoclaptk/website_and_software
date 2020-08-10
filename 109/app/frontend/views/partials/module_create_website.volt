<div class="box-register-login-system">
	{% if position == "header" or position == "footer" %}<div class="container">{% endif %}
    {% if request.get('msg') == 'error_password' %}
    <div class="alert alert-danger">Mật khẩu bạn nhập không đúng với username bạn đăng nhập!</div>
    {% endif %}
    <section class="tabblue clearfix">
        <ul class="tabs blue">
            <li>
                <input type="radio" name="tabs blue" id="tab1" checked>
                <label for="tab1">Đăng nhập</label>
                <div id="box-tab-content1">
                    <div id="tab-content1" class="tab-content">
                        <form method="post" name="frm_system_login">
                            <div class="form-group">
                                <div class="clearfix">
                                    <span class="tabaddon"><i class="fa fa-user fa-2x"></i></span>
                                    <input class="field" name="l_username" required type="text" placeholder="Tên đăng nhập. VD: lamweb123">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="clearfix">
                                    <span class="tabaddon"><i class="fa fa-lock fa-2x"></i></span>
                                    <input class="field" name="l_password" required type="password" placeholder="Mật khẩu">
                                </div>
                            </div>
                            <div class="btn-form clearfix">
                                <input type="submit" id="btn_system_login" value="Đăng nhập">
                            </div>
                        </form>
                    </div>
                </div>
            </li>
            <li>
                <input type="radio" name="tabs blue" id="tab2">
                <label for="tab2">Đăng ký</label>
                <div id="box-tab-content2">
                    <div id="tab-content2" class="tab-content">
                        {#<p>You can sign up free at reverie tech and get super awesome services tips. It's what all the cool kids are doing nowadays.</p>#}
                        <form method="post" name="frm_system_register">
                            <div class="form-group">
                                <div class="clearfix">
                                    <span class="tabaddon"><i class="fa fa-envelope-open fa-2x" style="font-size:17px"></i></span>
                                    <input class="field sub-email" name="r_email" required type="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="clearfix">
                                    <span class="tabaddon"><i class="fa fa-mobile fa-2x" style="font-size:35px"></i></span>
                                    <input class="field sub-phone" name="r_phone" required type="text" placeholder="Số điện thoại">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="clearfix">
                                    <span class="tabaddon"><i class="fa fa-facebook-square fa-2x"></i></span>
                                    <input class="field sub-facebook" name="r_facebook" required type="text" placeholder="Facebook">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="clearfix">
                                    <span class="tabaddon"><i class="fa fa-globe fa-2x"></i></span>
                                    <input class="field sub-domain" name="domain" required type="text" placeholder="Tên miền. VD: lamweb123">
                                    <span class="text-center domain-name">.{{ ROOT_DOMAIN }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="clearfix">
                                    <span class="tabaddon"><i class="fa fa-user fa-2x"></i></span>
                                    <input class="field sub-username" name="r_username" required type="text" placeholder="Tên đăng nhập. VD: lamweb123">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="clearfix">
                                    <span class="tabaddon"><i class="fa fa-lock fa-2x"></i></span>
                                    <input class="field" name="r_password" required type="password" placeholder="Mật khẩu">
                                </div>
                            </div>
                            <div class="btn-form clearfix">
                                <button class="btn btn-primary ladda-button" id="btn-create-domain" data-style="slide-left"><span class="ladda-label">Đăng ký</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </section>
	{% if position == "header" or position == "footer" %}</div>{% endif %}
</div>