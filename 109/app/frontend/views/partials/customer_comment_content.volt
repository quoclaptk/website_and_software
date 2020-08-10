<div class="md_customer_mesage_elm clearfix">
	<div class="col-md-8 col-list-comment">
		{% if customerComments|length > 0 %}
		{% for customerComment in customerComments %}
		{% if customerComment.photo != '' %}
		{% set photo = customerComment.photo %}
		{% else %}
		{% set photo = "/assets/images/no-image.png" %}
		{% endif %}
		<div class="media"> 
			<div class="media-left"> 
				<div class="md_customer_mesage_image">
					<img class="media-object img-circle img-cover max-width-none" src="{{ photo }}" data-holder-rendered="true">
				</div>
			</div> 
			<div class="media-body"> 
				<div class="md_customer_mesage_content">{{ image('assets/images/comment-open.gif') }}{{ customerComment.comment }}{{ image('assets/images/comment-close.gif') }}</div>
				<div class="text-right comment-author">
					<i class="text-right">{{ customerComment.name }}</i>
				</div>
			</div> 
	    </div>
	    {% endfor %}
	    {% else %}
	    <div class="alert alert-warning">Hiện chưa có phản hồi nào</div>
	    {% endif %}
    </div>
    <div class="col-md-4 col-form-comment">
    	<div class="box_custommer_comment_form">
    		<div id="customer_comment_success"></div>
    		<h4>Gửi ý kiến</h4>
    		{{ form('role':'form', 'name':'frm_customer_comment', 'id': 'frm_customer_comment', 'enctype':'multipart/form-data', 'action':'/send-customer-comment', 'data-name':word['_y_kien_khach_hang']) }}
    			<div class="form-group clearfix form-custommer-comment-name">
					{{ c_c_form.render("cc_f_name", {'class':'form-control','placeholder':word['_ho_ten']}) }}
					<div id="errorFrmCCFName" class="text-danger"></div>
				</div>
		        <div class="form-group clearfix form-custommer-comment-phone">
		            {{ c_c_form.render("cc_f_phone", {'class':'form-control','placeholder':word['_dien_thoai']}) }}
		            <div id="errorFrmCCFPhone" class="text-danger"></div>
		        </div>
		        <div class="form-group clearfix form-custommer-comment-email">
		            {{ c_c_form.render("cc_f_email", {'class':'form-control','placeholder':word['_email']}) }}
		            <div id="errorFrmCCFEmail" class="text-danger"></div>
		        </div>
		        <div class="form-group clearfix form-custommer-comment-address">
		            {{ c_c_form.render("cc_f_address", {'class':'form-control','placeholder':word['_dia_chi']}) }}
		        </div>
		        <div class="form-group clearfix">
		             <div class="customer_photo_upload clearfix">
		            	<span class="btn btn-success fileinput-button pull-left">
						    <i class="glyphicon glyphicon-plus"></i>
						    <span>{{ word._('_chon_hinh') }}...</span>
		            	 	<input type="file" onchange="readURL(this);" name="file_images_custom_cmts">
						</span>
						<img src="#" class="img_review" />
		            </div> 
		             <div id="errorFrmItemFileCmt" class="text-danger"></div>
		        </div>
		        <div class="form-group clearfix form-custommer-comment-comment">
		            {{ c_c_form.render("cc_f_comment", {'class':'form-control','placeholder':word['_noi_dung']}) }}
		            <div id="errorFrmCCFComment" class="text-danger"></div>
		        </div>
		        <div class="form-group clearfix">
		            <button type="submit" class="btn btn-warning ladda-button text-uppercase btn-send-form-customer-comment bar_web_bgr" data-style="slide-left"><span class="ladda-label">{{ word['_gui'] }}</span></button>
		        </div>
    		{{ endform() }}
    	</div>
    </div>
</div>