{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}

            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">
                    {#<div class="clearfix">
                        <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create/" ~ type, "Thêm mới", "class": "btn btn-primary") }}</div>
                        <div class="pull-right">
                            <a href="javascript:;" class="btn btn-primary" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
                        </div>
                        <p class="clear"></p>
                    </div>#}
                    <div class="row">
                        <div class="col-xs-12">
                            {% for key,item in result %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        {#<th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>#}
                                        <th width="7%">Thứ tự</th>
                                        <th width="15%">Tên</th>
                                        <th>Upload hình ảnh</th>
                                        {#<th width="8%" class="text-center">Ẩn/Hiện</th>
                                        <th width="8%" class="text-center">Sửa</th>
                                        <th width="8%" class="text-center">Xóa</th>#}
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                <tr>
                                    {#<td><input type="checkbox" name="select_all" value="{{ item['id'] }}"></td>#}
                                    <td>{{ key + 1 }}</td>
                                    <td>{{ item['name'] }}</td>
                                    {#<td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ type ~ '/' ~ item['id'] ~ '/' ~ page_current, ''~ item['name'] ~'' ) }}</td>#}
                                    <td>
                                        {{ form('role':'form','enctype':'multipart/form-data', 'action':'/' ~ ACP_NAME ~ '/' ~ controller_name ~ '/updatePage/' ~ type ~ '/' ~ item['id']) }}
                                        {#<div class="form-group">
                                            <ul class="list_category">
                                                {% for i in position %}
                                                    <li style="display: inline-block">
                                                        <input type="checkbox" name="position_{{ item['id'] }}[]" value="{{ i.id }}"{% if item['tmp_position_module_item_arr'] is defined %}{% if i.id in item['tmp_position_module_item_arr'] %}checked="checked"{% endif %}{% endif %}>
                                                        <span>{{ i.name }}</span>
                                                    </li>
                                                {% endfor %}
                                            </ul>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="name_{{ item['id'] }}" value="{{ item['name'] }}" class="form-control">
                                        </div>
                                        <div class="form-group clearfix">
                                            {% for i in item['list_banner'] %}
                                            <div class="col-md-3">
                                                <div class="form-group text-center box_other_photo">
                                                    {{ image('files/ads/' ~ SUB_FOLDER ~ '/' ~ i['photo'], 'width':'180') }}
                                                    <div>
                                                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/deleteBanner/' ~ type ~ '/' ~ item['id'] ~ '/' ~ i['id'], '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}
                                                        {% if i['active'] == 'Y' %}
                                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hideBanner/' ~ type ~ '/' ~ item['id'] ~ '/' ~ i['id'], '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                        {% else %}
                                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/showBanner/' ~ type ~ '/' ~ item['id'] ~ '/' ~ i['id'], '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                        {% endif %}
                                                    </div>

                                                </div>
                                            </div>
                                        {% endfor %}
                                        </div>#}
                                        <div class="form-group" id="load_photo_input_{{ item['id'] }}">
                                            {% for i in 1..10 %}
                                                <div class="col-md-6"><div class="form-group"><input type="file" name="banner_{{ item['id'] }}[]"></div></div>
                                            {% endfor %}
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="submit" name="ok_{{ item['id'] }}" value="Lưu" class="btn btn-primary">
                                            </div>
                                        </div>
                                        {{ endform() }}
                                    </td>

                                    {#<td class="text-center">
                                        {% if item['active'] == 'Y' %}
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hide/' ~ type ~ '/' ~ item['id'] ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                        {% else %}
                                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/show/' ~ type ~ '/' ~ item['id'] ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                        {% endif %}
                                    </td>
                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ type ~ '/' ~ item['id'] ~ '/' ~ page_current, '<i class="fa fa-edit"></i>' ) }}</td>
                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_delete/' ~ type ~ '/' ~ item['id'] ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>#}
                                </tr>
                                <tr>
                                    {% for i in item['list_banner'] %}
                                        {% if loop.first %}
                                        <div class="table-responsive mailbox-messages">
                                            <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                                                <th width="7%">Thứ tự</th>
                                                <th class="text-center">Hình ảnh</th>
                                                <th>Link</th>
                                                <th width="5%">Ẩn/Hiện</th>
                                                <th width="5%">Sửa</th>
                                                <th width="5%">Xóa</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                        {% endif %}
                                        <tr>
                                            <td><input type="checkbox" name="select_all" value="{{ i['id'] }}"></td>
                                            <td>{{ i['sort'] }}</td></td>
                                            <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ i['id'] ~ '/' ~ page_current, ''~ image('files/ads/' ~ SUB_FOLDER ~ '/' ~ i['photo'], 'style':'width:100px') ) }}</td>
                                            <td>{{ i['link'] }}</td>
                                            <td align="center">
                                                {% if i['active'] == 'Y' %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hideBanner/' ~ type ~ '/' ~ item['id'] ~ '/' ~ i['id'], '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                {% else %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/showBanner/' ~ type ~ '/' ~ item['id'] ~ '/' ~ i['id'], '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                {% endif %}
                                            </td>
                                            <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/updateBanner/' ~ type ~ '/' ~ item['id'] ~ '/' ~ i['id'], '<i class="fa fa-edit"></i>' ) }}</td>
                                            <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_deleteBanner/' ~ type ~ '/' ~ item['id'] ~ '/' ~ i['id'], '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                        </tr>
                                        {% if loop.last %}
                                            </tbody>

                                            </table>
                                            </div>
                                        {% endif %}
                                    {% endfor %}
                                </tr>
                                {% if loop.last %}
                                    </tbody>

                                    </table>
                                    </div>
                                {% endif %}
                            {% endfor %}
                        </div>
                    </div>

                </div>
                <div class="text-center">{{ partial('partials/pagination') }}</div>
            </div>

            {#<div class="box-footer">
                {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create/" ~ type, "Thêm mới", "class": "btn btn-primary") }}
            </div>#}

        </div>
    </div>
</section>
