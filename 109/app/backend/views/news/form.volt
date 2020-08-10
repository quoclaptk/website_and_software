{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {#<p><b class="text-danger">Chú ý: Website đa ngôn ngữ thì phải nhập dữ liệu đa ngôn ngữ bên Danh mục bài viết trước khi quản trị phần này để đồng bộ dữ liệu (VD: Website có 2 ngôn ngữ Việt Nam + English thì phải nhập cả phần tiếng Việt và tiếng Anh bên Danh mục bài viết trước)</b></p>#}
            {{ content() }}
            <div id="errorValid">
                {{ content() }}
            </div>
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed', 'name':'form_multilang') }}
            {{ form.render("id") }}
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label>Tên<span class="text-danger">(*)</span></label>
                                                        {{ form.render("name",{'class':'form-control set_url','id':'name'}) }}
                                                        {{ form.messages('name') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>Slogan</label>
                                                        {{ form.render("slogan",{'class':'form-control','id':'slogan'}) }}
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            <div class="form-group">
                                                <label for="summary">Mô tả</label>
                                                {{ form.render("summary",{'class':'form-control','id':'description'}) }}
                                            </div>

                                            <div class="form-group editor-form">
                                                <label for="description">Nội dung</label>
                                                {{ form.render("content",{'class':'form-control','id':'content'}) }}
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">SEO</div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="title">Title</label>
                                                                {{ form.render("title",{'class':'form-control','id':'title', 'rows':1}) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="keywords">Keywords</label>
                                                                {{ form.render("keywords",{'class':'form-control','id':'keywords', 'rows':1}) }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="description">Description</label>
                                                                {{ form.render("description",{'class':'form-control','id':'description', 'rows':1}) }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Đường dẫn<span class="text-danger">(*)</span></label>
                                                                {{ form.render("slug",{'class':'form-control','readonly':true}) }}
                                                                {{ form.messages('slug') }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="head_content">Nội dung thẻ head</label>
                                                                {{ form.render("head_content",{'class':'form-control', 'rows':5}) }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="body_content">Nội dung thẻ body</label>
                                                                {{ form.render("body_content",{'class':'form-control', 'rows':5}) }}
                                                            </div>
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
                                <div class="tab-pane fade" id="{{ langCode }}">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-7">
                                                   <div class="form-group">
                                                        <label for="name">Tên<span class="text-danger">(*)</span></label>
                                                        {{ form.render("name_" ~ langCode, {'class':'form-control set_url_' ~ langCode}) }}
                                                        {{ form.messages('name_' ~ langCode) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                   <div class="form-group">
                                                        <label for="slogan">Slogan</label>
                                                        {{ form.render("slogan_" ~ langCode, {'class':'form-control'}) }}
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            <div class="form-group">
                                                <label for="summary">Mô tả</label>
                                                {{ form.render("summary_" ~ langCode, {'class':'form-control'}) }}
                                            </div>

                                            <div class="form-group editor-form">
                                                <label>Nội dung</label>
                                                <p></p>
                                                {{ form.render("content_" ~ langCode,{'class':'form-control'}) }}
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">SEO</div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Title</label>
                                                                {{ form.render("title_" ~ langCode,{'class':'form-control', 'rows':1}) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Keywords</label>
                                                                {{ form.render("keywords_" ~ langCode,{'class':'form-control', 'rows':1}) }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                {{ form.render("description_" ~ langCode,{'class':'form-control', 'rows':1}) }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
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
                                    </div>
                                </div>
                                {% endif %}
                                {% endfor %}
                            </div>
                        </div>

                        <div class="col-md-4">
                            {% if news_category|length > 0 %}
                            <div class="panel panel-default">
                                <div class="panel-heading">Danh mục</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <ul class="list_category">
                                                    {% for i in news_category %}
                                                    <li>
                                                        <input type="checkbox" name="news_category[]" value="{{ i.id }}"{% if tmp_news_category is defined %}{% if i.id in tmp_news_category %}checked="checked"{% endif %}{% endif %}>
                                                        <span>{{ i.name }}</span>
                                                    </li>
                                                    {% endfor %}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {% endif %}

                            {% if news_menu|length > 0 %}
                            <div class="panel panel-default">
                                <div class="panel-heading">Danh mục</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="list-controls" style="margin-bottom: 10px;font-size: 16px">
                                                    <a href="javascript:void(0)" onclick="selectAll(this)" class="select-all text-primary"><div class="btn btn-default btn-sm checkbox-toggle" style="padding: 2px 6px;margin-right: 7px;"><i class="fa fa-square-o" style="font-size: 12px"></i></div><span style="vertical-align: middle;font-size: 15px">Chọn tất cả</span></a>
                                                </div>
                                                <ul class="list list_category">
                                                    {% for i in news_menu %}
                                                    <li>
                                                        <input type="checkbox" name="news_menu[]" value="{{ i.id }}"{% if tmp_news_menu is defined %}{% if i.id in tmp_news_menu %}checked="checked"{% endif %}{% endif %} class="menu-item-checkbox">
                                                        <span>{{ i.name }}</span>
                                                    </li>
                                                    {% endfor %}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {% endif %}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="display: block">Up hình để đưa vào bài viết</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{ partial('partials/ajaxupload', ['id': row_id, 'type':'news', 'img_upload_paths':img_upload_paths]) }}
                                            <input type="hidden" name="row_id" value="{{ row_id }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="display: block">Hình đại diện</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ form.render("photo",{'id':'photo'}) }}
                                                {{ form.messages('photo') }}
                                                <p></p>
                                                <label class="text-danger">- Ghi chú: Để up ảnh không bị mờ, ảnh cần nhẹ hơn 100kb | <a href="https://docs.google.com/document/d/1zaf8d_BAFSI41l413th-UulivgLU99yP9_Yo_EUDtwc/edit" target="_blank" style="text-decoration: underline;color: #f00"><i>Click xem hướng dẫn</i></a></label>
                                                {% if item is defined %}
                                                {{ image('files/news/' ~ SUB_FOLDER ~ '/' ~ item.folder ~ '/' ~ item.photo , 'width':'100', 'style':'margin-top:10px') }}
                                                {% endif %}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sort">Thứ tự</label>
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>

                            <div class="form-group">
                                <label for="active">Hiển thị</label>
                                {{ form.render("active",{'class':'form-control', 'style':'width:50%'}) }}
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
<script type="text/javascript">
    $(document).ready(function(){
        var editor = CKEDITOR.replace( 'content',{
            allowedContent:true,
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('editor-form').width(),
            height: 250,
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
                height: 250,
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
