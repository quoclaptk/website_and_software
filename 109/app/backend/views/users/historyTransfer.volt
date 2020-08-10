{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Lịch sử giao dịch</h3>
                </div>
                {{ content() }}
                {{ flashSession.output() }}
                <div class="table-responsive">
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
                            {% for s,i in historyTransfers %}
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
                </div>
            </div>
        </div>
    </div>
</section>