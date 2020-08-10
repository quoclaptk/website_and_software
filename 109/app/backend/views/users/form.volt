<div class="cart-box-container">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ title_bar }}</h4>
    </div>

    {{ content() }}
    {{ form("role":"form", "action":"hi/users/createsubdomain", "id":"form-create-domain") }}
    <div class="modal-body">

        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="subdomain_name">Tên domain</label>
                            {{ form.render("subdomain_name",{'class':'form-control','id':'subdomain_name'}) }}
                            {{ form.messages('subdomain_name') }}
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            {{ form.render("username",{'class':'form-control','id':'username'}) }}
                            {{ form.messages('username') }}
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            {{ form.render("password",{'class':'form-control','id':'password'}) }}
                            {{ form.messages('password') }}
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">Xác nhận mật khẩu</label>
                            {{ form.render("confirmPassword",{'class':'form-control','id':'confirmPassword'}) }}
                            {{ form.messages('confirmPassword') }}
                        </div>
                    </div>
                </div>


            </div><!-- /.box -->

        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" name="ok">Tạo web</button>
        {{ link_to("javascript:;", "Đóng", "class": "btn btn-danger", "data-dismiss":"modal") }}
    </div>
    {{ endform() }}
</div>