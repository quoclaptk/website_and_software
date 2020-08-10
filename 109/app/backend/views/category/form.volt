{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flash.output() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed','name':'form_multilang') }}
            {{ form.render("id") }}

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
                    <div class="panel panel-default">
                        <div class="panel-heading">{{ title_bar }}</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="parent_id">Danh mục cha</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="0">Chọn danh mục cha</option>
                                            {% for row in list %}
                                                {% set name = row.name %}
                                                {% if item is defined and item.parent_id == row.id %}
                                                    {% set selected = ' selected' %}
                                                {% else %}
                                                    {% set selected = '' %}
                                                {% endif %}

                                                {% if item is defined and (item.id == row.id or item.id == row.parent_id) %}
                                                    {% set disabled = ' disabled' %}
                                                {% else %}
                                                    {% set disabled = '' %}
                                                {% endif %}

                                                {% if row.level == 2 %}
                                                    {% set disabled_level = ' disabled' %}
                                                {% else %}
                                                    {% set disabled_level = '' %}
                                                {% endif %}

                                                <option value="{{ row.id }}"{{ selected ~ disabled ~ disabled_level}}>{{ name }}</option>
                                            {% endfor %}
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Tên<span class="text-danger">(*)</span></label>
                                        {{ form.render("name",{'class':'form-control set_url','id':'name'}) }}
                                        {{ form.messages('name') }}
                                    </div>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-2"><label for="banner">Kiểu icon</label></div>
                                        <div class="col-md-8">
                                            <label>
                                                <input type="radio" name="icon_type" value="1" {% if item is defined and item.icon_type == 1 %}checked{% else %}checked{% endif %}> Font icon
                                            </label>
                                            <label>
                                                <input type="radio" name="icon_type" value="2" {% if item is defined and item.icon_type == 2 %}checked{% endif %}> Hình ảnh
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2"><label for="name">Font icon (<a href="https://fontawesome.com/v4.7.0/icons/" target="_blank">Chọn font</a>)</label></div>
                                        <div class="col-md-6">
                                            {{ form.render("font_class",{'class':'form-control'}) }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-2"><label for="name">Hình icon</label></div>
                                        <div class="col-md-6">
                                            {{ form.render("icon") }}
                                            {% if item is defined and item.icon != '' %}
                                            {{ image('files/icon/' ~ SUB_FOLDER ~ '/' ~ item.icon ,  'style':'margin-top:10px;max-width:15px') }}
                                        {% endif %}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label for="banner">Banner</label>
                                            {{ form.render("banner",{'id':'banner'}) }}
                                            {{ form.messages('banner') }}
                                            {% if item is defined and item.banner != '' %}
                                                {{ image('files/category/' ~ SUB_FOLDER ~ '/' ~ item.banner ,  'style':'margin-top:10px;max-width:150px') }}
                                            {% endif %}
                                        </div>
                                        <div class="col-md-4">
                                            <label>
                                                {{ form.render("picture",{'class':'form-control', 'style':'width:50%'}) }} Hiện banner trong danh mục cha
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label>Banner menu sản phẩm sole</label>
                                            {{ form.render("banner_md_sole",{'id':'banner_md_sole'}) }}
                                            {{ form.messages('banner_md_sole') }}
                                            {% if item is defined and item.banner_md_sole != '' %}
                                                {{ image('files/category/' ~ SUB_FOLDER ~ '/' ~ item.banner_md_sole ,  'style':'margin-top:10px;max-width:150px') }}
                                            {% endif %}
                                        </div>
                                    </div>

                                    <div class="form-group editor-form">
                                        <label for="description">Nội dung</label>
                                        <p></p>
                                        {{ partial('partials/ajaxupload', ['id': row_id, 'type':'category']) }}
                                        <input type="hidden" name="row_id" value="{{ row_id }}">
                                        {{ form.render("content",{'class':'form-control','id':'content'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        {{ form.render("title",{'class':'form-control','id':'title'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="keywords">Keywords</label>
                                        {{ form.render("keywords",{'class':'form-control','id':'keywords'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        {{ form.render("description",{'class':'form-control','id':'description'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Đường dẫn<span class="text-danger">(*)</span></label>
                                        {{ form.render("slug",{'class':'form-control','readonly':true}) }}
                                        {{ form.messages('slug') }}
                                    </div>

                                    <div class="form-group">
                                        <label for="sort">Thứ tự</label>
                                        {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                        {{ form.messages('sort') }}
                                    </div>

                                    <div class="form-group">
                                        {#<div class="col-md-3">
                                            <label for="active">Nổi bật</label>
                                            {{ form.render("hot",{'class':'form-control', 'style':'width:50%'}) }}
                                        </div>#}
                                        <div class="col-md-3">
                                            <label for="menu">Main Menu</label>
                                            {{ form.render("menu",{'class':'form-control', 'style':'width:50%'}) }}
                                        </div>
                                        <div class="col-md-3">
                                            <label for="active">Hiển thị</label>
                                            {{ form.render("active",{'class':'form-control', 'style':'width:50%'}) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                {% for tmp in tmpSubdomainLanguages %}
                {% if tmp.language.code != 'vi' %}
                    {% set langCode = tmp.language.code %}
                    {% set langName = tmp.language.name %}
                    <div class="tab-pane fade tab-other-lang-{{ langCode }}" id="{{ langCode }}">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ title_bar }}</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-8">

                                        <div class="form-group">
                                            <label>Tên<span class="text-danger">(*)</span></label>
                                            {{ form.render("name_" ~ langCode,{'class':'form-control set_url_' ~ langCode}) }}
                                            {{ form.messages('name_' ~ langCode) }}
                                        </div>

                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group editor-form">
                                            <label for="description">Nội dung</label>
                                            <p></p>
                                            {{ partial('partials/ajaxupload', ['id': row_id_lang[langCode], 'type':'category', 'img_upload_paths':img_upload_lang_paths[langCode]]) }}
                                            <input type="hidden" name="row_id_{{ langCode }}" value="{{ row_id_lang[langCode] }}">
                                            {{ form.render("content_" ~ langCode,{'class':'form-control'}) }}
                                        </div>

                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            {{ form.render("title_" ~ langCode,{'class':'form-control'}) }}
                                        </div>

                                        <div class="form-group">
                                            <label for="keywords">Keywords</label>
                                            {{ form.render("keywords_" ~ langCode,{'class':'form-control'}) }}
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            {{ form.render("description_" ~ langCode,{'class':'form-control'}) }}
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Đường dẫn<span class="text-danger">(*)</span></label>
                                            {{ form.render("slug_" ~ langCode,{'class':'form-control', 'readonly':true}) }}
                                            {{ form.messages('slug_' ~ langCode) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {% endif %}
                {% endfor %}
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
<script type="text/javascript">
    $(document).ready(function(){
        var editor = CKEDITOR.replace( 'content',{
            allowedContent:true,
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('.editor-form').width(),
            height: 200,
        });
        {% for tmp in tmpSubdomainLanguages %}
            {% set langCode  = tmp.language.code %}
            {% if langCode != 'vi' %}
            var editor = CKEDITOR.replace( 'content_{{ langCode }}',{
                allowedContent:true,
                removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
                uiColor : '#3c8dbc',
                language:'en',
                skin:'moono',
                width: $('.editor-form').width(),
                height: 400,
            });
            {% endif %}

            $(".set_url_{{ langCode }}").bind("change keyup input",function() {
            var obj = $(this).val();
            var als = locdau(obj);
            $("input[name=slug_{{ langCode }}]").val(als);
        });
        {% endfor %}

        {% if tmpSubdomainLanguages|length > 0 %}
        $('form[name=form_multilang]').submit(function(e) {
            {% for tmp in tmpSubdomainLanguages %}
                {% set langCode  = tmp.language.code %}
                {% set langName  = tmp.language.name %}
                {% if langCode != 'vi' %}
                    if ($('input[name="name_{{ langCode }}"]').val() == '' || $('input[name="slug_{{ langCode }}"]').val() == '') {
                        toastr.error('Bạn chưa nhập đủ dữ liệu yêu cầu bên ngôn ngữ {{ langName }}');
                        e.preventDefault();
                        return false;
                    }
                {% endif %}
            {% endfor %}
        })
        {% endif %}
    });
</script>