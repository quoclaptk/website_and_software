{#
{% if subdomain.id == 1 %}
<div class="box-register-login-system">
    {% if request.get('msg') == 'error_password' %}
    <div class="alert alert-danger">Mật khẩu bạn nhập không đúng với username bạn đăng nhập!</div>
    {% endif %}
    <section class="tabblue">
        <ul class="tabs blue">
            <li>
                <input type="radio" name="tabs blue" id="tab1" checked>
                <label for="tab1">Đăng nhập</label>
                <div id="tab-content1" class="tab-content">
                    <form method="post" name="frm_system_login">
                        <div class="form-group">
                            <div class="clearfix">
                                <span class="tabaddon"><i class="fa fa-user fa-2x"></i></span>
                                <input class="field" name="l_username" required type="text" placeholder="Tên đăng nhập. VD: lamweb123">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="clearfix">
                                <span class="tabaddon"><i class="fa fa-lock fa-2x"></i></span>
                                <input class="field" name="l_password" required type="password" placeholder="Mật khẩu">
                            </div>
                        </div>
                        <div class="btn-form clearfix">
                            <input type="submit" id="btn_system_login" value="Đăng nhập">
                        </div>
                    </form>
                </div>
            </li>
            <li>
                <input type="radio" name="tabs blue" id="tab2">
                <label for="tab2">Tạo tài khoản dùng thử</label>
                <div id="tab-content2" class="tab-content">
                    <!-- <p>You can sign up free at reverie tech and get super awesome services tips. It's what all the cool kids are doing nowadays.</p> -->
                    <form method="post" name="frm_system_register">
                        <div class="form-group">
                            <div class="clearfix">
                                <span class="tabaddon"><i class="fa fa-globe fa-2x"></i></span>
                                <input class="field sub-domain" name="domain" required type="text" placeholder="Tên miền. VD: lamweb123">
                                <span class="text-center domain-name">.{{ ROOT_DOMAIN }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="clearfix">
                                <span class="tabaddon"><i class="fa fa-user fa-2x"></i></span>
                                <input class="field sub-username" name="r_username" required type="text" placeholder="Tên đăng nhập. VD: lamweb123">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="clearfix">
                                <span class="tabaddon"><i class="fa fa-lock fa-2x"></i></span>
                                <input class="field" name="r_password" required type="password" placeholder="Mật khẩu">
                            </div>
                        </div>
                        <div class="btn-form clearfix">
                            <button class="btn btn-primary ladda-button" id="btn-create-domain" data-style="slide-left"><span class="ladda-label">Đăng ký</span></button>
                        </div>
                    </form>
                </div>
            </li>
        </ul>
    </section>
</div>
<div class="box-all-news-system">
    <div class="title_bar_right bar_web_bgr text-uppercase">{{ title_bar_news }}</div>
    <div class="box-all-news-system-content" id="load-page-all-news">
        <div class="bs-all-news row">
            {% for i in pageNews.items %}
            {% set name = i.name %}
            {% if i.subdomain_name != '@' %}
            {% set url = tag.site_url('//' ~ i.subdomain_name ~ '.' ~ ROOT_DOMAIN ~ '/' ~ i.slug) %}
            {% else %}
            {% set url = tag.site_url('//' ~ ROOT_DOMAIN ~ '/' ~ i.slug) %}
            {% endif %}
            {% if i.photo != '' and file_exists('files/news/' ~ i.subdomain_folder ~ '/' ~ i.folder ~ '/' ~ i.photo) %}
            {% set photo = 'files/news/' ~ i.subdomain_folder ~ '/' ~ i.folder ~ '/' ~ i.photo  %}
            {% else %}
            {% set photo = "assets/images/no-image.png" %}
            {% endif %}
            <div class="media col-md-6">
                <div class="media-left"> 
                    <a href="{{ url }}" target="_blank"> 
                        {{ image(photo, 'alt':name, 'class':'media-object') }}
                    </a> 
                </div>
                <div class="media-body">
                    <h4 class="media-heading"><a href="{{ url }}" target="_blank">{{ name }}</a></h4>
                </div>
            </div>
            {% endfor %}
        </div>
        {% if pageNews.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'page':pageNews, 'url_page':'all-news', 'html_id':'load-page-all-news'}) }}</div>{% endif %}
    </div>
</div>
<div class="box-all-product-system">
    <div class="title_bar_right bar_web_bgr text-uppercase">{{ title_bar_product }}</div>
    <div class="box-all-product-system-content" id="load-page-all-product">
        <div class="bs-all-product row">
            {% for i in pageProduct.items %}
            {% set name = i.name %}
            {% if i.subdomain_name != '@' %}
            {% set url = tag.site_url('//' ~ i.subdomain_name ~ '.' ~ ROOT_DOMAIN ~ '/' ~ i.slug) %}
            {% else %}
            {% set url = tag.site_url('//' ~ ROOT_DOMAIN ~ '/' ~ i.slug) %}
            {% endif %}
            {% if i.photo != '' and file_exists('files/product/' ~ i.subdomain_folder ~ '/' ~ i.folder ~ '/' ~ i.photo) %}
            {% set photo = 'files/product/' ~ i.subdomain_folder ~ '/' ~ i.folder ~ '/' ~ i.photo  %}
            {% else %}
            {% set photo = "assets/images/no-image.png" %}
            {% endif %}
            <div class="media col-md-6">
                <div class="media-left"> 
                    <a href="{{ url }}" target="_blank"> 
                        {{ image(photo, 'alt':name, 'class':'media-object') }}
                    </a> 
                </div>
                <div class="media-body">
                    <h4 class="media-heading"><a href="{{ url }}" target="_blank">{{ name }}</a></h4>
                </div>
            </div>
            {% endfor %}
        </div>
        {% if pageProduct.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'page':pageProduct, 'url_page':'all-product', 'html_id':'load-page-all-product'}) }}</div>{% endif %}
    </div>
</div>
<div class="box-all-subdomain-system">
    <div class="title_bar_right bar_web_bgr text-uppercase">{{ title_bar_subdomain }}</div>
    <div class="box-all-subdomain-system-content" id="load-page-all-subdomain">
        <div class="bs-all-subdomain">
            <ul class="list-group">
                {% for i in pageSubdomain.items %}
                <li class="list-group-item"><a href="//{{ i.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank">{{ i.name ~ '.' ~ ROOT_DOMAIN }}</a></li>
                {% endfor %}
            </ul>
        </div>
        {% if pageSubdomain.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'page':pageSubdomain, 'url_page':'all-subdomain', 'html_id':'load-page-all-subdomain'}) }}</div>{% endif %}
    </div>
</div>
{% endif %}
#}