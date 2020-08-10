<div class="box_module_administrator">
	<div class="container">
		<div class="module_administrator_elm text-center hidden-xs">
			<nav class="nav_module_administrator">
				{{ link_to('hi', word._('_dang_nhap_quan_tri'), 'rel':'nofollow') }} |
				<a href="https://docs.google.com/document/d/1aV7OqZGu_d8KB6ASBXWXf1aQucuwM8t_IyCOFcMA3dA/edit" target="_blank" rel="nofollow">{{ word._('_huong_dan_quan_tri') }}</a>
				{% if subdomain.expired_date != '0000-00-00 00:00:00' %} |
				<a href="javascript:;" rel="nofollow">{{ word._('_ngay_het_han') }}: {{ date('d/m/Y', strtotime(subdomain.expired_date)) }}</a> |
				<a href="javascript:;" rel="nofollow">{{ word._('_con_lai') }}: {{ dayRemain }} {{ lcfirst(word._('_ngay')) }}</a>{% if subdomain.copyright == 'Y' %} |{% endif %}
				{% endif %}
				{% if subdomain.copyright == 'Y' %}
				<a href="//109.vn" target="blank" rel="nofollow"><b style="color:#f00">{{ word._('_thiet_ke_web') }}: 109.vn</b></a>{% if subdomain.copyright_name is not null and subdomain.copyright_link is not null %} |{% endif %}
				{% endif %}
				{% if subdomain.copyright_name is not null and subdomain.copyright_link is not null %}
				<a href="//{{ subdomain.copyright_link }}" target="blank" rel="nofollow"><b style="color:#f00">{{ subdomain.copyright_name }}: {{  subdomain.copyright_link }}</b></a>
				{% endif %}
			</nav>
		</div>
		<div class="hidden-sm hidden-md hidden-lg module_administrator_elm_mb" style="margin-top: 10px">
			<ul class="list-group"> 
				<li class="list-group-item">
					<span class="badge"><a href="/hi" rel="nofollow" style="color: #fff"><i class="fa fa-angle-right" aria-hidden="true"></i></a></span><a href="/hi" rel="nofollow" style="display: block">{{ word._('_huong_dan_quan_tri') }}</a>
				</li>
				{% if subdomain.expired_date != '0000-00-00 00:00:00' %}
				<li class="list-group-item">
					<span class="badge"><a href="javascript:;" rel="nofollow" style="color: #fff"><i class="fa fa-angle-right" aria-hidden="true"></i></a></span><a href="javascript:;" rel="nofollow" style="display: block">{{ word._('_ngay_het_han') }}: <b style="color:#f00">{{ date('d/m/Y', strtotime(subdomain.expired_date)) }}</b></a>
				</li>
				<li class="list-group-item">
					<span class="badge"><a href="javascript:;" rel="nofollow" style="color: #fff"><i class="fa fa-angle-right" aria-hidden="true"></i></a></span><a href="javascript:;" rel="nofollow" style="display: block">{{ word._('_con_lai') }}: <b style="color:#f00">{{ dayRemain }}</b> {{ lcfirst(word._('_ngay')) }}</a>
				</li>
				{% endif %}
				{% if subdomain.copyright == 'Y' %}
				<li class="list-group-item">
					<span class="badge"><a href="//109.vn" rel="nofollow" target="blank" style="color: #fff"><i class="fa fa-angle-right" aria-hidden="true"></i></a></span><a href="//109.vn" rel="nofollow" target="blank" style="display: block">{{ word._('_thiet_ke_web') }}: <b style="color:#f00">109.vn</b></a>
				</li>
				{% endif %}
				{% if subdomain.copyright_name is not null and subdomain.copyright_link is not null %}
				<li class="list-group-item">
					<span class="badge"><a href="//{{ subdomain.copyright_link }}" rel="nofollow" target="blank" style="color: #fff"><i class="fa fa-angle-right" aria-hidden="true"></i></a></span><a href="//{{ subdomain.copyright_link }}" rel="nofollow" target="blank" style="display: block">{{ subdomain.copyright_name }}: <b style="color:#f00">{{  subdomain.copyright_link }}</b></a>
				</li>
				{% endif %}
			</ul>
		</div>
	</div>
</div>