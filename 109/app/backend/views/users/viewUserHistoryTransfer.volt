<div class="table-responsive">
	<h4 class="text-warning" style="margin-top:0;color: #f00">Tài khoản: {{ user.username }}</h4>
	{% if userHistoryTransfers|length > 0 %}
	<table class="table table-bordered table-hover">
	    <thead>
	        <tr>
	            <th>STT</th>
                <th>Số tiền (vnđ)</th>
                <th>Mã giao dịch</th>
                <th>Mã thanh toán (Ngân lượng)</th>
                <th>Thời gian</th>
	        </tr>
	    </thead>
	    <tbody>
	    	{% for s,i in userHistoryTransfers %}
            <tr>
                <td>{{ s + 1 }}</td>
                <td>{{ number_format(i.amount, 0, ',', '.') }}</td>
                <td>{{ i.code }}</td>
                <td>{{ i.payment_id }}</td>
                <td>{{ date('H:i d/m/Y', strtotime(i.create_at)) }}</td>
            </tr>
            {% endfor %}
	    </tbody>
	</table>
	{% else %}
	<div class="alert alert-warning">Đại lý chưa có giao dịch</div>
	{% endif %}
</div>