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
                                <label for="word_key">Từ Khóa</label>
                                {{ form.render("word_key",{'class':'form-control','id':'word_key'}) }}
                                {{ form.messages('word_key') }}
                            </div>

                            <div class="form-group">
                                <label for="type">Giá Trị</label>
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
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