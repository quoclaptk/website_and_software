{% set banners = banner_service.getBannerAdsType('vertical_slider')  %}
<div class="banner-vertical">
    <div class="Advertise_Brand_Button">
        <i class="fa fa-caret-up ctrlpg_6_Image1" aria-hidden="true"></i>
    </div>
    <div class="container-marquee">
	    <div class="marquee-1 marquee-content">
	    	<div class="ctrlpg_6_Advertise1_pnl_Advertise">
	            {% for key,value in banners %}
			        {% set photo = "/files/ads/" ~ subdomain.folder ~ "/" ~ value.photo %}
			    <div class="text-center banner_static_elm">
			        <a href="{{ value.link }}" target="_blank">
			            <img src="{{ photo }}" alt="{{ value.name }}">
			        </a>
			    </div>
			    {% endfor %}
	        </div>
	    </div>
    </div>
    <div class="Advertise_Brand_Button">
        <i class="fa fa-caret-down ctrlpg_6_Image2" aria-hidden="true"></i>
    </div>
</div>