<div class="box_page">
    {{ partial("partials/breadcrumb") }}
    <div class="node-content">
        {# <div class="text-uppercase title"><h1>{{ title_bar }}</h1></div> #}
        <div class="title_bar_center text-uppercase"><h1>{{ title_bar }}</h1></div>
        <div class="form-checkout">
            {{ flashSession.output() }}
            <div class="row">
                {% if layout != 2 %}<div class="col-md-1"></div>{% endif %}
                {% if layout != 2 %}<div class="col-md-10">{% else %}<div class="col-md-12">{% endif %}
                    {% if cf['_cf_radio_show_header_contact_content'] == true %}
                    {{ partial('partials/mic_support_page', {'form_name':'frm_mic_support_header'}) }}
                    {% endif %}
                    {% if setting.contact != '' %}
                    <div id="contact_content">{{ setting.contact }}</div>
                    {% endif %}
                    {% if cf['_cf_radio_show_footer_contact_content'] == true %}
                    {{ partial('partials/mic_support_page', {'form_name':'frm_mic_support_footer'}) }}
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
                            {% if hotline != "" %}
                            <li class="clearfix">
                                <i class="icon-sodienthoai"></i>
                                <span><a href="tel:{{ hotline }}">{{ hotline }}</a></span>
                            </li>
                            {% endif %}
                            {% if setting.email != "" %}
                            <li class="clearfix">
                                <i class="icon-mail"></i>
                                <span><a href="mailto:{{ setting.email }}">{{ setting.email }}</a></span>
                            </li>
                            {% endif %}
                            {% if setting.tax_code != "" %}
                            <li class="clearfix">
                                <i class="fa fa-money"></i>
                                <span>{{ setting.tax_code }}</span>
                            </li>
                            {% endif %}
                        </ul>
                    </div>
                    {% endif %}
                    {% if setting.enable_form_contact == 1 %}
                    {{ form('role':'form', 'name':'frm_checkout') }}
                    <div class="form-group clearfix">
                        <label for="inputFullName">{{ word['_ho_ten'] }}(<span class="red">*</span>):</label>
                        {{ form.render("name",{'class':'form-control','placeholder':word['_ho_ten'] }) }}
                        {{ form.messages('name') }}
                    </div>
                    <div class="form-group clearfix">
                        <label for="inputEmail">Email:</label>
                        {{ form.render("email",{'class':'form-control','placeholder':'Email'}) }}
                    </div>

                    <div class="form-group clearfix">
                        <label for="inputPhone">{{ word['_dien_thoai'] }}(<span class="red">*</span>):</label>
                        {{ form.render("phone",{'class':'form-control','placeholder':word['_dien_thoai']}) }}
                        {{ form.messages('phone') }}
                    </div>

                    <div class="form-group clearfix">
                        <label for="inputAddress">{{ word['_dia_chi'] }}:</label>
                        {{ form.render("address",{'class':'form-control','placeholder':word['_dia_chi']}) }}
                        {{ form.messages('address') }}
                    </div>

                    <div class="form-group clearfix">
                        <label for="inputPhone">{{ word['_tieu_de'] }}:</label>
                        {{ form.render("subject",{'class':'form-control','placeholder':word['_tieu_de']}) }}
                    </div>

                    <div class="form-group clearfix">
                        <label for="inputNote">{{ word['_yeu_cau_khac'] }}:</label>
                        {{ form.render("comment",{'class':'form-control','placeholder':word['_yeu_cau_khac']}) }}
                    </div>

                    <div class="form-group clearfix">
                        <input type="submit" class="btn btn-success" value="{{ word['_gui'] }}">
                    </div>
                    {{ endform() }}
                    {% endif %}
                </div>
                {% if layout != 2 %}<div class="col-md-1"></div>{% endif %}
            </div>
        </div>
        {% if setting.enable_map == 1 %}
        <div class="title_bar_center text-uppercase"><h2>{{ word['_ban_do'] }}</h2></div>
        <div class="map_code">
            {{ setting.map_code }}
        </div>
        {% endif %}
    </div>
</div>