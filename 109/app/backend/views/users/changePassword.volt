{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Thay đổi mật khẩu</h3>
                </div>
                {{ content() }}

                <form method="post" autocomplete="off" action="">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="box-body">

                                <div class="form-group">
                                    <label for="password">Mật khẩu</label>
                                    {{ form.render("password",{'class':'form-control','id':'password'}) }}
                                    {{ form.messages('password') }}
                                </div>

                                <div class="form-group">
                                    <label for="confirmPassword">Xác nhận mật khẩu</label>
                                    {{ form.render("confirmPassword",{'class':'form-control','id':'confirmPassword'}) }}
                                    {{ form.messages('confirmPassword') }}
                                </div>

                                <div class="form-group">
                                    {{ submit_button("Change Password", "class": "btn btn-primary", "id":"submit-change-pass") }}
                                </div>

                            </div>
                        </div>
                    </div>

                </form>
        
            </div>
        </div>
    </div>
</section>