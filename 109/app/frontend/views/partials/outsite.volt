{% if cf['_cf_radio_menu_mobile'] == true %}
{{ partial('partials/mobile_menu', ['layout':layout]) }}
{% endif %}
{% if cf['_turn_off_phone_alo'] == true %}
{% if cf['_cf_select_phone_alo_type'] == 1 %}
{{ partial('partials/phone_ring') }}
{% endif %}
{% if cf['_cf_select_phone_alo_type'] == 2 %}
{{ partial('partials/phone_alo') }}
{% endif %}
{% endif %}
{% if cf['_cf_customer_mesage'] == true %}
{{ partial('partials/customer_message') }}
{% endif %}
{% if showPopup is defined %}
{% if cf['_cf_frm_ycbg'] == true and showPopup == 'Y' %}
{{ partial('partials/form_item') }}
{% endif %}
{% elseif cf['_cf_frm_ycbg'] == true %}
{{ partial('partials/form_item') }}
{% endif %}
{% if cf['_cf_mic_support'] == true %}
{{ partial('partials/mic_support') }}
{% endif %}
{% if cf['_cf_radio_banner_ads_left'] == true or cf['_cf_radio_banner_ads_right'] == true %}
{{ partial('partials/banner_ads_left_right') }}
{% endif %}
{% if cf['_cf_radio_bar_sms_mobile'] == true %}
	{% if cf['_cf_select_sms_mobile_type'] == 1 %}
	{{ partial('partials/phone_message_mobile') }}
	{% endif %}
	{% if cf['_cf_select_sms_mobile_type'] == 2 %}
	{{ partial('partials/phone_message_mobile_2') }}
	{% endif %}
{% endif %}
{% if cf['_cf_radio_module_zalo_fb_hotline'] == true %}
{{ partial('partials/module_zalo_fb_hotline') }}
{% endif %}
<p id="back-top">
    <FORM832-CUSTOM><span></span></a>
</p>

