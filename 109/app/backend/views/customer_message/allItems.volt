{% for key,item in pageCustomerMessages.items %}
    {% if loop.first %}
        <div class="table-responsive mailbox-messages">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th width="5%"><button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
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
                        <th width="5%">Xóa</th>
                    </tr>
                </thead>

            <tbody>
                {% endif %}
                <tr>
                    <td><input type="checkbox" name="select_all" value="{{ item.id }}"></td>
                    <td>{{ key + 1 }}</td>
                    {% if user.role == 3 %}
                    <td class="text-danger">{{ item.subdomain.name ~ '.' ~ ROOT_DOMAIN }}</td>
                    {% endif %}
                    {% if cf['_customer_message_name'] == true %}
                    <td>{{ item.name }}</td>
                    {% endif %}
                    {% if cf['_customer_message_phone'] == true %}
                    <td>{{ item.phone }}</td>
                    {% endif %}
                    {% if cf['_cf_radio_cmg_address'] == true %}
                    <td>{{ item.address }}</td>
                    {% endif %}
                    {% if cf['_cf_radio_cmg_birthday'] == true %}
                    <td>{{ date('d/m/Y', strtotime(item.birthday)) }}</td>
                    {% endif %}
                    {% if cf['_cf_radio_cmg_work_province'] == true %}
                    <td>{{ item.work_province }}</td>
                    {% endif %}
                    {% if cf['_cf_radio_cmg_home_town'] == true %}
                    <td>{{ item.home_town }}</td>
                    {% endif %}
                    {% if cf['_cf_radio_cmg_voice'] == true %}
                    <td>{{ item.voice }}</td>
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
                    <td>{{ item.comment }}</td>
                    {% endif %}
                    <td>{{ date('H:i d/m/Y', strtotime(item.created_at)) }}</td>
                    <td>
                        <textarea name="customer_message_note[{{ item.id }}]" class="form-control" rows="2">{{ item.note }}</textarea>
                    </td>
                    <td class="text-center">{{ link_to(ACP_NAME ~ '/customer_message/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                </tr>
                {% if loop.last %}
            </tbody>

        </table>
    </div>
    {% endif %}
{% endfor %}
{% if pageCustomerMessages.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'page':pageCustomerMessages, 'url_page':ACP_NAME ~ '/customer_message/allItems', 'html_id':'load-page-all-customer-message'}) }}</div>{% endif %}