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
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Tiêu đề</label>
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>
                            {#
                            <div class="form-group">
                                <label for="title">Vị trí </label>

                                <ul class="list_category">
                                    {% for i in position %}
                                        <li>
                                            <input type="checkbox" name="position[]" value="{{ i.id }}"{% if tmp_position_module_item_arr is defined %}{% if i.id in tmp_position_module_item_arr %}checked="checked"{% endif %}{% endif %}>
                                            <span>{{ i.name }}</span>
                                        </li>
                                    {% endfor %}
                                </ul>
                            </div>
                           #} 
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group editor-form">
                                <label for="description">Nội dung</label>
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
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width:$('editor-form').width(),
            height: 400,
            filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
            filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
            filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

            filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
            filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
            filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
        });
    })
</script>
