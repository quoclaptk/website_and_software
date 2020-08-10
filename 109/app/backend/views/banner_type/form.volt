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
                                <label for="name">Tiêu đề</label>
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>
                            <div class="form-group">
                                <label for="title">Position</label>

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
                        <div class="col-md-12">

                            <div class="form-group">
                                <label for="sort">Thứ tự</label>
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>

                            <div class="form-group">
                                <label for="active">Hiển thị</label>
                                {{ form.render("active") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default" id="banner">
                <div class="panel-heading">Thêm hình ảnh</div>
                <div class="panel-body">
                    {% if list_banner is defined and list_banner != '' %}
                        <div class="row">
                            <div class="col-md-12"><label>Hình ảnh hiện tại</label></div>
                            {% for i in list_banner %}
                                <div class="col-md-3">
                                    <div class="form-group text-center box_other_photo">
                                        {{ image('files/ads/' ~ SUB_FOLDER ~ '/' ~ i.photo, 'width':'180') }}
                                        <div>
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/deleteBanner/' ~ type ~ '/' ~ item.id ~ '/' ~ i.id, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}
                                            {% if i.active == 'Y' %}
                                                {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hideBanner/' ~ type ~ '/' ~ item.id ~ '/' ~ i.id, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                            {% else %}
                                                {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/showBanner/' ~ type ~ '/' ~ item.id ~ '/' ~ i.id, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                            {% endif %}
                                        </div>

                                    </div>
                                </div>
                            {% endfor %}
                        </div>
                    {% endif %}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Chọn số lượng</label>
                                <select id="select_number_photo" class="form-control">
                                    <option>Chọn số lượng</option>
                                    {% for i in 1..10 %}
                                        <option value="{{ i }}">{{ i }}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="load_photo_input">
                        {% for i in 1..6 %}
                            <div class="col-md-4"><div class="form-group"><input type="file" name="banner[]"></div></div>
                        {% endfor %}
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/index/" ~ type, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
<script>
    $('#select_number_photo').change(function () {
        $('#load_photo_input').html('');
        var number = $(this).val();
        var html = '';
        for (i = 0; i < number; i++) {
            var html = html + '<div class="col-md-4"><div class="form-group"><input type="file" name="banner[]"></div></div>';
        }

        $('#load_photo_input').html(html);
    })
</script>