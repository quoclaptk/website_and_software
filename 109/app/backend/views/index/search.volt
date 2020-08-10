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
                    </tr>
                {% endfor %}
                </tbody>
            </table>
            {% else %}<p class="text-center">--</p>{% endif %}
        </td>
        
       <td class="text-center">
            <p class="red bold">Ngày tạo: {{ create_at }}</p>
            <p class="red bold">{% if active_date != '--' %}Ngày kích hoạt: {% endif %}{{ active_date }}</p>
            {% if auth.getIdentity()['role'] == 1 and expired_date != '' %}
            <div class="form-group">
                <div class="date">
                  <span class="red bold">Ngày hết hạn: </span>  
                  <p class="red bold">{{ expired_date }}</p>
                </div>
          </div>
            {% else %}
            <span class="red bold">Ngày hết hạn: {{ expired_date }}</span> 
            {% endif %}
        </td>
    </tr>
{% endfor %}