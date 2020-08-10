{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Thêm tin tức</h3>
                </div><!-- /.box-header -->
                {{ content() }}
                <div class="row">
                    <div class="col-md-8">
                        <div class="box-body" style="position: relative">
                            <div class="load_pag_inx"></div>
                            <div class="form-group">
                                <label for="select_site">Chọn site lấy tin</label>
                                {{ site }}
                            </div>
                            <div class="form-group">
                                <label for="link_get_content">Nhập link lấy tin</label>
                                {{ link }}
                                <i class="fa fa-refresh" id="load_news" style="font-size:20px;color:#F00; margin-left:5px; margin-top: 8px; cursor:pointer"></i>
                            </div>
                            <div class="form-group">
                                <label for="select_site">Chọn tin</label>
                                <select name="select_article" id="select_article" class="form-control">
                                    <option value="">Chọn</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- form start -->
                {{ form('role':'form','enctype':'multipart/form-data') }}
                <div class="row">
                    <div class="col-md-8">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">Tên</label>
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>

                            <div class="form-group">
                                <label for="category_id">Danh mục</label>
                                {{ form.render("category_id",{'class':'form-control','id':'category_id', 'style':'max-width:50%'}) }}
                                {{ form.messages('category_id') }}
                            </div>

                            <div class="form-group">
                                <label for="summary">Mô tả</label>
                                {{ form.render("summary",{'class':'form-control','id':'summary'}) }}
                                {{ form.messages('summary') }}
                            </div>

                            <div class="form-group">
                                <label for="photo">Hình ảnh</label>
                                {{ img_get_content }}
                                <span id="img_get_content_show" style="margin-top: 10px; display: block"></span>
                            </div>

                            <div class="form-group">
                                <label for="content">Nội dung</label>
                                {{ form.render("content",{'class':'form-control','id':'content'}) }}
                                {{ form.messages('content') }}
                            </div>



                            <div class="form-group">
                                <label for="sort">Thứ tự</label>
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>

                            <div class="form-group">
                                <label for="active">Active</label>
                                {{ form.render("active") }}
                            </div>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="box-body">
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
                                <label for="tags">Tags</label>
                                <div style="clear:both"></div>
                                {% for t in tags %}
                                    <span style="margin-right: 5px"><input name="tag[]" type="checkbox" value="{{ t.id }}" /> {{ t.name }}</span>
                                {% endfor %}
                            </div>

                        </div>
                    </div>
                </div>


                <div class="box-footer">
                    {{ submit_button("Save", "class": "btn btn-primary", "data-type":"save") }}
                    {#{{ submit_button("Save & New", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}#}
                    {#{{ submit_button("Save & Close", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}#}
                    {{ link_to("acp/news", "Exit", "class": "btn btn-danger") }}
                </div>
                {{ endform() }}
            </div><!-- /.box -->
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function(){
        var editor = CKEDITOR.replace( 'content',{
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width:$('.col-md-6').innerWidth()-40,
            height: 600,
            filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
            filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
            filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

            filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
            filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
            filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
        });
    })
</script>
