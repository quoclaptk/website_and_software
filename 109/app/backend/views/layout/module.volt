{{ partial('partials/content_header') }}
{{ partial('partials/nav_layout') }}
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            {% for key,value in position_module_array %}
            <div class="panel panel-default">
                <div class="panel-heading">{{ key }}</div>
                <div class="panel-body">

                    <div style="width: 100%;margin:auto">
                        <div class="table-responsive mailbox-messages">
                            <table id="example" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th style="width:33%;">Thứ tự hiển thị(trên xuống dưới, trái qua phải)</th>
                                    <th width="10%">Ẩn/Hiện</th>
                                    <th width="10%">Cấu hình</th>
                                </tr>
                                </thead>
                                <tbody>
                                {% for j in position_module_array[key] %}
                                    <tr>
                                        <td>{{ j['module_name'] }}</td>
                                        <td class="text-center">
                                            <input type="text" name="sort" id="sort_{{ j['id'] }}" class="form-control" value="{{ j['sort'] }}" style="width:160px;margin-right:5px;display: inline-block">
                                            <a data-id="1602" onclick="update_layout_module_sort({{ j['id']}})" href="javascript:void(0)" style="display: inline-block"><i class="fa fa-refresh"></i></a>
                                        </td>
                                        <td align="center">
                                            {% if j['active'] == 'Y' %}
                                                {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hide/' ~ j['id'] ~ '/' ~ item.l.id, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                            {% else %}
                                                {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/show/' ~ j['id'] ~ '/' ~ item.l.id, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                            {% endif %}
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:;" class="config_module_css" data-url="{{ HTTP_HOST }}/{{ ACP_NAME }}/layout/moduleCss" data-id="{{ j['id']}}"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                    {% if j['child'] != '' %}
                                    {% for k in j['child'] %}
                                    <tr>
                                        <td>|--- {{ k['module_name'] }}</td>
                                        <td class="text-center">
                                            <input type="text" name="sort" id="sort_{{ k['id'] }}" class="form-control" value="{{ k['sort'] }}" style="width:160px;margin-right:5px;display: inline-block">
                                            <a data-id="1602" onclick="update_layout_module_sort({{ k['id']}})" href="javascript:void(0)" style="display: inline-block"><i class="fa fa-refresh"></i></a>
                                        </td>

                                        <td align="center">
                                            {% if k['active'] == 'Y' %}
                                                {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hide/' ~ k['id'] ~ '/' ~ item.l.id, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                            {% else %}
                                                {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/show/' ~ k['id'] ~ '/' ~ item.l.id, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                            {% endif %}
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:;" class="config_module_css" data-url="{{ HTTP_HOST }}/{{ ACP_NAME }}/layout/moduleCss" data-id="{{ k['id']}}"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                    {% endfor %}
                                    {% endif %}
                                {% endfor %}
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
            {% endfor %}


            <div class="box-footer">
                {#/{{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}#}
                {#{{ submit_button("Lưu & Đóng", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}#}
                {{ link_to(ACP_NAME ~ "/" ~ ACP_NAME ~ '/setting', "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
