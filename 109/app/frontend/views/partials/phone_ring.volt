{% if cf['_txt_phone_alo'] != '' %}
<div class="fix_tel{% if cf['_positon_phone_ring'] == true %} fix_tel_left{% endif %}">
  <div class="ring-alo-phone ring-alo-green ring-alo-show" id="ring-alo-phoneIcon">
    <div class="ring-alo-ph-circle"></div>
    <div class="ring-alo-ph-circle-fill"></div>
    <div class="ring-alo-ph-img-circle">
      <a href="tel:{{ phoneAlo }}">
        <i class="fa fa-phone"></i>
      </a>
    </div>
  </div>
  <div class="tel">
      <p class="fone"><a href="tel:{{ phoneAlo }}">{{ phoneAlo }}</a></p>
  </div>
</div>
{% endif %}
