{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}
            
            <!-- Custom Tabs -->
            <div class="row" style="margin-bottom:10px">
                <div class="col-md-6">
                    <form action="/{{ ACP_NAME }}/subdomain/search" id="subdomainSearchFrm"  method="post">
                        <div class="input-group">
                          <input type="text" name="keyword" placeholder="{{ word._('_tu_khoa_tim_ten_mien') }}" class="form-control">
                          <span class="input-group-btn">
                            <button id="btn-search-subdomain" class="btn btn-warning btn-flat ladda-button" data-style="slide-left"><span class="ladda-label">Tìm kiếm</span></button>
                          </span>
                        </div>
                    </form>
                </div>
            </div>
            <div id="subdomainListResult" style="margin-bottom: 10px"></div>
            <div class="nav-tabs-custom" id="nav-tabs-list-subdomain">
                <ul class="nav nav-tabs">
                    <li{% if request.get('active') == 'first' or request.get('active') == null %} class="active"{% endif %}><a href="#tab_1" data-toggle="tab">Tất cả</a></li>
                    <li{% if request.get('active') == 'third' %} class="active"{% endif %}><a href="#tab_2" data-toggle="tab">Đã kích hoạt</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane{% if request.get('active') == 'first' or request.get('active') == null %} active{% endif %}" id="tab_1">
                        <div class="panel panel-default">
                            <div class="panel-heading">Danh sách</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12">
                                        {% for key,item in page.items %}
                                            {% set id = item['id'] %}
                                            {% set createId = item['create_id'] %}
                                            {% set name = item['name'] %}
                                            {% set sum_rate = item['sum_rate'] %}
                                            {% set special = item['special'] %}
                                            {% set duplicate = item['duplicate'] %}
                                            {% set domains = item['domain']['name'] %}
                                            {% set display = item['display'] %}
                                            {% if loop.first %}
                                                <div class="table-responsive mailbox-messages">
                                                <table id="example" class="table table-bordered table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th width="7%">Thứ tự</th>
                                                    <th>Tên</th>
                                                    <th width="14%" class="text-center">Xếp hạng</th>
                                                    <th width="30%">Đổi giao diện</th>
                                                    <th width="15%">Người tạo</th>
                                                    <th>Tên miền</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                            {% endif %}
                                            <tr>
                                                <td>{{ key + 1 }}</td>
                                                <td>
                                                    <a href="//{{ name ~ '.' ~ ROOT_DOMAIN }}" target="_blank"{% if special == 'Y' %} style="font-weight:bold;color:#f00" {% endif %}{% if display == 'N' %} class="is-disabled"{% endif %}>{{ name ~ '.' ~ ROOT_DOMAIN }}</a> |
                                                    {% if duplicate != 'Y' %}
                                                    <a href="javascript:;" data-id="{{ id }}" data-name="{{ name ~ '.' ~ ROOT_DOMAIN }}" class="copy-website text-danger" style="font-weight:bold">Nhân bản</a>
                                                    {% else %}
                                                    <span class="bold text-danger">Người tạo web đã khóa nhân bản</span>
                                                    {% endif %}
                                                    {% if display == 'N' %}
                                                    <span class="text-danger bold">| Đã khóa link</span>
                                                    {% endif %}
                                                </td>
                                                <td>
                                                    {% if subdomainRate[id ] is not defined %}
                                                    <label class="total_rate_{{ item['id'] }}" style="color: #f00;">{{ sum_rate }}</label>
                                                    {% endif %}
                                                    <span class="pull-right">
                                                        <div class="star-rating" data-subdomain="{{ id }}" data-user="{{ identity['id'] }}" data-total="{{ sum_rate }}" {% if subdomainRate is defined and subdomainRate[id ] is defined %}data-mark="{{ subdomainRate[id ] }}"{% else %}data-mark="0"{% endif %}>
                                                            <input type="radio" name="rate_active_{{ id }}" class="rating" value="1"{% if subdomainRate is defined and subdomainRate[id ] is defined and subdomainRate[id] == 1 %} checked {% endif %}>
                                                            <input type="radio" name="rate_active_{{ id }}" class="rating" value="2"{% if subdomainRate is defined and subdomainRate[id ] is defined and subdomainRate[id] == 2 %} checked {% endif %}>
                                                            <input type="radio" name="rate_active_{{ id }}" class="rating" value="3"{% if subdomainRate is defined and subdomainRate[id ] is defined and subdomainRate[id] == 3 %} checked {% endif %}>
                                                            <input type="radio" name="rate_active_{{ id }}" class="rating" value="4"{% if subdomainRate is defined and subdomainRate[id ] is defined and subdomainRate[id] == 4 %} checked {% endif %}>
                                                            <input type="radio" name="rate_active_{{ id }}" class="rating" value="5"{% if subdomainRate is defined and subdomainRate[id ] is defined and subdomainRate[id] == 5 %} checked {% endif %}>
                                                        </div>
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/usingMainLayout/' ~ id, 'data-current':sessionSubdomain['subdomain_name'] ~ '.' ~ ROOT_DOMAIN, 'data-copy':name ~ '.' ~ ROOT_DOMAIN, 'Đổi giao diện <span class="text-danger">(Sẽ mất giao diện cũ)</span>', 'class':'change_domain_interface' ) }}
                                                </td>
                                                <td><a href="{{ subdomain_service.getSubdomainCreate(createId) }}"  target="_blank" style="color:#f00;font-weight:bold">{{ subdomain_service.getSubdomainCreate(createId) }}</a></td>
                                                <td>
                                                    {% if domains is not null %}
                                                        {% set listDomain = explode(',', domains) %}
                                                        {% for kd,domain in listDomain %}
                                                            <a href="http://{{ trim(domain) }}" target="_blank" style="font-weight: 600;color: #f00">{{ domain }}</a>{% if kd < count(listDomain) - 1 %}, {% endif %}
                                                        {% endfor %}
                                                    {% endif %}
                                                </td>
                                            </tr>
                                            {% if loop.last %}
                                                </tbody>

                                                </table>
                                                </div>
                                            {% endif %}
                                        {% endfor %}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane{% if request.get('active') == 'third' %} active{% endif %}" id="tab_2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Danh sách</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            {% for key,item in page.items %}
                                                {% set id = item['id'] %}
                                                {% set name = item['name'] %}
                                                {% set active = item['active'] %}
                                                {% set sum_rate = item['sum_rate'] %}
                                                {% set special = item['special'] %}
                                                {% set duplicate = item['duplicate'] %}
                                                {% set domains = item['domain']['name'] %}
                                                {% set display = item['display'] %}
                                                {% if loop.first %}
                                                    <div class="table-responsive mailbox-messages">
                                                    <table id="example" class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th width="7%">Thứ tự</th>
                                                        <th>Tên</th>
                                                        <th width="14%" class="text-center">Xếp hạng{% if identity['subdomain_name'] != '@' %} (do {{ identity['subdomain_name'] ~ '.' ~ ROOT_DOMAIN ~ ' chọn' }}){% endif %}</th>
                                                        <th width="30%">Đổi giao diện</th>
                                                        <th width="15%">Người tạo</th>
                                                        <th>Tên miền</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                {% endif %}
                                                {% if active == 'Y' %}
                                                <tr>
                                                    <td>{{ key + 1 }}</td>
                                                    <td>
                                                        <a href="//{{ name ~ '.' ~ ROOT_DOMAIN }}" target="_blank"{% if special == 'Y' %} style="font-weight:bold;color:#f00" {% endif %}{% if display == 'N' %} class="is-disabled"{% endif %}>{{ name ~ '.' ~ ROOT_DOMAIN }}</a> |
                                                        {% if duplicate != 'Y' %}
                                                        <a href="javascript:;" data-id="{{ id }}" data-name="{{ name ~ '.' ~ ROOT_DOMAIN }}" class="copy-website text-danger" style="font-weight:bold">Nhân bản</a>
                                                        {% else %}
                                                        <span class="bold text-danger">Người tạo web đã khóa nhân bản</span>
                                                        {% endif %}
                                                        {% if display == 'N' %}
                                                        <span class="text-danger bold">| Đã khóa link</span>
                                                        {% endif %}
                                                    </td>
                                                    <td>
                                                        {% if subdomainRate[id ] is not defined %}
                                                            <label class="total_rate_{{ item.id }}" style="color: #f00;">{{ sum_rate }}</label>
                                                        {% endif %}
                                                        <span class="pull-right">
                                                            <div class="star-rating" data-subdomain="{{ id }}" data-user="{{ identity['id'] }}" data-total="{{ sum_rate }}" {% if subdomainRate is defined and subdomainRate[id ] is defined %}data-mark="{{ subdomainRate[id ] }}"{% else %}data-mark="0"{% endif %}>
                                                                <input type="radio" name="rate_all_{{ id }}" class="rating" value="1"{% if subdomainRate is defined and subdomainRate[id ] is defined and subdomainRate[id] == 1 %} checked {% endif %}>
                                                                <input type="radio" name="rate_all_{{ id }}" class="rating" value="2"{% if subdomainRate is defined and subdomainRate[id ] is defined and subdomainRate[id] == 2 %} checked {% endif %}>
                                                                <input type="radio" name="rate_all_{{ id }}" class="rating" value="3"{% if subdomainRate is defined and subdomainRate[id ] is defined and subdomainRate[id] == 3 %} checked {% endif %}>
                                                                <input type="radio" name="rate_all_{{ id }}" class="rating" value="4"{% if subdomainRate is defined and subdomainRate[id ] is defined and subdomainRate[id] == 4 %} checked {% endif %}>
                                                                <input type="radio" name="rate_all_{{ id }}" class="rating" value="5"{% if subdomainRate is defined and subdomainRate[id ] is defined and subdomainRate[id] == 5 %} checked {% endif %}>
                                                            </div>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/usingMainLayout/' ~ id, 'data-current':sessionSubdomain['subdomain_name'] ~ '.' ~ ROOT_DOMAIN, 'data-copy':name ~ '.' ~ ROOT_DOMAIN, 'Đổi giao diện <span class="text-danger">(Sẽ mất giao diện cũ)</span>', 'class':'change_domain_interface' ) }}
                                                    </td>
                                                    <td> <a href="{{ subdomain_service.getSubdomainCreate(createId) }}"  target="_blank" style="color:#f00;font-weight:bold">{{ subdomain_service.getSubdomainCreate(createId) }}</a></td>
                                                    <td>
                                                        {% if domains is not null %}
                                                            {% set listDomain = explode(',', domains) %}
                                                            {% for kd,domain in listDomain %}
                                                                <a href="http://{{ trim(domain) }}" target="_blank" style="font-weight: 600;color: #f00">{{ domain }}</a>{% if kd < count(listDomain) - 1 %}, {% endif %}
                                                            {% endfor %}
                                                        {% endif %}
                                                    </td>
                                                </tr>
                                                {% endif %}
                                                {% if loop.last %}
                                                    </tbody>

                                                    </table>
                                                    </div>
                                                {% endif %}
                                            {% endfor %}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">{{ partial('partials/pagination') }}</div>
        </div>
    </div>
</section>

<div class="modal modal fade" id="modalFormCopyWebsite" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nhân bản website</h4>
        </div>
        <div class="modal-body">
            <div id="copy-website-content">Bạn muốn sao chép toàn bộ dữ liệu của website <a href="#" target="_blank" id="website-copy-name" class="text-danger">#</a> cho website <a href="http://{{ session_subdomain['host'] }}" target="_blank" class="text-danger">{{ session_subdomain['host'] }}</a>?</div>
        </div>
        <div class="modal-footer" style="text-align:left!important">
            <button type="submit" class="btn btn-primary" id="btn-create-website" name="ok">OK</button>
            {{ link_to("javascript:;", "Đóng", "class": "btn btn-danger", "data-dismiss":"modal") }}
        </div>
      </div>
    </div>
</div>