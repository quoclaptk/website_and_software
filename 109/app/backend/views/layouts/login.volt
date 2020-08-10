<div class="login-box-admin">
    <div class="login-box">
        <div class="login-logo">
            <b>{{ messageAdFile._('_administrator') }}</b>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">{{ messageAdFile._('_dang_nhap_de_bat_dau') }}</p>
            {{ form() }}
            <div class="form-group has-feedback">
                {{form.render('username',{'class': 'form-control', 'placeholder': messageAdFile._('_username')})}}
                {{form.messages('username')}}
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                {{ form.render('password',{'class': 'form-control', 'placeholder': messageAdFile._('_password')}) }}
                {{ form.messages('password') }}
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            {{ form.render('remember') }}
                            {{ messageAdFile._('_ghi_nho') }}
                        </label>
                    </div>
                    {{ form.render('csrf', ['value': security.getSessionToken()]) }}
                    {{ form.messages('csrf') }}
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ messageAdFile._('_dang_nhap') }}</button>
                </div>
            </div>
            {{ endform() }}

        </div>
    </div>
</div>
