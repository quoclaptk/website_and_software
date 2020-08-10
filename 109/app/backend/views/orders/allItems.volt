 {% for key,item in pageOrders.items %}
	{% if item.member_id != 0 %}
	{% set member = authFront.getMemberById(item.member_id) %}
	{% endif %}
    {% set order_info = json_decode(item.order_info) %}
    {% if loop.first %}
        <div class="table-responsive mailbox-messages">
        <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
            <th width="7%">Thứ tự</th>
            <th width="15%">Sản phẩm</th>
            {% if user.role == 3 %}
            <th>Gửi từ tên miền</th>
            {% endif %}
            <th>Mã đơn hàng</th>
            <th>Họ Tên</th>
            <th class="text-center">Thành viên</th>
            <th>Tổng tiền(vnđ)</th>
            <th>Thời gian đặt hàng</th>
            <th>Tình trạng</th>
            <th width="5%">Xem</th>
            <th width="5%">Xóa</th>
        </tr>
        </thead>

        <tbody>
    {% endif %}
    <tr>
        <td><input type="checkbox" name="select_all" value="{{ item.id }}"></td>
        <td>{{ key + 1 }}</td>
        <td>
            {% if count(order_info) == 1  %}
            {% for k,v in order_info %}
            <a href="{{ v.link }}" target="_blank">{{ v.name }}</a>
            {% endfor %}
            {% else %}
            Đơn hàng nhiều sản phẩm. {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, '<i class="text-danger" style="text-decoration:underline">Click</i>' ) }} để xem chi tiết
            {% endif %}
        </td>
        {% if user.role == 3 %}
        <td class="text-danger">{{ item.subdomain.name ~ '.' ~ ROOT_DOMAIN }}</td>
        {% endif %}
        <td>{{ item.code }}</td>
        <td>{{ item.name }}</td>
        <td class="text-center">{% if item.member_id != 0 and member is defined %}{{ member.username }}{% else %}---{% endif %}</td>
        <td>{{ tag.number_format(item.total) }}</td>
        <td>{{ date('H:i d/m/Y', strtotime(item.created_at)) }}</td>
        <td>{{ item.status.name }}</td>
        <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-eye"></i>' ) }}</td>
        <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
    </tr>
    {% if loop.last %}
        </tbody>

        </table>
        </div>
    {% endif %}
{% endfor %}
{% if pageOrders.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'page':pageOrders, 'url_page':ACP_NAME ~ '/orders/allItems', 'html_id':'load-page-all-orders'}) }}</div>{% endif %}