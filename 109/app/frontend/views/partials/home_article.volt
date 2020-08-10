{% if cf['_cf_radio_show_header_home_article'] == true %}
{{ partial('partials/mic_support_page', {'form_name':'frm_mic_support_header'}) }}
{% endif %}
{% if setting.article_home != '' %}
<div class="md_home_article">
{% if position == "header" or position == "footer" %}<div class="container">{% endif %}
{% if setting.enable_form_reg_article_home == 1 or setting.enable_video_article_home == 1 or setting.enable_image_article_home == 1 or setting.enable_search_advance_article_home == 1 %}
<div class="row">
	<div class="col-md-6 col-sm-12 col-home-article">
		{{ setting.article_home }}
	</div>
	<div class="col-md-6 col-sm-12 col-video-form">
	{% if setting.enable_video_article_home == 1 and setting.youtube_code != '' %}{{ setting.youtube_code }}{% endif %}
	{% if setting.enable_form_reg_article_home == 1 %}{{ partial("partials/form_item_module", ["layout":layout, "position":"center"]) }}{% endif %}
	{% if setting.enable_image_article_home == 1 and setting.image_article_home != '' and file_exists("files/default/" ~ subdomain.folder ~ "/" ~ setting.image_article_home) %}
	<div class="text-center box-image-article-home">
		{{ image("files/default/" ~ subdomain.folder ~ "/" ~ setting.image_article_home, 'class':'image_article_home') }}
	</div>
	{% endif %}
	{% if setting.enable_search_advance_article_home == 1 %}
	<div class="box-search-advanced-home">
		{{ partial("partials/search_advanced", ["layout":layout, "position":"left"]) }}
	</div>
	{% endif %}
	</div>
</div>
{% else %}
<div class="post_static">
	{{ news_service.replaceImageErrorInArticleHome(setting.article_home) }}
</div>
{% endif %}
{% if position == "header" and position == "footer" %}</div>{% endif %}
</div>
{% endif %}
{% if cf['_cf_radio_show_footer_home_article'] == true %}
{{ partial('partials/mic_support_page', {'form_name':'frm_mic_support_footer'}) }}
{% endif %}