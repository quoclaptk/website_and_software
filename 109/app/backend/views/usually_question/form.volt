{{ form('role':'form','action': '/' ~ ACP_NAME ~ '/' ~ router.getControllerName() ~ '/' ~ router.getActionName() ~ '/' ~ usually_question_id ,'enctype':'multipart/form-data', 'id':'form-add-question') }}
<input type="hidden" name="usually_question_id" value="{{ usually_question_id }}">
<div class="form-group">
    <label>Câu hỏi<span class="text-danger">(*)</span></label>
    {{ form.render("question",{'class':'form-control'}) }}
    {{ form.messages('question') }}
</div>

<div class="form-group editor-form">
    <label>Câu trả lời<span class="text-danger">(*)</span></label>
    {{ form.render("answer",{'class':'form-control'}) }}
    {{ form.messages('answer') }}
</div>
<div class="form-group">
    <label for="sort">Thứ tự</label>
    {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
    {{ form.messages('sort') }}
</div>
<div class="form-group">
    {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
</div>
{{ endform() }}
<script type="text/javascript">
    $(document).ready(function(){
        var editor = CKEDITOR.replace( 'answer',{
            allowedContent:true,
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('.editor-form').width(),
            height: 150,
        });

        $('#form-add-question').submit(function(e) {
	        e.preventDefault();
	        var form = $(this);
	        var usually_question_id = $(this).find('input[name=usually_question_id]').val();
	        var question = $(this).find('input[name=question]').val();
	        var sort = $(this).find('input[name=sort]').val();
	        var answer = CKEDITOR.instances.answer.getData();
	        var data = {'usually_question_id':usually_question_id, 'question':question, 'answer':answer, 'sort':sort}
	        $.ajax({
	            type: 'POST',
	            url: form.attr('action'),
	            data: data,
	            success: function (result) {
	                if (result != 1) {
	                	if (!isJSON(result)) {
	                    	$('#modalAddQuestion .modal-body').html(result);
	                    }
	                } else {
	                	window.location.reload();
	                }
	            }
	        })
	    });
    });
</script>