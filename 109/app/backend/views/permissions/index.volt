{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Phân quyền</h3>
                </div><!-- /.box-header -->
                {{ content() }}
    
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-6">
                            {% for profile in profiles %}
                            {% if loop.first %}
                            <div class="table-responsive mailbox-messages">
                                <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                                        <th width="5%">Id</th>
                                        <th width="5%">Sort</th>
                                        <th>Name</th>
                                        <th width="5%">Edit</th>   
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                    <tr>
                                        <td><input type="checkbox" id="select_all" name="select_all" value="{{ profile.id }}"></td>
                                        <td>{{ profile.id }}</td>
                                        <td>{{ profile.sort }}</td>
                                        <td>{{ profile.name }}</td>
                                    
                                        <td align="center">{{ link_to('acp/permissions/edit/' ~ profile.id, '<i class="fa fa-edit"></i>' ) }}</td>
                                  
                                    </tr>
                                    {% if loop.last %}
                                    </tbody>

                                </table>
                            </div>
                            {% endif %}
                            {% endfor %}

                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>
