{% set id = 2 %}
<div class="box_form_fast_register">
    <h4 class="title_form_fast_register">{{ word['_dang_ky_nhanh'] }}</h4>
    {{ form('role':'form', 'name':'frm_fast_register', 'action':'/send-form-info', 'data-name':word['_dang_ky_nhanh']) }}
      {{frm_item_form.render('frm_item_id', {'value':id})}}
      {% if detail is defined %}
      <input type="hidden" name="product_id" value="{{ detail.id }}">
      {% endif %}
      {% if cf['_cf_radio_fast_register_name'] == true %}
      <div class="form-group clearfix form-fast-register-name">
          {{ frm_item_form.render("frm_item_name_" ~ id, {'class':'form-control','placeholder':word['_ho_ten'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
          <div id="errorFrmFastRegName" class="text-danger"></div>
      </div>
      {% endif %}

      {% if cf['_cf_radio_fast_register_address'] == true %}
      <div class="form-group clearfix form-fast-register-address">
          {{ frm_item_form.render("frm_item_address_" ~ id, {'class':'form-control','placeholder':word['_dia_chi']}) }}
      </div>
      {% endif %}

      {% if cf['_cf_radio_fast_register_phone'] == true %}
      <div class="form-group clearfix form-fast-register-phone">
          {{ frm_item_form.render("frm_item_phone_" ~ id, {'class':'form-control','placeholder':word['_dien_thoai'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
          <div id="errorFrmFastRegPhone" class="text-danger"></div>
      </div>
      {% endif %}

      {% if cf['_cf_radio_fast_register_email'] == true %}
      <div class="form-group clearfix form-fast-register-email">
          {{ frm_item_form.render("frm_item_email_" ~ id, {'class':'form-control','placeholder':'Email'}) }}
          <div id="errorFrmFastRegEmamil" class="text-danger"></div>
      </div>
      {% endif %}

      {% if cf['_cf_radio_fast_register_method'] == true %}
      <div class="form-group clearfix form-fast-register-studen-number">
          {{ frm_item_form.render("frm_item_method_" ~ id, {'class':'form-control'}) }}
          <div id="errorFrmFastRegMethod" class="text-danger"></div>
      </div>
      {% endif %}

      {% if cf['_cf_radio_fast_register_day'] == true or cf['_cf_radio_fast_register_hour'] == true %}
        <div class="form-group row clearfix">
            {% if cf['_cf_radio_fast_register_day'] == true %}
              <div class="col-md-6 col-form-fast-register-module-day">
                {{ frm_item_form.render("frm_item_day_" ~ id, {'class':'form-control form-fast-register-datepicker', 'data-name':word['_ngay_nhan_lop'],'placeholder':word['_ngay_nhan_lop']}) }}
                <div id="errorFrmFastRegDay" class="text-danger"></div>
              </div>
            {% endif %}
            {% if cf['_cf_radio_fast_register_hour'] == true %}
            <div class="col-md-6 col-form-fast-register-module-hour-minute">
            	 {{ frm_item_form.render("frm_item_hour_one_" ~ id, {'class':'form-control'}) }}
            	 <div id="errorFrmFastRegHour" class="text-danger"></div>
            </div>
            {% endif %}
        </div>
      {% endif %}

      {% if cf['_cf_radio_fast_register_start_time'] == true %}
      <div class="form-group clearfix form-fast-register-start-time">
          {{ frm_item_form.render("frm_item_start_time_" ~ id, {'class':'form-control form-item-start-time','placeholder':word['_ngay_gio_khoi_hanh']}) }}
      </div>
      {% endif %}

      {% if cf['_cf_radio_fast_register_end_time'] == true %}
      <div class="form-group clearfix form-fast-register-end-time">
          {{ frm_item_form.render("frm_item_end_time_" ~ id, {'class':'form-control form-item-end-time','placeholder':word['_ngay_gio_ve']}) }}
      </div>
      {% endif %}

      {% if cf['_cf_radio_fast_register_number_ticket'] == true %}
      <div class="form-group clearfix form-fast-register-name">
          {{ frm_item_form.render("frm_item_number_ticket_" ~ id, {'class':'form-control','placeholder':word['_so_luong_ve'] ~ ' (' ~ word['_bat_buoc'] ~ ')'}) }}
          <div id="errorFrmFastRegNumberTicket" class="text-danger"></div>
      </div>
      {% endif %}
      
      {% if cf['_cf_radio_fast_register_comment'] == true %}
      <div class="form-group clearfix form-fast-register-comment">
          {{ frm_item_form.render("frm_item_comment_" ~ id, {'class':'form-control','rows':3,'placeholder':word['_yeu_cau_them']}) }}
          {{ frm_item_form.messages('frm_item_comment_' ~ id) }}
      </div>
      {% endif %}
      <div class="form-group clearfix">
          <button class="btn btn-block btn-warning ladda-button text-uppercase btn-send-form-fast-register bar_web_bgr" data-style="slide-left"><span class="ladda-label">{{ word['_nhan_lop'] }}</span></button>
      </div>
    {{ endform() }}
</div>