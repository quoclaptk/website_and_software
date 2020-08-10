{% set id = 1 %}
{% if setting.bgr_ycbg != '' %}
  {% set bgr = 'files/default/' ~ subdomain.folder ~ '/' ~ setting.bgr_ycbg %}
{% else %}
  {% set bgr = 'assets/images/ycbg.jpg' %}
{% endif %}
<div id="modalItemYcbg" class="modal-customize modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal"></button>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-6 hidden-xs hidden-sm img_popup_form_item">
                {{ image(bgr) }}
              </div>
              <div class="col-md-6 box_popup_form_item">
                  <div class="box_popup_form_item_input">
                    <h4 class="modal-title text-center" id="lblModelTitle">{{ word['_gui_yeu_cau_bao_gia'] }}</h4>
                    {{ form('role':'form', 'name':'frm_item_ycbg', 'id':'frm_item_ycbg', 'action':'/send-form-info', 'data-name':word['_gui_yeu_cau_bao_gia']) }}
                      {{frm_item_form.render('frm_item_id', {'value':id})}}
                      {% if cf['_frm_ycbg_name'] is defined and cf['_frm_ycbg_name'] == true %}
                      <div class="form-group clearfix">
                          {{ frm_item_form.render("frm_item_name_" ~ id,{'class':'form-control','placeholder':word['_ho_ten'] }) }}
                      </div>
                      {% endif %}
                      
                      {% if cf['_frm_ycbg_phone'] is defined and cf['_frm_ycbg_phone'] == true %}
                      <div class="form-group clearfix">
                          {{ frm_item_form.render("frm_item_phone_" ~ id, {'class':'form-control','placeholder':word['_dien_thoai'] ~ ' (*)'}) }}
                      </div>
                      {% endif %}
                      
                      {% if cf['_frm_ycbg_email'] is defined and cf['_frm_ycbg_email'] == true %}
                      <div class="form-group clearfix">
                          {{ frm_item_form.render("frm_item_email_" ~ id, {'class':'form-control','placeholder':word['_email']}) }}
                      </div>
                      {% endif %}

                      {% if cf['_frm_ycbg_class'] == true %}
                        <div class="form-group clearfix form-item-popup-class">
                            <div class="row">
                              <div class="col-md-6">{{ frm_item_form.render("frm_item_class_" ~ id, {'class':'form-control'}) }}</div>
                            </div>
                        </div>
                      {% endif %}

                      {% if cf['_frm_ycbg_subjects'] is defined and cf['_frm_ycbg_subjects'] == true %}
                      <div class="form-group clearfix form-item-popup-subjects">
                          {{ frm_item_form.render("frm_item_subjects_" ~ id, {'class':'form-control','placeholder':word['_mon_hoc'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
                      </div>
                      {% endif %}

                      {% if cf['_frm_ycbg_student_number'] is defined and cf['_frm_ycbg_student_number'] == true %}
                      <div class="form-group clearfix form-item-popup-studen-number">
                          {{ frm_item_form.render("frm_item_student_number_" ~ id, {'class':'form-control','placeholder':word['_so_luong_hoc_sinh'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
                      </div>
                      {% endif %}

                      {% if cf['_frm_ycbg_learning_level'] is defined and cf['_frm_ycbg_learning_level'] == true %}
                      <div class="form-group clearfix form-item-popup-learning-level">
                          {{ frm_item_form.render("frm_item_learning_level_" ~ id, {'class':'form-control','placeholder':word['_hoc_luc_hien_tai'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
                      </div>
                      {% endif %}

                      {% if cf['_frm_ycbg_learning_time'] == true %}
                        <div class="form-group clearfix form-item-popup-learning-time">
                          <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">{{ frm_item_form.render("frm_item_learning_time_" ~ id, {'class':'form-control'}) }}</div><div class="col-md-6 col-sm-6 col-xs-6 col-on-week">{{ word['_tren_tuan'] }}</div>
                        </div>
                      </div>
                      {% endif %}

                      {% if cf['_frm_ycbg_learning_day'] is defined and cf['_frm_ycbg_learning_day'] == true %}
                      <div class="form-group clearfix form-item-popup-learning-day">
                          {{ frm_item_form.render("frm_item_learning_day_" ~ id, {'class':'form-control','placeholder':word['_thoi_gian_hoc'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
                      </div>
                      {% endif %}

                      {% if cf['_frm_ycbg_request'] == true %}
                        <div class="form-group clearfix form-item-popup-request">
                          <div class="row">
                            <div class="col-md-6">{{ frm_item_form.render("frm_item_request_" ~ id, {'class':'form-control'}) }}</div>
                          </div>
                      </div>
                      {% endif %}

                      {% if cf['_frm_ycbg_teacher_code'] is defined and cf['_frm_ycbg_teacher_code'] == true %}
                      <div class="form-group clearfix form-item-popup-teacher-code">
                          {{ frm_item_form.render("frm_item_teacher_code_" ~ id, {'class':'form-control','placeholder':word['_ma_so_gia_su_da_chon']}) }}
                      </div>
                      {% endif %}

                      {% if cf['_cf_radio_frm_ycbg_place_pic'] == true or cf['_cf_radio_frm_ycbg_place_arrive'] == true %}
                      <div class="form-group row clearfix">
                          {% if cf['_cf_radio_frm_ycbg_place_pic'] == true %}
                          <div class="col-md-6 col_form_item_place_pic">
                              {{ frm_item_form.render("frm_item_place_pic_" ~ id, {'class':'form-control', 'data-name':word['_noi_can_don'], 'placeholder':word['_noi_can_don'] ~ ' (*)'}) }}
                          </div>
                          {% endif %}
                          {% if cf['_cf_radio_frm_ycbg_place_arrive'] == true %}
                          <div class="col-md-6 col_form_item_place_arrive">
                              {{ frm_item_form.render("frm_item_place_arrive_" ~ id, {'class':'form-control', 'data-name':word['_noi_can_den'],'placeholder':word['_noi_can_den'] ~ ' (*)'}) }}
                          </div>
                          {% endif %}
                      </div>
                      {% endif %}

                      {% if cf['_cf_radio_frm_ycbg_type'] == true %}
                        <div class="form-group clearfix">
                          {{ frm_item_form.render("frm_item_type_" ~ id, {'class':'form-control'}) }}
                      </div>
                      {% endif %}

                      {% if cf['_cf_radio_frm_ycbg_day'] == true or cf['_cf_radio_frm_ycbg_hour'] == true or cf['_cf_radio_frm_ycbg_minute'] == true %}
                        <div class="form-group row clearfix">
                            {% if cf['_cf_radio_frm_ycbg_day'] == true %}
                              <div class="col-md-6 col-form-item-day">
                                {{ frm_item_form.render("frm_item_day_" ~ id, {'class':'form-control form-item-datepicker', 'data-name':word['_ngay_don'],'placeholder':word['_ngay_don'] ~ ' (*)'}) }}
                              </div>
                            {% endif %}
                            {% if cf['_cf_radio_frm_ycbg_hour'] == true or cf['_cf_radio_frm_ycbg_minute'] == true %}
                            <div class="col-md-6 col-form-item-hour-minute">
                              <div class="row">
                                {% if cf['_cf_radio_frm_ycbg_hour'] == true %}
                                  <div class="col-md-6 col-form-item-hour">
                                    {{ frm_item_form.render("frm_item_hour_" ~ id, {'class':'form-control'}) }}
                                  </div>
                                {% endif %}
                                {% if cf['_cf_radio_frm_ycbg_minute'] == true %}
                                  <div class="col-md-6 col-form-item-minute">
                                    {{ frm_item_form.render("frm_item_minute_" ~ id, {'class':'form-control'}) }}
                                  </div>
                                {% endif %}
                              </div>
                            </div>
                            {% endif %}
                        </div>
                      {% endif %}
                      
                      {% if cf['_frm_ycbg_subject'] is defined and cf['_frm_ycbg_subject'] == true %}
                      <div class="form-group clearfix">
                          {{ frm_item_form.render("frm_item_subject_" ~ id, {'class':'form-control','placeholder':word['_tieu_de']}) }}
                      </div>
                      {% endif %}
                      
                      {% if cf['_frm_ycbg_comment'] is defined and cf['_frm_ycbg_comment'] == true %}
                      <div class="form-group clearfix">
                          {{ frm_item_form.render("frm_item_comment_" ~ id, {'class':'form-control','rows':7,'placeholder':word['_yeu_cau_khac']}) }}
                      </div>
                      {% endif %}
                      <div class="form-group clearfix">
                          <button class="btn btn-block btn-warning ladda-button text-uppercase" id="btn-send-ycbg" data-style="slide-left"><span class="ladda-label">{{ word['_gui_yeu_cau_bao_gia_ngay'] }}</span></button>
                      </div>
                    {{ endform() }}
                  </div>
              </div>
          </div>
      </div>
    </div>

  </div>
</div>