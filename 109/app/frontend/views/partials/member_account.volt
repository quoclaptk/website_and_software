{% if languageCode == 'vi' %}
	{% set urlAccount = 'tai-khoan' %}
	{% set urlLogout = 'dang-xuat' %}
	{% set urlRegister = 'dang-ky' %}
	{% set urllogin = 'dang-nhap' %}
{% else %}
	{% set urlAccount = languageCode ~ '/account' %}
	{% set urlLogout = languageCode ~ '/logout' %}
	{% set urlRegister = languageCode ~ '/signup' %}
	{% set urllogin = languageCode ~ '/login' %}
{% endif  %}
<div class="module_member_account">
	{% if position == 'header' or position == 'footer' %}
		<div class="container">
	{% endif %}
	<div class="row">
		<div class="col-md-4 hidden-xs hidden-sm col-mb-company"><div class="company_name txt_web_color text-uppercase">{{ setting.name }}</div></div>
		<div class="col-md-4 hidden-xs hidden-sm col-mb-hotline">
			<ul class="header_top_info">
                <li><i class="fa fa-phone-square txt_web_color" aria-hidden="true"></i><span>{{ word['_hotline'] }}: </span><strong><a href="tel:{{ hotline }}">{{ hotline }}</a></strong></li> |
                <li><i class="icon-mail txt_web_color"></i><span><a href="mail:{{ setting.email }}">{{ setting.email }}</a></span></li>
            </ul>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12  col-mb-acount">
			<nav class="account_bar_menu">
				{% if session.get('auth-guest') %}
				<a href="{{ tag.site_url(urlAccount) }}" class="get-register-box"><i class="fa fa-key"></i> {{ word['_tai_khoan'] }}</a>
		        <a href="{{ tag.site_url(urlLogout) }}" class="get-login-box"><i class="fa fa-lock"></i> {{ word['_dang_xuat'] }}</a>
				{% else %}
		        <a href="{{ tag.site_url(urlRegister) }}" class="get-register-box"><i class="fa fa-key"></i> {{ word['_dang_ky'] }}</a>
		        <a href="{{ tag.site_url(urllogin) }}" class="get-login-box"><i class="fa fa-lock"></i> {{ word['_dang_nhap'] }}</a>
		        {% endif %}
		    </nav>
		</div>
	</div>
	{% if position == 'header' or position == 'footer' %}
		</div>
	{% endif %}
</div>