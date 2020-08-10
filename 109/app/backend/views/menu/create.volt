{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data') }}
            <div class="panel panel-default">
                <div class="panel-heading">{{ title_bar }}</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3"><label for="name">Tiêu đề</label></div>
                        <div class="col-md-9">
                            <div class="form-group">
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><label for="name">Vị trí</label></div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <ul class="list_category">
                                    {% for i in position %}
                                        <li>
                                            <input type="checkbox" name="position[]" value="{{ i.id }}"{% if tmp_position_module_item_arr is defined %}{% if i.id in tmp_position_module_item_arr %}checked="checked"{% endif %}{% endif %}>
                                            <span>{{ i.name }}</span>
                                        </li>
                                    {% endfor %}
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><label for="name">Loại menu</label></div>
                        <div class="col-md-9">
                            <div class="form-group">
                                {{ form.render("style", {"class":"form-control", "style":"width:120px"}) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><label for="name">Thứ tự</label></div>
                        <div class="col-md-9">
                            <div class="form-group">
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><label>Main menuu</label></div>
                        <div class="col-md-9">
                            <div class="form-group">
                                {{ form.render("main", {"class":"form-control", "style":"width:90px"}) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><label for="name">Hiển thị</label></div>
                        <div class="col-md-9">
                            <div class="form-group">
                                {{ form.render("active", {"class":"form-control", "style":"width:90px"}) }}
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