<div class="customer_message customer_message_mobile" {% if cf['_positon_customer_message'] == false %}style="right:0"{% endif %}>
	<div class="btn btn-block btn-customer-message bar_web_bgr"><div class="fa fa-envelope icon_offline_button"></div><span>{{ word['_de_lai_tin_nhan_cho_chung_toi'] }}</span><i class="fa fa-angle-up shrink_icon"></i></div>
	<div class="form_customer_message col-md-12">
		<div class="box_customer_message">
			<div id="customer_message_success"></div>
			<p>{{ word['_vui_long_de_lai_loi_nhan'] }}</p>
			{{ form('role':'form', 'name':'frm_customer_message', 'id':'frm_customer_message', 'action':'/send-customer-message') }}
		        {% if cf['_customer_message_name'] is defined and cf['_customer_message_name'] == true %}
		        <div class="form-group clearfix">
		            {{ c_s_form.render("c_mgs_name",{'class':'form-control','placeholder':word['_xin_moi_nhap_ho_ten']}) }}
		            <div id="errorCMgsName" class="text-danger"></div>
		        </div>
		        {% endif %}
				
				{% if cf['_customer_message_phone'] is defined and cf['_customer_message_phone'] == true %}
		        <div class="form-group clearfix">
		            {{ c_s_form.render("c_mgs_phone",{'class':'form-control','placeholder':word['_vui_long_nhap_so_dien_thoai_cua_ban']}) }}
		            <div id="errorCMgsPhone" class="text-danger"></div>
		        </div>
		        {% endif %}
				
				{% if cf['_customer_message_comment'] is defined and cf['_customer_message_comment'] == true %}
		        <div class="form-group clearfix">
		            {{ c_s_form.render("c_mgs_comment",{'class':'form-control','placeholder':word['_hay_gui_tin_nhan_cho_chung_toi_van_de_cua_ban']}) }}
		            <div id="errorCMgsComment" class="text-danger"></div>
		        </div>
		        {% endif %}

		        <div class="form-group clearfix text-center">
		            <button class="btn btn btn-primary ladda-button bar_web_bgr" id="btn-send-cmgs" data-style="slide-left"><span class="ladda-label">{{ word['_gui_tin_nhan'] }}</span></button>
		        </div>
	    	{{ endform() }}
    	</div>
	</div>
</div>