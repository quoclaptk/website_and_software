{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Thêm mới</h3>
                </div><!-- /.box-header -->
                {{ content() }}
                <!-- form start -->
                {{ form('role':'form') }}
                <div class="row">
                    <div class="col-md-8">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">Tên</label>
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>

                            <div class="form-group">
                                <label for="link">Link</label>
                                {{ form.render("link",{'class':'form-control','id':'link'}) }}
                                {{ form.messages('link') }}
                            </div>

                            <div class="form-group">
                                <label for="wrapper">Thẻ bao</label>
                                {{ form.render("wrapper",{'class':'form-control','id':'wrapper'}) }}
                                {{ form.messages('wrapper') }}
                            </div>

                            <div class="form-group">
                                <label for="title">Thẻ title</label>
                                {{ form.render("title",{'class':'form-control','id':'title'}) }}
                                {{ form.messages('title') }}
                            </div>

                            <div class="form-group">
                                <label for="summary">Thẻ mô tả</label>
                                {{ form.render("summary",{'class':'form-control','id':'summary'}) }}
                                {{ form.messages('summary') }}
                            </div>

                            <div class="form-group">
                                <label for="img">Thẻ img</label>
                                {{ form.render("img",{'class':'form-control','id':'img'}) }}
                                {{ form.messages('img') }}
                            </div>

                            <div class="form-group">
                                <label for="img_link_replace">Img replace(nếu có)</label>
                                {{ form.render("img_link_replace",{'class':'form-control','id':'img_link_replace'}) }}
                            </div>

                            <div class="form-group">
                                <label for="href">Thẻ link</label>
                                {{ form.render("href",{'class':'form-control','id':'href'}) }}
                                {{ form.messages('href') }}
                            </div>

                            <div class="form-group">
                                <label for="content">Thẻ nội dung</label>
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


                </div>


                <div class="box-footer">
                    {{ submit_button("Save", "class": "btn btn-primary","data-type":"save") }}
                    {#{{ submit_button("Save & New", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}#}
                    {#{{ submit_button("Save & Close", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}#}
                    {{ link_to("acp/url_config", "Exit", "class": "btn btn-danger") }}
                </div>
                {{ endform() }}
            </div><!-- /.box -->
        </div>
    </div>
</section>
