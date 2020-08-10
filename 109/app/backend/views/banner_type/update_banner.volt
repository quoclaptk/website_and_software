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
                            <div class="form-group">
                                <label for="link">Link</label>
                                {{ form.render("link",{'class':'form-control','id':'link'}) }}
                                {{ form.messages('link') }}
                            </div>

                            <div class="form-group">
                                <label for="file">Upload hình ảnh</label>
                                {{ form.render("photo",{'id':'photo'}) }}
                                {% if item is defined %}{{ image('files/ads/' ~ SUB_FOLDER ~ '/' ~ item.photo , 'width':'100', 'style':'margin-top:10px') }}{% endif %}
                                {{ form.messages('photo') }}
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
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/" ~ banner_type_id, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>


