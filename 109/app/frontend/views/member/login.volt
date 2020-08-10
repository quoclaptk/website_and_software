<div class="box_register_member">
    {{ partial("partials/breadcrumb") }}
    <div class="content_front_page">
        <div class="title_bar_center text-uppercase bar_web_bgr"><h1>{{ title_bar }}</h1></div>
        <div class="box_login_member_frm">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="panel-title">{{ title_bar }}</div>
                    <div class="signup_login_link">{{ word._('_ban_chua_co_tai_khoan') }} {{ link_to(tag.site_url('dang-ky'), word._('_dang_ky')) }} {{ word._('_tai_day') }}</div>
                </div>  
                <div class="panel-body">
                    {{ form('role':'form', 'name':'frm_login', 'class': 'form-horizontal') }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    {{ form.render("username",{'class':'form-control'}) }}
                                </div>
                                {{ form.messages('username') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    {{ form.render("password",{'class':'form-control'}) }}
                                </div>
                                {{ form.messages('password') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="checkbox">
                                        <label>
                                          <input id="login-remember" type="checkbox" name="remember" value="1"> {{ word._('_ghi_nho') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                {{ form.render('csrf', ['value': security.getSessionToken()]) }}
                                {{ form.messages('csrf') }} 
                                <button id="btn-login" type="submit" class="btn btn-primary bar_web_bgr">{{ word._('_dang_nhap') }}</button>
                            </div>
                        </div>
                    {{ endform() }}
                 </div>
            </div>
        </div>
    </div>
</div>