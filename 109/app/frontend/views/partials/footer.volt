{% set tmpLayoutModuleFooters = module_item_service.getTmpLayoutModulePosition(setting.layout_id, 'footer') %}
{% if tmpLayoutModuleFooters is not null %}
<footer class="bar_web_bgr">
{% for i in tmpLayoutModuleFooters%}
    {% set id = i["id"] %}
    {% set html_id = i["type"] %}
    {% set module_id = i["module_id"] %}
    {% if html_id == "_footer_top" %}
        {% set class_name = "footer_middle" %}
    {% elseif html_id == "_footer_bottom" %}
        {% set class_name = "footer_bottom" %}
    {% elseif html_id == "_footer_3" %}
        {% set class_name = "footer_3" %}
    {% else %}
        {% set class_name = "" %}
    {% endif %}
    <div id="{{ html_id }}_{{ id }}" class="{{ class_name }}">
    {% if html_id == "_newsletter" %}
        {{ partial("partials/newsletter", ["layout":layout, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_footer_top" %}
    {% set moduleChilds = module_item_service.getModuleChild(module_id) %}
    {% if moduleChilds|length > 0 %}
    <div class="container">
        <div class="row">
            {% for moduleChild in moduleChilds %}
            {% if moduleChild.module_type == '_footer_company_info' and moduleChild.active ==  'Y' %}
            <div class="col-md-5 col-sm-6 col-footer-info">
                <div class="info_footer_middle">
                    <div class="title_footer text-uppercase"><h4>{{ setting.name }}</h4></div>
                    <div class="content_footer_middle">
                        {% if setting.enable_footer_default == 1 %}
                        <div class="content_company_info">
                            <ul>
                                {% if setting.address != "" %}
                                <li class="clearfix">
                                    <i class="icon-dia-chi"></i>
                                    <span><a href="https://www.google.com/maps/search/?api=1&query={{ setting.address }}" target="_blank">{{ setting.address }}</a></span>
                                </li>
                                {% endif %}
                                {% if hotline is not null %}
                                <li class="clearfix">
                                    <i class="icon-sodienthoai"></i>
                                    <span><a href="tel:{{ hotline }}">{{ hotline }}</a></span>
                                </li>
                                {% endif %}
                                {% if setting.email != "" %}
                                <li class="clearfix">
                                    <i class="icon-mail"></i>
                                    <span><a href="mailto:{{ setting.email }}">{{ setting.email }}</a></span>
                                </li>
                                {% endif %}
                                {% if setting.tax_code != "" %}
                                <li class="clearfix">
                                    <i class="fa fa-money"></i>
                                    <span>{{ setting.tax_code }}</span>
                                </li>
                                {% endif %}
                                {% if setting.website != "" %}
                                <li class="clearfix">
                                    <i class="fa fa-globe" aria-hidden="true"></i>
                                    <span><a href="//{{ setting.website }}" target="_blank">{{ setting.website }}</a></span>
                                </li>
                                {% endif %}
                                {% if setting.slogan != "" %}
                                <li class="clearfix">
                                    <i class="fa fa-bullseye" aria-hidden="true"></i>
                                    <span>{{ setting.slogan }}</span>
                                </li>
                                {% endif %}
                            </ul>
                        </div>
                        {% endif %}
                        {% if setting.footer != '' %}
                        <div class="footer_content">{{ setting.footer }}</div>
                        {% endif %}
                    </div>
                </div>
            </div>
            {% endif %}
            {% if moduleChild.module_type == '_footer_product_category' and moduleChild.active ==  'Y' %}
            <div class="col-md-4 col-sm-6 col-footer-category">
                {% set category = category_service.getCategoryParent()  %}
                <div class="info_footer_middle">
                    <div class="title_footer text-uppercase"><h4>{{ word['_danh_muc'] }}</h4></div>
                </div>
                <div class="content_footer_middle clearfix" id="company_category">
                    <div class="row">
                    {% for j in category %}
                        {% if demo_router is defined %}
                        {% set link = tag.site_url(demo_router ~ '/' ~ j.slug) %}
                        {% else %}
                        {% set link = tag.site_url(j.slug) %}
                        {% endif %}
                        <div class="col-md-6">
                            <div class="footer_category">
                                <a href="{{ link }}" title="{{ j.name }}">{{ j.name }}</a>
                            </div>
                        </div>
                    {% endfor %}
                    </div>
                </div>
            </div>
            {% endif %}
            {% if moduleChild.module_type == '_footer_policy' and moduleChild.active ==  'Y' %}
            <div class="col-md-3 col-footer-policy">
                {{ partial("partials/gerenal_policy", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id]) }}
            </div>
            {% endif %}
            {% endfor %}
        </div>
    </div>
    {% endif %}
    {% endif %}
    {% if html_id == "_footer_bottom" %}
        {% set moduleChilds = module_item_service.getModuleChild(module_id) %}
        {% if moduleChilds|length > 0 %}
        <div class="container">
            <div class="row">
                {% for moduleChild in moduleChilds %}
                {% if moduleChild.module_type == '_footer_weblink_copyright' and moduleChild.active ==  'Y' %}
                <div class="col-md-4 col-sm-6 col-xs-6 col-ss-12 col-sss-12 col-footer-facebook">
                    <div class="box_fanpage_footer">
                        <div class="fb-page" data-href="{{ setting.facebook }}" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"></div>
                    </div>
                    {#<div id="website_link">
                        <select class="form-control" onchange="window.open(this.value,'_self')">
                            <option>{{ word['_lien_ket_website'] }}</option>
                            <option value="//24h.com.vn">24h</option>
                            <option value="//vnexpress.vn">Vnexpress</option>
                            <option value="//dantri.com.vn">Dân trí</option>
                        </select>
                        <div class="layer array_select_link bar_web_bgr text-center"><span class="caret"></span></div>
                        
                    </div>#}
                    {% if setting.copyright != "" %}
                        <div id="copyright">{{ setting.copyright }}</div>
                    {% endif %}
                </div>
                {% endif %}
                {% if moduleChild.module_type == '_footer_social_industry_minister' and moduleChild.active ==  'Y' %}
                <div class="col-md-4 col-sm-6 col-xs-6 col-ss-12 col-sss-12 col-footer-social">
                    <div class="row">
                        <div class="col-md-7 col-lg-12">
                            <nav class="social_icon">
                                <ul>
                                    <li><a href="{{ setting.google }}" target="_blank"><img src="/assets/images/i_google.png" alt="Google"></a></li>
                                    <li><a href="{{ setting.facebook }}" target="_blank"><img src="/assets/images/i_facebook.png" alt="Facebook"></a></li>
                                    <li><a href="{{ setting.twitter }}" target="_blank"><img src="/assets/images/i_twitter.png" alt="Twitter"></a></li>
                                    <li><a href="{{ setting.youtube }}" target="_blank"><img src="/assets/images/i_youtube.png" alt="Youtube"></a></li>
                                </ul>
                            </nav>
                        </div>
                        {#<div class="col-md-5">
                            <a href="#" target="_blank" class="pull-right logo_bct">
                                <img src="/assets/images/dangkybct.png">
                            </a>
                        </div>#}
                    </div>
                </div>
                {% endif %}
                {% if moduleChild.module_type == '_footer_online_access' and moduleChild.active ==  'Y' %}
                <div class="col-md-4 col-sm-6 col-xs-6 col-ss-12 col-sss-12 col-footer-online-access">
                    <div class="user_online pull-right">
                        {{ partial('partials/access_online_block') }}
                        <a href="/sitemap.xml" class="site_map_url">Sitemaps</a>
                    </div>
                </div>
                {% endif %}
                {% endfor %}
            </div>
        </div>
        {% endif %}
    {% endif %}
    {% if html_id == "_footer_3" %}
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-sm-6 col-xs-6 col-ss-12 col-footer-info-2">
                <div class="content_footer_3">
                    {% if setting.enable_footer_default == 1 %}
                    <div class="content_company_info">
                        <ul>
                            {% if setting.name != "" %}
                            <li class="text-uppercase">{{ setting.name }}</li>
                            {% endif %}
                            {% if setting.address != "" %}
                            <li class="clearfix">
                                <i class="icon-dia-chi"></i>
                                <span><a href="https://www.google.com/maps/search/?api=1&query={{ setting.address }}" target="_blank">{{ setting.address }}</a></span>
                            </li>
                            {% endif %}
                            {% if hotline is not null %}
                            <li class="clearfix">
                                <i class="icon-sodienthoai"></i>
                                <span><a href="tel:{{ hotline }}">{{ hotline }}</a></span>
                            </li>
                            {% endif %}
                            {% if setting.email != "" %}
                            <li class="clearfix">
                                <i class="icon-mail"></i>
                                <span><a href="mailto:{{ setting.email }}">{{ setting.email }}</a></span>
                            </li>
                            {% endif %}
                            {% if setting.tax_code != "" %}
                            <li class="clearfix">
                                <i class="fa fa-money"></i>
                                <span>{{ setting.tax_code }}</span>
                            </li>
                            {% endif %}
                            {% if setting.website != "" %}
                            <li class="clearfix">
                                <i class="fa fa-globe" aria-hidden="true"></i>
                                <span><a href="//{{ setting.website }}" target="_blank">{{ setting.website }}</a></span>
                            </li>
                            {% endif %}
                            {% if setting.slogan != "" %}
                            <li class="clearfix">
                                <i class="fa fa-bullseye" aria-hidden="true"></i>
                                <span>{{ setting.slogan }}</span>
                            </li>
                            {% endif %}
                        </ul>
                    </div>
                    {% endif %}
                    {% if setting.footer != '' %}
                    <div class="footer_content">{{ setting.footer }}</div>
                    {% endif %}
                </div>
            </div>
            <div class="col-md-3 hidden-sm hidden-xs col-footer-social-2">
                <div class="box_fanpage_footer">
                    <div class="fb-page" data-href="{{ setting.facebook }}" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"></div>
                </div>
                <nav class="social_icon">
                    <ul>
                        <li><a href="{{ setting.google }}" target="_blank"><img src="/assets/images/i_google.png" alt="Google"></a></li>
                        <li><a href="{{ setting.facebook }}" target="_blank"><img src="/assets/images/i_facebook.png" alt="Facebook"></a></li>
                        <li><a href="{{ setting.twitter }}" target="_blank"><img src="/assets/images/i_twitter.png" alt="Twitter"></a></li>
                        <li><a href="{{ setting.youtube }}" target="_blank"><img src="/assets/images/i_youtube.png" alt="Youtube"></a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6 col-ss-12 col-footer-online-access-2">
                <div class="user_online pull-right">
                    {{ partial('partials/access_online_block') }}
                    <a href="/sitemap.xml" class="site_map_url">Sitemaps</a>
                </div>
            </div>
        </div>
    </div>
    {% endif %}
    {% if html_id == "_footer_total" %}
    {% set moduleChilds = module_item_service.getModuleChild(module_id) %}
    {% if moduleChilds|length > 0 %}
    <div class="box_footer_total">
    	<div class="container">
    	    <div class="row">
    	        {% for moduleChild in moduleChilds %}
    	        {% if moduleChild.module_type == '_footer_total_company_info' and moduleChild.active ==  'Y' %}
    	        <div class="col-md-4 col-sm-6 col-footer-total-flex col-footer-info">
    	            <div class="info_footer_middle">
    	                <div class="title_footer text-uppercase"><h4>{{ setting.name }}</h4></div>
    	                <div class="content_footer_middle">
    	                    {% if setting.enable_footer_default == 1 %}
    	                    <div class="content_company_info">
    	                        <ul>
    	                            {% if setting.address != "" %}
    	                            <li class="clearfix">
    	                                <i class="icon-dia-chi"></i>
    	                                <span><a href="https://www.google.com/maps/search/?api=1&query={{ setting.address }}" target="_blank">{{ setting.address }}</a></span>
    	                            </li>
    	                            {% endif %}
    	                            {% if hotline is not null %}
    	                            <li class="clearfix">
    	                                <i class="icon-sodienthoai"></i>
    	                                <span><a href="tel:{{ hotline }}">{{ hotline }}</a></span>
    	                            </li>
    	                            {% endif %}
    	                            {% if setting.email != "" %}
    	                            <li class="clearfix">
    	                                <i class="icon-mail"></i>
    	                                <span><a href="mailto:{{ setting.email }}">{{ setting.email }}</a></span>
    	                            </li>
    	                            {% endif %}
    	                            {% if setting.tax_code != "" %}
    	                            <li class="clearfix">
    	                                <i class="fa fa-money"></i>
    	                                <span>{{ setting.tax_code }}</span>
    	                            </li>
    	                            {% endif %}
    	                            {% if setting.website != "" %}
    	                            <li class="clearfix">
    	                                <i class="fa fa-globe" aria-hidden="true"></i>
    	                                <span><a href="//{{ setting.website }}" target="_blank">{{ setting.website }}</a></span>
    	                            </li>
    	                            {% endif %}
    	                            {% if setting.slogan != "" %}
    	                            <li class="clearfix">
    	                                <i class="fa fa-bullseye" aria-hidden="true"></i>
    	                                <span>{{ setting.slogan }}</span>
    	                            </li>
    	                            {% endif %}
    	                        </ul>
    	                    </div>
    	                    {% endif %}
    	                    {% if setting.footer != '' %}
    	                    <div class="footer_content">{{ setting.footer }}</div>
    	                    {% endif %}
    	                </div>
    	            </div>
    	        </div>
    	        {% endif %}
    	        {% if moduleChild.module_type == '_footer_total_category' and moduleChild.active ==  'Y' %}
    	        <div class="col-md-4 col-sm-6 col-footer-total-flex col-footer-category">
    	            {% set category = category_service.getCategoryParent()  %}
    	            <div class="info_footer_middle">
    	                <div class="title_footer text-uppercase"><h4>{{ word['_danh_muc'] }}</h4></div>
    	            </div>
    	            <div class="content_footer_middle clearfix" id="company_category">
    	                <div class="row">
    	                {% for j in category %}
    	                    {% if demo_router is defined %}
    	                    {% set link = tag.site_url(demo_router ~ '/' ~ j.slug) %}
    	                    {% else %}
    	                    {% set link = tag.site_url(j.slug) %}
    	                    {% endif %}
    	                    <div class="col-md-6">
    	                        <div class="footer_category">
    	                            <a href="{{ link }}" title="{{ j.name }}">{{ j.name }}</a>
    	                        </div>
    	                    </div>
    	                {% endfor %}
    	                </div>
    	            </div>
    	        </div>
    	        {% endif %}
    	        {% if moduleChild.module_type == '_footer_total_news' and moduleChild.active ==  'Y' %}
    	        <div class="col-md-4 col-footer-total-flex col-footer-policy">
    	            {{ partial("partials/gerenal_policy", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id]) }}
    	        </div>
    	        {% endif %}
    	        {% if moduleChild.module_type == '_footer_total_fanpage_copyright' and moduleChild.active ==  'Y' %}
    	        <div class="col-md-4 col-sm-6 col-footer-total-flex col-xs-6 col-ss-12 col-sss-12 col-footer-facebook">
    	            <div class="box_fanpage_footer">
    	                <div class="fb-page" data-href="{{ setting.facebook }}" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"></div>
    	            </div>
    	            {% if setting.copyright != "" %}
    	                <div id="copyright">{{ setting.copyright }}</div>
    	            {% endif %}
    	        </div>
    	        {% endif %}
    	        {% if moduleChild.module_type == '_footer_total_social_logo' and moduleChild.active ==  'Y' %}
    	        <div class="col-md-4 col-sm-6 col-xs-6 col-ss-12 col-sss-12 col-footer-total-flex col-footer-social">
    	            <nav class="social_icon">
    	                <ul>
    	                    <li><a href="{{ setting.google }}" target="_blank"><img src="/assets/images/i_google.png" alt="Google"></a></li>
    	                    <li><a href="{{ setting.facebook }}" target="_blank"><img src="/assets/images/i_facebook.png" alt="Facebook"></a></li>
    	                    <li><a href="{{ setting.twitter }}" target="_blank"><img src="/assets/images/i_twitter.png" alt="Twitter"></a></li>
    	                    <li><a href="{{ setting.youtube }}" target="_blank"><img src="/assets/images/i_youtube.png" alt="Youtube"></a></li>
    	                </ul>
    	            </nav>
    	        </div>
    	        {% endif %}
    	        {% if moduleChild.module_type == '_footer_total_access_online' and moduleChild.active ==  'Y' %}
    	        <div class="col-md-4 col-sm-6 col-xs-6 col-ss-12 col-sss-12 col-footer-total-flex col-footer-online-access">
    	            <div class="user_online">
    	                {{ partial('partials/access_online_block') }}
    	                <a href="/sitemap.xml" class="site_map_url">Sitemaps</a>
    	            </div>
    	        </div>
    	        {% endif %}
                {% if moduleChild.module_type == '_footer_total_map' and moduleChild.active ==  'Y' %}
                    <div class="col-md-4 col-sm-6 col-xs-6 col-ss-12 col-sss-12 col-footer-total-flex col-footer-google-map">
                        <div class="md_gooogle_map_elm">{{ setting.map_code }}</div>
                    </div>
                {% endif %}
                {% if moduleChild.module_type == 'post' %}
                    {% set post = mainGlobal.getPostFromId(moduleChild.module_type_id) %}
                    <div class="col-md-4 col-sm-6 col-xs-6 col-ss-12 col-sss-12 col-footer-total-flex col-footer-post-{{ moduleChild.module_id }}">
                        {% if post != "" and post.content != "" %}
                            <div class="box_post_footer_total">{{ post.content }}</div>
                        {% endif %}
                    </div>
                {% endif %}
                {% if moduleChild.module_type == '_footer_total_contact' and moduleChild.active ==  'Y' %}
                <div class="col-md-4 col-sm-6 col-xs-6 col-ss-12 col-sss-12 col-footer-total-flex col-footer-total-contact">
                    {{ partial("partials/footer_total_contact") }}
                </div>
                {% endif %}
    	        {% endfor %}
    	    </div>
    	</div>
    </div>
    {% endif %}
    {% endif %}
    {% if html_id == "_home_news_hot_index" %}
    {{ partial("partials/news_hot", ["position":"footer", "layout":layout, "html_id":html_id, "id": id, "news_type":"news", "type":"hot", "limit":4]) }}
    {% endif %}
    {% if html_id == "_module_news_introduct" %}
    {{ partial("partials/module_news_introduct", ["layout":layout, "id":id, "html_id":html_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_news_new" %}
    {{ partial("partials/news_hot", ["position":"footer", "layout":layout, "html_id":html_id, "id":id, "news_type":2, "type":"new", "limit":4]) }}
    {% endif %}
    {% if html_id == "_news_most_view" %}
    {{ partial("partials/news_hot", ["position":"footer", "layout":layout, "html_id":html_id, "id":id, "news_type":2, "type":"most_view", "limit":4]) }}
    {% endif %}
    {% if html_id == "banner" %}
    {{ partial("partials/banner", ["position":"footer", "layout":layout, "html_id":html_id, "id": id, "module_id":module_id]) }}
    {% endif %}
    {% if html_id == "post" %}
    {{ partial("partials/post", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_comment_facebook" %}
    {{ partial("partials/facebook_comment", ["layout":layout, "html_id":html_id, "id": id, "module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_admin_login" %}
    {{ partial("partials/admin_login", ["layout":layout, "id":id, "html_id":html_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_search" %}
    {{ partial("partials/search", ["layout":layout, "id":id, "html_id":html_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_customer_message" %}
    {{ partial("partials/customer_message_module", ["layout":layout, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_frm_ycbg" %}
    {{ partial("partials/form_item_module", ["layout":layout, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_customer_comment" %}
    {{ partial("partials/customer_comment", ["layout":layout, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_home_product_hot" %}
    {{ partial("partials/product_hot", ["layout":layout, "id":id, "html_id":html_id, "type":"hot", "limit":8, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_home_product_new" %}
    {{ partial("partials/product_hot", ["layout":layout, "id":id, "html_id":html_id, "type":"new", "limit":8, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_policy_service" %}
    {{ partial("partials/policy_service", ["layout":layout, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_search_advanced" %}
    {{ partial("partials/search_advanced", ["layout":layout, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_category_hot" %}
    {{ partial("partials/category_hot", ["layout":layout, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_register_login" %}
    {{ partial("partials/member_account", ["layout":layout, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_product_hot_slide" %}
    {{ partial("partials/product_hot_slide", ["layout":layout, "position":"footer", "type":"hot"]) }}
    {% endif %}
    {% if html_id == "_text_marquee_horizontal" %}
    {{ partial("partials/text_marquee_horizontal", ["layout":layout, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_contact" %}
    {{ partial("partials/contact", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_banner_2" %}
    {{ partial("partials/md_banner_2", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_banner_3" %}
    {{ partial("partials/md_banner_3", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_google_map" %}
    {{ partial("partials/md_google_map", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_usually_question" %}
    {{ partial("partials/usually_question", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_subdomain_implement" %}
    {{ partial("partials/subdomain_implement", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_module_create_website" %}
    {{ partial("partials/module_create_website", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_slider_news" %}
    {{ partial("partials/slider_news", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_news_hot_effect" %}
    {{ partial("partials/news_hot_effect", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_home_product_category" %}
    {{ partial("partials/home_product_category", ["position":"footer", "layout":layout, "id":id, "html_id":html_id, "limit":8]) }}
    {% endif %}
    {% if html_id == "_home_news_menu" %}
    {{ partial("partials/home_news_menu", ["layout":layout, "id":id, "html_id":html_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_product_hot_about" %}
    {{ partial("partials/product_hot_about", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_news_statistical" %}
    {{ partial("partials/news_statistical", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_md_slider_news" %}
    {{ partial("partials/md_slider_news", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    {% if html_id == "_category_group_sole" %}
    {{ partial("partials/category_group_sole", ["module_id":module_id, "position":"footer"]) }}
    {% endif %}
    </div>
{% endfor %}
</footer>
{% endif %}
{% if cf['_cf_radio_module_administrator'] == true %}
{{ partial('partials/module_administrator') }}
{% endif %}