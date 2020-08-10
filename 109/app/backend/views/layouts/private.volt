<div class="wrapper">
    <header class="main-header hidden-md hidden-lg">
      <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          {% if session_subdomain['subdomain_name'] == '@' %}
            {% set url = DOMAIN %}
          {% else %}
            {% set url = session_subdomain['subdomain_name'] ~ '.' ~ DOMAIN %}
          {% endif %}
          <div class="pull-left" id="subdomain_name_login"><span>Đăng nhập:</span> <h3><a href="//{{ url }}" target="_blank">{{ url }}</a></h3></div>
          <div class="navbar-custom-menu">
              {#<ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                    {{image('backend/dist/img/user_admin.jpg', 'class':'user-image', 'alt':'User Image')}}
                    <span class="hidden-xs">{{ auth.getUserName() }}</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="user-header">
                      {{image('backend/dist/img/user_admin.jpg', 'class':'img-circle', 'alt':'User Image')}}
                      <p>
                        {{ auth.getUsername() }} - Administrators
                      </p>
                    </li>

                    <li class="user-footer">
                      <div class="pull-left">
                        {{ link_to(ACP_NAME ~ '/users/changePassword', 'Change Password', 'class':'btn btn-default btn-flat') }}
                      </div>
                      <div class="pull-right">
                        {{ link_to(ACP_NAME ~ '/index/logout', 'Sign out', 'class':'btn btn-default btn-flat') }}
                      </div>
                    </li>

                  </ul>
                </li>

              </ul>#}
          </div>
       
      </nav>
    </header>
    <aside class="main-sidebar" style="padding-top: 0">
      <section class="sidebar">
        <div class="user-panel hidden-xs hidden-sm" style="background: #3c8dbc;">
          <span style="color: #fff;font-size:13px">Đăng nhập:</span> <h4 style="margin-top:5px;margin-bottom: 0"><a style="color: #fff" href="//{{ session_subdomain['host'] }}" target="_blank">{{ session_subdomain['host'] }}</a></h4>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            {#<li>{{ link_to('', '<i class="fa fa-archive"></i> <span>View Site</span>', 'target':'_blank') }}</li>#}
            <li{% if router.getControllerName() == "index" %} class="active"{% endif %}>{{ link_to(ACP_NAME, '<i class="fa fa-home"></i> <span>Home</span>') }}</li>

            {#<li{% if router.getControllerName() == "setting" and router.getActionName() == "index" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/setting', '<i class="fa fa-cog"></i> <span>Thiết lập</span>') }}</li>
            <li{% if router.getControllerName() == "setting" and router.getActionName() == "layout" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/setting/layout', '<i class="fa fa-cog"></i> <span>Thiết lập giao diện</span>') }}</li>#}

            <li class="treeview menu-open{% if router.getControllerName() == "setting" %} active{% endif %}">
                {{ link_to('#', '<i class="fa fa-newspaper-o"></i> <span>Thiết lập</span> <i class="fa fa-angle-left pull-right"></i>') }}
                <ul class="treeview-menu" style="display: block">
                    <li{% if router.getControllerName() == "setting" and router.getActionName() == "content" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/setting/content', '<i class="fa fa-circle-o"></i> <span>Nội dung</span>') }}</li>
                    <li{% if router.getControllerName() == "setting" and router.getActionName() == "config" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/setting/config', '<i class="fa fa-circle-o"></i> <span>Cấu hình</span>') }}</li>
                    <li{% if router.getControllerName() == "setting" and router.getActionName() == "newinterface" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/setting/newinterface', '<i class="fa fa-circle-o"></i> <span>Giao diện</span>') }}</li>
                    <li{% if router.getControllerName() == "orders" %} class="active"{% endif %}>
                    <a href="/{{ ACP_NAME }}/orders"><i class="fa fa-circle-o"></i> <span>Đơn hàng</span>
                      {% if hashOrder == true %}
                      <i class="fa fa-star" aria-hidden="true" style="position:absolute;top:5px;right: 90px;color: #f00;font-size: 14px"></i>
                      {% endif %}
                    </a>
                  </li>
                    <li{% if router.getControllerName() == "layout" and router.getActionName() == "css" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/layout/css/' ~ setting_page.layout_id , 'target':'_blank', '<i class="fa fa-circle-o"></i> <span>CSS</span>') }}</li>
                    {#
                    {% if session_subdomain['role'] is defined and session_subdomain['role'] == 1 %}
                    <li{% if router.getControllerName() == "system" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/system/ipAccess', '<i class="fa fa-circle-o"></i> <span>Thống kê truy cập</span>') }}</li>
                    {% else %}
                    <li{% if router.getControllerName() == "ipAccess" and router.getActionName() == "config" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/setting/ipAccess', '<i class="fa fa-circle-o"></i> <span>Thống kê truy cập</span>') }}</li>
                    {% endif %}
                    #}
                </ul>
            </li>
            <li class="treeview menu-open{% if router.getControllerName() == "category" or router.getControllerName() == "product" or router.getControllerName() == "product_detail" or router.getControllerName() == "product_element" %} active{% endif %}">
                {{ link_to('#', '<i class="fa fa-laptop"></i> <span>Sản phẩm</span> <i class="fa fa-angle-left pull-right"></i>') }}
                <ul class="treeview-menu" style="display: block">
                    <li>{{ link_to(ACP_NAME ~ '/category', '<i class="fa fa-circle-o"></i> <span>Danh mục</span>') }}</li>
                    <li>{{ link_to(ACP_NAME ~ '/product', '<i class="fa fa-circle-o"></i> <span>Danh sách</span>') }}</li>
                    <li>{{ link_to(ACP_NAME ~ '/product_detail', '<i class="fa fa-circle-o"></i> <span>Chi tiết</span>') }}</li>
                    <li>{{ link_to(ACP_NAME ~ '/product_element', '<i class="fa fa-circle-o"></i> <span>Thuộc tính</span>') }}</li>
                    <li>{{ link_to(ACP_NAME ~ '/price_range', '<i class="fa fa-circle-o"></i> <span>Khoảng giá tìm kiếm</span>') }}</li>
                    {#<li>{{ link_to(ACP_NAME ~ '/config_item/group/product', '<i class="fa fa-circle-o"></i> <span>Cấu hình</span>') }}</li>#}
                </ul>
            </li>

            <li class="treeview menu-open{% if router.getControllerName() == "news_menu" or router.getControllerName() == "news" %} active{% endif %}">
                {{ link_to('#', '<i class="fa fa-newspaper-o"></i> <span>Bài viết</span> <i class="fa fa-angle-left pull-right"></i>') }}
                <ul class="treeview-menu" style="display: block">
                    <li>{{ link_to(ACP_NAME ~ '/news_menu', '<i class="fa fa-circle-o"></i> <span>Danh mục</span>') }}</li>
                    <li>{{ link_to(ACP_NAME ~ '/news', '<i class="fa fa-circle-o"></i> <span>Danh sách</span>') }}</li>
                </ul>
            </li>

            <li{% if router.getControllerName() == "landing_page" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/landing_page', '<i class="fa fa-compass"></i> <span>Landing Page</span>') }}</li>

            {% if menu_page|length > 0 %}
            <li class="treeview menu-open{% if router.getControllerName() == "menu" %} active{% endif %}">
                {{ link_to('#', '<i class="fa fa-bars"></i> <span>Menu</span> <i class="fa fa-angle-left pull-right"></i>') }}
                <ul class="treeview-menu" style="display: block">
                    {% for i in menu_page %}
                    <li>{{ link_to(ACP_NAME ~ '/menu/update/' ~ i.id, '<i class="fa fa-circle-o"></i> <span>' ~ i.name ~ '</span>') }}</li>
                    {% endfor %}
                </ul>
            </li>
            {% endif %}
            
            {% if subdomain.other_interface != 'Y' %}
            <li{% if router.getControllerName() == "subdomain" and router.getActionName() == "all" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/subdomain/all', '<i class="fa fa-list-ol"></i> <span>Web đã làm</span>') }}</li>

            <li{% if router.getControllerName() == "setting" and router.getActionName() == "language" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/setting/language', '<i class="fa fa-language"></i> <span>Đa ngôn ngữ</span>') }}</li>
            {% endif %}

            {#<li{% if router.getControllerName() == "orders" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/orders', '<i class="fa fa-shopping-cart"></i> <span>Đơn hàng</span>') }}</li>#}
            
            
            {% if multiple_page|length > 0 %}
            <li{% if router.getControllerName() == "news_type" and (router.getActionName() == "index" or router.getActionName() == "create" or router.getActionName() == "update") %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/news_type', '<i class="fa fa-newspaper-o"></i> <span>Trang tin Chuyên mục</span>') }}</li>
            
              {% for i in multiple_page %}
              <li class="treeview menu-open{% if (router.getControllerName() == "news_category" or router.getControllerName() == "news") and multiple_page != "" and type is defined and type in arr_news_type_multiple %} active{% endif %}">
                  {{ link_to('#', '<i class="fa fa-dot-circle-o"></i> <span>'~ i.name ~'</span> <i class="fa fa-angle-left pull-right"></i>') }}
                  <ul class="treeview-menu" style="display: block">
                      <li>{{ link_to(ACP_NAME ~ '/news_category/index/' ~ i.id, '<i class="fa fa-circle-o"></i> <span>Danh mục</span>') }}</li>
                      <li>{{ link_to(ACP_NAME ~ '/news/index/' ~ i.id, '<i class="fa fa-circle-o"></i> <span>Danh sách</span>') }}</li>
                  </ul>
                </li>
                {% endfor %}
              
            <li{% if router.getControllerName() == "news_type" and (router.getActionName() == "static" or router.getActionName() == "createStatic" or router.getActionName() == "updateStatic") %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/news_type/static', '<i class="fa fa-newspaper-o"></i> <span>Trang tĩnh</span>') }}</li>
            {% endif %}
            <li{% if router.getControllerName() == "posts" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/posts', '<i class="fa fa-newspaper-o"></i> <span>Tự soạn thảo</span>') }}</li>

            {#{% if static_page|length > 0 %}
                <li class="treeview{% if router.getControllerName() == "news_type" and static_page != "" and news_type_id is defined and news_type_id in arr_news_type_static %} active{% endif %}">
                    {{ link_to('#', '<i class="fa fa-dot-circle-o"></i> <span>Trang tĩnh</span> <i class="fa fa-angle-left pull-right"></i>') }}
                    <ul class="treeview-menu">
                        {% for i in static_page %}
                            <li>{{ link_to(ACP_NAME ~ '/news_type/update/' ~ i.id, '<i class="fa fa-circle-o"></i> <span>'~ i.name ~'</span>') }}</li>
                        {% endfor %}
                    </ul>
                </li>
            {% endif %}#}

            
            {#<li{% if router.getControllerName() == "posts" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/posts', '<i class="fa fa-newspaper-o"></i> <span>Trình soạn thảo</span>') }}</li>#}

            {#<li class="treeview{% if router.getControllerName() == "banner"%} active{% endif %}">#}
                {#{{ link_to('#', '<i class="fa fa-picture-o"></i> <span>Banner/Hình ảnh</span> <i class="fa fa-angle-left pull-right"></i>') }}#}
              {#<ul class="treeview-menu">#}
                {#<li>{{ link_to(ACP_NAME ~ '/banner_type', '<i class="fa fa-circle-o"></i> <span>Loại banner</span>') }}</li>#}
                {#<li>{{ link_to(ACP_NAME ~ '/banner', '<i class="fa fa-circle-o"></i> <span>Banner</span>') }}</li>#}
              {#</ul>#}
            {#</li>

            <li class="treeview{% if router.getControllerName() == "banner_type" or router.getControllerName() == "banner" or router.getControllerName() == "posts" or router.getControllerName() == "video" or router.getControllerName() == "support" or router.getControllerName() == "menu" or router.getControllerName() == "module_item" %} active{% endif %}">
            {{ link_to('#', '<i class="fa fa-picture-o"></i> <span>Nội dung module</span> <i class="fa fa-angle-left pull-right"></i>') }}
                <ul class="treeview-menu">
                    <li{% if router.getControllerName() == "menu" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/menu', '<i class="fa fa-sitemap"></i> <span>Menu</span>') }}</li>
                    <li{% if (router.getControllerName() == "banner_type" and type == 1) %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/banner_type/index/1', '<i class="fa fa-circle-o"></i> <span>Banner tĩnh</span>') }}</li>
                    <li{% if (router.getControllerName() == "banner_type" and type == 2) %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/banner_type/index/2', '<i class="fa fa-circle-o"></i> <span>Slider</span>') }}</li>
                    <li{% if (router.getControllerName() == "banner_type" and type == 3) %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/banner_type/index/3', '<i class="fa fa-circle-o"></i> <span>Đối tác/Quảng cáo</span>') }}</li>
                    <li{% if router.getControllerName() == "posts" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/posts', '<i class="fa fa-circle-o"></i> <span>Tự soạn thảo</span>') }}</li>
                    <li{% if router.getControllerName() == "clip" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/clip', '<i class="fa fa-play-circle-o"></i> <span>Video</span>') }}</li>
                    <li{% if router.getControllerName() == "support" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/support', '<i class="fa fa-life-ring"></i> <span>Hỗ trợ trực tuyến</span>') }}</li>
                    <li{% if router.getControllerName() == "support" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/module_item/update/_fanpage_left_right', '<i class="fa fa-circle-o"></i> <span>Facebook</span>') }}</li>
                    <li{% if router.getControllerName() == "support" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/module_item/update/_header_top', '<i class="fa fa-circle-o"></i> <span>Top header</span>') }}</li>
                    <li{% if router.getControllerName() == "support" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/module_item/update/_header_logo_search_cart', '<i class="fa fa-circle-o"></i> <span>Logo - Tìm kiếm - Giỏ hàng</span>') }}</li>
                    <li{% if router.getControllerName() == "support" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/module_item/update/_home_news_hot_index', '<i class="fa fa-circle-o"></i> <span>Tin nổi bật</span>') }}</li>
                </ul>
            </li>
            <li class="treeview{% if router.getControllerName() == "banner_type" or router.getControllerName() == "banner" %} active{% endif %}">
            {{ link_to('#', '<i class="fa fa-picture-o"></i> <span>Banner - Hình ảnh</span> <i class="fa fa-angle-left pull-right"></i>') }}
                <ul class="treeview-menu">
                    <li{% if (router.getControllerName() == "banner_type" and type == 1) %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/banner_type/index/1', '<i class="fa fa-circle-o"></i> <span>Banner tĩnh</span>') }}</li>
                    <li{% if (router.getControllerName() == "banner_type" and type == 2) %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/banner_type/index/2', '<i class="fa fa-circle-o"></i> <span>Slider</span>') }}</li>
                    <li{% if (router.getControllerName() == "banner_type" and type == 3) %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/banner_type/index/3', '<i class="fa fa-circle-o"></i> <span>Đối tác/Quảng cáo</span>') }}</li>
                </ul>
            </li>#}
            <li{% if router.getControllerName() == "banner_type" or router.getControllerName() == "banner" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/banner', '<i class="fa fa-picture-o"></i> <span>Banner - Hình ảnh</span>') }}</li>
            <li{% if router.getControllerName() == "usually_question" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/usually_question', '<i class="fa fa-question-circle"></i> <span>Hỏi đáp</span>') }}</li>
            <li{% if router.getControllerName() == "clip" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/clip', '<i class="fa fa-play-circle-o"></i> <span>Video</span>') }}</li>

            <li{% if router.getControllerName() == "word_item" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/word_item', '<i class="fa fa-file-word-o"></i> <span>Từ ngữ</span>') }}</li>

            <li{% if router.getControllerName() == "member" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/member', '<i class="fa fa-user"></i> <span>Quản lý thành viên</span>') }}</li>

            {#<li{% if router.getControllerName() == "word_item" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/word_item', '<i class="fa fa-file-word-o"></i> <span>Từ ngữ</span>') }}</li>
            {#<li{% if router.getControllerName() == "contact" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/contact', '<i class="fa fa-users"></i> <span>Liên hệ</span>') }}</li>
            <li{% if router.getControllerName() == "news_type" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/word_item', '<i class="fa fa-newspaper-o"></i> <span>Từ ngữ web</span>') }}</li>#}


            {#<li{% if router.getControllerName() == "tags" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/tags', '<i class="fa fa-tags"></i> <span>Tags</span>') }}</li>#}
              {#<li class="treeview{% if router.getControllerName() == "category" or router.getControllerName() == "news" %} active{% endif %}">
                  {{ link_to('#', '<i class="fa fa-newspaper-o"></i> <span>News</span> <i class="fa fa-angle-left pull-right"></i>') }}
                  <ul class="treeview-menu">
                      <li>{{ link_to(ACP_NAME ~ '/category', '<i class="fa fa-circle-o"></i> <span>Danh mục</span>') }}</li>
                      <li>{{ link_to(ACP_NAME ~ '/news', '<i class="fa fa-circle-o"></i> <span>Tin tức</span>') }}</li>

                  </ul>
              </li>



              <li class="treeview{% if router.getControllerName() == "textLink"%} active{% endif %}">
                  {{ link_to('#', '<i class="fa fa-link"></i> <span>Text link</span> <i class="fa fa-angle-left pull-right"></i>') }}
                  <ul class="treeview-menu">
                      <li>{{ link_to(ACP_NAME ~ '/text_link/index/1', '<i class="fa fa-circle-o"></i> <span>Footer 1</span>') }}</li>
                      <li>{{ link_to(ACP_NAME ~ '/text_link/index/2', '<i class="fa fa-circle-o"></i> <span>Footer 2</span>') }}</li>
                      <li>{{ link_to(ACP_NAME ~ '/text_link/index/3', '<i class="fa fa-circle-o"></i> <span>Footer 3</span>') }}</li>
                  </ul>
              </li>

              <li class="treeview{% if router.getControllerName() == "users" or  router.getControllerName() == "permissions" or  router.getControllerName() == "profiles" %} active{% endif %}">
                  {{ link_to('#', '<i class="fa fa-group"></i> <span>Admin Management</span> <i class="fa fa-angle-left pull-right"></i>') }}
                  <ul class="treeview-menu">
                      <li>{{ link_to(ACP_NAME ~ '/users', '<i class="fa fa-users"></i> <span>Users</span>') }}</li>
                      <li>{{ link_to(ACP_NAME ~ '/profiles', '<i class="fa fa-users"></i> <span>Nhóm quản trị</span>') }}</li>
                      <li>{{ link_to(ACP_NAME ~ '/permissions', '<i class="fa fa-exchange"></i> <span>Phân quyền</span>') }}</li>
                  </ul>
              </li>#}
              {#<li{% if router.getControllerName() == "layout" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/layout', '<i class="fa fa-cog"></i> <span>Tùy chỉnh layout</span>') }}</li>#}
              
              {#<li{% if router.getControllerName() == "banner_html" and router.getActionName() == "index" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/banner_html', '<i class="fa fa-picture-o"></i> <span>Banner</span>') }}</li>#}
              <li{% if router.getControllerName() == "system" and router.getActionName() == "ipAcess" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/system/ipAccess', '<i class="fa fa-users"></i> <span>Thống kê truy cập</span>') }}</li>
              {#<li>{{ link_to(ACP_NAME ~ '/index/deleleCache', '<i class="fa fa-trash"></i> <span>Xóa cache</span>') }}</li>#}
              {% if auth.getIdentity()['role'] == 1 %}
                <li class="treeview menu-open{% if router.getControllerName() == "module_list" or router.getControllerName() == "module_group" or router.getControllerName() == "config_kernel" or router.getControllerName() == "config_core" %} active{% endif %}">
                  {{ link_to('#', '<i class="fa fa-cog"></i> <span>Quản lý hệ thống</span> <i class="fa fa-angle-left pull-right"></i>') }}
                  <ul class="treeview-menu" style="display: block">
                      <li{% if router.getControllerName() == "module_list" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/module_item/list', '<i class="fa fa-circle-o"></i> <span>Sửa tên module riêng</span>') }}</li>
                      <li{% if router.getControllerName() == "module_group" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/module_group/setting', '<i class="fa fa-circle-o"></i> <span>Cấu hình module</span>') }}</li>
                      <li{% if router.getControllerName() == "config_kernel" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/config_kernel/config', '<i class="fa fa-circle-o"></i> <span>Cấu hình hệ thống</span>') }}</li>
                      <li{% if router.getControllerName() == "config_core" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/config_core/list', '<i class="fa fa-circle-o"></i> <span>Hướng dẫn cấu hinh</span>') }}</li>
                  </ul>
                </li>
                <li class="treeview menu-open{% if router.getControllerName() == "auth" %} active{% endif %}">
                  {{ link_to('#', '<i class="fa fa-cog"></i> <span>Quản lý Admin</span> <i class="fa fa-angle-left pull-right"></i>') }}
                  <ul class="treeview-menu" style="display: block">
                      <li{% if router.getActionName() == "wordEdit" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/auth/wordEdit', '<i class="fa fa-circle-o"></i> <span>Từ ngữ admin</span>') }}</li>
                      <li{% if router.getControllerName() == "auth" and router.getActionName() == "css" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/auth/css', '<i class="fa fa-circle-o"></i> <span>Css admin login</span>') }}</li>
                  </ul>
                </li>
              {% endif %}
            {#
            {% if auth.getIdentity()['role'] == 1 %}
            <li class="treeview menu-open{% if router.getControllerName() == "setting" %} active{% endif %}">
                {{ link_to('#', '<i class="fa fa-newspaper-o"></i> <span>Thống kê</span> <i class="fa fa-angle-left pull-right"></i>') }}
                <ul class="treeview-menu" style="display: block">
                    <li{% if router.getControllerName() == "system" and router.getActionName() == "ipAcess" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/system/ipAccess', '<i class="fa fa-circle-o"></i> <span>Thống kê truy cập</span>') }}</li>
                    <li{% if router.getControllerName() == "system" and router.getActionName() == "ipAccessAds" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/system/ipAccessAds', '<i class="fa fa-circle-o"></i> <span>Thống kê theo ip</span>') }}</li>
                    <li{% if router.getControllerName() == "system" and router.getActionName() == "linkAccessAds" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/system/linkAccessAds', '<i class="fa fa-circle-o"></i> <span>Thống kê theo link</span>') }}</li>
                </ul>
            </li>
            {% endif %}
            #}
              
              {#<li{% if router.getControllerName() == "domain_pointer" %} class="active"{% endif %}>{{ link_to(ACP_NAME ~ '/domain_pointer/create', '<i class="fa fa-plus-circle"></i> <span>Trỏ tên miền</span>') }}</li>#}
              
              <li><hr></li>
              <li>
                <nav style="padding: 12px 5px 12px 15px;border-left: 3px solid transparent;color: #b8c7ce"><a href="/{{ ACP_NAME }}/users/changePassword"><i class="fa fa-users" style="font-size: 12px"></i></a><a href="/{{ ACP_NAME }}/users/changePassword" style="padding-left: 10px">Đổi mật khẩu</a> | <a href="/{{ ACP_NAME }}/index/logout">Thoát</a></nav>
              </li>
          </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
    <div class="content-wrapper"> {{ content() }}</div>
</div>