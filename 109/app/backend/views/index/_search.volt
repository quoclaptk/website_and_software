{% for sort,item in list_sub_domain %}
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
                {#<a href="javascript:;" class="btn btn-sm btn-success btn-user-history-transfer" data-id="{{ item['id'] }}" style="margin-top:10px"><i class="fa fa-credit-card" aria-hidden="true"></i> Lịch sử giao dịch</a>#}
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
        {% else %}--{% endif %}
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