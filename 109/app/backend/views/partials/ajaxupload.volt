<span class="btn btn-success fileinput-button">
    <i class="glyphicon glyphicon-plus"></i>
    <span>Chọn hình...</span>
    <!-- The file input field used as target for the file upload widget -->
    {% if type is defined and id is defined %}
    <input class="fileupload" type="file" name="files[]" data-id="{{ id }}" data-type="{{ type }}" multiple>
    {% elseif system_file is defined and id is defined  %}
    <input class="fileupload" type="file" name="files[]" data-id="{{ id }}" data-system="{{ system_file }}" multiple>
    {% endif %}
    

</span>
<p></p>
<p><strong class="text-danger">- Chú ý: Khi xóa hình, hình trong bài viết sẽ mất</strong></p>
<p><strong class="text-danger">- Bạn được up tối đa 30 hình/bài. Cần up hình dưới 200kb để không bị tự động giảm chất lượng hình ảnh(bị mờ hình)</strong></p>

<!-- The global progress bar -->
{#<div id="progress" class="progress">
    <div class="progress-bar progress-bar-success"></div>
</div>#}
<!-- The container for the uploaded files -->
{% if type is defined and id is defined %}
{% set file_id = type ~ '_' ~ id %}
{% elseif system_file is defined and id is defined  %}
{% set file_id = system_file ~ '_' ~ id %}
{% endif %}
<div id="files_{{ file_id }}" class="files ajaxfileuploadimg clearfix">
    <div class="row">
        <div class="col-md-12">
            <div class="ajaxfileimg">
            	{% if img_upload_paths is defined and img_upload_paths != '' %}
            		{% for i in img_upload_paths %}
            			<div class="pull-left text-center img-upload-editor">
                            <p><img src="{{ i }}"></p>
                            <a href="javascript:;" data-url="{{ i }}" class="delete_img_upload_editor"><i class="fa fa-times"></i></a>
                        </div>
        			{% endfor %}
            	{% endif %}
            </div>
        </div>
    </div>
</div>