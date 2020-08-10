{% if list|length > 0 %}
<div class="row">
    <div class="col-xs-12">
        {% for key,item in list %}
            {% if item.sum_rate > 0 %}
                {% set sum_rate = item.sum_rate %}
            {% else %}
                {% set sum_rate = 0 %}
            {% endif %}
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
                    {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/usingMainLayout/' ~ item.id, 'Đổi giao diện <sơma<span class="text-danger">(Sẽ mất giao diện cũ)</span>', 'class':'change_domain_interface' ) }}
                </td>
                <td>
                    <a href="http://{{ item.domain_name }}" target="_blank" style="font-weight: 600;color: #f00">{{ item.domain_name }}</a>
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
{% endif %}