{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Danh mục Category Video</h3>
                </div><!-- /.box-header -->
                {{ content() }}
                <div class="box-header">
                    {{ submit_button("Delete","id":"delete_all", "class":"btn btn-primary", "onclick":"delete_all('category_video')") }}
                    {{ submit_button("Show","id":"show_all", "class":"btn btn-primary", "onclick":"show_all('category_video')") }}
                    {{ submit_button("Hide","id":"hide_all", "class":"btn btn-primary", "onclick":"hide_all('category_video')") }}
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            {% for category_video in page.items %}
                            {% if loop.first %}
                            <div class="table-responsive mailbox-messages">
                                <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                                        <th width="5%">Id</th>
                                        <th width="5%">Sort</th>
                                        <th>Name</th>
                                        <th width="5%">Hot</th>
                                        <th width="5%">Show/Hide</th>
                                        <th width="5%">Edit</th>
                                        <th width="5%">Delete</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                    <tr>
                                        <td><input type="checkbox" id="select_all" name="select_all" value="{{ category_video.id }}"></td>
                                        <td>{{ category_video.id }}</td>
                                        <td>{{ category_video.sort }}</td>
                                        <td>{{ category_video.name }}</td>
                                        <td align="center">
                                        {% if category_video.hot == 'Y' %}
                                            {{ link_to('acp/category_video/hidehot/' ~ category_video.id, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                        {% else %}
                                            {{ link_to('acp/category_video/showhot/' ~ category_video.id, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                        {% endif %}
                                        </td>
                                        <td align="center">
                                        {% if category_video.active == 'Y' %}
                                            {{ link_to('acp/category_video/hide/' ~ category_video.id, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                        {% else %}
                                            {{ link_to('acp/category_video/show/' ~ category_video.id, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                        {% endif %}
                                        </td>
                                        <td align="center">{{ link_to('acp/category_video/update/' ~ category_video.id, '<i class="fa fa-edit"></i>' ) }}</td>
                                        <td align="center">{{ link_to('acp/category_video/_delete/' ~ category_video.id, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                    </tr>
                                    {% if loop.last %}
                                    </tbody>

                                </table>
                            </div>
                            {% endif %}
                            {% endfor %}

                            {{ partial('partials/pagination') }}
                            <div style="clear:both"></div>
                            {{ link_to("acp/category_video/create", "Create new", "class": "btn btn-primary") }}
                            {#{{ link_to(url ~ this.view.getControllerName(), "Create new", "class": "btn btn-primary") }}#}
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>
