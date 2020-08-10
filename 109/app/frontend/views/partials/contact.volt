<div class="md_contact" id="md-contact-{{ module_id }}">
	{% if position == 'header' or position == 'footer' %}<div class="container">{% endif %}
	<div class="title_bar_right bar_web_bgr text-uppercase">{{ word['_lien_he'] }}</div>
	<div class="md_contact_box">
		{% if setting.contact != '' %}
            <div id="contact_content">{{ setting.contact }}</div>
            {% endif %}
            {% if setting.enable_contact_default == 1 %}
            <div id="content_company_info_contact">
                <ul>
                    {% if setting.name != "" %}
                    <li class="text-uppercase">{{ setting.name }}</li>
                    {% endif %}
                    {% if setting.address != "" %}
                    <li class="clearfix">
                        <i class="icon-dia-chi"></i>
                        <span>{{ setting.address }}</span>
                    </li>
                    {% endif %}
                    {% if hotline is not null %}
                    <li class="clearfix">
                        <i class="icon-sodienthoai"></i>
                        <span>Điện thoại: <a href="tel:{{ hotline }}">{{ hotline }}</a></span>
                    </li>
                    {% endif %}
                    {% if setting.email != "" %}
                    <li class="clearfix">
                        <i class="icon-mail"></i>
                        <span>Email: <a href="mailto:{{ setting.email }}">{{ setting.email }}</a></span>
                    </li>
                    {% endif %}
                </ul>
            </div>
        {% endif %}
        {% if setting.enable_form_contact == 1 %}
        <div id="contact_module_success_{{ position }}"></div>
        {{ form('role':'form', 'name':'frm_md_contact_' ~ position, 'action':'/send-contact') }}
        <div class="form-group clearfix">
            {{ contact_form.render("name",{'class':'form-control','placeholder':'Họ tên(*)'}) }}
            <div id="errorContactName{{ position }}" class="text-danger"></div>
        </div>
        <div class="form-group clearfix">
            {{ contact_form.render("email",{'class':'form-control'}) }}
            <div id="errorContactEmail{{ position }}" class="text-danger"></div>
        </div>

        <div class="form-group clearfix">
            {{ contact_form.render("phone",{'class':'form-control','placeholder':'Điện thoại(*)'}) }}
            <div id="errorContactPhone{{ position }}" class="text-danger"></div>
        </div>

        <div class="form-group clearfix">
            {{ contact_form.render("address",{'class':'form-control','placeholder':'Địa chỉ(*)'}) }}
            <div id="errorContactAddress{{ position }}" class="text-danger"></div>
        </div>

        <div class="form-group clearfix">
            {{ contact_form.render("subject",{'class':'form-control'}) }}
        </div>

        <div class="form-group clearfix">
            {{ contact_form.render("comment",{'class':'form-control'}) }}
        </div>

        <div class="form-group clearfix">
            <button class="btn btn btn-primary ladda-button btn-send-contact-module bar_web_bgr" data-position="{{ position }}" data-style="slide-left"><span class="ladda-label">{{ word['_gui'] }}</span></button>
        </div>
        {{ endform() }}
        {% endif %}
        {% if setting.enable_map != '' and setting.map_code != '' %}
        <div class="title_bar_center text-uppercase"><h2>Bản đồ</h2></div>
        <div class="map_code">{{ setting.map_code }}</div>
        {% endif %}
	</div>
	{% if position == 'header' or position == 'footer' %}</div>{% endif %}
</div>
