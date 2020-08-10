{% set languages = config_service.getLanguagesTranslate() %}
{% if languages|length > 0 %}
<div class="google_translate_fag">
	<div class="container">
		<div class="row">
			<ul class="col-md-6 pull-left header_top_info">
	            <li><i class="fa fa-phone-square txt_web_color" aria-hidden="true"></i><span>{{ word['_hotline'] }}: </span><strong><a href="tel:{{ hotline }}">{{ hotline }}</a></strong></li> |
	            <li><i class="icon-mail txt_web_color"></i><span><a href="mail:{{ setting.email }}">{{ setting.email }}</a></span></li>
	        </ul>
			<div class="col-md-6 text-right">
				<nav>
					{% for key,language in languages %}
					<a href="javscript:;" onclick="doGoogleLanguageTranslator('vi|{{ key }}'); return false;" >
						<img src="/assets/images/flag/{{ key }}.png" alt="{{ language }}">
					</a>
					{% endfor %}
				</nav>
			</div>
		</div>
	</div>
</div>
{% endif %}