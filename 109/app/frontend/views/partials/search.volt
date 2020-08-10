{% if demo_router is defined %}
    {% set linkSearch = tag.site_url(demo_router ~ '/p-search') %}
{% else %}
    {% if languageCode == 'vi' %}
        {% set linkSearch = '/tim-kiem' %}
    {% else %}
        {% set linkSearch =  '/' ~ languageCode ~ '/search' %}
    {% endif %}
{% endif %}
{% set category = category_service.getCategoryParent()  %}
<div class="search-module">
	{% if position == 'left' or position == 'right' %}
	<form method="get" action="{{ linkSearch }}">
		{% if category|length > 0 %}
		<div class="form-group">
			<select class="form-control" name="catID">
				<option value="0">{{ word['_danh_muc'] }}</option>
                {% for k in category %}
                <option value="{{ k.id }}"{% if request.get('catID') == k.id %} selected {% endif %}>{{ k.name }}</option>
                {% endfor %}
			</select>
		</div>
		{% endif %}
		<div class="form-group">
			<div class="input-group add-on">
		      <input type="text" class="form-control" value="{{ request.get('q') }}" placeholder="{{ word['_tim_kiem'] }}" name="q" required>
		      <div class="input-group-btn">
		        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
		      </div>
		    </div>
		</div>
	</form>
	{% else %}
	<div class="container">
		<div class="row">
			<div class="col-md-2 hidden-xs hidden-sm"></div>
			<div class="col-md-8 col-sm-12">
				<div class="input-group group_search">
			        <form method="get" class="clearfix" action="{{ linkSearch }}">
			        {% if category|length > 0 %}
			            <select class="form-control pull-left" name="catID">
			                <option value="0">{{ word['_danh_muc'] }}</option>
			                {% for k in category %}
			                <option value="{{ k.id }}"{% if request.get('catID') == k.id %} selected {% endif %}>{{ k.name }}</option>
			                {% endfor %}
			            </select>
			        {% endif %}
			            <div class="pull-left">
		                     <input type="text" class="form-control" value="{{ request.get('q') }}" placeholder="{{ word['_tim_kiem'] }}" name="q" required>
		                </div>
		                <button type="submit" class="btn_search">
		                    <i class="icon-search"></i>
		                </button>
			        </form>
			    </div>
			</div>
			<div class="col-md-2 hidden-xs hidden-sm"></div>
		</div>
		
    </div>
	{% endif %}
</div>