{% set id = 1 %}
{% if position == "right" %}
    {% set box_class = "box_right_element" %}
{% elseif position == "left" %}
    {% set box_class = "box_left_element" %}
{% else %}
  {% set box_class = "box_center_element" %}
{% endif %}

<div class="{{ box_class }}">
    {% if position == 'header' or position == 'footer' %}<div class="container">{% endif %}
    <div class="title_bar_right bar_web_bgr text-uppercase">{{ word['_gui_yeu_cau_bao_gia'] }}</div>
    <div class="box_form_item_module">
        <div id="form_item_module_success_{{ position }}"></div>
        {{ form('role':'form', 'name':'frm_item_ycbg_module', 'enctype':'multipart/form-data', 'action':'/send-form-info', 'data-name':word['_gui_yeu_cau_bao_gia'], 'data-position':position) }}
          {{frm_item_form.render('frm_item_id', {'value':id})}}
          {% if cf['_frm_ycbg_name'] is defined and cf['_frm_ycbg_name'] == true %}
          <div class="form-group clearfix form-item-name">
              {{ frm_item_form.render("frm_item_name_" ~ id,{'class':'form-control','placeholder':word['_ho_ten'] ~ ' (' ~ word['_bat_buoc'] ~ ')' }) }}
              <div id="errorFrmItemName{{ position }}" class="text-danger"></div>
          </div>
          {% endif %}
          
          {% if cf['_frm_ycbg_phone'] is defined and cf['_frm_ycbg_phone'] == true %}
          <div class="form-group clearfix form-item-phone">
              {{ frm_item_form.render("frm_item_phone_" ~ id, {'class':'form-control','placeholder':word['_dien_thoai'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
              <div id="errorFrmItemPhone{{ position }}" class="text-danger"></div>
          </div>
          {% endif %}
          
          {% if cf['_frm_ycbg_email'] is defined and cf['_frm_ycbg_email'] == true %}
          <div class="form-group clearfix form-item-email">
              {{ frm_item_form.render("frm_item_email_" ~ id, {'class':'form-control','placeholder':word['_email'] ~ ' (' ~ word['_khong_bat_buoc'] ~ ')'}) }}
              <div id="errorFrmItemEmail{{ position }}" class="text-danger"></div>
          </div>
          {% endif %}

          {% if cf['_frm_ycbg_class'] == true %}
            <div class="form-group clearfix form-item-class">
                <div class="row">
                  <div class="col-md-6">{{ frm_item_form.render("frm_item_class_" ~ id, {'class':'form-control'}) }}</div>
                </div>
                <div id="errorFrmItemClass{{ position }}" class="text-danger"></div>
            </div>
          {% endif %}

          {% if cf['_frm_ycbg_subjects'] is defined and cf['_frm_ycbg_subjects'] == true %}
          <div class="form-group clearfix form-item-subjects">
              {{ frm_item_form.render("frm_item_subjects_" ~ id, {'class':'form-control','placeholder':word['_mon_hoc'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
              <div id="errorFrmItemSubjects{{ position }}" class="text-danger"></div>
          </div>
          {% endif %}

          {% if cf['_frm_ycbg_student_number'] is defined and cf['_frm_ycbg_student_number'] == true %}
          <div class="form-group clearfix form-item-studen-number">
              {{ frm_item_form.render("frm_item_student_number_" ~ id, {'class':'form-control','placeholder':word['_so_luong_hoc_sinh'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
              <div id="errorFrmItemStudentNumber{{ position }}" class="text-danger"></div>
          </div>
          {% endif %}

          {% if cf['_frm_ycbg_learning_level'] is defined and cf['_frm_ycbg_learning_level'] == true %}
          <div class="form-group clearfix form-item-learning-level">
              {{ frm_item_form.render("frm_item_learning_level_" ~ id, {'class':'form-control','placeholder':word['_hoc_luc_hien_tai'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
              <div id="errorFrmItemLearningLevel{{ position }}" class="text-danger"></div>
          </div>
          {% endif %}

          {% if cf['_frm_ycbg_learning_time'] == true %}
            <div class="form-group clearfix form-item-learning-time">
              <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">{{ frm_item_form.render("frm_item_learning_time_" ~ id, {'class':'form-control'}) }}</div><div class="col-md-6 col-sm-6 col-xs-6 col-on-week">{{ word['_tren_tuan'] }}</div>
                <div class="clear"></div>
                <div id="errorFrmItemLearningTime{{ position }}" class="col-md-12 text-danger"></div>
            </div>
          </div>
          {% endif %}

          {% if cf['_frm_ycbg_learning_day'] is defined and cf['_frm_ycbg_learning_day'] == true %}
          <div class="form-group clearfix form-item-learning-day">
              {{ frm_item_form.render("frm_item_learning_day_" ~ id, {'class':'form-control','placeholder':word['_thoi_gian_hoc'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
              <div id="errorFrmItemLearningDay{{ position }}" class="text-danger"></div>
          </div>
          {% endif %}

          {% if cf['_frm_ycbg_request'] == true %}
            <div class="form-group clearfix form-item-request">
              <div class="row">
                <div class="col-md-6">{{ frm_item_form.render("frm_item_request_" ~ id, {'class':'form-control'}) }}</div>
              </div>
              <div id="errorFrmItemRequest{{ position }}" class="text-danger"></div>
          </div>
          {% endif %}

          {% if cf['_frm_ycbg_teacher_code'] is defined and cf['_frm_ycbg_teacher_code'] == true %}
          <div class="form-group clearfix form-item-teacher-code">
              {{ frm_item_form.render("frm_item_teacher_code_" ~ id, {'class':'form-control','placeholder':word['_ma_so_gia_su_da_chon']}) }}
          </div>
          {% endif %}

          {% if cf['_cf_radio_frm_ycbg_place_pic'] == true or cf['_cf_radio_frm_ycbg_place_arrive'] == true %}
          <div class="form-group row clearfix">
              {% if cf['_cf_radio_frm_ycbg_place_pic'] == true %}
              <div class="col-md-6 col_form_item_module_place_pic">
                  {{ frm_item_form.render("frm_item_place_pic_" ~ id, {'class':'form-control', 'data-name':word['_noi_can_don'], 'placeholder':word['_noi_can_don'] ~ ' (*)'}) }}
                  <div id="errorFrmItemPlacePic{{ position }}" class="text-danger"></div>
              </div>
              {% endif %}
              {% if cf['_cf_radio_frm_ycbg_place_arrive'] == true %}
              <div class="col-md-6 col_form_item_module_place_arrive">
                  {{ frm_item_form.render("frm_item_place_arrive_" ~ id, {'class':'form-control', 'data-name':word['_noi_can_den'],'placeholder':word['_noi_can_den'] ~ ' (*)'}) }}
                  <div id="errorFrmItemPlaceArrive{{ position }}" class="text-danger"></div>
              </div>
              {% endif %}
          </div>
          {% endif %}

          {% if cf['_cf_radio_frm_ycbg_type'] == true %}
            <div class="form-group clearfix form-item-type">
              {{ frm_item_form.render("frm_item_type_" ~ id, {'class':'form-control'}) }}
          </div>
          {% endif %}

          {% if cf['_cf_radio_frm_ycbg_day'] == true or cf['_cf_radio_frm_ycbg_hour'] == true or cf['_cf_radio_frm_ycbg_minute'] == true %}
            <div class="form-group row clearfix">
                {% if cf['_cf_radio_frm_ycbg_day'] == true %}
                  <div class="col-md-6 col-form-item-module-day">
                    {{ frm_item_form.render("frm_item_day_" ~ id, {'class':'form-control form-item-datepicker', 'data-name':word['_ngay_don'],'placeholder':word['_ngay_don'] ~ ' (*)'}) }}
                    <div id="errorFrmItemDay{{ position }}" class="text-danger"></div>
                  </div>
                {% endif %}
                {% if cf['_cf_radio_frm_ycbg_hour'] == true or cf['_cf_radio_frm_ycbg_minute'] == true %}
                <div class="col-md-6 col-form-item-module-hour-minute">
                  <div class="row">
                    {% if cf['_cf_radio_frm_ycbg_hour'] == true %}
                      <div class="col-md-6 col-form-item-moudle-hour">
                        {{ frm_item_form.render("frm_item_hour_" ~ id, {'class':'form-control'}) }}
                      </div>
                    {% endif %}
                    {% if cf['_cf_radio_frm_ycbg_minute'] == true %}
                      <div class="col-md-6 col-form-item-module-minute">
                        {{ frm_item_form.render("frm_item_minute_" ~ id, {'class':'form-control'}) }}
                      </div>
                    {% endif %}
                  </div>
                </div>
                {% endif %}
            </div>
          {% endif %}
          
          {% if cf['_frm_ycbg_subject'] is defined and cf['_frm_ycbg_subject'] == true %}
          <div class="form-group clearfix form-item-subject">
              {{ frm_item_form.render("frm_item_subject_" ~ id, {'class':'form-control','placeholder':word['_tieu_de']}) }}
              {{ frm_item_form.messages('frm_item_subject_' ~ id) }}
          </div>
          {% endif %}

          {% if cf['_frm_ycbg_file'] == true %}
          <div class="form-group row clearfix form-item-file">
              <div class="col-md-2 col-text-form-item-file"><div class="text-form-item-file">{{ word._('_gui_cv') }}</div></div>
              <div class="col-md-4">{{ frm_item_form.render("frm_item_file_" ~ id) }}</div>
              <div class="col-md-6 col-text-form-item-not"><div class="text-form-item-not text-danger">{{ word._('_chap_nhan_file') }}</div></div>
              <div class="col-md-12"><div id="errorFrmItemFile{{ position }}" class="text-danger"></div></div>
          </div>
          {% endif %}
          
          {% if cf['_frm_ycbg_comment'] is defined and cf['_frm_ycbg_comment'] == true %}
          <div class="form-group clearfix form-item-comment">
              {{ frm_item_form.render("frm_item_comment_" ~ id, {'class':'form-control','rows':7,'placeholder':word['_yeu_cau_khac'] ~ ' (' ~ word['_khong_bat_buoc'] ~ ')'}) }}
              {{ frm_item_form.messages('frm_item_comment_' ~ id) }}
          </div>
          {% endif %}
          <div class="form-group clearfix">
              <button type="submit" class="btn btn-block btn-warning ladda-button text-uppercase btn-send-ycbg bar_web_bgr" data-position="{{ position }}" data-style="slide-left"><span class="ladda-label">{{ word['_gui_yeu_cau_bao_gia_ngay'] }}</span></button>
          </div>
        {{ endform() }}
    </div>
  {% if position == 'header' or position == 'footer' %}</div>{% endif %}
</div>