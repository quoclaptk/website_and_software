{% if html_id == "_search" %}
{{ partial("partials/search", ["layout":layout, "id":id, "html_id":html_id, "position":position]) }}
{% endif %}
{% if html_id == "_md_search_advanced" %}
{{ partial("partials/search_advanced", ["layout":layout, "position":position]) }}
{% endif %}
{% if html_id == "menu" %}
{{ partial("partials/main_menu", ["module_id":module_id]) }}
{% endif %}
{% if html_id == "_product_category_left_right" %}
{{ partial("partials/category_multipe", ["layout":layout, "id":id, "html_id":html_id, "position":position]) }}
{% endif %}
{% if html_id == "_product_selling_left_right" %}
{{ partial("partials/product_hot_left_right", ["layout":layout, "id":id, "html_id":html_id, "type":"selling", "limit":5, "position":position]) }}
{% endif %}
{% if html_id == "banner" %}
{{ partial("partials/banner", ["layout":layout, "id":id, "html_id":html_id, "module_id":module_id, "position":position]) }}
{% endif %}
{% if html_id == "_banner_slider_vertical" %}
{{ partial('partials/banner_slider_vertical', ["layout":layout, "id":id, "html_id":html_id, "module_id":module_id, "position":position]) }}
{% endif %}
{% if html_id == "_company_info_left_right" %}
{{ partial("partials/company_info_left_right", ["layout":layout, "id":id, "html_id":html_id, "position":position]) }}
{% endif %}
{% if html_id == "_fanpage_left_right" %}
{{ partial("partials/fanpage_left_right", ["layout":layout, "id":id, "html_id":html_id, "position":position]) }}
{% endif %}
{% if html_id == "post" %}
{{ partial("partials/post", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id, "position":position]) }}
{% endif %}
{% if html_id == "_admin_login" %}
{{ partial("partials/admin_login", ["layout":layout, "id":id, "html_id":html_id, "position":position]) }}
{% endif %}
{% if html_id == "_comment_facebook" %}
{{ partial("partials/facebook_comment", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id, "position":position]) }}
{% endif %}
{% if html_id == "_access_online" %}
{{ partial("partials/access_online", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id, "position":position]) }}
{% endif %}
{% if html_id == "_newsletter" %}
{{ partial("partials/newsletter", ["layout":layout, "position":position]) }}
{% endif %}
{% if html_id == "_customer_message" %}
{{ partial("partials/customer_message_module", ["layout":layout, "position":position]) }}
{% endif %}
{% if html_id == "_frm_ycbg" %}
{{ partial("partials/form_item_module", ["layout":layout, "position":position]) }}
{% endif %}
{% if html_id == "_home_news_hot_index" %}
{{ partial("partials/news_hot", ["position":position, "layout":layout, "html_id":html_id, "id":id, "news_type":2, "type":"hot", "limit":4]) }}
{% endif %}
{% if html_id == "_news_new" %}
{{ partial("partials/news_hot", ["position":position, "layout":layout, "html_id":html_id, "id":id, "news_type":2, "type":"new", "limit":4]) }}
{% endif %}
{% if html_id == "_news_most_view" %}
{{ partial("partials/news_hot", ["position":position, "layout":layout, "html_id":html_id, "id":id, "news_type":2, "type":"most_view", "limit":4]) }}
{% endif %}
{% if html_id == "_md_lr_news_menu" %}
{{ partial("partials/news_menu_category_multipe", ["layout":layout, "id":id, "html_id":html_id, "position":position]) }}
{% endif %}
{% if html_id == "_md_contact" %}
{{ partial("partials/contact", ["module_id":module_id, "position":position]) }}
{% endif %}
{% if html_id == "_md_banner_2" %}
{{ partial("partials/md_banner_2", ["module_id":module_id, "position":position]) }}
{% endif %}
{% if html_id == "_md_banner_3" %}
{{ partial("partials/md_banner_3", ["module_id":module_id, "position":position]) }}
{% endif %}
{% if html_id == "_md_google_map" %}
{{ partial("partials/md_google_map", ["module_id":module_id, "position":position]) }}
{% endif %}
{% if html_id == "_md_date_time" %}
{{ partial("partials/md_date_time", ["module_id":module_id, "position":position]) }}
{% endif %}
{% if html_id == "_text_marquee_horizontal" %}
{{ partial("partials/text_marquee_horizontal", ["layout":layout, "position":position]) }}
{% endif %}