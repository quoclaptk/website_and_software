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
                            {% for item in items %}
                                {% if loop.first %}
                                    <div class="table-responsive mailbox-messages">
                                    <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="6%">Thứ tự</th>
                                        <th>Tên</th>
                                        {% for key,value in layout %}
                                        <th class="text-center" width="10%">Layout {{ key + 1 }}</th>
                                        {% endfor %}
                                    </tr>
                                    </thead>

                                    <tbody>
                                {% endif %}
                                <tr>
                                    <td>{{ item.sort }}</td>
                                    <td>{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, ''~ item.name ~'' ) }}</td>
                                    {% for key,value in layout %}
                                        <td class="text-center">
                                            <input type="checkbox" name="module_{{ item.id}}[]" value="{{ value.id }}" {% if array_tmp[item.id] is defined and in_array(value.id, array_tmp[item.id]) %}checked{% endif %}>
                                        </td>
                                    {% endfor %}
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
