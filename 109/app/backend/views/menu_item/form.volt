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
                        <div class="col-md-3"><label for="name">Tiêu đề</label></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>
                        </div>
                    </div>

                    {% if item.module_name == 'link' %}
                    <div class="row">
                        <div class="col-md-3"><label for="name">Url</label></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ form.render("url",{'class':'form-control','id':'url'}) }}
                                {{ form.messages('url') }}
                            </div>
                        </div>
                    </div>
                    {% else %}
                    <div class="row">
                        <div class="col-md-3"><label>Link khác</label></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ form.render("other_url",{'class':'form-control','id':'other_url'}) }}
                                {{ form.messages('other_url') }}
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="url" value="{{ item.url }}">
                    {% endif %}

                    <div class="row">
                        <div class="col-md-3"><label for="name">Kiểu icon</label></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <input type="radio" name="icon_type" value="1" {% if item.icon_type == 1 %}checked{% endif %}> Font icon
                                </label>
                                <label>
                                    <input type="radio" name="icon_type" value="2" {% if item.icon_type == 2 %}checked{% endif %}> Hình ảnh
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><label for="name">Font icon(<a href="https://fontawesome.com/v4.7.0/icons/" target="_blank">Chọn font</a>)</label></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ form.render("font_class",{'class':'form-control'}) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><label for="name">Hình ảnh</label></div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    {{ form.render("photo",{'id':'photo'}) }}
                                </div>
                                <div class="col-md-6">
                                    {% if item.photo != '' %}
                                    {{ image('files/icon/' ~ SUB_FOLDER ~ '/' ~ item.photo , 'style':'width:16px;margin-top:10px') }}
                                    <p></p>
                                    <a href="/hi/menu_item/deletePhoto/{{ item.id }}" class="btn btn-sm btn-danger" onclick="if(!confirm('Bạn muốn xóa icon này?')) return false">Xóa</a>
                                    {% endif %}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><label for="name">Thứ tự</label></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><label for="name">Target new(Click chuyển cửa sổ mới)</label></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ form.render("new_blank", {"class":"form-control", "style":"width:90px"}) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><label for="name">Hiển thị</label></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ form.render("active", {"class":"form-control", "style":"width:90px"}) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ link_to(ACP_NAME ~ "/menu/update/" ~ item.menu_id, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>