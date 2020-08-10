<style type="text/css">
.ajaxfileuploadimg {margin-left: 10px;border: 1px solid #ccc;position: relative;}
.ajaxfileuploadimg .img-upload-editor {width: 92px; height: 92px;border:1px solid #ccc;margin-right: 7px;position: relative;display: table;margin-bottom: 10px}
.ajaxfileuploadimg .img-upload-editor > p {display: table-cell;vertical-align: middle;}
.ajaxfileuploadimg img {max-width:100%;max-height: 90px;}
.ajaxfileuploadimg .delete_img_upload_editor {position: absolute;color: #f00;top: -13px;right: -7px;font-size: 17px;}
</style>
{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flash.output() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            {{ form.render("id") }}
            <div class="panel panel-default">
                <div class="panel-heading">{{ title_bar }}</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">

                            <div class="form-group">
                                <label for="name">Tên<span class="text-danger">(*)</span></label>
                                {{ form.render("name",{'class':'form-control set_url','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>

                            <div class="form-group">
                                <label for="name">Email</label>
                                {{ form.render("email",{'class':'form-control set_url','id':'email'}) }}
                                {{ form.messages('email') }}
                            </div>

                            <div class="form-group">
                                <label for="name">SĐT</label>
                                {{ form.render("phone",{'class':'form-control set_url','id':'phone'}) }}
                                {{ form.messages('phone') }}
                            </div>

                            <div class="form-group">
                                <label for="name">Địa chỉ</label>
                                {{ form.render("address",{'class':'form-control set_url','id':'email'}) }}
                            </div>

                            <div class="form-group">
                                <label for="name">Hình ảnh</label>
                                <div class="customer_photo_upload clearfix">{{ partial('partials/ajaxuploadsingle', ['id':'customer_photo', 'type':'cdn', 'folder':folder]) }}</div>
                                <input type="hidden" name="cc_f_photo"{% if item is defined and item.photo != '' %} value="{{ item.photo }}"{% endif %}>
                                {% if item is defined and item.photo != '' %}
                                <p></p>
                                <label>Hình hiện tại</label>
                                <img src="{{ item.photo }}" width="100px">
                                {% endif %}
                            </div>

                            <div class="form-group">
                                <label for="description">Nội dung</label>
                                {{ form.render("comment",{'class':'form-control','id':'comment'}) }}
                            </div>

                            <div class="form-group">
                                <label for="sort">Thứ tự</label>
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>

                            <div class="form-group">
                                <label for="active">Hiển thị</label>
                                {{ form.render("active",{'class':'form-control', 'style':'width:100px'}) }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ submit_button("Lưu & Thêm Mới", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}
                {{ submit_button("Lưu & Đóng", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}
                {{ link_to(ACP_NAME ~ "/orders?active=customer_comment", "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>