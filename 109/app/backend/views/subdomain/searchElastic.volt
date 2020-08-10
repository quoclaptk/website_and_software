{% if result['total'] > 0 %}
<div class="row">
    <div class="col-xs-12">
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
            {% for key,item in result['hits'] %}
                {% set id = item['_id'] %}
                {% set source = item['_source'] %}
                {% set name = source['name'] %}
                {% set createId = source['create_id'] %}
                {% set sum_rate = source['sum_rate'] %}
                {% set special = source['special'] %}
                {% set duplicate = source['duplicate'] %}
                {% set display = source['display'] %}
                {% set domains = source['domain']['name'] %}
                <tr>
                    <td>{{ key + 1 }}</td>
                    <td>
                        <a href="//{{ name ~ '.' ~ ROOT_DOMAIN }}" target="_blank"{% if special == 'Y' %} style="font-weight:bold;color:#f00" {% endif %}>{{ name ~ '.' ~ ROOT_DOMAIN }}</a> |
                        {% if duplicate != 'Y' %}
                        <a href="javascript:;" data-id="{{ id }}" data-name="{{ name ~ '.' ~ ROOT_DOMAIN }}" class="copy-website text-danger" style="font-weight:bold"{% if display == 'N' %} class="is-disabled"{% endif %}>Nhân bản</a>
                        {% else %}
                        <span class="bold text-danger">Người tạo web đã khóa nhân bản</span>
                        {% endif %}
                        {% if display == 'N' %}
                        <span class="text-danger bold">| Đã khóa link</span>
                        {% endif %}
                    </td>
                    <td>
                        <label class="total_rate_{{ id }}" style="color: #f00;">{{ sum_rate }}</label>
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
                    <td><a href="{{ subdomain_service.getSubdomainCreate(createId) }}" target="_blank" style="color:#f00;font-weight:bold">{{ subdomain_service.getSubdomainCreate(createId) }}</a></td>
                    <td>
                        {% if domains is not null %}
                            {% set listDomain = explode(',', domains) %}
                            {% for kd,domain in listDomain %}
                                <a href="http://{{ trim(domain) }}" target="_blank" style="font-weight: 600;color: #f00">{{ domain }}</a>{% if kd < count(listDomain) - 1 %}, {% endif %}
                            {% endfor %}
                        {% endif %}
                    </td>
                </tr>
            {% endfor %}
            </tbody>

            </table>
        </div>
    </div>
</div>
{% endif %}