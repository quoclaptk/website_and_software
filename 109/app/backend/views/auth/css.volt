{{ partial('partials/content_header') }}
{#{{ partial('partials/nav_layout') }}#}
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            <div class="panel panel-default">
                <div class="panel-heading">Giao diện</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="css">Tùy chỉnh CSS</label>
                                <textarea id="css" name="css" class="form-control" placeholder="Css" rows="30" style="overflow:auto">
                                {{ css_file }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row" style="border-top:2px solid #007bff;padding-top: 5px">
                        <div class="col-md-3">
                            <label for="title">Favicon</label>
                            <input type="file" name="image" class="form-control" id="image">
                        </div>
                        <div class="col-md-2" style="pardding-top:20px">
                            {% set favicon = image('/uploads/favicon.ico' , 'style':'width:56px;margin-top:10px') %}
                            {% if has_file == 'has file' %}
                                <p>{{ favicon }}</p>
                                <p><a href="/{{ ACP_NAME ~ '/' ~ router.getControllerName() }}/deleteImate" class="btn btn-sm btn-danger" onclick="if(!confirm('Xác nhận xóa?')) return false"><i class="fa fa-times"></i> Xóa hình</a></p>
                            {% endif %}
                        </div>
                        <div class="col-md-2"><b>(16 x 16)px</b></div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ submit_button("Lưu & Đóng", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}
                {{ link_to(ACP_NAME , "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>

