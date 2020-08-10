<div class="box_register_member">
    {{ partial("partials/breadcrumb") }}
    <div class="content_front_page">
        <div class="title_bar_center text-uppercase bar_web_bgr"><h1>{{ title_bar }}</h1></div>
        <div class="box_member_info">
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group list-group-member">
                        <a href="{{ tag.site_url(_url_account) }}" class="list-group-item active">{{ ucfirst(word._('_thong_tin_tai_khoan')) }}</a>
                        <a href="{{ tag.site_url(_url_change_pass) }}" class="list-group-item">{{ ucfirst(word._('_doi_mat_khau')) }}</a>
                        <a href="{{ tag.site_url(_url_order_history) }}" class="list-group-item">{{ ucfirst(word._('_lich_su_don_hang')) }}</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="panel-title">{{ word._('_cap_nhat_thong_tin_ca_nhan') }}</div>
                        </div>  
                        <div class="panel-body">
                            {{ flashSession.output() }}
                            {{ form('role':'form', 'name':'frm_account', 'class': 'form-horizontal') }}
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Email</label>
                                    <div class="col-md-9">
                                        {{ form.render("email",{'class':'form-control','disabled':true}) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Username</label>
                                    <div class="col-md-9">
                                        {{ form.render("username",{'class':'form-control','disabled':true}) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">{{ word['_ho_ten'] }}</label>
                                    <div class="col-md-9">
                                        {{ form.render("fullName",{'class':'form-control','placeholder':''}) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">{{ word['_dien_thoai'] }}</label>
                                    <div class="col-md-9">
                                        {{ form.render("phone",{'class':'form-control','placeholder':''}) }}
                                        {{ form.messages('phone') }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">{{ word._('_dia_chi') }}</label>
                                    <div class="col-md-9">
                                        {{ form.render("address",{'class':'form-control','placeholder':''}) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-9">
                                        {{ form.render('csrf', ['value': security.getSessionToken()]) }}
                                        {{ form.messages('csrf') }} 
                                        <button type="submit" class="btn btn-primary bar_web_bgr">{{ word._('_cap_nhat') }}</button>
                                    </div>
                                </div>
                            {{ endform() }}
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>