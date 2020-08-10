{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div id="errorValid">
                {{ content() }}
            </div>
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            <div class="panel panel-default">
                <div class="panel-heading">{{ title_bar }}</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            {% if tmpSubdomainLanguages|length > 0 %}
                            <ul id="langTab" class="nav nav-tabs">
                                <li class="active">
                                    <a href="#vi" data-toggle="tab" class="btn btn-info">Việt Nam</a>
                                </li>
                                {% for tmp in tmpSubdomainLanguages %}
                                {% if tmp.language.code != 'vi' %}
                                    <li>
                                        <a href="#{{ tmp.language.code }}" data-toggle="tab" class="btn btn-info">{{ tmp.language.name }}</a>
                                    </li>
                                {% endif %}
                                {% endfor %}
                            </ul>
                            {% endif %}
                            <div id="myTabContent" class="tab-content">
                                <div class="tab-pane fade in active" id="vi">
                                    <div class="form-group">
                                        <label for="name">Tên</label>
                                        {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="link">Link</label>
                                        {{ form.render("link",{'class':'form-control','id':'link'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="file">Upload hình ảnh</label>
                                        {{ form.render("photo",{'id':'photo'}) }}
                                        <p></p>
                                        <label class="text-danger">- Ghi chú: Để up ảnh không bị mờ, ảnh cần nhẹ hơn 500kb | <a href="https://docs.google.com/document/d/1zaf8d_BAFSI41l413th-UulivgLU99yP9_Yo_EUDtwc/edit" target="_blank" style="text-decoration: underline;color: #f00"><i>Click xem hướng dẫn</i></a></label>
                                        {% if item is defined %}{{ image('files/ads/' ~ SUB_FOLDER ~ '/' ~ item.photo , 'width':'100', 'style':'margin-top:10px') }}{% endif %}
                                    </div>
                                </div>
                                {% for tmp in tmpSubdomainLanguages %}
                                {% if tmp.language.code != 'vi' %}
                                {% set langCode = tmp.language.code %}
                                {% set langName = tmp.language.name %}
                                <div class="tab-pane fade" id="{{ langCode }}">
                                    <div class="form-group">
                                        <label >Tên</label>
                                        {{ form.render("name_" ~ langCode,{'class':'form-control'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label>Link</label>
                                        {{ form.render("link_" ~ langCode,{'class':'form-control','id':'link'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label>Upload hình ảnh</label>
                                        {{ form.render("photo_" ~ langCode) }}
                                        <p></p>
                                        <label class="text-danger">- Ghi chú: Kích thước ảnh upload tối đa là: 500kb</label>
                                        {% if itemLangData is defined %}{{ image('files/ads/' ~ SUB_FOLDER ~ '/' ~ itemLangData[langCode].photo , 'width':'100', 'style':'margin-top:10px') }}{% endif %}
                                    </div>
                                </div>
                                {% endif %}
                                {% endfor %}
                            </div>

                            <div class="form-group">
                                <label for="sort">Thứ tự</label>
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>

                            <div class="form-group">
                                <label for="active">Active</label>
                                {{ form.render("active",{'class':'form-control','style':'width:80px'}) }}
                            </div>
                        </div>
                        {% if banner_type != '' %}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="link">Loại banner</label>
                                <ul class="list_category">
                                    {% for i in banner_type %}
                                        <li>
                                            <input type="checkbox" name="banner_type[]" value="{{ i.id }}"{% if tmp_banner_banner_type_arr is defined %}{% if i.id in tmp_banner_banner_type_arr %}checked="checked"{% endif %}{% endif %}>
                                            <span>{{ i.name }}</span>
                                        </li>
                                    {% endfor %}
                                </ul>
                            </div>
                        </div>
                        {% endif %}

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


