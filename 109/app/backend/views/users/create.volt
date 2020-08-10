{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Create a User</h3>
                </div>
                {{ content() }}
                <!-- form start -->
                {{ form('role':'form') }}
                <div class="row">
                    <div class="col-md-8">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">UserName</label>
                                {{ form.render("username",{'class':'form-control','id':'username'}) }}
                                {{ form.messages('username') }}
                            </div>

                            <div class="form-group">
                                <label for="name">E-mail</label>
                                {{ form.render("email",{'class':'form-control','id':'email'}) }}
                                {{ form.messages('email') }}
                            </div>

                            <div class="form-group">
                                <label for="category_id">Profile</label>
                                {{ form.render("profilesId",{'class':'form-control','id':'profilesId', 'style':'max-width:50%'}) }}
                                {{ form.messages('profilesId') }}
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Password</label>
                                {{ form.render("password",{'class':'form-control','id':'password'}) }}
                                {{ form.messages('password') }}
                            </div>

                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password</label>
                                {{ form.render("confirmPassword",{'class':'form-control','id':'confirmPassword'}) }}
                                {{ form.messages('confirmPassword') }}
                            </div>

                            <div class="form-group">
                                <label for="sort">Thứ tự</label>
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>

                            <div class="form-group">
                                <label for="active">Banned</label>
                                {{ form.render("banned") }}
                            </div>

                            <div class="form-group">
                                <label for="active">Suspended</label>
                                {{ form.render("suspended") }}
                            </div>

                            <div class="form-group">
                                <label for="active">Active</label>
                                {{ form.render("active") }}
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
                {{ endform() }}
            </div>
        </div>
    </div>
</section>