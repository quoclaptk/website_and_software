{#{{ partial('partials/nav_setting') }}#}
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}
            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            {#<h5 style="color:#f00;font-style: italic;margin:0;margin-bottom:3px">Click để chỉnh sửa thông tin</h5>#}
            <div class="panel panel-default">
                <div class="panel-heading" style="display:block !important">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse_info" style="display: block;">Banner</a></h4>
                </div>
                <div id="collapse_info" class="panel-collapse collapse in">
                    <div class="panel-body">
                                
                        <div class="row" style="margin-top:20px" id="load_baner_html">                                
                            <table class="table table-bordered">
                                <tr>
                                  <th class="text-center">Banner</th>
                                  <th style="width: 82px"></th>
                                </tr>
                                {%  if banner_html_sub != '' %}
                                <tr>
                                  <td>
                                      <iframe src="{{ HTTP_HOST ~ '/' ~ ACP_NAME ~ '/banner_html/view/' ~ banner_html_sub.id }}" class="select_banner_html" width="100%" height="320" frameborder="0" scrolling="no"></iframe>
                                  </td>
                                  <td class="text-center" style="vertical-align: middle">
                                    <div class="text-center form-group">
                                        <label>Hình nền</label>
                                         <div class="form-group">
                                            <button type="button" data-id="{{ banner_html_sub.id }}" class="btn btn-warning openModalEditCss" style="margin-right: 10px">
                                            <i class="fa fa-css3"></i> Sửa CSS
                                            </button>
                                        </div>
                                        {{ form.render("banner_1",{'id':'banner_1'}) }}
                                        {% if setting.banner_1 != '' %}
                                        {{ image('files/default/' ~ SUB_FOLDER ~ '/' ~ setting.banner_1 , 'style':'max-width:100%;margin-top:10px;max-height:80px') }}
                                        {% else %}
                                        {{ image('backend/dist/img/no-image.png' , 'style':'max-width:50%;margin-top:10px;max-height:150px') }}
                                        {% endif %}
                                    </div>
                                    <input type="radio" name="banner_html_id" value="{{ banner_html_sub.id }}" checked>
                                  </td>
                                </tr>
                                {% endif %}
                                {% for i in banner_html %}
                                <tr>
                                  <td>
                                      <iframe src="{{ HTTP_HOST ~ '/' ~ ACP_NAME ~ '/banner_html/view/' ~ i.id }}" class="select_banner_html" width="100%" height="320" frameborder="0" scrolling="no"></iframe>
                                  </td>
                                  <td class="text-center" style="vertical-align: middle">
                                    <input type="radio" name="banner_html_copy_id" value="{{ i.id }}" >
                                  </td>
                                </tr>
                                {% endfor %}
                            </table>
                        </div>

                        
                        
                        
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Save", "class": "btn btn-primary","data-type":"save") }}
                {#{{ submit_button("Save & New", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}#}
                {#{{ submit_button("Save & Close", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}#}
                {{ link_to("/category", "Exit", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}

        </div>
    </div>
</section>

<div class="modal modal fade" id="myModalEditCss" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      {{ form('role':'form','action':'', 'id':'form-edit-css') }}
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Sửa css banner</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" id="save-css" class="btn btn-primary">Lưu</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      {{ endform() }}
      
    </div>
</div>