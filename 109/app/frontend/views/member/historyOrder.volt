<div class="box_register_member">
    {{ partial("partials/breadcrumb") }}
    <div class="content_front_page">
        <div class="title_bar_center text-uppercase bar_web_bgr"><h1>{{ title_bar }}</h1></div>
        <div class="box_member_info">
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group list-group-member">
                        <a href="{{ tag.site_url(_url_account) }}" class="list-group-item">{{ ucfirst(word._('_thong_tin_tai_khoan')) }}</a>
                        <a href="{{ tag.site_url(_url_change_pass) }}" class="list-group-item">{{ ucfirst(word._('_doi_mat_khau')) }}</a>
                        <a href="{{ tag.site_url(_url_order_history) }}" class="list-group-item active">{{ ucfirst(word._('_lich_su_don_hang')) }}</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="panel-title">{{ title_bar }}</div>
                        </div>  
                        <div class="panel-body">
                            {% if orders|length > 0 %}
                            <fieldset class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ word._('_ma_don_hang') }}</th>
                                            <th>{{ word._('_tong_tien') }}</th>
                                            <th>{{ word._('_thoi_gian_dat_hang') }}</th>
                                            <th>{{ word._('_tinh_trang') }}</th>
                                            <th>{{ word._('_xem_chi_tiet') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% for key,order in orders %}
                                        <tr>
                                            <td>{{ key + 1 }}</td>
                                            <td>{{ order.code }}</td>
                                            <td>{{ number_format(order.total,0,',','.') }} VND</td>
                                            <td>{{ date('H:i d-m-Y', strtotime(order.created_at)) }}</td>
                                            <td>{{ order.status.name }}</td>
                                            <td class="text-center">{{ link_to( _url_order_history ~ '/' ~ order.id, '<i class="fa fa-eye"></i>') }}</td>
                                        </tr>
                                        {% endfor %}
                                    </tbody>
                                </table>
                            </fieldset>
                            {% else %}
                            <strong>{{ word._('_hien_tai_ban_chua_co_don_hang') }}</strong>
                            {% endif %}
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>