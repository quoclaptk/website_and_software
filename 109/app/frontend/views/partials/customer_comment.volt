{% set customerComments = customer_comment_service.getListCustomerComment() %}
<div class="md_customer_mesage clearfix">
	{% if position == 'header' or position == 'footer' %}<div class="container"><div class="row">{% endif %}
		<div class="title_bar_right bar_web_bgr text-uppercase">{{ word['_y_kien_khach_hang'] }}</div>
		{{ partial('partials/customer_comment_content', ['customerComments':customerComments]) }}
    {% if position == 'header' or position == 'footer' %}</div></div>{% endif %}
</div>