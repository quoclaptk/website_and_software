<div class="box_register_member">
    {{ partial("partials/breadcrumb") }}
    <div class="content_front_page">
    	<div class="title_bar_center text-uppercase bar_web_bgr"><h1>{{ title_bar }}</h1></div>
    	<div class="box_register_member_frm">
    		<div class="panel panel-success">
                <div class="panel-heading">
                    <div class="panel-title">{{ title_bar }}</div>
                    <div class="signup_login_link">{{ word._('_ban_da_la_thanh_vien') }} {{ link_to(tag.site_url('dang-nhap'), word._('_dang_nhap')) }} {{ word._('_tai_day') }}</div>
                </div>  
                <div class="panel-body">
                	{{ content() }}
                	{{ form('role':'form', 'name':'frm_register', 'class': 'form-horizontal') }}
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email(<span class="red">*</span>)</label>
                            <div class="col-md-9">
                                <div class="input-group">
    								<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
    								{{ form.render("email",{'class':'form-control'}) }}
    				            </div>
    				            {{ form.messages('email') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ word._('_ten_dang_nhap') }}(<span class="red">*</span>)</label>
                            <div class="col-md-9">
                                <div class="input-group">
    								<span class="input-group-addon"><i class="fa fa-user"></i></span>
    								{{ form.render("username",{'class':'form-control'}) }}
    				            </div>
    				            {{ form.messages('username') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ word._('_mat_khau') }}(<span class="red">*</span>)</label>
                            <div class="col-md-9">
                                <div class="input-group">
    								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
    								{{ form.render("password",{'class':'form-control'}) }}
    				            </div>
    				            {{ form.messages('password') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ word._('_nhap_lai_mat_khau') }}(<span class="red">*</span>)</label>
                            <div class="col-md-9">
                                <div class="input-group">
    								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
    								{{ form.render("confirmPassword",{'class':'form-control'}) }}
    				            </div>
    				            {{ form.messages('confirmPassword') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-9">
                            	{{ form.render('csrf', ['value': security.getSessionToken()]) }}
    							{{ form.messages('csrf') }} 
                                <button id="btn-signup" type="submit" class="btn btn-primary bar_web_bgr">{{ word._('_dang_ky') }}</button>
                            </div>
                        </div>
                    {{ endform() }}
                 </div>
            </div>
    	</div>
    </div>
</div>