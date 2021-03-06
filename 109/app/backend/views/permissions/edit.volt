{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Cập nhật phân quyền nhóm <strong>{{ profile.name }}</strong></h3>
                </div>
                {{ content() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <form method="post">

                            {% for resource, actions in acl.getResources() %}

                                    <h3>{{ acl.getResourceDescription(resource) }}</h3>

                                    <table class="table table-bordered table-striped table-hover" align="center">
                                        <thead>
                                            <tr>
                                                <th width="5%"></th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {% for action in actions %}
                                            <tr>
                                                <td align="center"><input type="checkbox" name="permissions[]" value="{{ resource ~ '.' ~ action }}"  {% if permissions[resource ~ '.' ~ action] is defined %} checked="checked" {% endif %}></td>
                                                <td>{{ acl.getActionDescription(action)}}</td>
                                            </tr>
                                            {% endfor %}
                                        </tbody>
                                    </table>

                            {% endfor %}

                            
                            
                           <div class="box-footer">
                            {{ submit_button("Save", "class": "btn btn-primary", "data-type":"save") }}
                            {#{{ submit_button("Save & New", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}#}
                            {#{{ submit_button("Save & Close", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}#}
                            {{ link_to("acp/permissions", "Exit", "class": "btn btn-danger") }}
                        </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

