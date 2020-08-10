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
                            <div class="form-group row">
                                <div class="col-md-3"><b>{{ word._('_ma_don_hang') }}</b></div>
                                <div class="col-md-9">{{ order.code }}</div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3"><b>{{ word._('_ho_ten') }}</b></div>
                                <div class="col-md-9">{{ order.name }}</div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3"><b>S{{ word._('_dien_thoai') }}</b></div>
                                <div class="col-md-9">{{ order.phone }}</div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3"><b>Email</b></div>
                                <div class="col-md-9">{{ order.email }}</div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3"><b>{{ word._('_dia_chi') }}</b></div>
                                <div class="col-md-9">{{ order.address }}</div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3"><b>{{ word._('_ghi_chu') }}</b></div>
                                <div class="col-md-9">{{ order.comment }}</div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3"><b>{{ word._('_tinh_trang') }}</b></div>
                                <div class="col-md-9">{{ order.status.name }}</div>
                            </div>
                         </div>
                    </div>

                    {% set order_info = json_decode(order.order_info) %}
                    <div class="panel panel-default">
                        <div class="panel-heading">{{ word._('_danh_sach_san_pham') }}</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="20%">{{ word._('_san_pham') }}</th>
                                        <th>{{ word._('_hinh_anh') }}</th>
                                        <th>{{ word._('_gia') }}</th>
                                        <th>{{ word._('_so_luong') }}</th>
                                        <th>{{ word._('_thanh_tien') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {% for key,value in order_info %}
                                        <tr>
                                            <th scope="row">{{ key + 1 }}</th>
                                            <td><a href="{{ value.link }}" target="_blank">{{ value.name }}</a></td>
                                            <td class="text-center">{{ image(value.photo, "width":70) }}</td>
                                            <td>{{ tag.number_format(value.price) ~ ' VND' }}</td>
                                            <td>{{ value.qty }}</td>
                                            <td>{{ tag.number_format(value.total) ~ ' VND' }}</td>
                                        </tr>
                                    {% endfor %}
                                        <tr>
                                            <td colspan="5" class="text-right">{{ word._('_tong_tien') }}</td>
                                            <td>{{ tag.number_format(order.total, 0, ',', '.') ~ ' VND' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>