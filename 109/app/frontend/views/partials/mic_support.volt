{% if cf['_positon_box_mic_support'] == true %}
    {% set bar_class = 'livesupport-left' %}
    {% set form_class = 'contentheader-left' %}
{% else %}
    {% set bar_class = '' %}
    {% set form_class = '' %}
{% endif %}
<div class="livesupport {{ bar_class }} hidden-sm hidden-xs clearfix">
    <div class="contentheader {{ form_class }} bar_web_bgr">
        <div class="btnkn2tv">
            <div class="btnkn2tv_title text-uppercase">{{ word['_yeu_cau_mic_ho_tro'] }}</div>
            <div class="btnkn2tv_hotline">{{ word['_hotline'] }}: {{ cf['_txt_phone_alo'] }}</div>
        </div>
    </div>
    {#<div class="box_mic_support">
        <div class="text-center">
            <strong class="text-uppercase title-mic-support">{{ word['_yeu_cau_tu_van_qua_dien_thoai'] }}</strong>
        </div>
        <div id="mic_support_message"></div>
        <div class="frm_mic_support">
            {{ form('role':'form', 'name':'frm_mic_support', 'action':'/send-customer-message') }}
                {% if cf['_customer_message_name'] is defined and cf['_customer_message_name'] == true %}
                <div class="form-group clearfix">
                    {{ c_s_form.render("c_mgs_name",{'class':'form-control','placeholder':word['_xin_moi_nhap_ho_ten']}) }}
                    <div id="errorMicName" class="text-danger"></div>
                </div>
                {% endif %}
                
                {% if cf['_customer_message_phone'] is defined and cf['_customer_message_phone'] == true %}
                <div class="form-group clearfix">
                    {{ c_s_form.render("c_mgs_phone",{'class':'form-control','placeholder':word['_vui_long_nhap_so_dien_thoai_cua_ban']}) }}
                    <div id="errorMicPhone" class="text-danger"></div>
                </div>
                {% endif %}

                <div class="form-group clearfix text-center">
                    <button class="btn btn btn-primary ladda-button bar_web_bgr" id="btn-send-mic" data-style="slide-left"><i class="fa fa-phone-square"></i><span class="ladda-label">{{ word['_gui_yeu_cau'] }}</span></button>
                </div>
            {{ endform() }}
        </div>
    </div>#}
</div>

<!-- Modal -->
<div class="modal fade" id="supportRequestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-uppercase" id="supportRequestModalLabel">{{ word['_yeu_cau_tu_van_qua_dien_thoai'] }}</h4>
      </div>
      <div class="modal-body">
        <div id="mic_support_message_frm_mic_support"></div>
        <div class="frm_mic_support">
            {{ form('role':'form', 'name':'frm_mic_support', 'action':'/send-customer-message') }}
                {% if cf['_customer_message_name'] is defined and cf['_customer_message_name'] == true %}
                <div class="form-group clearfix">
                    {{ c_s_form.render("c_mgs_name",{'class':'form-control','placeholder':word['_xin_moi_nhap_ho_ten']}) }}
                    <div class="errorMicName text-danger"></div>
                </div>
                {% endif %}
                
                {% if cf['_customer_message_phone'] is defined and cf['_customer_message_phone'] == true %}
                <div class="form-group clearfix">
                    {{ c_s_form.render("c_mgs_phone",{'class':'form-control','placeholder':word['_vui_long_nhap_so_dien_thoai_cua_ban']}) }}
                    <div class="errorMicPhone text-danger"></div>
                </div>
                {% endif %}
            {{ endform() }}
        </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn btn-primary ladda-button bar_web_bgr btn-send-mic" data-form="frm_mic_support" data-style="slide-left"><i class="fa fa-phone-square"></i><span class="ladda-label">{{ word['_gui_yeu_cau'] }}</span></button>
      </div>
    </div>
  </div>
</div>