{#{{ partial('partials/nav_setting') }}#}
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
           <div class="box">
            <div class="box-header">
              <h3 class="box-title">Thống kê Ip truy cập</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              {% if ipAcesses is defined and ipAcesses != '' %}
              <table id="ipAccessTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>IP</th>
                  <th width="30%">Ghi chú</th>
                  <th>Hôm nay</th>
                  <th>Hôm qua</th>
                  <th>Tuần này</th>
                  <th>Tháng này</th>
                  <th>Năm nay</th>
                  <th>Link</th>
                </tr>
                </thead>
                <tbody>
                {% for ip,ipAcess in ipAcesses %}
                <tr>
                  <td>{{ ip }}</td>
                  <td>
                    <textarea name="ip_note" data-ip="{{ ip }}" class="form-control" style="width:100%">{% if ipAcess['note'] is defined %}{{ ipAcess['note'] }}{% endif %}</textarea>
                  </td>
                  <td>{{ ipAcess['today'] }}</td>
                  <td>{{ ipAcess['yesterday'] }}</td>
                  <td>{{ ipAcess['week'] }}</td>
                  <td>{{ ipAcess['month'] }}</td>
                  <td>{{ ipAcess['year'] }}</td>
                  <td><a href="javascript:;" data-ip="{{ ip }}" data-url='{{ ipAcess['url'] }}' class="btn btn-info btn-view-url-access">Xem</a></td>
                </tr>
                {% endfor %}
                </tbody>
                <tfoot>
                <tr>
                  <th>IP</th>
                  <th width="30%">Ghi chú</th>
                  <th>Hôm nay</th>
                  <th>Hôm qua</th>
                  <th>Tuần này</th>
                  <th>Tháng này</th>
                  <th>Năm nay</th>
                  <th>Link</th>
                </tr>
                </tfoot>
            </table>
            {% else %}
            <div class="alert alert-warning">No records</div>
            {% endif %}
            </div>
            <!-- /.box-body -->
          </div>
        </div>
    </div>
</section>
