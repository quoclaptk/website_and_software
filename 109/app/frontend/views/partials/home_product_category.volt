{% set categories = product_service.getProductCategoryShowHome(cf['_number_product_category_home']) %}
{% set categoryRecursives = category_service.recursiveCategory() %}
{% if categories|length > 0 %}
	{% if layout == 2 %}
        {% set product_class = "col-md-4" %}
    {% elseif layout == 1 %}
        {% set product_class = "col-md-3" %}
    {% else %}
        {% set product_class = "col-md-4" %}
    {% endif %}
    {% if demo_router is defined %}
        {% set router = demo_router %}
    {% else %}
        {% set router = '' %}
    {% endif %}
    {% if cf['_cf_radio_search_for_home_category'] == true %}
    <script>
        var categories = [];
        {% for cate in categoryRecursives %}
            var items = [];
            var name = '{{ cate.name }}';
            var link = '/{{ cate.slug }}/';
            items.push(name);
            items.push(link);
            categories.push(items);
        {% endfor %}
        $(function(){
            $('.categories-autocomplete').autoComplete({
                minChars: 0,
                source: function(term, suggest){
                    term = term.toLowerCase();
                    var suggestions = [];
                    for (i=0;i<categories.length;i++)
                        if (~(categories[i][0]+' '+categories[i][1]).toLowerCase().indexOf(term)) suggestions.push(categories[i]);
                    suggest(suggestions);
                },
                renderItem: function (item, search){
                    search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                    var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                    return '<div class="autocomplete-suggestion" data-url="'+item[1]+'">'+item[0].replace(re, "<b>$1</b>")+'</div>';
                },
                onSelect: function(e, term, item){
                    window.location.href = item.data('url');
                }
            });
        });
    </script>
    {% endif %}
    <div class="box_product_hot_index{% if layout == 1 %} content_main{% endif %} clearfix">
        {% if position == "header" or position == "footer" %}<div class="container">{% endif %}
        {% for i in categories %}
        	{% set title = i['name'] %}
        	{% if demo_router is defined %}
                {% if languageCode == 'vi' %}
                    {% set cate_link = tag.site_url(demo_router ~ '/' ~ i['slug']) %}
                {% else %}
                    {% set cate_link = tag.site_url(demo_router ~ '/' ~ languageCode ~ '/' ~ i['slug']) %}
                {% endif %}
            {% else %}
                {% if languageCode == 'vi' %}
                    {% set cate_link = tag.site_url(i['slug']) %}
                {% else %}
                    {% set cate_link = tag.site_url(languageCode ~ '/' ~ i['slug']) %}
                {% endif %}
            {% endif %}
            {% set products = i['products'] %}
	        <div class="box_product_home_categtory">
		        <div class="title_bar_center bar_web_bgr text-uppercase">
                    <h2 class="text-uppercase">{{ link_to(cate_link, title) }}</h2>
                    {{ link_to(cate_link, word['_xem_them'], 'class':'view_category pull-right') }}
                    {% if cf['_cf_radio_search_for_home_category'] == true %}
                    <div class="box_category_autocomplete">
                        <form onsubmit="$('.categories-autocomplete').blur();return false;" class="pure-form">
                            <input class="categories-autocomplete form-control" autofocus type="text" name="q_category" placeholder="{{ word._('_tim_kiem') }}">
                            <i class="icon-search"></i>
                        </form>
                    </div>
                    {% endif %}
                </div>
                {% set viewMoreWord  = word._('_dang_cap_nhat') %}
		        {% if products|length > 0 %}
                {% set viewMoreWord  = word._('_xem_them') %}
                <div class="box_list_product">
		            <div class="row">
		            {% for l in products %}
		                {{ product_helper.productListViewHtml(product_class, router, l, subdomain, setting, cf, word, ['array':true])}}
		            {% endfor %}
		            </div>
		        </div>
                {% endif %}
                <div class="btn_view_more text-center">{{ link_to(cate_link, viewMoreWord, 'class':'btn btn-success') }}</div>
			</div>
            {% if i['childs'] is defined and i['childs'] != '' %}
                {% set childs = i['childs'] %}
                {% for j in childs %}
                    {% set titleChild = j['name'] %}
                    {% if demo_router is defined %}
                        {% if languageCode == 'vi' %}
                            {% set cateLinkChild = tag.site_url(demo_router ~ '/' ~ j['slug']) %}
                        {% else %}
                            {% set cateLinkChild = tag.site_url(demo_router ~ '/' ~ languageCode ~ '/' ~ j['slug']) %}
                        {% endif %}
                    {% else %}
                        {% if languageCode == 'vi' %}
                            {% set cateLinkChild = tag.site_url(j['slug']) %}
                        {% else %}
                            {% set cateLinkChild = tag.site_url(languageCode ~ '/' ~ j['slug']) %}
                        {% endif %}
                    {% endif %}
                    {% set viewMoreWord  = word._('_dang_cap_nhat') %}
                    {% set productChilds = j['products'] %}
                    {% set viewMoreWord  = word._('_xem_them') %}
                    <div class="box_product_home_categtory">
                        <div class="title_bar_center bar_web_bgr text-uppercase"><h2 class="text-uppercase">{{ link_to(cateLinkChild, titleChild) }}</h2>{{ link_to(cateLinkChild, word['_xem_them'], 'class':'view_category pull-right') }}</div>
                        {% if productChilds|length > 0 %}
                        {% set viewMoreWord  = word._('_xem_them') %}
                        <div class="box_list_product">
                            <div class="row">
                            {% for k in productChilds %}
                                {{ product_helper.productListViewHtml(product_class, router, k, subdomain, setting, cf, word, ['array':true])}}
                            {% endfor %}
                            </div>
                        </div>
                        {% endif %}
                        <div class="btn_view_more text-center">{{ link_to(cateLinkChild,viewMoreWord, 'class':'btn btn-success') }}</div>
                    </div>
                {% endfor %}
            {% endif %}
		{% endfor %}
        {% if position == "header" and position == "footer" %}</div>{% endif %}
    </div>
{% endif %}