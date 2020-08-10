{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Category</h3>
                </div><!-- /.box-header -->
                {{ content() }}
                <div class="box-header">
                    {{ submit_button("Delete","id":"delete_all", "class":"btn btn-primary", "onclick":"delete_all('users')") }}
                    {#{{ submit_button("Show","id":"show_all", "class":"btn btn-primary", "onclick":"show_all('users')") }}
                    {{ submit_button("Hide","id":"hide_all", "class":"btn btn-primary", "onclick":"hide_all('users')") }}#}
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            {% for user in page.items %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                                        <th>Id</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Profile</th>
                                        <th>Banned?</th>
                                        <th>Suspended?</th>
                                        <th>Confirmed?</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                <tr>
                                    <td><input type="checkbox" id="select_all" name="select_all" value="{{ user.id }}"></td>
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.username }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.profile.name }}</td>
                                    <td>{{ user.banned == 'Y' ? 'Yes' : 'No' }}</td>
                                    <td>{{ user.suspended == 'Y' ? 'Yes' : 'No' }}</td>
                                    <td>{{ user.active == 'Y' ? 'Yes' : 'No' }}</td>
                                    <td width="12%">{{ link_to("acp/users/edit/" ~ user.id, '<i class="fa fa-pencil-square-o"></i> Edit', "class": "btn") }}</td>
                                    <td width="12%">{{ link_to("acp/users/_delete/" ~ user.id, '<i class="fa fa-times"></i> Delete', "class": "btn", 'onclick':'if(!confirm("Xác nhận xóa?")) return false') }}</td>
                                </tr>
                                {% if loop.last %}
                                    </tbody>

                                    </table>
                                    </div>
                                {% endif %}
                            {% endfor %}

                            {{ partial('partials/pagination') }}
                            <div style="clear:both"></div>
                            {{ link_to("acp/users/create", "Create new", "class": "btn btn-primary") }}
                            {#{{ link_to(url ~ this.view.getControllerName(), "Create new", "class": "btn btn-primary") }}#}
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div><!-- /.box -->
    </div>
</section>