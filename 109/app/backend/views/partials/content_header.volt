<section class="content-header">
    <h1 class="page-header text-uppercase">
        {{ module_name }}
    </h1>
    <div class="box_elm_breadcrumb clearfix">
    	{% if itemInfo is defined %}
    	<div class="dataTables_info text-danger bold pull-left" style="padding: 8px 0">Bạn có {{ itemInfo['total'] ~ ' ' ~ lcfirst(module_name) }}, {{ itemInfo['hide'] ~ ' ' ~ lcfirst(module_name) }} bị ẩn</div>
    	{% endif %}
	    <ol class="breadcrumb pull-right" style="margin:0">
	        <li><a href="{{ HTTP_HOST ~ '/' ~ ACP_NAME }}"><i class="fa fa-dashboard"></i> Trang chủ</a></li>
	        {{ breadcrumb }}
	    </ol>
    </div>
</section>