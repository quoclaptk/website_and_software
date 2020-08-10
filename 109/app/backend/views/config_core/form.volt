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
                                <label for="config_group_id">Nhóm cấu hình</label>
                                {{ form.render("config_group_id",{'class':'form-control','id':'config_group_id', 'style':'max-width:50%'}) }}
                                {{ form.messages('config_group_id') }}
                            </div>

                            <div class="form-group">
                                <label for="parent_id">Cấu hình cha</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="parent_id" class="form-control">
                                            <option value="0">Chọn module cha</option>
                                            {% for row in list %}
                                                {% set name = row.name %}
                                                {% if item is defined and item.parent_id == row.id %}
                                                    {% set selected = ' selected' %}
                                                {% else %}
                                                    {% set selected = '' %}
                                                {% endif %}

                                                {% if item is defined and item.id == row.id %}
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
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name">Tên</label>
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>

                            <div class="form-group">
                                <label for="type">Kiểu</label>
                                {{ form.render("type",{'class':'form-control','id':'type','style':'width:110px'}) }}
                            </div>

                            <div class="form-group">
                                <label for="name">Tên field</label>
                                {{ form.render("field",{'class':'form-control','id':'field'}) }}
                                {{ form.messages('field') }}
                            </div>

                            <div class="form-group">
                                <label for="value">Giá trị mặc định</label>
                                {{ form.render("value",{'class':'form-control','id':'value'}) }}
                            </div>

                            <div class="form-group">
                                <label for="min_value">Giá trị nhỏ nhất</label>
                                {{ form.render("min_value",{'class':'form-control','id':'min_value'}) }}
                            </div>

                            <div class="form-group">
                                <label for="max_value">Giá trị lớn nhất</label>
                                {{ form.render("max_value",{'class':'form-control','id':'max_value'}) }}
                            </div>

                            <div class="form-group">
                                <label for="description">Mô tả cấu hình</label>
                                {{ form.render("description",{'class':'form-control','id':'value'}) }}
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label for="sort">Thứ tự</label>
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>

                            <div class="form-group">
                                <label for="active">Hiển thị</label>
                                {{ form.render("active",{'class':'form-control','id':'sort','style':'width:90px'}) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ submit_button("Lưu & Thêm Mới", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}
                {{ submit_button("Lưu & Đóng", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}
                {{ link_to(ACP_NAME ~ "/" ~ controller_name, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>