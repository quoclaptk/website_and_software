{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            <div class="panel panel-default">
                <div class="panel-heading">{{ title_bar }}</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Tên</label>
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                {{ form.render("email",{'class':'form-control','id':'email'}) }}
                                {{ form.messages('email') }}
                            </div>

                            <div class="form-group">
                                <label for="zalo">Zalo</label>
                                {{ form.render("zalo",{'class':'form-control','id':'zalo'}) }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Số điện thoại</label>
                                {{ form.render("phone",{'class':'form-control','id':'phone'}) }}
                                {{ form.messages('phone') }}
                            </div>

                            <div class="form-group">
                                <label for="skype">Skype</label>
                                {{ form.render("skype",{'class':'form-control','id':'skype'}) }}
                            </div>

                            <div class="form-group">
                                <label for="messenger">Messenger(Facebook)</label>
                                {{ form.render("messenger",{'class':'form-control','id':'messenger'}) }}
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sort">Thứ tự</label>
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>

                            <div class="form-group">
                                <label for="active">Hiển thị</label>
                                {{ form.render("active") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ submit_button("Lưu & Thêm Mới", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}
                {{ submit_button("Lưu & Đóng", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}
                {{ link_to(ACP_NAME ~ "/" ~ controller_name, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
