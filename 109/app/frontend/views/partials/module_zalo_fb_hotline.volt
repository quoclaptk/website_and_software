{% if cf['_cf_radio_zalo_show_hide'] == true %}
{% if cf['_cf_select_zalo_button_type'] is not defined or (cf['_cf_select_zalo_button_type'] is defined and cf['_cf_select_zalo_button_type'] == 1)%}
<div class="call-mobile2">
	<a data-animate="fadeInDown" rel="noopener noreferrer" href="{{ hotlineZalo }}" target="_blank" class="button success" style="border-radius:99px;" data-animated="true">
    <span> ZALO </span></a>
</div>
{% else %}
<a href="{{ hotlineZalo }}" class="numberzalo" rel="nofollow">
	<img src="/assets/images/zalo.png" alt="{{ hotlineZalo }}">
</a>
{% endif %}
{% endif %}
{% if cf['_cf_radio_facebook_show_hide'] == true %}
<div class="call-mobile1">
	<a data-animate="fadeInDown" rel="noopener noreferrer" href="{{ cf['_cf_text_link_fb'] }}" target="_blank" class="button success" style="border-radius:99px;" data-animated="true"><span> FACEBOOK </span></a>
</div>
{% endif %}
{% if cf['_cf_radio_hotline_show_hide'] == true %}
<div class="call-mobile">
	<a id="callnowbutton" href="tel:{{ hotlineNumber }}">{{ hotlineNumber }}</a><i class="fa fa-phone"></i>
</div>
{% endif %}