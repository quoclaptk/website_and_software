{% if id == 0 %}
	{% set css = '/assets/source/bannerhtml/css/style3.css' %}
{% else %}
	{% set css = '/bannerhtml/' ~ folder ~ '/' ~ id ~ '/style.css'  %}
{% endif %}
<head>
	<meta charset="utf-8" />
	<link type="text/css" rel="stylesheet" href="{{ css }}" />
</head>
{% if setting.banner_1 != '' %}
	{% set bgr = '/files/default/' ~ folder ~ '/' ~ setting.banner_1  %}
{% else %}
	{% set bgr = '' %}
{% endif %}
<div class="container-banner">	
	<div class="bannerbackground" {% if bgr != '' %}style="background:url('{{ bgr }}')"{% else %}style="background:#1e84cc"{% endif %}> 	
		<div class="bannerlogo" style="background: url('/files/default/{{ folder ~ '/' ~ setting.logo }}') no-repeat center;background-size: 100%;"></div>
			<div class="bannercompany">{{ setting.name }}</div>
			<div class="bannerslogan">{{ setting.slogan }}</div>
			<div class="bannerproduct">
				{% if setting.banner_2 != '' %}
				{{ image('files/default/' ~ folder ~ '/' ~ setting.banner_2 , 'class':'producth1') }}
				{% else %}
				{{ image('backend/dist/img/no-image.png', 'class':'producth1') }}
				{% endif %}
				{% if setting.banner_3 != '' %}
				{{ image('files/default/' ~ folder ~ '/' ~ setting.banner_3 , 'class':'producth2') }}
				{% else %}
				{{ image('backend/dist/img/no-image.png', 'class':'producth2') }}
				{% endif %}
				{% if setting.banner_4 != '' %}
				{{ image('files/default/' ~ folder ~ '/' ~ setting.banner_4 , 'class':'producth3') }}
				{% else %}
				{{ image('backend/dist/img/no-image.png', 'class':'producth3') }}
				{% endif %}
			</div>		
			<div class="bannercontact"> 			
			<div class="bannerhotline">{% if setting.hotline != '' %}Hotline: 0911.111.111 - {% endif %}{% if setting.email != '' %}Mail: {{ setting.email }}{% endif %}</div>
			{% if setting.address != '' %}
			<div class="banneraddress">Địa chỉ: {{ setting.address }}</div>
			{% endif %}
		</div>
		
		<!-- //start affer -->
		<div id="bannerafterafter-wrap">
			<div class="bannerafterx1 bannerafterx-01">
				<div class="banneraftecloud1 banneraftecloud-01"></div>
			</div>

			<div class="banneraftex2 bannerafterx-02">
				<div class="banneraftecloud2 banneraftecloud-02"></div>
			</div>

			<div class="banneraftex3 bannerafterx-03">
				<div class="banneraftecloud3 banneraftecloud-03"></div>
			</div>

			<div class="banneraftex4 bannerafterx-05">
				<div class="banneraftecloud4 banneraftecloud-04"></div>
			</div>

			<div class="banneraftex5 banneraftex-05">
				<div class="banneraftecloud5 banneraftecloud-05"></div>
			</div>
		</div>
		<!-- //end affer -->
			
	</div>	
</div>