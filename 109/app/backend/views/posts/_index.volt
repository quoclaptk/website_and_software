{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}

            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">
                    {% if auth.getIdentity()['role'] == 1 %}
                    <div class="clearfix">
                        <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary") }}</div>
                        {#<div class="pull-right">
                            <a href="javascript:;" class="btn btn-primary" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="show_all('{{ controller_name }}', {{ page_current }})">Hiển thị nhiều mục</a>
                            <a href="javascript:;" class="btn btn-primary" onclick="hide_all('{{ controller_name }}', {{ page_current }})">Ẩn nhiều mục</a>
                        </div>#}
                        <p class="clear"></p>
                    </div>
                    {% endif %}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                    {% for key,item in result %}
                                        {% if loop.first %}
                                            <div class="table-responsive mailbox-messages">
                                            <table id="example" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                {#<th width="7%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>#}
                                                <th width="7%">Thứ tự</th>
                                                <th>Nội dung</th>
                                                {#<th width="8%" class="text-center">Ẩn/Hiện</th>
                                                <th width="5%" class="text-center">Sửa</th>
                                                <th width="5%" class="text-center">Xóa</th>#}
                                            </tr>
                                            </thead>

                                            <tbody>
                                        {% endif %}
                                        <tr>
                                            {#<td><input type="checkbox" name="select_all" value="{{ item['id'] }}"></td>#}
                                            <td>{{ key + 1 }}</td>
                                            {#<td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, ''~ item.name ~'' ) }}</td>#}
                                            <td>
                                                {{ form('role':'form','enctype':'multipart/form-data', 'action':'/' ~ ACP_NAME ~ '/' ~ controller_name ~ '/updatePage/' ~ item['id']) }}
                                                {#<div class="form-group">
                                                    <ul class="list_category">
                                                        {% for i in position %}
                                                            <li style="display: inline-block">
                                                                <input type="checkbox" name="position_{{ item['id'] }}[]" value="{{ i.id }}"{% if item['tmp_position_module_item_arr'] is defined %}{% if i.id in item['tmp_position_module_item_arr'] %}checked="checked"{% endif %}{% endif %}>
                                                                <span>{{ i.name }}</span>
                                                            </li>
                                                        {% endfor %}
                                                    </ul>
                                                </div>#}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" name="name_{{ item['id'] }}" value="{{ item['name'] }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="checkbox">
                                                                <label><input type="checkbox" name="messenger_form_{{ item['id'] }}" value="Y" {% if item['messenger_form'] == 'Y' %}checked{% endif %}> Hiện box tin nhắn</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group editor-form">
                                                    <textarea name="content_{{ item['id'] }}" class="form-control">{{ item['content'] }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" name="ok_{{ item['id'] }}" value="Lưu" class="btn btn-primary">
                                                </div>
                                                {{ endform() }}
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        var editor = CKEDITOR.replace( 'content_{{ item['id'] }}',{
                                                            allowedContent:true,
                                                            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
                                                            uiColor : '#3c8dbc',
                                                            language:'en',
                                                            skin:'moono',
                                                            width: $('.editor-form').width(),
                                                            height: 300
                                                        });
                                                    })
                                                </script>
                                            </td>

                                            {#<td class="text-center">
                                                {% if item['active'] == 'Y' %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hide/' ~ item['id'] ~ '/' ~ page_current, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                {% else %}
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/show/' ~ item['id'] ~ '/' ~ page_current, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                {% endif %}
                                            </td>
                                            <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-edit"></i>' ) }}</td
                                            <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_delete/' ~ item['id'] ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>>#}
                                        </tr>
                                        {% if loop.last %}
                                            </tbody>

                                            </table>
                                            </div>
                                        {% endif %}
                                    {% endfor %}
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="display: block">Up hình để đưa vào bài viết</div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {{ partial('partials/ajaxupload', ['id': row_id, 'type':'post']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {#<div class="text-center">{{ partial('partials/pagination') }}</div>#}
            </div>
            {#
            <div class="box-footer">
                {{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary") }}
            </div>
            #}
        </div>
    </div>
</section>
