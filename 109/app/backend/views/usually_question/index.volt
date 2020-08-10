<style type="text/css">
.ajaxfileuploadimg {margin-left: 10px;border: 1px solid #ccc;position: relative;}
.ajaxfileuploadimg .img-upload-editor {width: 92px; height: 92px;border:1px solid #ccc;margin-right: 7px;position: relative;display: table;margin-bottom: 10px}
.ajaxfileuploadimg .img-upload-editor > p {display: table-cell;vertical-align: middle;}
.ajaxfileuploadimg img {max-width:100%;max-height: 90px;}
.ajaxfileuploadimg .delete_img_upload_editor {position: absolute;color: #f00;top: -13px;right: -7px;font-size: 17px;}
</style>
{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flash.output() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            <div class="panel panel-default">
                <div class="panel-heading">{{ title_bar }}</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">Tiêu đề<span class="text-danger">(*)</span></label>
                            {{ form.render("name",{'class':'form-control'}) }}
                            {{ form.messages('name') }}
                        </div>

                        <div class="form-group col-md-6">
                            <label for="name">Slogan</label>
                            {{ form.render("slogan",{'class':'form-control'}) }}
                        </div>

                        <div class="form-group col-md-6">
                            <label for="name">Hình ảnh</label>
                            <div class="customer_photo_upload clearfix">{{ partial('partials/ajaxuploadsingle', ['id':'usually_question', 'type':'cdn', 'folder':SUB_FOLDER]) }}</div>
                            <input type="hidden" name="us_photo" {% if usuallyQuestion is defined and usuallyQuestion != '' and usuallyQuestion.photo != '' %}value="{{ usuallyQuestion.photo }}"{% endif %}>
                            {% if usuallyQuestion is defined and usuallyQuestion != '' and usuallyQuestion.photo != '' %}
                            <p></p>
                            <label>Hình hiện tại</label>
                            <img src="{{ usuallyQuestion.photo }}" width="100px">
                            {% endif %}
                        </div>
						
						<div class="form-group col-md-12">
                            <h4><label for="name" class="text-danger">Danh sách câu hỏi + câu trả lời:</label></h4>
                            <div><a href="javascript:;" class="btn btn-primary btn-add-question"><i class="fa fa-plus"></i> Thêm mới</a></div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="7%">STT</th>
                                        <th>Câu hỏi + Trả lời</th>
                                        <th width="7%" class="text-center">Ẩn/Hiện</th>
                                        <th width="7%" class="text-center">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {% for answer in answers %}
                                    <tr>
                                        <th scope="row"><input type="text" name="sort[{{ answer.id }}]" value="{{ answer.sort }}" class="form-control" style="width:50px"></th>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="question[{{ answer.id }}]" value="{{ answer.question }}" class="form-control">
                                            </div>
                                            <div class="form-group editor-form">
                                                <textarea class="form-control" name="answer[{{ answer.id }}]">{{ answer.answer }}</textarea>
                                            </div>
                                        </td>
                                        <td class="text-center"><input type="checkbox" name="active[{{ answer.id }}]" value="Y" class="active-item" {% if answer.active == 'Y' %}checked{% endif %}></td>
                                        <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~  '/_delete/' ~ answer.id, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                    </tr>
                                    {% endfor %}
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group col-md-6">
                        	<a href="javascript:;" class="btn btn-primary btn-add-question"><i class="fa fa-plus"></i> Thêm mới</a>
                        </div>
                    </div>
                </div>
              
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ link_to(ACP_NAME, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>
<div class="modal modal fade" id="modalAddQuestion" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thêm câu hỏi + trả lời</h4>
        </div>
        <div class="modal-body">
        	
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
	$('.btn-add-question').on('click',function() {
        var id = {{ usuallyQuestion.id }};
        $('#modalAddQuestion').find('.modal-body').load('/' + acp_name + '/usually_question/create/' + id, function(result){
            $('#modalAddQuestion').modal({show:true});
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        {% for key,answer in answers %}
        var editor = CKEDITOR.replace( 'answer[{{ answer.id }}]',{
            allowedContent:true,
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('.editor-form').width(),
            height: 100
        });
        {% endfor %}
    });
</script>