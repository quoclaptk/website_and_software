{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flash.output() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            <div class="panel panel-default">
                <div class="panel-heading">{{ title_bar }}</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="parent_id">Module cha</label>
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

                            <div class="form-group">
                                <label for="name">Tên<span class="text-danger">(*)</span></label>
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                {{ form.render("type",{'class':'form-control','id':'type'}) }}
                                {{ form.messages('type') }}
                            </div>

                            <div class="form-group">
                                <label for="link">Link admin</label>
                                {{ form.render("link",{'class':'form-control','id':'link'}) }}
                            </div>
                
                            {#<div class="form-group">
                                <label for="title">Vị trí</label>

                                <ul class="list_category">
                                    {% for i in position %}
                                        <li>
                                            <input type="checkbox" name="position[]" value="{{ i.id }}"{% if tmp_position_module_group_arr is defined %}{% if i.id in tmp_position_module_group_arr %}checked="checked"{% endif %}{% endif %}>
                                            <span>{{ i.name }}</span>
                                        </li>
                                    {% endfor %}
                                </ul>
                            </div>#}

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="photo">Hình ảnh</label>
                                {{ form.render("photo",{'id':'photo'}) }}
                                {{ form.messages('photo') }}
                                {% if item is defined %}
                                    {{ image('files/module/' ~ item.photo ,  'style':'margin-top:10px;max-width:300px') }}
                                {% endif %}
                            </div>

                            <div class="form-group">
                                <label for="sort">Thứ tự</label>
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>


                            <div class="form-group">
                                <label for="active">Hiển thị</label>
                                {{ form.render("active", {"class":"form-control", "style":"width:90px"})}}
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