{% set tmpLayoutModuleCenters = module_item_service.getTmpLayoutModulePosition(setting.layout_id, 'center') %}
{% if tmpLayoutModuleCenters is not null %}
{% for i in tmpLayoutModuleCenters %}
    {% set id = i["id"] %}
    {% set html_id = i["type"] %}
    {% set module_id = i["module_id"] %}
    {% if html_id == "_home_article" %}
	{{ partial("partials/home_article", ["layout":layout, "id":id, "html_id":html_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_module_news_introduct" %}
	{{ partial("partials/module_news_introduct", ["layout":layout, "id":id, "html_id":html_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_home_product_hot" %}
	{{ partial("partials/product_hot", ["position":"center", "layout":layout, "id":id, "html_id":html_id, "type":"hot", "limit":8]) }}
	{% endif %}
	{% if html_id == "_home_product_category" %}
	{{ partial("partials/home_product_category", ["position":"center", "layout":layout, "id":id, "html_id":html_id, "limit":8]) }}
	{% endif %}
	{% if html_id == "_home_product_new" %}
	{{ partial("partials/product_hot", ["position":"center", "layout":layout, "id":id, "html_id":html_id, "type":"new", "limit":8]) }}
	{% endif %}
	{% if html_id == "_home_news_hot_index" %}
	{{ partial("partials/news_hot", ["position":"center", "layout":layout, "html_id":html_id, "id":id, "news_type":2, "type":"hot", "limit":4]) }}
	{% endif %}
	{% if html_id == "_news_new" %}
	{{ partial("partials/news_hot", ["position":"center", "layout":layout, "html_id":html_id, "id":id, "news_type":2, "type":"new", "limit":4]) }}
	{% endif %}
	{% if html_id == "_news_most_view" %}
	{{ partial("partials/news_hot", ["position":"center", "layout":layout, "html_id":html_id, "id":id, "news_type":2, "type":"most_view", "limit":4]) }}
	{% endif %}
	{% if html_id == "banner" %}
	{{ partial("partials/banner", ["position":"center", "layout":layout, "html_id":html_id, "module_id":module_id]) }}
	{% endif %}
	{% if html_id == "menu" %}
	{{ partial("partials/main_menu", ["layout":layout, "position":"center"]) }}
	{% endif %}
	{% if html_id == "post" %}
	{{ partial("partials/post", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_comment_facebook" %}
	{{ partial("partials/facebook_comment", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_admin_login" %}
	{{ partial("partials/admin_login", ["layout":layout, "id":id, "html_id":html_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_newsletter" %}
	{{ partial("partials/newsletter", ["layout":layout, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_search" %}
	{{ partial("partials/search", ["layout":layout, "id":id, "html_id":html_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_customer_message" %}
	{{ partial("partials/customer_message_module", ["layout":layout, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_frm_ycbg" %}
	{{ partial("partials/form_item_module", ["layout":layout, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_home_news_menu" %}
	{{ partial("partials/home_news_menu", ["layout":layout, "id":id, "html_id":html_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_policy_service" %}
	{{ partial("partials/policy_service", ["layout":layout, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_search_advanced" %}
	{{ partial("partials/search_advanced", ["layout":layout, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_menu_top_2" %}
	{{ partial("partials/main_menu_2", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_category_hot" %}
	{{ partial("partials/category_hot", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_register_login" %}
	{{ partial("partials/member_account", ["layout":layout, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_product_hot_slide" %}
	{{ partial("partials/product_hot_slide", ["layout":layout, "position":"center", "type":"hot"]) }}
	{% endif %}
	{% if html_id == "_text_marquee_horizontal" %}
	{{ partial("partials/text_marquee_horizontal", ["layout":layout, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_customer_comment" %}
	{{ partial("partials/customer_comment", ["layout":layout, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_contact" %}
	{{ partial("partials/contact", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_banner_2" %}
	{{ partial("partials/md_banner_2", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_banner_3" %}
	{{ partial("partials/md_banner_3", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_google_map" %}
	{{ partial("partials/md_google_map", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_usually_question" %}
	{{ partial("partials/usually_question", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_date_time" %}
	{{ partial("partials/md_date_time", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_subdomain_implement" %}
	{{ partial("partials/subdomain_implement", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_module_create_website" %}
	{{ partial("partials/module_create_website", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_slider_news" %}
	{{ partial("partials/slider_news", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_news_hot_effect" %}
	{{ partial("partials/news_hot_effect", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_product_hot_about" %}
	{{ partial("partials/product_hot_about", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_news_statistical" %}
	{{ partial("partials/news_statistical", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_md_slider_news" %}
	{{ partial("partials/md_slider_news", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
	{% if html_id == "_category_group_sole" %}
	{{ partial("partials/category_group_sole", ["module_id":module_id, "position":"center"]) }}
	{% endif %}
{% endfor %}
{% endif %}