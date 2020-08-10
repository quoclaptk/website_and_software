{% if position == "right" %}
    {% set box_class = "box_right_element" %}
{% elseif position == "left" %}
    {% set box_class = "box_left_element" %}
{% else %}
	{% set box_class = "box_center_element" %}
{% endif %}

<div class="{{ box_class }}">
    <div class="title_bar_right bar_web_bgr text-uppercase">{{ word['_de_lai_tin_nhan_cho_chung_toi'] }}</div>
    <div class="box_customer_message">
		<div id="customer_message_module_success_{{ position }}"></div>
		<p>{{ word['_vui_long_de_lai_loi_nhan'] }}</p>
		{% if cf['_cf_radio_cmg_label'] == true %}
		{{ form('role':'form', 'name':'frm_customer_message_' ~ position, 'class':'form-horizontal', 'enctype':'multipart/form-data', 'action':'/send-customer-message') }}
		{% else %}
		{{ form('role':'form', 'name':'frm_customer_message_' ~ position, 'enctype':'multipart/form-data', 'action':'/send-customer-message') }}
		{% endif %}
	        {% if cf['_cf_radio_box_option'] is defined and cf['_cf_radio_box_option'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_box_tuy_chon'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_box_option_select",{'class':'form-control'}) }}
	            <div id="errorCMgsBoxSelectOption{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_work_province'] is defined and cf['_cf_radio_cmg_work_province'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_tinh_thanh_day'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_work_province",{'class':'form-control'}) }}
	            <div id="errorCMgsWorkProvince{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_customer_message_name'] is defined and cf['_customer_message_name'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_ho_ten'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_name",{'class':'form-control','placeholder':word['_xin_moi_nhap_ho_ten']}) }}
	            <div id="errorCMgsName{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_birthday'] is defined and cf['_cf_radio_cmg_birthday'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_ngay_sinh'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_birthday",{'class':'form-control customer-message-datepicker'}) }}
	            <div id="errorCMgsBirthday{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_home_town'] == true or cf['_cf_radio_cmg_voice'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_nguyen_quan'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	        		<div class="row">
	        			{% if cf['_cf_radio_cmg_home_town'] == true %}
	        			<div class="col-sm-6">
	        				{{ c_s_form.render("c_mgs_home_town",{'class':'form-control'}) }}
	        				<div id="errorCMgsHomeTown{{ position }}" class="text-danger"></div>
	        			</div>
	        			{% endif %}
	        			{% if cf['_cf_radio_cmg_voice'] == true %}
	        			<div class="col-sm-6">
	        				{{ c_s_form.render("c_mgs_voice",{'class':'form-control'}) }}
	        				<div id="errorCMgsVoice{{ position }}" class="text-danger"></div>
	        			</div>
	        			{% endif %}
	        		</div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_address'] is defined and cf['_cf_radio_cmg_address'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_dia_chi'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_address",{'class':'form-control'}) }}
	            <div id="errorCMgsAddress{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_email'] is defined and cf['_cf_radio_cmg_email'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_email'] }}<span class="text-danger font-normal"></span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_email",{'class':'form-control'}) }}
	            <div id="errorCMgsEmail{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}
			
			{% if cf['_customer_message_phone'] is defined and cf['_customer_message_phone'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_dien_thoai'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_phone",{'class':'form-control','placeholder':word['_vui_long_nhap_so_dien_thoai_cua_ban']}) }}
	            <div id="errorCMgsPhone{{ position }}" class="text-danger"></div>
	            {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_portrait'] is defined and cf['_cf_radio_cmg_portrait'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_anh_the'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            <div class="portrait_image_upload clearfix">{{ partial('partials/ajaxupload', ['id':'portrait', 'type':'cdn', 'folder':subdomain.folder]) }}</div>
	            <div id="errorCMgsPortraitImage{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
		        <input type="hidden" name="c_mgs_portrait_image">
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_certificate'] is defined and cf['_cf_radio_cmg_certificate'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_anh_bang_cap'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            <div class="certificate_image_upload clearfix">{{ partial('partials/ajaxupload', ['id': 'certificate', 'type':'cdn', 'folder':subdomain.folder]) }}</div>
	            <div id="errorCMgsCertificateImage{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
		        <input type="hidden" name="c_mgs_certificate_image">
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_college_address'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_sinh_vien_giao_vien_truong'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_college_address",{'class':'form-control','placeholder':word['_vi_du_dai_hoc_su_pham']}) }}
	            <div id="errorCMgsCollegeAddress{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_major'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_nganh_hoc'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_major",{'class':'form-control'}) }}
	            <div id="errorCMgsMajor{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_graduation_year'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_nam_tot_nghiep'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_graduation_year",{'class':'form-control'}) }}
	            <div id="errorCMgsGraduationYear{{ position }}" class="text-danger"></div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_level'] == true or cf['_cf_radio_cmg_gender'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_hien_la'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	        		<div class="row">
	        			{% if cf['_cf_radio_cmg_level'] == true %}
	        			<div class="col-sm-6">
	        				{{ c_s_form.render("c_mgs_level",{'class':'form-control'}) }}
	        				<div id="errorCMgsLevel{{ position }}" class="text-danger"></div>
	        			</div>
	        			{% endif %}
	        			{% if cf['_cf_radio_cmg_gender'] == true %}
	        			<div class="col-sm-6">
	        				{{ c_s_form.render("c_mgs_gender",{'class':'form-control'}) }}
	        				<div id="errorCMgsGender{{ position }}" class="text-danger"></div>
	        			</div>
	        			{% endif %}
	        		</div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_forte'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_uu_diem'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_forte",{'class':'form-control','placeholder':word['_vi_du_kinh_nghiem']}) }}
	            <div id="errorCMgsForte{{ position }}" class="text-danger"></div>
	            {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_subjects'] == true %}
        	{% set subjects = form_helper.subjectsTrainingCheckbox() %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_mon_day'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
				<div class="row">
					{% for key,subject in subjects %}
					{% set inpName = 'c_mgs_subjects' %}
					<div class="col-sm-4">
						<div class="checkbox">
							<label>{{ c_s_form.render(inpName, {'value':key}) }} {{ subject }}</label>
						</div>
					</div>
				{% endfor %}
				</div>
	            <div id="errorCMgsSubjects{{ position }}" class="text-danger"></div>
	            {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_class'] == true %}
        	{% set classes = form_helper.classSelect(true) %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_lop_day'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
				<div class="row">
					{% for key,class in classes %}
					{% set inpName = 'c_mgs_class' %}
					<div class="col-sm-4">
						<div class="checkbox">
							<label>{{ c_s_form.render(inpName, {'value':key}) }} {{ class }}</label>
						</div>
					</div>
				{% endfor %}
				</div>
	            <div id="errorCMgsClass{{ position }}" class="text-danger"></div>
	            {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_teaching_time'] == true %}
        	{% set traningTimes = form_helper.trainingTimeCheckBox() %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_thoi_gian_day'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
				<div class="row">
					{% for key,traningTime in traningTimes %}
					{% set inpName = 'c_mgs_training_time' %}
					<div class="col-sm-4">
						<div class="checkbox">
							<label>{{ c_s_form.render(inpName, {'value':key}) }} {{ traningTime }}</label>
						</div>
					</div>
				{% endfor %}
				</div>
	            <div id="errorCMgsTrainingTime{{ position }}" class="text-danger"></div>
	            {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_salary'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_yeu_cau_luong_toi_thieu'] }}</label><div class="col-sm-9">{% endif %}
        		<div class="row">
        			<div class="col-sm-8">{{ c_s_form.render("c_mgs_salary",{'class':'form-control'}) }}</div>
        			<div class="col-sm-4 col-on-week">{{ word['_tren_buoi'] }}</div>
        		</div>
		        {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}
			
			{% if cf['_customer_message_comment'] is defined and cf['_customer_message_comment'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_yeu_cau_khac'] }}<span class="text-danger font-normal">({{ word['_bat_buoc'] }})</span></label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_comment",{'class':'form-control','placeholder':word['_hay_gui_tin_nhan_cho_chung_toi_van_de_cua_ban']}) }}
	            <div id="errorCMgsComment{{ position }}" class="text-danger"></div>
	            {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        {% if cf['_cf_radio_cmg_other_request'] == true %}
	        <div class="form-group clearfix">
	        	{% if cf['_cf_radio_cmg_label'] == true %}<label class="col-sm-3 control-label">{{ word['_yeu_cau_khac'] }}</label><div class="col-sm-9">{% endif %}
	            {{ c_s_form.render("c_mgs_other_request",{'class':'form-control', 'placeholder':word['_yeu_cau_khac']}) }}
	            {% if cf['_cf_radio_cmg_label'] == true %}</div>{% endif %}
	        </div>
	        {% endif %}

	        <div class="form-group clearfix text-center">
	            <button class="btn btn btn-primary ladda-button btn-send-cmgs-module bar_web_bgr" data-position="{{ position }}" data-style="slide-left"><span class="ladda-label">{{ word['_gui_tin_nhan'] }}</span></button>
	        </div>
    	{{ endform() }}
	</div>
</div>
