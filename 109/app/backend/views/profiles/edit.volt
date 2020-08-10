{{ partial('partials/content_header') }}


<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Profile</h3>
                </div><!-- /.box-header -->
                {{ content() }}
                
                <form method="post" autocomplete="off">


                <div class="row">
                    
                    <div class="col-md-6">

                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#A" data-toggle="tab">Basic</a></li>
                        <li><a href="#B" data-toggle="tab">Users</a></li>
                    </ul>

                    <div class="tabbable">
                        <div class="tab-content">
                            <div class="tab-pane active" id="A">

                                {{ form.render("id") }}

                                <div class="form-group">
                                    <label for="name">Tên</label>
                                    {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                    {{ form.messages('name') }}
                                </div>
                                
                                <div class="form-group">
                                    <label for="sort">Thứ tự</label>
                                    {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                    {{ form.messages('sort') }}
                                </div>

                                <div class="clearfix">
                                    <label for="active">Active?</label>
                                    {{ form.render("active") }}
                                </div>

                            </div>

                            <div class="tab-pane" id="B">
                                <p>
                                    <table class="table table-bordered table-striped table-hover" align="center">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Name</th>
                                                <th>Banned?</th>
                                                <th>Suspended?</th>
                                                <th>Active?</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {% for user in profile.users %}
                                            <tr>
                                                <td>{{ user.id }}</td>
                                                <td>{{ user.username }}</td>
                                                <td>{{ user.banned == 'Y' ? 'Yes' : 'No' }}</td>
                                                <td>{{ user.suspended == 'Y' ? 'Yes' : 'No' }}</td>
                                                <td>{{ user.active == 'Y' ? 'Yes' : 'No' }}</td>
                                                <td width="12%">{{ link_to("acp/users/edit/" ~ user.id, '<i class="icon-pencil"></i> Edit', "class": "btn") }}</td>
                                                <td width="12%">{{ link_to("acp/users/_delete/" ~ user.id, '<i class="icon-remove"></i> Delete', "class": "btn") }}</td>
                                            </tr>
                                        {% else %}
                                            <tr><td colspan="3" align="center">There are no users assigned to this profile</td></tr>
                                        {% endfor %}
                                        </tbody>
                                    </table>
                                </p>
                            </div>

                        </div>
                    </div>
                    <div class="box-footer">
                        {{ submit_button("Save", "class": "btn btn-primary","data-type":"save") }}
                        {#{{ submit_button("Save & New", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}#}
                        {#{{ submit_button("Save & Close", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}#}
                        {{ link_to("acp/profiles", "Exit", "class": "btn btn-danger") }}
                    </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


</div