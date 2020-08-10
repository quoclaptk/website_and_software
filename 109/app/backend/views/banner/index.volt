{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}
            
            {{ form('role':'form','enctype':'multipart/form-data') }}
            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">
                     <p class="text-danger bold">Ghi chú: Để up hình không bị mờ, hình slider cần nhẹ hơn 500kb | <a href="https://docs.google.com/document/d/1zaf8d_BAFSI41l413th-UulivgLU99yP9_Yo_EUDtwc/edit?fbclid=IwAR0GwMnwv3-60t2P43FdpFjjT0nzw4xsl5RQBKzf5ySr8KtUjjfKzRjyMH8" target="_blank">Xem hướng dẫn</a></p>
                    <div class="clearfix">
                        <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary") }}</div>
                        <div class="pull-right">
                            {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                            <a href="javascript:;" class="btn btn-success" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                            <a href="javascript:;" class="btn btn-warning" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
                            <a href="javascript:;" class="btn btn-danger" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                        </div>
                        <p class="clear"></p>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            
                            <div class="table-responsive mailbox-messages">
                            <table id="example" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr style="font-size: 13px">
                                <th width="5%"><input type="checkbox" name="select-all-item" class="flat-red"></th>
                                <th width="7%">Thứ tự</th>
                                <th>Tên</th>
                                <th class="text-center">Hình ảnh</th>
                                <th>Link</th>
                                {% for i in banner_type %}
                                <th>{{i.name}}</th>
                                {% endfor %}
                                <th>Banner: Hình ảnh 2</th>
                                <th>Banner: Hình ảnh 3</th>
                                <th>Banner trái</th>
                                <th>Banner phải</th>
                                <th>Slider dọc</th>
                                <th width="5%">Ẩn/Hiện</th>
                                <th width="5%">Sửa</th>
                                <th width="5%">Xóa</th>
                            </tr>
                            </thead>

                            <tbody>
                                {% for item in bannerList %}
                                <tr>
                                    <td><input type="checkbox" name="select_all" value="{{ item.id }}"></td>
                                    <td>{{ item.sort }}</td>
                                    <td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, ''~ item.name ~'' ) }}</td>
                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, ''~ image('files/ads/' ~ SUB_FOLDER ~ '/' ~ item.photo, 'style':'width:100px') ) }}</td>
                                    <td>{{ item.link }}</td>
                                    {% for k,i in banner_type %}
                                    <td class="text-center"><input type="checkbox" name="banner_{{ item.id }}[]" value="{{ i.id }}" {% if item.count[k] > 0 %}checked{% endif %}></td>
                                    {% endfor  %}
                                    <td align="center">
                                        <input type="checkbox" name="md_banner_2_{{ item.id }}" value="1" {% if item.md_banner_2 == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" name="md_banner_3_{{ item.id }}" value="1" {% if item.md_banner_3 == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" name="left_ads_{{ item.id }}" value="1" {% if item.left_ads == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" name="right_ads_{{ item.id }}" value="1" {% if item.right_ads == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" name="vertical_slider_{{ item.id }}" value="1" {% if item.vertical_slider == 'Y' %}checked{% endif %}>
                                    </td>
                                    <td align="center">
                                        {% if item.active == 'Y' %}
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hide/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                        {% else %}
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/show/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                        {% endif %}
                                    </td>
                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-edit"></i>' ) }}</td>
                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                </tr>
                            {% endfor %}
                            </tbody>

                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                {#<div class="text-center">{{ partial('partials/pagination') }}</div>#}
            </div>

            <div class="box-footer">
                <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary") }}</div>
                <div class="pull-right">
                    {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                    <a href="javascript:;" class="btn btn-success" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                    <a href="javascript:;" class="btn btn-warning" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
                    <a href="javascript:;" class="btn btn-danger" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                </div>
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
