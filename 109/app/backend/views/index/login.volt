<div class="login-box">
  <div class="login-logo">
    <b>Admin</b> Login
  </div>
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your administrator</p>
    {{ form() }}
      <div class="form-group has-feedback">
        {{form.render('username',{'class': 'form-control'})}}
        {{form.messages('username')}}
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        {{ form.render('password',{'class': 'form-control'}) }}
        {{ form.messages('password') }}
      <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
            {{ form.render('remember') }}
            Remember Me
            </label>
          </div>
          {{ form.render('csrf', ['value': security.getToken()]) }}
        </div>
        <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign in</button>
        </div>
      </div>
    {{ endform() }}

  </div>
</div>

