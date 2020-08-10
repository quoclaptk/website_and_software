<div class="box_md_date_time">
	<div class="title_md_date_time title_bar_{{ position }} bar_web_bgr text-uppercase"><h2>{{ word._('_cap_nhat_hang_ngay_chuong_trinh_bat_dau_tu') }}</h2></div>
	<div class="box_md_date_time_elm">
		<div class="txt_md_date_time_today text-uppercase">{{ word._('_hom_nay') }}</div>
		<div class="txt_md_date_time_day">{{ date('d/m/Y', time()) }}</div>
		<div class="txt_md_date_time_hour">{{ date('H:i', time()) }}</div>
	</div>
</div>