{#{{ partial('partials/nav_setting') }}#}
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
           <div class="box">
            <div class="box-header">
              <h3 class="box-title">{{ title_bar }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              {% if ipAcesses is defined and ipAcesses != '' %}
              <table id="ipAccessTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>{% if router.getActionName() == 'linkAccessAds' %}Link{% else %}IP{% endif %}</th>
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
                  <td><a href="javascript:;" data-ip="{{ ip }}" {% if router.getActionName() == 'linkAccessAds' %}data-url='{{ ipAcess['ip'] }}'{% else %}data-url='{{ ipAcess['url'] }}'{% endif %} class="btn btn-info btn-view-url-access">Xem</a></td>
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
            <div class="box_access_online">
              <div class="user_online">
                <ul>
                  {% if cf['_cf_radio_access_online_now'] %}
                  <li>
                      <label class="title_user_online">{{ word['_dang_online'] }}</label>
                      {{ mainGlobal.online_number_display(count_online['online'], ['class':'box_number']) }}
                  </li>
                  {% endif %}
                  {% if cf['_cf_radio_access_online_yesterday'] %}
                  <li>
                      <label class="title_user_online">{{ word['_hom_qua'] }}</label>
                      {{ mainGlobal.online_number_display(count_online['yesterday'], ['class':'box_number']) }}
                  </li>
                  {% endif %}
                  {% if cf['_cf_radio_access_online_today'] %}
                  <li>
                      <label class="title_user_online">{{ word['_hom_nay'] }}</label>
                      {{ mainGlobal.online_number_display(count_online['day'], ['class':'box_number']) }}
                  </li>
                  {% endif %}
                  {% if cf['_cf_radio_access_online_this_week'] %}
                  <li>
                      <label class="title_user_online">{{ word['_tuan_nay'] }}</label>
                      {{ mainGlobal.online_number_display(count_online['week'], ['class':'box_number']) }}
                  </li>
                  {% endif %}
                  {% if cf['_cf_radio_access_online_this_month'] %}
                  <li>
                      <label class="title_user_online">{{ word['_thang_nay'] }}</label>
                      {{ mainGlobal.online_number_display(count_online['month'], ['class':'box_number']) }}
                  </li>
                  {% endif %}
                  {% if cf['_cf_radio_access_online_this_year'] %}
                  <li>
                      <label class="title_user_online">{{ word['_nam_nay'] }}</label>
                      {{ mainGlobal.online_number_display(count_online['year'], ['class':'box_number']) }}
                  </li>
                  {% endif %}
                  {% if cf['_cf_radio_access_online_total'] %}
                  <li>
                      <label class="title_user_online">{{ word['_tong_truy_cap'] }}</label>
                      {{ mainGlobal.online_number_display(count_online['all'], ['class':'box_number']) }}
                  </li>
                  {% endif %}
                </ul>
              </div>
            </div>
            {% endif %}
            </div>
            <!-- /.box-body -->
          </div>
        </div>
    </div>
</section>
<style type="text/css">
  .box_access_online{padding:5px 10px;border:1px solid #ebebeb;max-width: 300px}
  .box_access_online .user_online{color:#333}
  .box_access_online .user_online ul li .box_number b{border:1px solid #333}
  .box_access_online .user_online ul li label{width:50%}
  .box_access_online .user_online ul li span{float:right}
  .user_online{
    color: #fff;
  }
   .user_online ul{
      padding-top:3px
  }
   .user_online ul li{
      padding-top:5px;
      font-size:14px
  }
   .user_online ul li:first-child{
      padding: 0
  }
   .user_online ul li label{
      width:160px;
      font-weight:400;
      font-size: 13px
  }
   .user_online ul li label:hover{
      box-shadow: none
  }
   .user_online ul li:first-child label{
      font-size: 14px;
  }
   .user_online ul li .box_number b{
      padding:0px 3px;
      border:1px solid #fff;
      margin-right:1px;
      font-weight: 400
  }
</style>
