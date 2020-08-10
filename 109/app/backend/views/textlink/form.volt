{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Text link</h3>
                </div><!-- /.box-header -->
                {{ content() }}
                <!-- form start -->
                {{ form('role':'form','enctype':'multipart/form-data') }}
                <div class="row">
                    <div class="col-md-10">
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
                    {{ submit_button("Save", "class": "btn btn-primary", "data-type":"save") }}
                    {#{{ submit_button("Save & New", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}#}
                    {#{{ submit_button("Save & Close", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}#}
                    {{ link_to("acp/textlink/index/" ~ type, "Exit", "class": "btn btn-danger") }}
                </div>
                {{ endform() }}
            </div><!-- /.box -->
        </div>
    </div>
</section>


