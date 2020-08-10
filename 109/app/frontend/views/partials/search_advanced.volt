{% set categorySearchs = category_service.getCategoryParent()  %}
{% set productElements = product_service.getProductElementSearch({'search':true}) %}
{% set priceRanges = product_service.getPriceRange() %}
{% if languageCode == 'vi' %}
	{% set urlSearch = 'tim-kiem' %}
{% else %}
	{% set urlSearch = languageCode ~ '/search' %}
{% endif %}
<div class="box_search_advanced clearfix">
	<div class="title_bar_left bar_web_bgr text-uppercase">{{ word['_tim_kiem'] }}</div>
	{% if position == 'left' or position == 'right' %}
	<form method="get" action="{{ tag.site_url(urlSearch) }}">
		{% if categorySearchs|length > 0 %}
		<div class="form-group">
			<select name="catID" class="form-control">
				<option value="0">{{ word['_danh_muc'] }}</option>
				{% for category in categorySearchs %}
				<option value="{{ category.id }}"{% if request.getQuery('catID') == category.id %} selected{% endif %}>{{ category.name }}</option>
				{% endfor %}
			</select>
		</div>
		{% endif %}
		{% if productElements != '' %}
			{% for key,productElement in productElements %}
			{% set elementName = 'element' ~ key %}
			<div class="form-group">
				<select name="{{ elementName }}" class="form-control">
					<option value="0">{{ productElement['name'] }}</option>
					{% if productElement['details'] is defined %}
					{% for detail in productElement['details'] %}
					<option value="{{ detail['id'] }}"{% if request.getQuery(elementName) == detail['id'] %} selected{% endif %}>{{ detail['name'] }}</option>
					{% endfor %}
					{% endif %}
				</select>
			</div>
			{% endfor %}
		{% endif %}
		{% if priceRanges|length > 0 %}
		<div class="form-group">
			<select name="price" class="form-control">
				<option value="0">{{ word['_khoang_gia'] }}</option>
				{% for priceRange in priceRanges %}
				{% if priceRange.to_price != 0 %}
				{% set value = priceRange.from_price ~ '-' ~ priceRange.to_price %}
				{% else %}
				{% set value = priceRange.from_price %}
				{% endif %}
				<option value="{{ value }}"{% if request.getQuery('price') == value %} selected{% endif %}>{{ priceRange.name }}</option>
				{% endfor %}
			</select>
		</div>
		{% endif %}
		<div class="form-group">
		    <input type="text" name="q" value="{{ request.getQuery('q') }}" placeholder="{{ word['_nhap_tu_khoa'] }}" class="form-control">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-block btn-warning text-uppercase btn-search-advanced">{{ word['_tim_kiem'] }}</button>
		</div>
	</form>
	{% endif %}
	{% if position == 'header' or position == 'footer' or position == 'center' %}
	{% if position == 'header' or position == 'footer' %}
	<div class="container">
	{% endif %}
		<div class="box_search_advanced_inner row">
			<form method="get" action="/tim-kiem">
				<div class="col-lg-10 col-md-10 col-search-advanced-elm">
					<div class="row">
						<div class="col-md-4 col-sm-6 col-xs-12 col-search-advanced">
							<div class="form-group">
								<input type="text" name="q" value="{{ request.getQuery('q') }}" placeholder="{{ word['_nhap_tu_khoa'] }}" class="form-control">
							</div>
						</div>
						{% if categorySearchs|length > 0 %}
						<div class="col-md-4 col-sm-6 col-xs-12 col-search-advanced">
							<div class="form-group">
								<select name="category" class="form-control">
									<option value="0">{{ word['_danh_muc'] }}</option>
									{% for category in categorySearchs %}
									<option value="{{ category.id }}"{% if request.getQuery('category') == category.id %} selected{% endif %}>{{ category.name }}</option>
									{% endfor %}
								</select>
							</div>
						</div>
						{% endif %}
						{% if productElements != '' %}
						{% for key,productElement in productElements %}
						{% set elementName = 'element' ~ key %}
						<div class="col-md-4 col-sm-6 col-xs-12 col-search-advanced">
							<div class="form-group">
								<select name="{{ elementName }}" class="form-control">
									<option value="0">{{ productElement['name'] }}</option>
									{% if productElement['details'] is defined %}
									{% for detail in productElement['details'] %}
									<option value="{{ detail['id'] }}"{% if request.getQuery(elementName) == detail['id'] %} selected{% endif %}>{{ detail['name'] }}</option>
									{% endfor %}
									{% endif %}
								</select>
							</div>
						</div>
						{% endfor %}
						{% endif %}
						{% if priceRanges|length > 0 %}
						<div class="col-md-4 col-sm-6 col-xs-12 col-search-advanced">
							<div class="form-group">
								<select name="price" class="form-control">
									<option value="0">{{ word['_khoang_gia'] }}</option>
									{% for priceRange in priceRanges %}
									{% if priceRange.to_price != 0 %}
									{% set value = priceRange.from_price ~ '-' ~ priceRange.to_price %}
									{% else %}
									{% set value = priceRange.from_price %}
									{% endif %}
									<option value="{{ value }}"{% if request.getQuery('price') == value %} selected{% endif %}>{{ priceRange.name }}</option>
									{% endfor %}
								</select>
							</div>
						</div>
						{% endif %}
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-search-advanced-btn">
					<button type="submit" class="btn btn-block btn-warning text-uppercase btn-search-advanced">{{ word['_tim_kiem'] }}</button>
				</div>
			</form>
		</div>
	{% if position == 'header' or position == 'footer' %}
	</div>
	{% endif %}
	{% endif %}
</div>