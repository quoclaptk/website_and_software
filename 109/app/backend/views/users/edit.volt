{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Profile</h3>
                </div><!-- /.box-header -->
            

                <form method="post" autocomplete="off">


                    {{ content() }}

                    <div class="row">

                        <div class="col-md-10">

                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#A" data-toggle="tab">Basic</a></li>
                                <li><a href="#B" data-toggle="tab">Successful Logins</a></li>
                                <li><a href="#C" data-toggle="tab">Password Changes</a></li>
                                <li><a href="#D" data-toggle="tab">Reset Passwords</a></li>
                            </ul>

                            <div class="tabbable">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="A">

                                        {{ form.render("id") }}

                                        <div class="span4 col-md-6">

                                            <div class="clearfix">
                                                <label for="username">Username</label>
                                                {{ form.render("username", {'class':'form-control','id':'username'}) }}
                                            </div>
                                            
                                            {#<div class="form-group">
                                                <label for="password">Password</label>
                                                {{ form.render("password",{'class':'form-control','id':'password'}) }}
                                                {{ form.messages('password') }}
                                            </div>

                                            <div class="form-group">
                                                <label for="confirmPassword">Confirm Password</label>
                                                {{ form.render("confirmPassword",{'class':'form-control','id':'confirmPassword'}) }}
                                                {{ form.messages('confirmPassword') }}
                                            </div>#}

                                            <div class="clearfix">
                                                <label for="profilesId">Profile</label>
                                                {{ form.render("profilesId", {'class':'form-control','id':'profilesId'}) }}
                                            </div>

                                            <div class="clearfix">
                                                <label for="suspended">Suspended?</label>
                                                {{ form.render("suspended", {'class':'form-control','id':'suspended','style':'width:150px'}) }}
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="sort">Thứ tự</label>
                                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                                {{ form.messages('sort') }}
                                            </div>

                                        </div>

                                        <div class="span4 col-md-6">

                                            <div class="clearfix">
                                                <label for="email">E-Mail</label>
                                                {{ form.render("email", {'class':'form-control','id':'email'}) }}
                                            </div>

                                            <div class="clearfix">
                                                <label for="banned">Banned?</label>
                                                {{ form.render("banned", {'class':'form-control','id':'banned','style':'width:150px'}) }}
                                            </div>

                                            <div class="clearfix">
                                                <label for="active">Confirmed?</label>
                                                {{ form.render("active", {'class':'form-control','id':'active','style':'width:150px'}) }}
                                            </div>

                                        </div>
                                    </div>

                                    <div class="tab-pane" id="B">
                                        <p>
                                        <table class="table table-bordered table-striped table-hover" align="center">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>IP Address</th>
                                                    <th>User Agent</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {% for login in user.successLogins %}
                                                    <tr>
                                                        <td>{{ login.id }}</td>
                                                        <td>{{ login.ipAddress }}</td>
                                                        <td>{{ login.userAgent }}</td>
                                                    </tr>
                                                {% else %}
                                                    <tr><td colspan="3" align="center">User does not have successfull logins</td></tr>
                                                {% endfor %}
                                            </tbody>
                                        </table>
                                        </p>
                                    </div>

                                    <div class="tab-pane" id="C">
                                        <p>
                                        <table class="table table-bordered table-striped table-hover" align="center">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>IP Address</th>
                                                    <th>User Agent</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {% for change in user.passwordChanges %}
                                                    <tr>
                                                        <td>{{ change.id }}</td>
                                                        <td>{{ change.ipAddress }}</td>
                                                        <td>{{ change.userAgent }}</td>
                                                        <td>{{ date("Y-m-d H:i:s", change.createdAt) }}</td>
                                                    </tr>
                                                {% else %}
                                                    <tr><td colspan="3" align="center">User has not changed his/her password</td></tr>
                                                {% endfor %}
                                            </tbody>
                                        </table>
                                        </p>
                                    </div>

                                    <div class="tab-pane" id="D">
                                        <p>
                                        <table class="table table-bordered table-striped table-hover" align="center">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Date</th>
                                                    <th>Reset?</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {% for reset in user.resetPasswords %}
                                                    <tr>
                                                        <th>{{ reset.id }}</th>
                                                        <th>{{ date("Y-m-d H:i:s", reset.createdAt) }}
                                                        <th>{{ reset.reset == 'Y' ? 'Yes' : 'No' }}
                                                    </tr>
                                                {% else %}
                                                    <tr><td colspan="3" align="center">User has not requested reset his/her password</td></tr>
                                                {% endfor %}
                                            </tbody>
                                        </table>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                                            
                    <div class="box-footer">
                        {{ submit_button("Save", "class": "btn btn-primary","data-type":"save") }}
                        {#{{ submit_button("Save & New", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}#}
                        {#{{ submit_button("Save & Close", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}#}
                        {{ link_to("acp/users", "Exit", "class": "btn btn-danger") }}
                    </div> 

                </form>
            </div>
        </div>
    </div>
</div>
</section>