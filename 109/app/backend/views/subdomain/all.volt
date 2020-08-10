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
                                        {% for key,item in subdomains['all'].items %}
                                            {% if item.sum_rate > 0 %}
                                                {% set sum_rate = item.sum_rate %}
                                            {% else %}
                                                {% set sum_rate = 0 %}
                                            {% endif %}
                                            {% set name = item.name %}
                                            {% set createId = item.create_id %}
                                            {% set display = item.display %}
                                            {% if loop.first %}
                                                <div class="table-responsive mailbox-messages">
                                                <table id="example" class="table table-bordered table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th width="7%">Thứ tự</th>
                                                    <th>Tên</th>
                                                    <th width="14%" class="text-center">Xếp hạng</th>
                                                    <th width="30%">Đổi giao diện</th>
                                                    <th width="19%">Người tạo</th>
                                                    <th>Tên miền</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                            {% endif %}
                                            <tr>
                                                <td>{{ key + 1 }}</td>
                                                <td>
                                                    <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank"{% if item.special == 'Y' %} style="font-weight:bold;color:#f00" {% endif %}{% if display == 'N' %} class="is-disabled"{% endif %}>{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a> |
                                                    {% if item.duplicate != 'Y' %}
                                                    <a href="javascript:;" data-id="{{ item.id }}" data-name="{{ item.name ~ '.' ~ ROOT_DOMAIN }}" class="copy-website text-danger" style="font-weight:bold">Nhân bản</a>
                                                    {% else %}
                                                    <span class="bold text-danger">Người tạo web đã khóa nhân bản</span>
                                                    {% endif %}
                                                    {% if display == 'N' %}
                                                    <span class="text-danger bold">| Đã khóa link</span>
                                                    {% endif %}
                                                </td>
                                                <td>
                                                    <label class="total_rate_{{ item.id }}" style="color: #f00;">{{ sum_rate }}</label>
                                                    <span class="pull-right"><div class="star-rating" data-subdomain="{{ item.id }}" data-user="{{ identity['id'] }}" data-total="{{ sum_rate }}" {% if subdomainRate is defined and subdomainRate[item.id ] is defined %}data-mark="{{ subdomainRate[item.id ] }}"{% else %}data-mark="0"{% endif %}>
                                                            <input type="radio" name="rate_all_{{ item.id }}" class="rating" value="1"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 1 %} checked {% endif %}>
                                                            <input type="radio" name="rate_all_{{ item.id }}" class="rating" value="2"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 2 %} checked {% endif %}>
                                                            <input type="radio" name="rate_all_{{ item.id }}" class="rating" value="3"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 3 %} checked {% endif %}>
                                                            <input type="radio" name="rate_all_{{ item.id }}" class="rating" value="4"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 4 %} checked {% endif %}>
                                                            <input type="radio" name="rate_all_{{ item.id }}" class="rating" value="5"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 5 %} checked {% endif %}>
                                                        </div>
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/usingMainLayout/' ~ item.id, 'data-current':sessionSubdomain['subdomain_name'] ~ '.' ~ ROOT_DOMAIN, 'data-copy':name ~ '.' ~ ROOT_DOMAIN, 'Đổi giao diện <span class="text-danger">(Sẽ mất giao diện cũ)</span>', 'class':'change_domain_interface' ) }}
                                                </td>
                                                <td><a href="{{ subdomain_service.getSubdomainCreate(createId) }}"  target="_blank" style="color:#f00;font-weight:bold">{{ subdomain_service.getSubdomainCreate(createId) }}</a></td>
                                                <td>
                                                    <a href="http://{{ item.domain_name }}" target="_blank" style="font-weight: 600;color: #f00">{{ item.domain_name }}</a>
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
                                <div class="text-center">
                                    <ul class="pagination">
                                        {% for key in 1..subdomains['all'].total_pages %}
                                            <li{% if key == page_current %} class="active"{% endif %}><a href="/{{ ACP_NAME ~ '/' ~ this.view.getControllerName() | lower ~ '/' ~ this.view.getActionName() | lower ~ '?page=' ~ key ~ '&active=third' }}">{{ key }}</a></li>
                                        {% endfor %}
                                      </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="tab-pane{% if request.get('active') == 'first' %} active{% endif %}" id="tab_2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Danh sách</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            {% for key,item in subdomains['active'].items %}
                                                {% if item.sum_rate > 0 %}
                                                    {% set sum_rate = item.sum_rate %}
                                                {% else %}
                                                    {% set sum_rate = 0 %}
                                                {% endif %}
                                                {% set name = item.name %}
                                                {% set createId = item.create_id %}
                                                {% set display = item.display %}
                                                {% if loop.first %}
                                                    <div class="table-responsive mailbox-messages">
                                                    <table id="example" class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th width="7%">Thứ tự</th>
                                                        <th>Tên</th>
                                                        <th width="14%" class="text-center">Xếp hạng</th>
                                                        <th width="30%">Đổi giao diện</th>
                                                        <th width="15%">Người tạo web</th>
                                                        <th>Tên miền</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                {% endif %}
                                                <tr>
                                                    <td>{{ key + 1 }}</td>
                                                    <td>
                                                        <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank"{% if item.special == 'Y' %} style="font-weight:bold;color:#f00" {% endif %}{% if display == 'N' %} class="is-disabled"{% endif %}>{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a> |
                                                        {% if item.duplicate != 'Y' %}
                                                        <a href="javascript:;" data-id="{{ item.id }}" data-name="{{ item.name ~ '.' ~ ROOT_DOMAIN }}" class="copy-website text-danger" style="font-weight:bold">Nhân bản</a>
                                                        {% else %}
                                                        <span class="bold text-danger">Người tạo web đã khóa nhân bản</span>
                                                        {% endif %}
                                                        {% if display == 'N' %}
                                                        <span class="text-danger bold">| Đã khóa link</span>
                                                        {% endif %}
                                                    </td>
                                                    <td>
                                                        <label class="total_rate_{{ item.id }}" style="color: #f00;">{{ sum_rate }}</label>
                                                        <span class="pull-right">
                                                            <div class="star-rating" data-subdomain="{{ item.id }}" data-user="{{ identity['id'] }}" data-total="{{ sum_rate }}" {% if subdomainRate is defined and subdomainRate[item.id ] is defined %}data-mark="{{ subdomainRate[item.id ] }}"{% else %}data-mark="0"{% endif %}>
                                                                <input type="radio" name="rate_active_{{ item.id }}" class="rating" value="1"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 1 %} checked {% endif %}>
                                                                <input type="radio" name="rate_active_{{ item.id }}" class="rating" value="2"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 2 %} checked {% endif %}>
                                                                <input type="radio" name="rate_active_{{ item.id }}" class="rating" value="3"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 3 %} checked {% endif %}>
                                                                <input type="radio" name="rate_active_{{ item.id }}" class="rating" value="4"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 4 %} checked {% endif %}>
                                                                <input type="radio" name="rate_active_{{ item.id }}" class="rating" value="5"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 5 %} checked {% endif %}>
                                                            </div>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/usingMainLayout/' ~ item.id, 'data-current':sessionSubdomain['subdomain_name'] ~ '.' ~ ROOT_DOMAIN, 'data-copy':name ~ '.' ~ ROOT_DOMAIN, 'Đổi giao diện <span class="text-danger">(Sẽ mất giao diện cũ)</span>', 'class':'change_domain_interface' ) }}
                                                    </td>
                                                    <td><a href="{{ subdomain_service.getSubdomainCreate(createId) }}"  target="_blank" style="color:#f00;font-weight:bold">{{ subdomain_service.getSubdomainCreate(createId) }}</a></td>
                                                    <td>
                                                        <a href="http://{{ item.domain_name }}" target="_blank" style="font-weight: 600;color: #f00">{{ item.domain_name }}</a>
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
                                    <div class="text-center">
                                        <ul class="pagination">
                                            {% for key in 1..subdomains['active'].total_pages %}
                                                <li{% if key == page_current %} class="active"{% endif %}><a href="/{{ ACP_NAME ~ '/' ~ this.view.getControllerName() | lower ~ '/' ~ this.view.getActionName() | lower ~ '?page=' ~ key ~ '&active=first' }}">{{ key }}</a></li>
                                            {% endfor %}
                                          </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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