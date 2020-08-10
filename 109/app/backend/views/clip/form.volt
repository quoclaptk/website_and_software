{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div id="errorValid">
                {{ content() }}
            </div>
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed', 'name':'form_multilang') }}
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Tên</label>
                                        {{ form.render("name",{'class':'form-control set_url','id':'name'}) }}
                                        {{ form.messages('name') }}
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Đường dẫn<span class="text-danger">(*)</span></label>
                                        {{ form.render("slug",{'class':'form-control','id':'slug'}) }}
                                        {{ form.messages('slug') }}
                                    </div>

                                    <div class="form-group">
                                        <label for="code">Mã nhúng</label>
                                        {{ form.render("code",{'class':'form-control','id':'code'}) }}
                                        <p class="help-block">VD: https://www.youtube.com/watch?v=<span style="color:#f00">C4kx3HSth64</span></p>
                                        {{ form.messages('code') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
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

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group editor-form">
                                        <label for="description">Mô tả</label>
                                        <p></p>
                                        {{ partial('partials/ajaxupload', ['id': row_id, 'type':'clip']) }}
                                        <input type="hidden" name="row_id" value="{{ row_id }}">
                                        {{ form.render("summary",{'class':'form-control','id':'summary'}) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="sort">Thứ tự</label>
                                        {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                        {{ form.messages('sort') }}
                                    </div>

                                    <div class="form-group">
                                        <label for="active">Hiển thị</label>
                                        {{ form.render("active",{'class':'form-control','id':'sort','style':'width:80px'}) }}
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
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ title_bar }}</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        
                                        <div class="form-group">
                                            <label for="name">Tên<span class="text-danger">(*)</span></label>
                                            {{ form.render("name_" ~ langCode, {'class':'form-control set_url_' ~ langCode}) }}
                                            {{ form.messages('name_' ~ langCode) }}
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Đường dẫn<span class="text-danger">(*)</span></label>
                                            {{ form.render("slug_" ~ langCode,{'class':'form-control'}) }}
                                            {{ form.messages('slug_' ~ langCode) }}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title</label>
                                            {{ form.render("title_" ~ langCode, {'class':'form-control'}) }}
                                        </div>

                                        <div class="form-group">
                                            <label>Keywords</label>
                                            {{ form.render("keywords_" ~ langCode,{'class':'form-control'}) }}
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            {{ form.render("description_" ~ langCode,{'class':'form-control'}) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group editor-form">
                                            <label for="description">Mô tả</label>
                                            <p></p>
                                            {{ partial('partials/ajaxupload', ['id': row_id_lang[langCode], 'type':'clip', 'img_upload_paths':img_upload_lang_paths[langCode]]) }}
                                            <input type="hidden" name="row_id_{{ langCode }}" value="{{ row_id_lang[langCode] }}">
                                            {{ form.render("summary_" ~ langCode,{'class':'form-control'}) }}
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
        var editor = CKEDITOR.replace( 'summary',{
            allowedContent:true,
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('editor-form').width(),
            height: 300,
        });
        {% for tmp in tmpSubdomainLanguages %}
            {% set langCode  = tmp.language.code %}
            {% if langCode != 'vi' %}
            var editor = CKEDITOR.replace( 'summary_{{ langCode }}',{
                allowedContent:true,
                removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
                uiColor : '#3c8dbc',
                language:'en',
                skin:'moono',
                width: $('.editor-form').width(),
                height: 300,
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
