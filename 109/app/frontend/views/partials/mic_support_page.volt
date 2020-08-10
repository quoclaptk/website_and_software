<div class="livesupport_page clearfix">
    <div class="box_mic_support">
        <div class="text-center">
            <strong class="text-uppercase title-mic-support">{{ word['_yeu_cau_tu_van_qua_dien_thoai'] }}</strong>
        </div>
        <div id="mic_support_message_{{ form_name }}"></div>
        <div class="frm_mic_support_">
            {{ form('role':'form', 'name':form_name, 'action':'/send-customer-message') }}
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

                <div class="form-group clearfix text-center">
                    <button class="btn btn btn-primary ladda-button bar_web_bgr btn-send-mic" data-form="{{ form_name }}" data-style="slide-left"><i class="fa fa-phone-square"></i><span class="ladda-label">{{ word['_gui_yeu_cau'] }}</span></button>
                </div>
            {{ endform() }}
        </div>
    </div>
</div>