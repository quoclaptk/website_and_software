<div class="box_module_administrator">
	<div class="module_administrator_elm text-center">
		<nav class="nav_module_administrator">
			{{ link_to('hi', word._('_dang_nhap_quan_tri'), 'rel':'nofollow') }} |
			<a href="https://docs.google.com/document/d/1aV7OqZGu_d8KB6ASBXWXf1aQucuwM8t_IyCOFcMA3dA/edit" target="_blank" rel="nofollow">{{ word._('_huong_dan_quan_tri') }}</a>
			{% if subdomainCurrent.expired_date != '0000-00-00 00:00:00' %} |
			<a href="javascript:;" rel="nofollow">{{ word._('_ngay_het_han') }}: {{ date('d/m/Y', strtotime(subdomainCurrent.expired_date)) }}</a> |
			<a href="javascript:;" rel="nofollow">{{ word._('_con_lai') }}: {{ dayRemain }} {{ lcfirst(word._('_ngay')) }}</a>
			{% endif %}
		</nav>
	</div>
</div>
<style type="text/css">
	.box_module_administrator {padding: 5px 0;background: #f3f3f3;position: fixed;width: 100%;bottom: 0;left: 0}
	.box_module_administrator a{color: #656c79;padding: 0 34px 0 33px}
</style>