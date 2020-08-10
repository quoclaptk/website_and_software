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
                  {#<li class="active"><a href="#tab_1" data-toggle="tab">Nổi bật</a></li>#}
                  <li class="active"><a href="#tab_2" data-toggle="tab">Đã kích hoạt</a></li>
                  <li><a href="#tab_3" data-toggle="tab">Mới tạo</a></li>
                  <li><a href="#tab_4" data-toggle="tab">Tất cả</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_2">
                        <div class="tab-pane active" id="tab_2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Danh sách</div>
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-xs-12">
                                            {% for key,item in subdomains['active'] %}
                                                {% if loop.first %}
                                                    <div class="table-responsive mailbox-messages">
                                                    <table id="example" class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th width="7%">Thứ tự</th>
                                                        <th>Tên</th>
                                                        <th width="14%" class="text-center">Xếp hạng</th>
                                                        <th width="30%">Đổi giao diện</th>
                                                        <th>Tên miền</th>
                                                        {#<th>Xem thử</th>#}
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                {% endif %}
                                                <tr>
                                                    <td>{{ key + 1 }}</td>
                                                    <td>
                                                        <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank"{% if item.special == 'Y' %} style="font-weight:bold;color:#f00" {% endif %}>{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a> |
                                                        {% if item.duplicate != 'Y' %}
                                                        <a href="javascript:;" data-id="{{ item.id }}" data-name="{{ item.name ~ '.' ~ ROOT_DOMAIN }}" class="copy-website text-danger" style="font-weight:bold">Nhân bản</a>
                                                        {% endif %}
                                                    </td>
                                                    <td>
                                                        <label class="total_rate_{{ item.id }}" style="color: #f00;">{{ item.rate }}</label>
                                                        <span class="pull-right">
                                                            <div class="star-rating" data-subdomain="{{ item.id }}" data-user="{{ identity['id'] }}" data-total="{{ item.rate }}" {% if subdomainRate is defined and subdomainRate[item.id ] is defined %}data-mark="{{ subdomainRate[item.id ] }}"{% else %}data-mark="0"{% endif %}>
                                                                <input type="radio" name="rate_active_{{ item.id }}" class="rating" value="1"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 1 %} checked {% endif %}>
                                                                <input type="radio" name="rate_active_{{ item.id }}" class="rating" value="2"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 2 %} checked {% endif %}>
                                                                <input type="radio" name="rate_active_{{ item.id }}" class="rating" value="3"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 3 %} checked {% endif %}>
                                                                <input type="radio" name="rate_active_{{ item.id }}" class="rating" value="4"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 4 %} checked {% endif %}>
                                                                <input type="radio" name="rate_active_{{ item.id }}" class="rating" value="5"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 5 %} checked {% endif %}>
                                                            </div>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/usingMainLayout/' ~ item.id, 'Đổi giao diện <sơma<span class="text-danger">(Sẽ mất giao diện cũ)</span>', 'class':'change_domain_interface' ) }}
                                                    </td>
                                                    <td>
                                                        {% if item.domains is defined %}
                                                            {% for k, domain in item.domains %}
                                                                <a href="http://{{ domain['name'] }}" target="_blank" style="font-weight: 600;color: #f00">{{ domain['name'] }}</a>{% if k < (count(item.domains) - 1) %}, {% endif %} 
                                                            {% endfor %}
                                                        {% endif %}
                                                    </td>
                                                    {#
                                                    <td>
                                                        <a href="//{{ session_subdomain['host']  ~ '/' ~ item.name }}" target="_blank">{{ session_subdomain['host']  ~ '/' ~ item.name }}</a>
                                                    </td>
                                                    #}
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
                    </div>
                     <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_3">
                        <div class="tab-pane active" id="tab_3">
                            <div class="panel panel-default">
                                <div class="panel-heading">Danh sách</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            {% for key,item in subdomains['list'] %}
                                                {% if loop.first %}
                                                    <div class="table-responsive mailbox-messages">
                                                    <table id="example" class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th width="7%">Thứ tự</th>
                                                        <th>Tên</th>
                                                        <th width="14%" class="text-center">Xếp hạng</th>
                                                        <th width="30%">Đổi giao diện</th>
                                                        <th>Tên miền</th>
                                                        {#<th>Xem thử</th>#}
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                {% endif %}
                                                <tr>
                                                    <td>{{ key + 1 }}</td>
                                                    <td>
                                                        <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank"{% if item.special == 'Y' %} style="font-weight:bold;color:#f00" {% endif %}>{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a> |
                                                        {% if item.duplicate != 'Y' %}
                                                        <a href="javascript:;" data-id="{{ item.id }}" data-name="{{ item.name ~ '.' ~ ROOT_DOMAIN }}" class="copy-website text-danger" style="font-weight:bold">Nhân bản</a>
                                                        {% endif %}
                                                    </td>
                                                    <td>
                                                        <label class="total_rate_{{ item.id }}" style="color: #f00;">{{ item.rate }}</label>
                                                        <span class="pull-right">
                                                            <div class="star-rating" data-subdomain="{{ item.id }}" data-user="{{ identity['id'] }}" data-total="{{ item.rate }}" {% if subdomainRate is defined and subdomainRate[item.id ] is defined %}data-mark="{{ subdomainRate[item.id ] }}"{% else %}data-mark="0"{% endif %}>
                                                                <input type="radio" name="rate_list_{{ item.id }}" class="rating" value="1"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 1 %} checked {% endif %}>
                                                                <input type="radio" name="rate_list_{{ item.id }}" class="rating" value="2"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 2 %} checked {% endif %}>
                                                                <input type="radio" name="rate_list_{{ item.id }}" class="rating" value="3"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 3 %} checked {% endif %}>
                                                                <input type="radio" name="rate_list_{{ item.id }}" class="rating" value="4"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 4 %} checked {% endif %}>
                                                                <input type="radio" name="rate_list_{{ item.id }}" class="rating" value="5"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 5 %} checked {% endif %}>
                                                            </div>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/usingMainLayout/' ~ item.id, 'Đổi giao diện <sơma<span class="text-danger">(Sẽ mất giao diện cũ)</span>', 'class':'change_domain_interface' ) }}
                                                    </td>
                                                    <td>
                                                        {% if item.domains is defined %}
                                                            {% for k, domain in item.domains %}
                                                                <a href="http://{{ domain['name'] }}" target="_blank" style="font-weight: 600;color: #f00">{{ domain['name'] }}</a>{% if k < (count(item.domains) - 1) %}, {% endif %} 
                                                            {% endfor %}
                                                        {% endif %}
                                                    </td>
                                                    {#
                                                    <td>
                                                        <a href="//{{ session_subdomain['host']  ~ '/' ~ item.name }}" target="_blank">{{ session_subdomain['host']  ~ '/' ~ item.name }}</a>
                                                    </td>
                                                    #}
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
                    </div>
                     <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_4">
                        <div class="tab-pane active" id="tab_3">
                            <div class="panel panel-default">
                                <div class="panel-heading">Danh sách</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            {% for key,item in subdomains['all'] %}
                                                {% if loop.first %}
                                                    <div class="table-responsive mailbox-messages">
                                                    <table id="example" class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th width="7%">Thứ tự</th>
                                                        <th>Tên</th>
                                                        <th width="14%" class="text-center">Xếp hạng</th>
                                                        <th width="30%">Đổi giao diện</th>
                                                        <th>Tên miền</th>
                                                        {#<th>Xem thử</th>#}
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                {% endif %}
                                                <tr>
                                                    <td>{{ key + 1 }}</td>
                                                    <td>
                                                        <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank"{% if item.special == 'Y' %} style="font-weight:bold;color:#f00" {% endif %}>{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a> |
                                                        {% if item.duplicate != 'Y' %}
                                                        <a href="javascript:;" data-id="{{ item.id }}" data-name="{{ item.name ~ '.' ~ ROOT_DOMAIN }}" class="copy-website text-danger" style="font-weight:bold">Nhân bản</a>
                                                        {% endif %}
                                                    </td>
                                                    <td>
                                                        <label class="total_rate_{{ item.id }}" style="color: #f00;">{{ item.rate }}</label>
                                                        <span class="pull-right"><div class="star-rating" data-subdomain="{{ item.id }}" data-user="{{ identity['id'] }}" data-total="{{ item.rate }}" {% if subdomainRate is defined and subdomainRate[item.id ] is defined %}data-mark="{{ subdomainRate[item.id ] }}"{% else %}data-mark="0"{% endif %}>
                                                                <input type="radio" name="rate_all_{{ item.id }}" class="rating" value="1"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 1 %} checked {% endif %}>
                                                                <input type="radio" name="rate_all_{{ item.id }}" class="rating" value="2"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 2 %} checked {% endif %}>
                                                                <input type="radio" name="rate_all_{{ item.id }}" class="rating" value="3"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 3 %} checked {% endif %}>
                                                                <input type="radio" name="rate_all_{{ item.id }}" class="rating" value="4"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 4 %} checked {% endif %}>
                                                                <input type="radio" name="rate_all_{{ item.id }}" class="rating" value="5"{% if subdomainRate is defined and subdomainRate[item.id ] is defined and subdomainRate[item.id] == 5 %} checked {% endif %}>
                                                            </div>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/usingMainLayout/' ~ item.id, 'Đổi giao diện <sơma<span class="text-danger">(Sẽ mất giao diện cũ)</span>', 'class':'change_domain_interface' ) }}
                                                    </td>
                                                    <td>
                                                        {% if item.domains is defined %}
                                                            {% for k, domain in item.domains %}
                                                                <a href="http://{{ domain['name'] }}" target="_blank" style="font-weight: 600;color: #f00">{{ domain['name'] }}</a>{% if k < (count(item.domains) - 1) %}, {% endif %} 
                                                            {% endfor %}
                                                        {% endif %}
                                                    </td>
                                                    {#
                                                    <td>
                                                        <a href="//{{ session_subdomain['host']  ~ '/' ~ item.name }}" target="_blank">{{ session_subdomain['host']  ~ '/' ~ item.name }}</a>
                                                    </td>
                                                    #}
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
                    </div>
                     <!-- /.tab-pane -->
                </div>
                  <!-- nav-tabs-custom -->
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