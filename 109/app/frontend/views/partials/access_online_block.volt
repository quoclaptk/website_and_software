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