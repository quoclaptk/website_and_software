{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}
            
            <div style="position: relative">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 10px">
                    <li role="presentation" {% if request.get('active') == 'customer_message' or request.get('active') == '' %}class="active"{% endif %}>
                        <a href="#customer_message" aria-controls="customer_message" role="tab" data-toggle="tab">{{ word['_de_lai_tin_nhan_cho_chung_toi'] }}</a>
                        {% if pageCustomerMessages.items|length > 0 %}
                        <i class="fa fa-star" aria-hidden="true" style="position:absolute;top:-7px;right: -5px;color: #f00;font-size: 16px"></i>
                        {% endif %}
                    </li>
                    <li role="presentation" {% if request.get('active') == 'form_item' %}class="active"{% endif %}>
                        <a href="#form_item" aria-controls="form_item" role="tab" data-toggle="tab">{{ word['_gui_yeu_cau_bao_gia'] }}</a>
                        {% if pageFrmItemYcbg.items|length > 0 %}
                        <i class="fa fa-star" aria-hidden="true" style="position:absolute;top:-7px;right: -5px;color: #f00;font-size: 16px"></i>
                        {% endif %}
                    </li>
                     <li role="presentation" {% if request.get('active') == 'form_item_fast_register' %}class="active"{% endif %}>
                        <a href="#form_item_fast_register" aria-controls="form_item_fast_register" role="tab" data-toggle="tab">{{ word['_dang_ky_nhanh'] }}</a>
                        {% if frm_item_fast_register|length > 0 %}
                        <i class="fa fa-star" aria-hidden="true" style="position:absolute;top:-7px;right: -5px;color: #f00;font-size: 16px"></i>
                        {% endif %}
                    </li>
                    <li role="presentation" {% if request.get('active') == 'newsletter' %}class="active"{% endif %}>
                        <a href="#newsletter" aria-controls="newsletter" role="tab" data-toggle="tab">Email đăng ký</a>
                        {% if newsletter|length > 0 %}
                        <i class="fa fa-star" aria-hidden="true" style="position:absolute;top:-7px;right: -5px;color: #f00;font-size: 16px"></i>
                        {% endif %}
                    </li>
                    <li role="presentation" {% if request.get('active') == 'order' %}class="active"{% endif %}>
                        <a href="#order" aria-controls="order" role="tab" data-toggle="tab">Đơn hàng</a>
                        {% if pageOrders.items|length > 0 %}
                        <i class="fa fa-star" aria-hidden="true" style="position:absolute;top:-7px;right: -5px;color: #f00;font-size: 16px"></i>
                        {% endif %}
                    </li>
                    <li role="presentation" {% if request.get('active') == 'contact' %}class="active"{% endif %}>
                        <a href="#contact" aria-controls="contact" role="tab" data-toggle="tab">Liên hệ</a>
                        {% if contact|length > 0 %}
                        <i class="fa fa-star" aria-hidden="true" style="position:absolute;top:-7px;right: -5px;color: #f00;font-size: 16px"></i>
                        {% endif %}
                    </li>
                    <li role="presentation" {% if request.get('active') == 'customer_comment' %}class="active"{% endif %}>
                        <a href="#customer_comment" aria-controls="customer_comment" role="tab" data-toggle="tab">Ý kiến khách hàng</a>
                        {% if customerComments|length > 0 %}
                        <i class="fa fa-star" aria-hidden="true" style="position:absolute;top:-7px;right: -5px;color: #f00;font-size: 16px"></i>
                        {% endif %}
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane {% if request.get('active') == 'customer_message' or request.get('active') == '' %}active{% endif %}" id="customer_message">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="display: block">Danh sách {{ word['_de_lai_tin_nhan_cho_chung_toi'] }}</div>
                            <div class="panel-body">
                                {% if setting_page.order_admin_note != '' %}
                                <div class="form-group">{{ setting_page.order_admin_note }}</div>
                                {% endif %}
                                {% if !isNotDeleteOrder %}
                                <div class="clearfix">
                                    <div class="pull-right">
                                        <a href="javascript:;" class="btn btn-danger" onclick="delete_all('customer_message', {{ page_current }})">Xóa nhiều mục</a>
                                    </div>
                                    <p class="clear"></p>
                                </div>
                                {% endif %}
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div id="load-page-all-customer-message">
                                            {% for key,item in pageCustomerMessages.items %}
                                                {% if loop.first %}
                                                    <div class="table-responsive mailbox-messages">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%"><input type="checkbox" name="select-all-item" class="flat-red"></th>
                                                                    <th width="7%">Thứ tự</th>
                                                                    {% if user.role == 3 %}
                                                                    <th>Gửi từ tên miền</th>
                                                                    {% endif %}
                                                                    {% if cf['_customer_message_name'] == true %}
                                                                    <th>{{ word['_ho_ten'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_customer_message_phone'] == true %}
                                                                    <th>{{ word['_dien_thoai'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_address'] == true %}
                                                                    <th>{{ word['_dia_chi'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_birthday'] == true %}
                                                                    <th>{{ word['_ngay_sinh'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_box_option'] == true %}
                                                                    <th>{{ word['_box_tuy_chon'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_work_province'] == true %}
                                                                    <th>{{ word['_tinh_thanh_day'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_home_town'] == true %}
                                                                    <th>{{ word['_nguyen_quan'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_voice'] == true %}
                                                                    <th>{{ word['_giong_noi'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_portrait'] == true %}
                                                                    <th>{{ word['_anh_the'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_certificate'] == true %}
                                                                    <th>{{ word['_anh_bang_cap'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_college_address'] == true %}
                                                                    <th>{{ word['_sinh_vien_giao_vien_truong'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_major'] == true %}
                                                                    <th>{{ word['_nganh_hoc'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_graduation_year'] == true %}
                                                                    <th>{{ word['_nam_tot_nghiep'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_level'] == true %}
                                                                    <th>{{ word['_trinh_do'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_gender'] == true %}
                                                                    <th>{{ word['_gioi_tinh'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_forte'] == true %}
                                                                    <th>{{ word['_uu_diem'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_subjects'] == true %}
                                                                    <th>{{ word['_mon_day'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_class'] == true %}
                                                                    <th>{{ word['_lop_day'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_teaching_time'] == true %}
                                                                    <th>{{ word['_thoi_gian_day'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_salary'] == true %}
                                                                    <th>{{ word['_yeu_cau_luong_toi_thieu'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_cmg_other_request'] == true %}
                                                                    <th>{{ word['_yeu_cau_khac'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_customer_message_comment'] == true %}
                                                                    <th width="25%">Nội dung</th>
                                                                    {% endif %}
                                                                    <th>Thời gian gửi</th>
                                                                    <th width="25%">Ghi chú</th>
                                                                    {% if !isNotDeleteOrder %}
                                                                    <th width="5%">Xóa</th>
                                                                    {% endif %}
                                                                </tr>
                                                            </thead>
                                                        <tbody>
                                                            {% endif %}
                                                            <tr>
                                                                <td><input type="checkbox" name="select_all" value="{{ item.id }}"></td>
                                                                <td>{{ key + 1 }}</td>
                                                                {% if user.role == 3 %}
                                                                <td class="text-danger{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} red bold{% endif %}">{{ item.subdomain.name ~ '.' ~ ROOT_DOMAIN }}</td>
                                                                {% endif %}
                                                                {% if cf['_customer_message_name'] == true %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.name }}</td>
                                                                {% endif %}
                                                                {% if cf['_customer_message_phone'] == true %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.phone }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_address'] == true %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.address }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_birthday'] == true %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}{{ date('d/m/Y', strtotime(item.birthday)) }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_box_option'] == true %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.box_select_option }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_work_province'] == true %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.work_province }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_home_town'] == true %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.home_town }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_voice'] == true %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.voice }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_portrait'] == true %}
                                                                <td class="text-center"><img src="{{ item.portrait_image }}" width="80"></td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_certificate'] == true %}
                                                                <td class="text-center"><img src="{{ item.certificate_image }}" width="80"></td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_college_address'] == true %}
                                                                <td>{{ item.college_address }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_major'] == true %}
                                                                <td>{{ item.major }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_graduation_year'] == true %}
                                                                <td>{{ item.graduation_year }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_level'] == true %}
                                                                <td>{{ item.level }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_gender'] == true %}
                                                                <td>{{ item.gender }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_forte'] == true %}
                                                                <td>{{ item.forte }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_subjects'] == true %}
                                                                <td>{{ item.subjects }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_class'] == true %}
                                                                <td>{{ item.class }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_teaching_time'] == true %}
                                                                <td>{{ item.teaching_time }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_salary'] == true %}
                                                                <td>{{ item.salary }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_cmg_other_request'] == true %}
                                                                <td>{{ item.other_request }}</td>
                                                                {% endif %}
                                                                {% if cf['_customer_message_comment'] == true %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3%} class="red bold"{% endif %}>{{ item.comment }}</td>
                                                                {% endif %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ date('H:i d/m/Y', strtotime(item.created_at)) }}</td>
                                                                <td>
                                                                   <textarea data-id="{{ item.id }}" class="form-control update-subdomain-note-2" rows="2">{{ item.note }}</textarea>
                                                                </td>
                                                                {% if !isNotDeleteOrder %}
                                                                <td class="text-center">{{ link_to(ACP_NAME ~ '/customer_message/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                                                 {% endif %}
                                                            </tr>
                                                            {% if loop.last %}
                                                        </tbody>

                                                    </table>
                                                </div>
                                                {% endif %}
                                            {% endfor %}
                                            {% if pageCustomerMessages.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'page':pageCustomerMessages, 'url_page':ACP_NAME ~ '/customer_message/allItems', 'html_id':'load-page-all-customer-message'}) }}</div>{% endif %}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane {% if request.get('active') == 'form_item' %}active{% endif %}" id="form_item">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="display: block">Danh sách {{ word['_gui_yeu_cau_bao_gia'] }}</div>
                            <div class="panel-body">
                                {% if setting_page.order_admin_note != '' %}
                                <div class="form-group">{{ setting_page.order_admin_note }}</div>
                                {% endif %}
                                {% if !isNotDeleteOrder %}
                                <div class="clearfix">
                                    <div class="pull-right">
                                        <a href="javascript:;" class="btn btn-danger" onclick="delete_all('form_item', {'type':'hot'})">Xóa nhiều mục</a>
                                    </div>
                                    <p class="clear"></p>
                                </div>
                                {% endif %}
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div id="load-page-all-form-item-ycbg">
                                            {% for key,item in pageFrmItemYcbg.items %}

                                                {% if loop.first %}
                                                    <div class="table-responsive mailbox-messages">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%"><input type="checkbox" name="select-all-hot" class="flat-red"></th>
                                                                    <th width="7%">Thứ tự</th>
                                                                    {% if user.role == 3 %}
                                                                    <th>Gửi từ tên miền</th>
                                                                    {% endif %}
                                                                    <th>{{ word['_ho_ten'] }}</th>
                                                                    <th>{{ word['_dien_thoai'] }}</th>
                                                                    {% if cf['_frm_ycbg_email'] == true %}
                                                                        <th>{{ word['_email'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_class'] == true %}
                                                                        <th>{{ word['_lop'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_subjects'] == true %}
                                                                        <th>{{ word['_mon_hoc'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_student_number'] == true %}
                                                                        <th>{{ word['_so_luong_hoc_sinh'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_learning_level'] == true %}
                                                                        <th>{{ word['_hoc_luc_hien_tai'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_learning_time'] == true %}
                                                                        <th>{{ word['_so_buoi'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_learning_day'] == true %}
                                                                        <th>{{ word['_thoi_gian_hoc'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_request'] == true %}
                                                                        <th>{{ word['_yeu_cau'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_teacher_code'] == true %}
                                                                        <th>{{ word['_ma_so_gia_su_da_chon'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_subject'] == true %}
                                                                        <th>{{ word['_tieu_de'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_frm_ycbg_place_pic'] == true %}
                                                                        <th>{{ word['_noi_can_don'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_frm_ycbg_place_arrive'] == true %}
                                                                        <th>{{ word['_noi_can_den'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_frm_ycbg_type'] == true %}
                                                                        <th>{{ word['_loai_xe'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_cf_radio_frm_ycbg_day'] == true or cf['_cf_radio_frm_ycbg_hour'] == true or cf['_cf_radio_frm_ycbg_minute'] == true %}
                                                                        <th>Thời gian đặt</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_file'] == true %}
                                                                        <th class="text-center">{{ word['_gui_cv'] }}</th>
                                                                    {% endif %}
                                                                    {% if cf['_frm_ycbg_comment'] == true %}
                                                                        <th width="25%">{{ word['_yeu_cau_khac'] }}</th>
                                                                    {% endif %}
                                                                    <th>Thời gian gửi</th>
                                                                    <th width="25%">Ghi chú</th>
                                                                    {% if !isNotDeleteOrder %}
                                                                    <th width="5%">Xóa</th>
                                                                    {% endif %}
                                                                </tr>
                                                            </thead>
                                                        <tbody>
                                                            {% endif %}
                                                            <tr>
                                                                <td><input type="checkbox" name="select_all_hot" class="hot-item" value="{{ item.id }}"></td>
                                                                <td>{{ key + 1 }}</td>
                                                                {% if user.role == 3 %}
                                                                <td class="text-danger{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} red bold{% endif %}">{{ item.subdomain.name ~ '.' ~ ROOT_DOMAIN }}</td>
                                                                {% endif %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.name }}</td>
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.phone }}</td>
                                                                {% if cf['_frm_ycbg_email'] == true %}
                                                                    <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.email }}</td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_class'] == true %}
                                                                    <td>{{ item.class }}</td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_subjects'] == true %}
                                                                    <td>{{ item.subjects }}</td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_student_number'] == true %}
                                                                    <td>{{ item.studen_number }}</td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_learning_level'] == true %}
                                                                    <td>{{ item.learning_level }}</td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_learning_time'] == true %}
                                                                   <td>{{ item.learning_time }}</td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_learning_day'] == true %}
                                                                   <td>{{ item.learning_day }}</td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_request'] == true %}
                                                                    <td>{{ item.request }}</td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_teacher_code'] == true %}
                                                                    <td>{{ item.teacher_code }}</td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_subject'] == true %}
                                                                    <td>{{ item.subject }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_frm_ycbg_place_pic'] == true %}
                                                                    <td>{{ item.place_pic }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_frm_ycbg_place_arrive'] == true %}
                                                                    <td>{{ item.place_arrive }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_frm_ycbg_type'] == true %}
                                                                    <td>{{ item.type }}</td>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_frm_ycbg_day'] == true or cf['_cf_radio_frm_ycbg_hour'] == true or cf['_cf_radio_frm_ycbg_minute'] == true %}
                                                                    <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ date('d/m/Y', strtotime(item.day)) ~ ' - ' ~ item.hour ~ item.minute }}</td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_file'] == true %}
                                                                    <td><a href="/files/document/{{ SUB_FOLDER ~ '/' ~ item.file }}" target="_blank">{{ item.file }}</a></td>
                                                                {% endif %}
                                                                {% if cf['_frm_ycbg_comment'] == true %}
                                                                    <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.comment }}</td>
                                                                {% endif %}
                                                                <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ date('H:i d/m/Y', strtotime(item.created_at)) }}</td>
                                                                <td>
                                                                    <textarea data-id="{{ item.id }}"  class="form-control update-subdomain-note-3" rows="2">{{ item.note }}</textarea>
                                                                </td>
                                                                {% if !isNotDeleteOrder %}
                                                                <td class="text-center">{{ link_to(ACP_NAME ~ '/form_item/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                                                {% endif %}
                                                            </tr>
                                                            {% if loop.last %}
                                                        </tbody>

                                                    </table>
                                                </div>
                                                {% endif %}
                                            {% endfor %}
                                            {% if pageFrmItemYcbg.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'page':pageFrmItemYcbg, 'url_page':ACP_NAME ~ '/form_item/allItems', 'html_id':'load-page-all-form-item-ycbg'}) }}</div>{% endif %}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane {% if request.get('active') == 'form_item_fast_register' %}active{% endif %}" id="form_item_fast_register">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="display: block">Danh sách {{ word['_dang_ky_nhanh'] }}</div>
                            <div class="panel-body">
                                {% if setting_page.order_admin_note != '' %}
                                <div class="form-group">{{ setting_page.order_admin_note }}</div>
                                {% endif %}
                                {% if !isNotDeleteOrder %}
                                <div class="clearfix">
                                    <div class="pull-right">
                                        <a href="javascript:;" class="btn btn-danger" onclick="delete_all('form_item', , {'type':'new'})">Xóa nhiều mục</a>
                                    </div>
                                    <p class="clear"></p>
                                </div>
                                {% endif %}
                                <div class="row">
                                    <div class="col-xs-12">
                                        {% for key,item in frm_item_fast_register %}
                                            {% if loop.first %}
                                                <div class="table-responsive mailbox-messages">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%"><input type="checkbox" name="select-all-new" class="flat-red"></th>
                                                                <th width="7%">Thứ tự</th>
                                                                {% if user.role == 3 %}
                                                                <th>Gửi từ tên miền</th>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_fast_register_name'] == true %}
                                                                <th>{{ word['_ho_ten'] }}</th>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_fast_register_phone'] == true %}
                                                                <th>{{ word['_dien_thoai'] }}</th>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_fast_register_email'] == true %}
                                                                <th>{{ word['_email'] }}</th>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_fast_register_address'] == true %}
                                                                <th>{{ word['_dia_chi'] }}</th>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_fast_register_start_time'] == true %}
                                                                <th>{{ word['_ngay_gio_khoi_hanh'] }}</th>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_fast_register_end_time'] == true %}
                                                                <th>{{ word['_ngay_gio_ve'] }}</th>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_fast_register_number_ticket'] == true %}
                                                                <th>{{ word['_so_luong_ve'] }}</th>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_fast_register_method'] == true %}
                                                                <th>{{ word['_chon_hinh_thuc_nhan_lop'] }}</th>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_fast_register_day'] == true or cf['_cf_radio_fast_register_hour'] == true %}
                                                                    <th>{{ word['_thoi_gian_nhan_lop'] }}</th>
                                                                {% endif %}
                                                                {% if cf['_cf_radio_fast_register_comment'] == true %}
                                                                    <th width="25%">{{ word['_yeu_cau_them'] }}</th>
                                                                {% endif %}
                                                                <th>Thời gian gửi</th>
                                                                <th width="25%">Ghi chú</th>
                                                                {% if !isNotDeleteOrder %}
                                                                <th width="5%">Xóa</th>
                                                                {% endif %}
                                                            </tr>
                                                        </thead>

                                                    <tbody>
                                                        {% endif %}
                                                        <tr>
                                                            <td><input type="checkbox" name="select_all_new" class="new-item" value="{{ item.id }}"></td>
                                                            <td>{{ key + 1 }}</td>
                                                            {% if user.role == 3 %}
                                                            <td class="text-danger">{{ item.subdomain.name ~ '.' ~ ROOT_DOMAIN }}</td>
                                                            {% endif %}
                                                            {% if cf['_cf_radio_fast_register_name'] == true %}
                                                            <td>{{ item.name }}</td>
                                                            {% endif %}
                                                            {% if cf['_cf_radio_fast_register_phone'] == true %}
                                                            <td>{{ item.phone }}</td>
                                                            {% endif %}
                                                            {% if cf['_cf_radio_fast_register_email'] == true %}
                                                            <td>{{ item.email }}</td>
                                                            {% endif %}
                                                            {% if cf['_cf_radio_fast_register_address'] == true %}
                                                            <td>{{ item.address }}</td>
                                                            {% endif %}
                                                            {% if cf['_cf_radio_fast_register_start_time'] == true %}
                                                            <td>{{ date('H:i d/m/Y', strtotime(item.start_time))}}</td>
                                                            {% endif %}
                                                            {% if cf['_cf_radio_fast_register_end_time'] == true %}
                                                            <td>{{ date('H:i d/m/Y', strtotime(item.end_time))}}</td>
                                                            {% endif %}
                                                            {% if cf['_cf_radio_fast_register_number_ticket'] == true %}
                                                            <td>{{ item.number_ticket }}</td>
                                                            {% endif %}
                                                            {% if cf['_cf_radio_fast_register_method'] == true %}
                                                            <td>{{ item.method }}</td>
                                                            {% endif %}
                                                            {% if cf['_cf_radio_fast_register_day'] == true or cf['_cf_radio_fast_register_hour'] == true %}
                                                                <td>{{ date('d/m/Y', strtotime(item.day)) ~ ' - ' ~ item.hour }}</td>
                                                            {% endif %}
                                                            {% if cf['_cf_radio_fast_register_comment'] == true %}
                                                                <td>{{ item.comment }}</td>
                                                            {% endif %}
                                                            <td>{{ date('H:i d/m/Y', strtotime(item.created_at)) }}</td>
                                                            <td>
                                                                <textarea name="form_item_note[{{ item.id }}]" class="form-control note-order-form-item" data-id="{{item.id}}" rows="2">{{ item.note }}</textarea>
                                                            </td>
                                                            {% if !isNotDeleteOrder %}
                                                            <td class="text-center">{{ link_to(ACP_NAME ~ '/form_item/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                                            {% endif %}
                                                        </tr>
                                                        {% if loop.last %}
                                                    </tbody>

                                                </table>
                                            </div>
                                            {% endif %}
                                        {% endfor %}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane {% if request.get('active') == 'newsletter' %}active{% endif %}" id="newsletter">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="display: block">Danh sách Email nhận bản tin</div>
                            <div class="panel-body">
                                {% if setting_page.order_admin_note != '' %}
                                <div class="form-group">{{ setting_page.order_admin_note }}</div>
                                {% endif %}
                                {% if !isNotDeleteOrder %}
                                <div class="clearfix">
                                    <div class="pull-right">
                                        <a href="javascript:;" class="btn btn-danger" onclick="delete_all('newsletter', {'type':'selling'})">Xóa nhiều mục</a>
                                    </div>
                                    <p class="clear"></p>
                                </div>
                                {% endif %}
                                <div class="row">
                                    <div class="col-xs-12">
                                        {% for key,item in newsletter %}
                                            {% if loop.first %}
                                                <div class="table-responsive mailbox-messages">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%"><input type="checkbox" name="select-all-selling" class="flat-red"></th>
                                                            <th width="7%">Thứ tự</th>
                                                            {% if user.role == 3 %}
                                                            <th>Gửi từ tên miền</th>
                                                            {% endif %}
                                                            <th>Email</th>
                                                            <th>Ngày giờ đăng ký</th>
                                                        </tr>
                                                    </thead>

                                                <tbody>
                                                    {% endif %}
                                                    <tr>
                                                        <td><input type="checkbox" name="select_all_selling" class="selling-item" value="{{ item.id }}"></td>
                                                        <td>{{ key + 1 }}</td>
                                                        {% if user.role == 3 %}
                                                        <td class="text-danger">{{ item.subdomain.name ~ '.' ~ ROOT_DOMAIN }}</td>
                                                        {% endif %}
                                                        <td>{{ item.email }}</td>
                                                        <td>{{ date('H:i d/m/Y', strtotime(item.created_at)) }}</td>
                                                    </tr>
                                                    {% if loop.last %}
                                                </tbody>

                                            </table>
                                        </div>
                                            {% endif %}
                                        {% endfor %}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane {% if request.get('active') == 'order' %}active{% endif %}" id="order">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="display: block">Danh sách đơn hàng</div>
                            <div class="panel-body">
                                {% if setting_page.order_admin_note != '' %}
                                <div class="form-group">{{ setting_page.order_admin_note }}</div>
                                {% endif %}
                                {% if !isNotDeleteOrder %}
                                <div class="clearfix">
                                    <div class="pull-right">
                                        <a href="javascript:;" class="btn btn-danger" onclick="delete_all('{{ controller_name }}', {{ page_current }})">Xóa nhiều mục</a>
                                    </div>
                                    <p class="clear"></p>
                                </div>
                                {% endif %}
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div id="load-page-all-orders">
                                            {% for key,item in pageOrders.items %}
                                            	{% if item.member_id != 0 %}
                                            	{% set member = authFront.getMemberById(item.member_id) %}
                                            	{% endif %}
                                                {% set currency = (item.currency is not empty) ? item.currency : 'VNĐ' %}
                                                {% set order_info = json_decode(item.order_info) %}
                                                {% if cf['_cf_text_price_text'] != '' %}
                                                    {% if cf['_cf_radio_price_unit_position'] == 1 %}
                                                        {% set totalCart = cf['_cf_text_price_text'] ~ tag.number_format(item.total) %}
                                                    {% else %}
                                                        {% set totalCart = tag.number_format(item.total) ~ cf['_cf_text_price_text'] %}
                                                    {% endif %}
                                                {% else %}
                                                    {% set totalCart = tag.number_format(item.total) ~ " " ~ currency %}
                                                {% endif %}
                                                {% if loop.first %}
                                                    <div class="table-responsive mailbox-messages">
                                                    <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th width="5%"><input type="checkbox" name="select-all-promotion" class="flat-red"></th>
                                                        <th width="7%">Thứ tự</th>
                                                        <th width="15%">Sản phẩm</th>
                                                        {% if user.role == 3 %}
                                                        <th>Gửi từ tên miền</th>
                                                        {% endif %}
                                                        <th>Mã đơn hàng</th>
                                                        <th>Họ Tên</th>
                                                        <th class="text-center">Thành viên</th>
                                                        <th>Tổng tiền</th>
                                                        <th>Thời gian đặt hàng</th>
                                                        <th width="12%">Phương thức thanh toán</th>
                                                        <th>Tình trạng</th>
                                                        <th width="5%">Xem</th>
                                                        {% if !isNotDeleteOrder %}
                                                        <th width="5%">Xóa</th>
                                                        {% endif %}
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                {% endif %}
                                                <tr>
                                                    <td><input type="checkbox" name="select_all_promotion" class="promotion-item" value="{{ item.id }}"></td>
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
                                                    <td class="text-center">{% if item.member_id != 0 and member is defined and member %}{{ member.username }}{% else %}---{% endif %}</td>
                                                    <td>{{ totalCart }}</td>
                                                    <td>{{ date('H:i d/m/Y', strtotime(item.created_at)) }}</td>
                                                    <td>
                                                        {% if item.payment_method == 1 %}
                                                            {{ word._('_thanh_toan_khi_nhan_hang') }}
                                                        {% else %}
                                                            {{ word._('_thanh_toan_chuyen_khoan_qua_ngan_hang') }}
                                                        {% endif %}
                                                    </td>
                                                    <td>{{ item.status.name }}</td>
                                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/update/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-eye"></i>' ) }}</td>
                                                    {% if !isNotDeleteOrder %}
                                                    <td class="text-center">{{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                                    {% endif %}
                                                </tr>
                                                {% if loop.last %}
                                                    </tbody>

                                                    </table>
                                                    </div>
                                                {% endif %}
                                            {% endfor %}
                                            {% if pageOrders.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'page':pageOrders, 'url_page':ACP_NAME ~ '/orders/allItems', 'html_id':'load-page-all-orders'}) }}</div>{% endif %}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane {% if request.get('active') == 'contact' %}active{% endif %}" id="contact">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="display: block">Danh sách liên hệ</div>
                            <div class="panel-body">
                                {% if setting_page.order_admin_note != '' %}
                                <div class="form-group">{{ setting_page.order_admin_note }}</div>
                                {% endif %}
                                {% if !isNotDeleteOrder %}
                                <div class="clearfix">
                                    <div class="pull-right">
                                        <a href="javascript:;" class="btn btn-danger" onclick="delete_all('contact', {'type':'out-stock'})">Xóa nhiều mục</a>
                                    </div>
                                    <p class="clear"></p>
                                </div>
                                {% endif %}
                                <div class="row">
                                    <div class="col-xs-12">
                                        {% for key,item in contact %}
                                            {% if loop.first %}
                                                <div class="table-responsive mailbox-messages">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%"><input type="checkbox" name="select-all-out-stock" class="flat-red"></th>
                                                            <th width="7%">Thứ tự</th>
                                                            {% if user.role == 3 %}
                                                            <th>Gửi từ tên miền</th>
                                                            {% endif %}
                                                            <th>Họ Tên</th>
                                                            <th>SĐT</th>
                                                            <th>Email</th>
                                                            <th>Địa chỉ</th>
                                                            <th>Chủ đề</th>
                                                            <th>Nội dung</th>
                                                            <th width="15%">Ghi chú</th>
                                                            <th>Ngày giờ</th>
                                                            {% if !isNotDeleteOrder %}
                                                            <th width="5%">Xóa</th>
                                                            {% endif %}
                                                        </tr>
                                                    </thead>

                                                <tbody>
                                                    {% endif %}
                                                    <tr>
                                                        <td><input type="checkbox" class="out-stock-item" name="select_all_out-stock" value="{{ item.id }}"></td>
                                                        <td>{{ key + 1 }}</td>
                                                        {% if user.role == 3 %}
                                                        <td class="text-danger">{{ item.subdomain.name ~ '.' ~ ROOT_DOMAIN }}</td>
                                                        {% endif %}
                                                        <td>{{ item.name}}</td>
                                                        <td>{{ item.phone }}</td>
                                                        <td>{{ item.email }}</td>
                                                        <td>{{ item.address }}</td>
                                                        <td>{{ item.subject }}</td>
                                                        <td>{{ item.comment }}</td>
                                                        <td>
                                                            <textarea name="contact_note" data-id="{{ item.id }}" class="form-control" rows="2">{{ item.note }}</textarea>
                                                        </td>
                                                        <td>{{ date('H:i d/m/Y', strtotime(item.created_at)) }}</td>
                                                        {% if !isNotDeleteOrder %}
                                                        <td class="text-center">{{ link_to(ACP_NAME ~ '/contact/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                                        {% endif %}
                                                    </tr>
                                                    {% if loop.last %}
                                                </tbody>

                                            </table>
                                        </div>
                                            {% endif %}
                                        {% endfor %}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane {% if request.get('active') == 'customer_comment' %}active{% endif %}" id="customer_comment">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="display: block">{{ word['_y_kien_khach_hang'] }}</div>
                            <div class="panel-body">
                                {% if setting_page.order_admin_note != '' %}
                                <div class="form-group">{{ setting_page.order_admin_note }}</div>
                                {% endif %}
                                <div class="clearfix">
                                    <div class="pull-left">{{ link_to(ACP_NAME ~ "/customer_comment/create", "Thêm mới", "class": "btn btn-primary") }}</div>
                                    <div class="pull-right">
                                        <a href="javascript:;" class="btn btn-danger" onclick="delete_all('contact', {'type':'active'})">Xóa nhiều mục</a>
                                    </div>
                                    <p class="clear"></p>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        {% for key,item in customerComments %}
                                            {% if loop.first %}
                                                <div class="table-responsive mailbox-messages">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%"><input type="checkbox" name="select-all-active" class="flat-red"></th>
                                                            <th width="7%">Thứ tự</th>
                                                            {% if user.role == 3 %}
                                                            <th>Gửi từ tên miền</th>
                                                            {% endif %}
                                                            <th>Họ Tên</th>
                                                            <th>SĐT</th>
                                                            <th>Email</th>
                                                            <th>Địa chỉ</th>
                                                            <th>Nội dung</th>
                                                            <th>Hình ảnh</th>
                                                            <th>Ngày giờ</th>
                                                            <th>Kích hoạt</th>
                                                            <th width="5%">Sửa</th>
                                                            {% if !isNotDeleteOrder %}
                                                            <th width="5%">Xóa</th>
                                                            {% endif %}
                                                        </tr>
                                                    </thead>

                                                <tbody>
                                                    {% endif %}
                                                    <tr>
                                                        <td><input type="checkbox" name="select_all_active" class="active-item" value="{{ item.id }}"></td>
                                                        <td>{{ key + 1 }}</td>
                                                        {% if user.role == 3 %}
                                                        <td class="text-danger{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} red bold{% endif %}">{{ item.subdomain.name ~ '.' ~ ROOT_DOMAIN }}</td>
                                                        {% endif %}
                                                        <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.name}}</td>
                                                        <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.phone }}</td>
                                                        <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.email }}</td>
                                                        <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.address }}</td>
                                                        <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ item.comment }}</td>
                                                        <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{% if item.photo != '' %}<img src="{{ item.photo }}" style="width:80px">{% endif %}</td>
                                                        <td{% if item.subdomain.id == subdomainChild.id and user.role == 3 %} class="red bold"{% endif %}>{{ date('H:i d/m/Y', strtotime(item.created_at)) }}</td>
                                                        <td class="text-center">
                                                            {% if item.active == 'Y' %}
                                                                {{ link_to(ACP_NAME ~ '/customer_comment/hide/' ~ item.id, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                                                            {% else %}
                                                                {{ link_to(ACP_NAME ~ '/customer_comment/show/' ~ item.id, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                                                            {% endif %}
                                                        </td>
                                                        <td align="center">{{ link_to(ACP_NAME ~ '/customer_comment/update/' ~ item.id, '<i class="fa fa-edit"></i>' ) }}</td>
                                                        {% if !isNotDeleteOrder %}
                                                        <td class="text-center">{{ link_to(ACP_NAME ~ '/customer_comment/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                                                        {% endif %}
                                                    </tr>
                                                    {% if loop.last %}
                                                </tbody>

                                            </table>
                                        </div>
                                            {% endif %}
                                        {% endfor %}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
