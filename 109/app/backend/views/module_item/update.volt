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
                                <label for="name">Tên module: <span class="text-danger" style="font-size: 18px">{{ item.name }}</span></label>

                            </div>
                            <div class="form-group">
                                <label for="title">Vị trí</label>

                                <ul class="list_category">
                                    {% for i in data_position %}
                                        <li class="clearfix">
                                            <div class="col-md-2">
                                                <input type="checkbox" name="position[]" value="{{ i['id'] }}"{% if tmp_position_module_item_arr is defined %}{% if i['id'] in tmp_position_module_item_arr %}checked="checked"{% endif %}{% endif %}>
                                                <span>{{ i['name'] }}</span>
                                            </div>
                                            {#{% if i['sort'] is defined %}
                                            <div class="col-md-2">
                                                <input type="text" name="sort_{{ i['id'] }}" value="{{ i['sort'] }}" class="form-control">
                                            </div>
                                            {% endif %}#}
                                        </li>
                                    {% endfor %}
                                </ul>
                            </div>
                            {% if group_type == '_fanpage_left_right' %}
                            <div class="form-group">
                                <label for="title">Link fanpage facebook</label>
                                <input name="facebook" class="form-control" value="{{ setting.facebook }}">
                            </div>
                            {% endif %}

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">


                            <div class="form-group">
                                <label for="active">Hiển thị</label>
                                <input type="checkbox" value="{{ item.active }}" {% if item.active == 'Y' %}checked{% endif %}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ link_to(ACP_NAME, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
