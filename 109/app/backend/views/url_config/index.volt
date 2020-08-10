{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Link website</h3>
                </div><!-- /.box-header -->
                {{ content() }}
                <div class="box-header">
                    {{ submit_button("Delete","id":"delete_all", "class":"btn btn-primary", "onclick":"delete_all('url_config')") }}
                    {{ submit_button("Show","id":"show_all", "class":"btn btn-primary", "onclick":"show_all('url_config')") }}
                    {{ submit_button("Hide","id":"hide_all", "class":"btn btn-primary", "onclick":"hide_all('url_config')") }}
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            {% for list in page.items %}
                            {% if loop.first %}
                            <div class="table-responsive mailbox-messages">
                                <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                                        <th width="5%">Id</th>
                                        <th width="5%">Sort</th>
                                        <th>Name</th>
                                        <th width="5%">Show/Hide</th>
                                        <th width="5%">Edit</th>
                                        <th width="5%">Delete</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                    <tr>
                                        <td><input type="checkbox" id="select_all" name="select_all" value="{{ list.id }}"></td>
                                        <td>{{ list.id }}</td>
                                        <td>{{ list.sort }}</td>
                                        <td>{{ list.name }}</td>
                                        <td align="center">
                                        {% if list.active == 'Y' %}
                                            {{ link_to('acp/url_config/hide/' ~ list.id, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                        {% else %}
                                            {{ link_to('acp/url_config/show/' ~ list.id, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                        {% endif %}
                                        </td>
                                        <td align="center">{{ link_to('acp/url_config/update/' ~ list.id, '<i class="fa fa-edit"></i>' ) }}</td>
                                        <td align="center">{{ link_to('acp/url_config/_delete/' ~ list.id, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                    </tr>
                                    {% if loop.last %}
                                    </tbody>

                                </table>
                            </div>
                            {% endif %}
                            {% endfor %}

                            {{ partial('partials/pagination') }}
                            <div style="clear:both"></div>
                            {{ link_to("acp/url_config/create", "Create new", "class": "btn btn-primary") }}
                            {#{{ link_to(url ~ this.view.getControllerName(), "Create new", "class": "btn btn-primary") }}#}
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>