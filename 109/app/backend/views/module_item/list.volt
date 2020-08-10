{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}
            
            {{ form('role':'form','enctype':'multipart/form-data') }}
            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-xs-12">
                            {% for key,item in items %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="6%">Thứ tự</th>
                                        <th>Tên</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                <tr>
                                    <td>{{ key + 1 }}</td>
                                    <td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/updateList/' ~ item.name, ''~ item.name ~'' ) }}</td>
                                </tr>
                                {% if loop.last %}
                                    </tbody>

                                    </table>
                                    </div>
                                {% endif %}
                            {% endfor %}
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
