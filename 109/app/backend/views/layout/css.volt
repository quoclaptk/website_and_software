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
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="minify" value="1" class="minimal"{% if minify == 1 %} checked{% endif %}> Nén CSS (minify css)
                                    </label>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="css">Tùy chỉnh CSS</label>
                                {#<div class="text-center" style="margin-bottom: 20px">
                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/resetCss/' ~ item.l.id , 'Khôi phục css gốc', 'style':'text-decoration:underline' ) }}
                                </div>#}
                                <textarea id="css" name="css" class="form-control" placeholder="Css" rows="30" style="overflow:auto">{{ cssFile }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ submit_button("Lưu & Đóng", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}
                {{ link_to(ACP_NAME, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>

