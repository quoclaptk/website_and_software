{% for key,item in pageFrmItemYcbg.items %}
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
                        {% if cf['_frm_ycbg_comment'] == true %}
                            <th width="25%">{{ word['_yeu_cau_khac'] }}</th>
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
                    <td>{{ item.name }}</td>
                    <td>{{ item.phone }}</td>
                    {% if cf['_frm_ycbg_email'] == true %}
                        <td>{{ item.email }}</td>
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
                        <td>{{ date('d/m/Y', strtotime(item.day)) ~ ' - ' ~ item.hour ~ item.minute }}</td>
                    {% endif %}
                    {% if cf['_frm_ycbg_comment'] == true %}
                        <td>{{ item.comment }}</td>
                    {% endif %}
                    <td>{{ date('H:i d/m/Y', strtotime(item.created_at)) }}</td>
                    <td>
                        <textarea name="form_item_note[{{ item.id }}]" class="form-control" rows="2">{{ item.note }}</textarea>
                    </td>
                    <td class="text-center">{{ link_to(ACP_NAME ~ '/form_item/_delete/' ~ item.id ~ '/' ~ page_current, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}</td>
                </tr>
                {% if loop.last %}
            </tbody>

        </table>
    </div>
    {% endif %}
{% endfor %}
{% if pageFrmItemYcbg.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'page':pageFrmItemYcbg, 'url_page':ACP_NAME ~ '/form_item/allItems', 'html_id':'load-page-all-form-item-ycbg'}) }}</div>{% endif %}