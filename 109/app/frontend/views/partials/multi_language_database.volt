{% if tmpSubdomainLanguages|length > 0 %}
<div class="google_translate_fag">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<ul class="pull-left header_top_info">
		            <li><i class="fa fa-phone-square txt_web_color" aria-hidden="true"></i><span>{{ word['_hotline'] }}: </span><strong><a href="tel:{{ hotline }}">{{ hotline }}</a></strong></li> |
		            <li><i class="icon-mail txt_web_color"></i><span><a href="mail:{{ setting.email }}">{{ setting.email }}</a></span></li>
		        </ul>
	        </div>
			<div class="col-md-6 hidden-xs text-right col-language">
				<nav class="language-flag">
					{% for key,tmp in tmpSubdomainLanguages %}
					<a href="{{ languageUrls[tmp.language.code] }}">
						<img src="/assets/images/flag/{{ tmp.language.code }}.png" alt="{{ tmp.language.name }}">
					</a>
					{% endfor %}
				</nav>
			</div>
			<div class="col-md-6 hidden-sm hidden-md hidden-lg">
				<select class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
					<option value="">{{ word._('_chon_ngon_ngu') }}</option>
					{% for key,tmp in tmpSubdomainLanguages %}
					<option value="{{ languageUrls[tmp.language.code] }}"{% if languageCode == tmp.language.code %} selected{% endif %}>{{ tmp.language.name }}</option>
					{% endfor %}
				</select>
			</div>
		</div>
	</div>
</div>
{% endif %}