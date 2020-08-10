{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            {{ form.render("id") }}
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

                            <div class="form-group">
                                <label for="name">Đường dẫn<span class="text-danger">(*)</span></label>
                                {{ form.render("slug",{'class':'form-control','id':'slug'}) }}
                                {{ form.messages('slug') }}
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

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group editor-form">
                                <label for="description">Nội dung</label>
                                <p></p>
                                {{ partial('partials/ajaxupload', ['id': row_id, 'type':'news_category']) }}
                                <input type="hidden" name="row_id" value="{{ row_id }}">
                                {{ form.render("content",{'class':'form-control','id':'content'}) }}
                            </div>

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
                {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/index/" ~ type, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        var editor = CKEDITOR.replace( 'content',{
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width:$('editor-form').width(),
            height: 400,
        });
    })
</script>