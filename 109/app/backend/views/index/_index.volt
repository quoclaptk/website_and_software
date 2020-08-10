{% if appType == 'gerenal' %}
    <section class="content">
        {{ flashSession.output() }}
        <div class="form-group">
            {#<a href="javascript:;" class="btn btn-primary create_website" id="create_website" data-url="{{ HTTP_HOST }}/{{ ACP_NAME }}/subdomain/create">Tạo web</a>#}
            {% if subdomain.other_interface != 'Y' %}
            <a href="{{ HTTP_HOST }}/{{ ACP_NAME }}/subdomain/all" class="btn btn-primary">Tạo web</a>
            {% endif %}
            <select class="form-control" onchange="window.open(this.value,'_self');" style="display:inline-block;max-width: 140px">
                <option value="{{ HTTP_HOST }}/{{ ACP_NAME }}">Ngày hết hạn</option>
                <option value="{{ HTTP_HOST }}/{{ ACP_NAME }}/index?expired=desc" {% if request.get('expired') == 'desc' %}selected{% endif %}>Dài nhất</option>
                <option value="{{ HTTP_HOST }}/{{ ACP_NAME }}/index?expired=asc" {% if request.get('expired') == 'asc' %}selected{% endif %}>Ngắn nhất</option>
            </select>
            <select class="form-control" onchange="window.open(this.value,'_self');" style="display:inline-block;max-width: 100px">
                <option value="{{ HTTP_HOST }}/{{ ACP_NAME }}">Số dư</option>
                <option value="{{ HTTP_HOST }}/{{ ACP_NAME }}/index?balance=desc" {% if request.get('balance') == 'desc' %}selected{% endif %}>Nhiều nhất</option>
                <option value="{{ HTTP_HOST }}/{{ ACP_NAME }}/index?balance=asc" {% if request.get('balance') == 'asc' %}selected{% endif %}>Ít nhất</option>
            </select>
            <div style="display:inline-block">
                <input type="text" id="subdomain_filter" class="form-control" placeholder="Tìm kiếm..." style="min-width: 200px">
            </div>
            <div class="pull-right" style="display:inline-block;padding-left:3px">
                <input type="text" name="copyright_subdomain" value="{{ subdomainChild.copyright_link }}" data-type="copyright_link" data-id="{{ session_subdomain['subdomain_id'] }}" class="form-control" placeholder="Link bản quyền">
            </div>
            <div class="pull-right" style="display:inline-block">
                <input type="text" name="copyright_subdomain" value="{{ subdomainChild.copyright_name }}" data-type="copyright_name" data-id="{{ session_subdomain['subdomain_id'] }}" class="form-control" placeholder="Text bản quyền">
            </div>
        </div>
        {% if notiDomainExpire|length > 0 %}
        <div class="row" id="notiDomainExpire">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Danh sách Website sắp hết hạn (còn 20 đến trên 1 ngày). Nhưng website đã hết hạn không hiển thị</div>

                    <div class="panel-body">
                        <div class="table-responsive mailbox-messages">
                           <div style="color:red">Danh sách Website sắp hết hạn (còn 20 đến trên 1 ngày). Nhưng website đã hết hạn không hiển thị</div>
                            <table id="table-subdomain-expired" class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Tên miền</th>
                                    <th>Người tạo</th>
                                    <th>Ngày còn lại</th>
                                    <th>Hotline</th>
                                    <th>Số điện thoại</th>
                                    <th>Ghi chú</th>
                                </tr>
                                </thead>

                                <tbody id="subdomain_expired">
                                    {% for index, item in notiDomainExpire %}
                                    {% set expired = (strtotime(date('Y-m-d 00:00:00', strtotime(item.expired_date))) - strtotime(date('Y-m-d 00:00:00', currentDate)))/60/60/24 %}
                                    {% if item.name == '@' %}
                                        {% set subdomain = ROOT_DOMAIN %}
                                    {% else %}
                                        {% set subdomain = item.name ~ '.' ~ ROOT_DOMAIN %}
                                    {% endif %}
                                        <tr>
                                            <td>{{loop.index}}</td>
                                            <td><a href="http://{{ subdomain }}" target="_blank">{{ subdomain }}</a></td>
                                            <td>
                                            {% for domain in item.domain %}
                                                {% set domainName = domain.name  %}
                                                {% if loop.last  %}
                                                    <a href="http://{{ domainName }}" target="_blank" style="font-weight: 600;color: #f00">{{ domainName }} 
                                                {% else %}
                                                    <a href="http://{{ domainName }}" target="_blank" style="font-weight: 600;color: #f00">{{ domainName ~ "," }} 
                                                {% endif %}
                                            {% endfor %}
                                            </td>
                                            <td><a href="{{ subdomain_service.getSubdomainCreate(item.create_id) }}"  target="_blank" style="color:#f00;font-weight:bold">{{ subdomain_service.getSubdomainCreate(item.create_id) }}</a></td>
                                            <td style="color:red">{{ expired }}</td>
                                            <td>{{item.settingVi.hotline}}</td>
                                            <td>{{item.settingVi.phone}}</td>
                                            <td><textarea rows="1" data-id="{{ item.id }}" class="form-control update-subdomain-note">{{ item.note }}</textarea></td>
                                        </tr>
                                    {% endfor %}
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {% endif %}
        <div class="row">

            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">Danh sách website</div>

                    <div class="panel-body">
                        <div class="table-responsive mailbox-messages">
                            <table id="table-subdomain" class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center" width="17%">Tên miền phụ</th>
                                    <th width="18%" class="text-center">Xếp hạng</th>
                                    <th class="text-center" width="10%">Ghi chú</th>
                                    {% if auth.getIdentity()['role'] == 1 %}
                                    <th class="text-center" width="15%">Thống kê</th>
                                    <th width="10%" class="text-center">Bản quyền</th>
                                    {% endif %}
                                    <th width="15%" class="text-center">Tên miền</th>
                                    <th width="15%">User quản lý</th>
                                    <th width="13%" class="text-center"></th>
                                    <th width="7%" class="text-center"></th>
                                    <th width="20%" class="text-center"></th>
                                    {% if auth.getIdentity()['role'] == 1 %}
                                    <th width="5%" class="text-center">Xóa</th>
                                    {% endif %}
                                </tr>
                                </thead>

                                <tbody id="subdomain_list">
                                {% for sort,item in listSubdomain %}
                                    {% set layout_id = item['layout_id'] %}
                                    {% if item['id'] == session_subdomain['subdomain_id'] %}
                                    {% set class = ' btn-danger' %}
                                    {% else %}
                                    {% set class = '' %}
                                    {% endif %}
                                    {% if item['name'] == '@' %}
                                        {% set subdomain = ROOT_DOMAIN %}
                                    {% else %}
                                        {% set subdomain = item['name'] ~ '.' ~ ROOT_DOMAIN %}
                                    {% endif %}
                                    {% if sort == 0 %}
                                        {% set title_login = 'Đăng nhập' %}
                                    {% else %}
                                        {% set title_login = 'Đăng nhập' %}
                                    {% endif %}
                                    {% if item['domain'] is defined %}
                                        {% set domain = item['domain'] %}
                                    {% else %}
                                        {% set domain = '' %}
                                    {% endif  %}
                                    {% set create_at = date('d/m/Y', strtotime(item['create_at'])) %}

                                    {% if item['active_date'] != '0000-00-00 00:00:00' %}
                                    {% set active_date = date('d/m/Y', strtotime(item['active_date'])) %}
                                    {% else %}
                                    {% set active_date = '--' %}
                                    {% endif %}

                                    {% if item['expired_date'] != '0000-00-00 00:00:00' %}
                                        {% set expired_date = date('d/m/Y', strtotime(item['expired_date'])) %}
                                    {% else %}
                                        {% set expired_date = '' %}
                                    {% endif %}

                                    {% if item['create_name'] == '@' %}
                                        {% set create_name = DOMAIN %}
                                    {% else %}
                                        {% set create_name = item['create_name'] ~ '.' ~ DOMAIN %}
                                    {% endif %}

                                    {% set sum_rate = item['sum_rate'] %}
                                    <tr>
                                        <td>
                                            <p><a href="http://{{ subdomain }}" target="_blank">{{ subdomain }}</a></p>
                                            <p>
                                            {{ link_to(ACP_NAME ~ '/index/loginSubdomain/' ~ item['id'] , title_login, 'class':'btn btn-sm btn-primary' ~ class) }}</p>
                                            {% if sort > 0 %}
                                             <div>{{ link_to('javascript:;', 'Đổi mật khẩu', 'data-url':'/' ~ ACP_NAME ~ '/users/changePasswordUser/' ~ item['id'], 'class':'btn btn-sm btn-warning change-pass-user' ) }}</div>
                                            {% endif %}
                                            {% if auth.getIdentity()['role'] == 1 or sort == 0 %}
                                            <div class="balance_user" style="margin-top: 10px">
                                                <div class="row">
                                                    <div class="col-md-5"><strong{% if auth.getIdentity()['role'] == 1 %} style="display: block;margin-top:7px"{% endif %}>Số dư:</strong></div>
                                                    <div class="col-md-7">{% if auth.getIdentity()['role'] == 1 %}<input type="text" name="user_balance_txt" value="{{ item['balance'] }}" data-id="{{ item['id'] }}" class="form-control">{% else %}<strong style="color:#f00">{{ item['balance'] }}</strong>{% endif %}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="user_history">
                                                <a href="javascript:;" class="btn btn-sm btn-info btn-user-history" data-id="{{ item['id'] }}" style="margin-top:10px"><i class="fa fa-history" aria-hidden="true"></i> Lịch sử</a>
                                                {% if sort == 0 %}
                                                <a href="/{{ ACP_NAME }}/users/nganluongPayment" class="btn btn-sm btn-success btn-user-transfer" data-id="{{ item['id'] }}" style="margin-top:10px"><i class="fa fa-usd" aria-hidden="true"></i> Nạp tiền</a>
                                                {% endif %}
                                            </div>
                                            {% endif %}
                                        </td>
                                        <td class="text-center">
                                            <span>
                                                <div class="star-rating" data-subdomain="{{ item['id'] }}" data-user="{{ identity['id'] }}" data-total="{{ sum_rate }}" {% if subdomainRate is defined and subdomainRate[item['id'] ] is defined %}data-mark="{{ subdomainRate[item['id'] ] }}"{% else %}data-mark="0"{% endif %}>
                                                    <input type="radio" name="rate_active_{{ item['id'] }}" class="rating" value="1"{% if subdomainRate is defined and subdomainRate[item['id'] ] is defined and subdomainRate[item['id']] == 1 %} checked {% endif %}>
                                                    <input type="radio" name="rate_active_{{ item['id'] }}" class="rating" value="2"{% if subdomainRate is defined and subdomainRate[item['id'] ] is defined and subdomainRate[item['id']] == 2 %} checked {% endif %}>
                                                    <input type="radio" name="rate_active_{{ item['id'] }}" class="rating" value="3"{% if subdomainRate is defined and subdomainRate[item['id'] ] is defined and subdomainRate[item['id']] == 3 %} checked {% endif %}>
                                                    <input type="radio" name="rate_active_{{ item['id'] }}" class="rating" value="4"{% if subdomainRate is defined and subdomainRate[item['id'] ] is defined and subdomainRate[item['id']] == 4 %} checked {% endif %}>
                                                    <input type="radio" name="rate_active_{{ item['id'] }}" class="rating" value="5"{% if subdomainRate is defined and subdomainRate[item['id'] ] is defined and subdomainRate[item['id']] == 5 %} checked {% endif %}>
                                                </div>
                                            </span>
                                        </td>
                                        <td>
                                        	{% if sort > 0 %}
                                            	<textarea data-id="{{ item['id'] }}" name="note[{{ item['id'] }}]" class="form-control update-subdomain-note" rows="3">{{ item['note'] }}</textarea>
                                            {% endif %}
                                        </td>

                                        {% if auth.getIdentity()['role'] == 1 %}
                                        <td>
                                            <div>Web đã tạo: <strong style="color: #f00">{{ item['count_child'] }}</strong></div>
                                            <div>Web đã kích hoạt: <strong style="color: #f00">{{ item['count_child_active'] }}</strong></div>
                                            {% if sort > 0 %}<div>Người tạo: <strong style="color: #000">{{ create_name }}</strong></div>{% endif %}
                                            {% if sort > 0 %}
                                            <div>
                                                <p>Tên miền quản lý: <strong style="color:#f00">{{ item['username'] }}</strong>{% for i in item['list_username'] %}<strong style="color:#f00">, {{ i }}</strong>{% endfor %}</p>
                                                <a href="javascript:;" class="btn btn-sm btn-primary add-subdomain-user" data-id="{{ item['id'] }}">Thêm</a>
                                            </div>
                                            {% endif %}
                                        </td>
                                        <td align="center">
                                            {% if sort > 0 %}
                                                {% if item['copyright'] == 'Y' %}
                                                    {{ link_to(ACP_NAME ~ '/subdomain/unCopyright/' ~ item['id'], '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                {% else %}
                                                    {{ link_to(ACP_NAME ~ '/subdomain/copyright/' ~ item['id'], '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                {% endif %}
                                            {% endif %}
                                        </td>
                                        {% endif %}

                                        <td>
                                            {% if domain != '' %}
                                            <table class="table">
                                                <tbody>
                                                {% for key,value in domain %}
                                                    <tr>
                                                        {#<th scope="row">{{ key + 1 }}</th>#}
                                                        <td><a href="http://{{ value['name'] }}" target="_blank">{{ value['name'] }}</a></td>
                                                        <td>{{ link_to(ACP_NAME ~ '/domain/delete/' ~ value['id'], '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                                    </tr>
                                                {% endfor %}
                                                </tbody>
                                            </table>
                                            {% else %}<p class="text-center">--</p>{% endif %}
                                            {% if sort > 0 %}
                                                <p class="text-center">{{ link_to('javascript:;', 'Thêm tên miền', 'data-url':'/' ~ ACP_NAME ~ '/subdomain/addDomain/' ~ item['id'], 'class':'btn btn-sm btn-primary add-domain' ) }}</p>
                                            {% endif %}
                                            <a href="https://docs.google.com/document/d/1VLTErxIMMd-sYXSGgNx3tr1g9s6uW2BAB_djwue2C7g/edit" target="_blank" style="display:block;margin-top:10px;text-decoration: underline;">Hướng dẫn trỏ tên miền</a>
                                        </td>
                                        <td><strong style="color:#f00">{{ item['username'] }}</strong>{% for i in item['list_username_manage'] %}<strong style="color:#f00">, {{ i }}</strong>{% endfor %}
                                            <p></p>
                                            <div>
                                                <a href="javascript:;" class="btn btn-sm btn-primary add-subdomain-user-manage" data-id="{{ item['id'] }}">Thêm người quản lý</a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <p class="red bold">Ngày tạo: {{ create_at }}</p>
                                            <p class="red bold">{% if active_date != '--' %}Ngày kích hoạt: {% endif %}{{ active_date }}</p>
                                            {% if auth.getIdentity()['role'] == 1 and expired_date != '' %}
                                            <div class="form-group">
                                                <div class="input-group date">
                                                  <span class="red bold">Ngày hết hạn: </span>  
                                                  <input type="text" class="form-control pull-right expired_date" value="{{ expired_date }}" data-id="{{ item['id'] }}">
                                                </div>
                                            </div>
                                            {% else %}
                                            <span class="red bold">Ngày hết hạn: {{ expired_date }}</span>  
                                            {% endif %}
                                        </td>
                                        <td class="text-center">
                                        {% if sort > 0 %}
                                            {% if item['active'] == 'Y' %}
                                                {{ link_to('javascript:;', 'Gia hạn ngày sử dụng', 'data-url':'/' ~ ACP_NAME ~ '/subdomain/addExpiredDate/' ~ item['id'], 'class':'btn btn-sm btn-success add-expired-domain') }}
                                            {% else %}
                                                <p>--</p>
                                            {% endif %}
                                        {% else %}--{% endif %}
                                        <p></p>
                                        {% if sort > 0 %}
                                            {% if item['suspended'] == 'N' %}
                                                {{ link_to(ACP_NAME ~ '/subdomain/suspended/' ~ item['id'], 'Hiện thông báo khóa web', 'class':'btn btn-sm btn-primary') }}
                                            {% else %}
                                                {{ link_to(ACP_NAME ~ '/subdomain/unSuspended/' ~ item['id'], 'Đang hiện thông báo khóa web', 'class':'btn btn-sm btn-danger') }}
                                            {% endif %}
                                        {% else %}--{% endif %}
                                        {% if sort > 0 %}
                                        <p></p>
                                        {% if item['closed'] == 'N' %}
                                            {{ link_to(ACP_NAME ~ '/subdomain/closed/' ~ item['id'], 'Đóng web hoàn toàn', 'class':'btn btn-sm btn-primary') }}
                                        {% else %}
                                            {{ link_to(ACP_NAME ~ '/subdomain/unClosed/' ~ item['id'], 'Mở web hoàn toàn', 'class':'btn btn-sm btn-danger') }}
                                        {% endif %}
                                        {% else %}---{% endif %}
                                        <p></p>
                                        {% if item['is_ssl'] == 'N' %}
                                        <a href="/{{ ACP_NAME ~ '/subdomain/isSsl/' ~ item['id'] }}" data-amount="{{ number_format(activeSslAmount, 0, ',', '.') }}" data-domain="{{ item['name'] ~ '.' ~ ROOT_DOMAIN }}" class="btn btn-sm btn-primary btn-active-ssl">Kích hoạt SSL</a>
                                        {% else %}
                                            <div class="alert alert-danger">Đã kích hoạt SSL</div>
                                        {% endif %}
                                        </td>
                                        <td align="center">
                                            {% if sort > 0 %}
                                                <p>
                                                    {% if item['other_interface'] == 'Y' %}
                                                        {{ link_to(ACP_NAME ~ '/subdomain/unOtherInterface/' ~ item['id'], 'class':'btn btn-danger', 'Đang khóa giao diện' ) }}
                                                    {% else %}
                                                        {{ link_to(ACP_NAME ~ '/subdomain/otherInterface/' ~ item['id'], 'class':'btn btn-primary', 'Đã mở giao diện' ) }}
                                                    {% endif %}
                                                </p>
                                            {% endif %}
                                            {% if sort > 0 %}
                                                <p>
                                                    {% if item['display'] == 'Y' %}
								                        {{ link_to(ACP_NAME ~ '/subdomain/unDisplay/' ~ item['id'], 'class':'btn btn-primary', 'Khóa link 110.vn' ) }}
								                    {% else %}
								                        {{ link_to(ACP_NAME ~ '/subdomain/display/' ~ item['id'], 'class':'btn btn-danger', 'Mở link 110.vn' ) }}
								                    {% endif %}
                                                </p>
                                            {% endif %}
                                            {% if sort > 0 %}
                                                <p>
                                                    {% if item['duplicate'] == 'Y' %}
                                                        {{ link_to(ACP_NAME ~ '/subdomain/unDuplicate/' ~ item['id'], 'class':'btn btn-danger', 'Mở nhân bản' ) }}
                                                    {% else %}
                                                        {{ link_to(ACP_NAME ~ '/subdomain/showDuplicate/' ~ item['id'], 'class':'btn btn-primary', 'Khóa nhân bản' ) }}
                                                    {% endif %}
                                                </p>
                                            {% endif %}
                                        </td>
                                        {% if auth.getIdentity()['role'] == 1 %}
                                        <td align="center">
                                            {% if sort > 0 %}
                                                {{ link_to(ACP_NAME ~ '/subdomain/delete/' ~ item['id'], '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}
                                            {% endif %}
                                        </td>
                                        {% endif %}
                                        {#<td align="center"><a href="http://{{ subdomain }}" class="btn btn-success" target="_blank">View</a></td>#}
                                    </tr>
                                {% endfor %}
                                </tbody>

                            </table>
                        </div>

                        <div class="text-center">
                            <ul class="pagination">
                                {% if qParam is defined and qParam == false %}
                                {% set url = url_page %}
                                {% else %}
                                {% set url = url_page ~ '?' %}
                                {% endif %}
                                {% for key in 1..total_pages %}
                                    <li{% if key == page_current %} class="active"{% endif %}><a href="{{ url ~ 'page=' ~ key }}">{{ key }}</a></li>
                                {% endfor %}
                              </ul>
                        </div>
                    </div>

                    <!-- /.panel-body -->
                    {#
                    <div class="text-center">{{ partial('partials/pagination') }}</div>
                    #}
                    
                </div>

            </div>

        </div>
        {{ partial('partials/module_administrator') }}
    </section>
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Modal header</h3>
        </div>
        <div class="modal-body">
            <p>My modal content here…</p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Close</button>
        </div>
    </div>
    <div class="modal modal fade" id="modalAddUser" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          {{ form('role':'form','action':'', 'id':'form-save-user') }}
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Thêm tên miền quản lý</h4>
            </div>
            <div class="modal-body">
              
            </div>
            <div class="modal-footer">
              <button type="button" id="save-user-subdomain" class="btn btn-primary">Lưu</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
          </div>
          {{ endform() }}
        </div>
    </div>
    <div class="modal modal fade" id="modalAddUserManage" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          {{ form('role':'form','action':'', 'id':'form-save-user-manage') }}
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Thêm user quản lý</h4>
            </div>
            <div class="modal-body">
              
            </div>
            <div class="modal-footer">
              <button type="button" id="save-user-subdomain-manage" class="btn btn-primary">Lưu</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
          </div>
          {{ endform() }}
        </div>
    </div>
    <div class="modal modal fade" id="modalViewUserHistory" role="dialog">
        <div class="modal-dialog modal-md">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Lịch sử</h4>
            </div>
            <div class="modal-body">
            </div>
          </div>
        </div>
    </div>

    <div class="modal modal fade" id="modalViewUserHistoryTransfer" role="dialog">
        <div class="modal-dialog modal-md">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Lịch sử giao dịch</h4>
            </div>
            <div class="modal-body">
            </div>
          </div>
        </div>
    </div>

    <div class="modal fadeg" tabindex="-1" role="dialog" id="modalAddDomainGuide">
      <div class="modal-dialog modal-md">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Hướng dẫn trỏ tên miền</h4>
            </div>
            <div class="modal-body">
                <div class="help-block" style="margin-bottom: 0">
                    <ul>
                        <li>
                            <div class="form-group">Đăng nhập quản lý tên miền của bạn (ví dụ: nam123.com) và cấu hình DNS như sau ( Bạn cần chờ 15 phút hoặc tối đa 4h để tên miền hoạt động):</div>
                            <div class="alert" role="alert" style="margin:0;border-top: 1px solid #ccc">
                                <div>Recored Type: <strong style="color: #0000FF">A</strong></div>
                                <div>Host: <strong style="color: #0000FF">@</strong></div>
                                <div>Value: <strong style="color: #0000FF">{{ ipAdress }}</strong></div>
                            </div>
                            <div class="alert" role="alert" style="margin-top:0;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc">
                                <div>Recored Type: <strong style="color: #0000FF">CNAME</strong></div>
                                <div>Host: <strong style="color: #0000FF">www</strong></div>
                                <div>Value: <strong style="color: #0000FF">nam123.com</strong></div>
                            </div>
                            {#<div class="alert alert-info" role="alert">
                                <div>Recored Type: TXT</div>
                                <div>Host: @</div>
                                <div>Value: bfb3e6dcc3c1eff8ed6a0044ad7415a8</div>
                            </div>#}
                            <label>Hình minh họa</label>
                            <div class="text-center">{{ image('backend/dist/img/add-domain.png') }}</div>
                        </li>
                    </ul>
                </div>
            </div>
          </div>
        </div>
    </div>
{% else %}
<section class="content">
    <p id="default">
        <img src="/backend/dist/img/default_pic.jpg">
    </p>
</div>
{% endif %}