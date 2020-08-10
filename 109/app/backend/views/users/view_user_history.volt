<div class="table-responsive">
	<h4 class="text-warning" style="margin-top:0;color: #000">Tài khoản: <span style="color: #f00">{{ user.username }}</span> | Số dư: <span style="color: #f00">{{ number_format(user.balance, 0, ',', '.') }} đ</span></h4>

	<div>

	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 10px">
	    <li role="presentation" class="active"><a href="#action" aria-controls="action" role="tab" data-toggle="tab">Tài khoản</a></li>
	    <li role="presentation"><a href="#tranfer" aria-controls="tranfer" role="tab" data-toggle="tab">Giao dịch</a></li>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
	    <div role="tabpanel" class="tab-pane active" id="action" style="max-height: 600px">
	    	{% if userHistories|length > 0 %}
			<table class="table table-bordered table-hover">
			    <thead>
			        <tr>
			            <th>STT</th>
			            <th>Hành động</th>
			            <th>Số tiền (đ)</th>
			            <th>Thời gian</th>
			        </tr>
			    </thead>
			    <tbody>
			    	{% for key,value in userHistories %}
			        <tr>
			            <th scope="row">{{ key + 1 }}</th>
			            <td>{{ value.summary }} {% if value.subdomain_name != '' %}{{ value.subdomain_name ~ '.' ~ ROOT_DOMAIN }}{% endif %}</td>
			            <td>{{ number_format(value.amount, 0, ',', '.') }}</td>
			            <td>{{ date('H:i d/m/Y', strtotime(value.create_at)) }}</td>
			        </tr>
			        {% endfor %}
			    </tbody>
			</table>
			{% else %}
			<div class="alert alert-warning">Chưa có lịch sử</div>
			{% endif %}
	    </div>
	    <div role="tabpanel" class="tab-pane" id="tranfer">
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
	  </div>

	</div>

	
</div>