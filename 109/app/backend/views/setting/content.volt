{#{{ partial('partials/nav_setting') }}#}
<section class="content">
<div class="row">
<!-- left column -->
<div class="col-md-12">
    {{ content() }}
    {{ flashSession.output() }}
    {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed', 'name':'form_multilang') }}
        <input type="hidden" name="active" value="{% if request.get('active') != '' %}{{ request.get('active') }}{% else %}content{% endif %}">
    <div>
        {% if tmpSubdomainLanguages|length > 0 %}
        <ul id="langTab" class="nav nav-tabs">
            <li class="active">
                <a href="#vi" data-toggle="tab" class="btn btn-info">Việt Nam</a>
            </li>
            {% for tmp in tmpSubdomainLanguages %}
            {% if tmp.language.code != 'vi' %}
                <li>
                    <a href="#{{ tmp.language.code }}" data-toggle="tab" class="btn btn-info">{{ tmp.language.name }}</a>
                </li>
            {% endif %}
            {% endfor %}
        </ul>
        {% endif %}

        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="vi">
                <div class="panel panel-default">                            
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                    {{ form.messages('name') }}
                                </div>

                                <div class="form-group">
                                    {{ form.render("email",{'class':'form-control','id':'email'}) }}
                                    {{ form.messages('email') }}
                                </div>

                                <div class="form-group">
                                    {{ form.render("slogan",{'class':'form-control','id':'slogan'}) }}
                                    {{ form.messages('slogan') }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                      <label class="text-danger" style="font-size:13px">Ghi chú: Để tự động thay đổi  hotline, zalo, sđt trên web ( 60 phút đổi 1 lần ). Nhập list  hotline, zalo, sđt có dấu ; để phân cách mỗi lần đổi, ví dụ: 0911111111;0911111111;0911111111;</label>
                                    {{ form.render("hotline",{'class':'form-control','id':'hotline'}) }}

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ form.render("address",{'class':'form-control','id':'address'}) }}
                                </div>

                                <div class="form-group">
                                    {{ form.render("copyright",{'class':'form-control','id':'copyright'}) }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ form.render("website",{'class':'form-control','id':'website'}) }}
                                </div>
                                <div class="form-group">
                                    {{ form.render("business_license",{'class':'form-control','id':'business_license'}) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ form.render("tax_code",{'class':'form-control'}) }}
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            {% for node,i in list_config_item_arr %}
                                {% if i is not null %}
                                    {% set name = i['name'] %}
                                    {% set type = i['type'] %}
                                    {% set field = i['field'] %}
                                    {% set value = i['value'] %}
                                    {% set min_value = i['min_value'] %}
                                    {% set max_value = i['max_value'] %}
                                    <div class="col-md-6" style="display:flex">
                                        <div class="form-group clearfix" style="width:100%">
                                            <div class="box_config_setting clearfix">
                                                <div class="row">
                                                    <label class="col-md-4 text-right"{% if type == 'text' %} style="margin-top: 5px"{% endif %}>{{ name }}</label>
                                                    <div class="col-md-8">
                                                        <div class="clearfix">
                                                            {% if type == 'text' %}
                                                                <div style="padding-left:20px">
                                                                    <input {% if (min_value != null and min_value != 0) or (max_value != null and max_value != 0) %}type="number"{% else %}type="text"{% endif %} name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ i['place_holder'] }}" {% if min_value != null and min_value != 0 %}min="{{ min_value }}"{% endif %} {% if max_value != null and max_value != 0 %}max="{{ max_value }}"{% endif %}>
                                                                </div>
                                                            {% endif %}

                                                            {% if type == 'email' %}
                                                                <div>
                                                                    <input type="email" name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ i['place_holder'] }}">
                                                                </div>
                                                            {% endif %}

                                                            {% if type == 'textarea' %}
                                                                <div style="padding-left: 20px">
                                                                    <textarea name="{{ field }}" class="form-control" rows="1" placeholder="{{ i['place_holder'] }}">{{ value }}</textarea>
                                                                </div>
                                                            {% endif %}

                                                            {% if type == 'radio' %}
                                                                {% for j in i['list_value'] %}
                                                                    <div class="radio pull-left" style="margin-top: 0;{% if i['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                        <label>
                                                                            <input type="radio" name="{{ field }}[]" value="{{ j['name'] }}"{% if j['select'] == 1 %} checked{% endif %} class="flat-red"><span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ j['name'] }}</span>
                                                                        </label>
                                                                    </div>
                                                                {% endfor %}
                                                            {% endif %}

                                                            {% if type == 'checkbox' %}
                                                                {% for j in i['list_value'] %}
                                                                    <div class="checkbox" style="margin-top: 0;{% if i['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                        <label>
                                                                            <input type="checkbox" name="{{ field }}[]" value="{{ j['name'] }}"{% if j['select'] == 1 %} checked{% endif %} class="flat-red">
                                                                            <span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ j['name'] }}</span>
                                                                        </label>
                                                                    </div>
                                                                {% endfor %}
                                                            {% endif %}

                                                            {% if type == 'select' %}
                                                                <div class="checkbox" style="margin-top: 0;{% if i['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                    <label>
                                                                        <select class="form-control" name="{{ field }}">
                                                                            {% for j in i['list_value'] %}
                                                                                <option value="{{ j['name'] }}"{% if j['select'] == 1 %} selected{% endif %}>{{ j['name'] }}</option>
                                                                            {% endfor %}
                                                                        </select>
                                                                    </label>
                                                                </div>
                                                            {% endif %}
                                                        </div>

                                                        {% if i['guide'] is defined %}
                                                            <p><a href="javascript:;" class="view-config-guide" data-id="{{ i['config_core_id'] }}" style="color: #f00;font-size: 12px;text-decoration: underline"><i>Click để xem hướng dẫn</i></a></p>
                                                        {% endif %}
                                                    </div>
                                                </div>
                                                {% if i['child'] is defined and i['child']|length > 0 %}
                                                    {% set child = i['child'] %}
                                                    {% for k in child %}
                                                        {% set name = k['name'] %}
                                                        {% set type = k['type'] %}
                                                        {% set field = k['field'] %}
                                                        {% set value = k['value'] %}
                                                        {% set min_value = k['min_value'] %}
                                                        {% set max_value = k['max_value'] %}
                                                        <div class="row">
                                                            <label class="col-md-4 text-right"{% if type == 'text' %} style="margin-top: 5px"{% endif %}>{{ name }}</label>
                                                            <div class="col-md-8">
                                                                <div class="clearfix">
                                                                    {% if type == 'text' %}
                                                                        <div style="padding-left:20px">
                                                                            <input {% if (min_value != null and min_value != 0) or (max_value != null and max_value != 0) %}type="number"{% else %}type="text"{% endif %} name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ k['place_holder'] }}" {% if min_value != null and min_value != 0 %}min="{{ min_value }}"{% endif %} {% if max_value != null and max_value != 0 %}max="{{ max_value }}"{% endif %} style="margin-bottom:10px">
                                                                        </div>
                                                                    {% endif %}

                                                                    {% if type == 'email' %}
                                                                        <div>
                                                                            <input type="email" name="{{ field }}" value="{{ value }}" class="form-control" placeholder="{{ k['place_holder'] }}">
                                                                        </div>
                                                                    {% endif %}

                                                                    {% if type == 'textarea' %}
                                                                        <div style="padding-left: 20px">
                                                                            <textarea name="{{ field }}" class="form-control" rows="1" placeholder="{{ k['place_holder'] }}">{{ value }}</textarea>
                                                                        </div>
                                                                    {% endif %}

                                                                    {% if type == 'radio' %}
                                                                        {% for l in k['list_value'] %}
                                                                            <div class="radio pull-left" style="margin-top: 0;{% if k['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                <label>
                                                                                    <input type="radio" name="{{ field }}[]" value="{{ l['name'] }}"{% if l['select'] == 1 %} checked{% endif %} class="flat-red"><span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ l['name'] }}</span>
                                                                                </label>
                                                                            </div>
                                                                        {% endfor %}
                                                                    {% endif %}

                                                                    {% if type == 'checkbox' %}
                                                                        {% for l in k['list_value'] %}
                                                                            <div class="checkbox" style="margin-top: 0;{% if k['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                                <label>
                                                                                    <input type="checkbox" name="{{ field }}[]" value="{{ l['name'] }}"{% if l['select'] == 1 %} checked{% endif %} class="flat-red">
                                                                                    <span class="text-sm" style="margin-left: 7px;vertical-align: middle">{{ l['name'] }}</span>
                                                                                </label>
                                                                            </div>
                                                                        {% endfor %}
                                                                    {% endif %}

                                                                    {% if type == 'select' %}
                                                                        <div class="checkbox" style="margin-top: 0;{% if k['guide'] is defined %}margin-bottom:0{% endif %}">
                                                                            <label>
                                                                                <select class="form-control" name="{{ field }}">
                                                                                    {% for l in k['list_value'] %}
                                                                                        <option value="{{ l['name'] }}"{% if l['select'] == 1 %} selected{% endif %}>{{ l['name'] }}</option>
                                                                                    {% endfor %}
                                                                                </select>
                                                                            </label>
                                                                        </div>
                                                                    {% endif %}
                                                                </div>

                                                                {% if k['guide'] is defined %}
                                                                <p><a href="javascript:;" class="view-config-guide" data-id="{{ k['config_core_id'] }}" style="color: #f00;font-size: 12px;text-decoration: underline"><i>Click để xem hướng dẫn</i></a></p>
                                                                {% endif %}
                                                            </div>
                                                        </div>
                                                    {% endfor %}
                                                {% endif %}
                                            </div>
                                        </div>
                                    </div>
                                {% endif %}
                            {% endfor %}
                        </div>
                        
                        <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                            <div class="col-md-12">
                                <div class="row form-group">
                                    <div class="col-md-3">
                                        <label>
                                            Logo
                                        </label>
                                        {{ form.render("logo",{'id':'logo'}) }}
                                        <div class="text-center" style="margin-top:20px;border: 1px solid #ccc; padding: 10px;">
                                            {% if setting.logo != '' %}
                                                <p>{{ image('files/default/' ~ SUB_FOLDER ~ '/' ~ setting.logo , 'style':'margin-top:40px;margin-bottom:40px;max-width:200px') }}</p>
                                                <p><a href="/{{ ACP_NAME ~ '/' ~ router.getControllerName() }}/deleteImate?type=logo&language=vi" class="btn btn-sm btn-danger" onclick="if(!confirm('Xác nhận xóa?')) return false"><i class="fa fa-times"></i> Xóa hình</a></p>
                                            {% endif %}
                                            {{ form.render("enable_logo_text",{'class':'form-control', 'style':'width:50%', 'value':0}) }}
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="title">Chữ trên</label>
                                                {{ form.render('logo_text_up', {'class':'form-control'}) }}
                                            </div>
                                            <div class="col-md-5">
                                                <label for="title">Chữ dưới</label>
                                                {{ form.render('logo_text_down', {'class':'form-control'}) }}
                                            </div>
                                        </div>
                                        <div id="load_logo_fonts" style="border:1px solid #ccc;padding:10px">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="text-center form-group" id="logo_text_type_1">
                                                        <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                        <p class="text2_logo">{{ logo_text_down }}</p>
                                                    </div>
                                                    <div class="text-center">
                                                        {{ form.render("enable_logo_text",{'class':'form-control', 'style':'width:50%', 'value':1}) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="text-center form-group" id="logo_text_type_2">
                                                        <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                        <p class="text2_logo">{{ logo_text_down }}</p>
                                                    </div>
                                                    <div class="text-center">
                                                        {{ form.render("enable_logo_text",{'class':'form-control', 'style':'width:50%', 'value':2}) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="text-center form-group" id="logo_text_type_3">
                                                        <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                        <p class="text2_logo">{{ logo_text_down }}</p>
                                                    </div>
                                                    <div class="text-center">
                                                        {{ form.render("enable_logo_text",{'class':'form-control', 'style':'width:50%', 'value':3}) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="text-center form-group" id="logo_text_type_4">
                                                        <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                        <p class="text2_logo">{{ logo_text_down }}</p>
                                                    </div>
                                                    <div class="text-center">
                                                        {{ form.render("enable_logo_text",{'class':'form-control', 'style':'width:50%', 'value':4}) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="text-center form-group" id="logo_text_type_5">
                                                        <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                        <p class="text2_logo">{{ logo_text_down }}</p>
                                                    </div>
                                                    <div class="text-center">
                                                        {{ form.render("enable_logo_text",{'class':'form-control', 'style':'width:50%', 'value':5}) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="text-center form-group" id="logo_text_type_6">
                                                        <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                        <p class="text2_logo">{{ logo_text_down }}</p>
                                                    </div>
                                                    <div class="text-center">
                                                        {{ form.render("enable_logo_text",{'class':'form-control', 'style':'width:50%', 'value':6}) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row" style="border-top:2px solid #007bff;padding-top: 5px">
                                    <div class="col-md-3">
                                        <label for="title">Favicon</label>
                                        {{ form.render("favicon",{'id':'favicon'}) }}
                                    </div>
                                    <div class="col-md-2" style="pardding-top:20px">
                                        {% if setting.favicon != '' %}
                                            <p>{{ image('files/default/' ~ SUB_FOLDER ~ '/' ~ setting.favicon , 'style':'width:16px;margin-top:10px') }}</p>
                                            <p><a href="/{{ ACP_NAME ~ '/' ~ router.getControllerName() }}/deleteImate?type=favicon" class="btn btn-sm btn-danger" onclick="if(!confirm('Xác nhận xóa?')) return false"><i class="fa fa-times"></i> Xóa hình</a></p>
                                        {% endif %}
                                    </div>
                                    <div class="col-md-2"><b>(16 x 16)px</b></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="border-top:2px solid #007bff;padding-top: 5px">
                                    <div class="col-md-3">
                                        <label for="title">Hình nền yêu cầu báo giá</label>
                                        {{ form.render("bgr_ycbg",{'id':'bgr_ycbg'}) }}
                                    </div>
                                    <div class="col-md-6" style="pardding-top:20px">{% if setting.bgr_ycbg != '' %}
                                        <p>{{ image('files/default/' ~ SUB_FOLDER ~ '/' ~ setting.bgr_ycbg , 'style':'width:100px;margin-top:10px') }}</p>
                                        <p><a href="/{{ ACP_NAME ~ '/' ~ router.getControllerName() }}/deleteImate?type=bgr_ycbg" class="btn btn-sm btn-danger" onclick="if(!confirm('Xác nhận xóa?')) return false"><i class="fa fa-times"></i> Xóa hình</a></p>
                                    {% endif %}</div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row" style="border-top:2px solid #007bff;padding-top: 5px">
                                    <div class="col-md-3">
                                        <label for="title">Hình đại diện share mạng XH(Facebook, Zalo,...) | <a href="https://docs.google.com/document/d/1PPgDGulXNlL2CE3jLL0w_6b1yD_tu9SzijSVF0eUd_g/edit" target="_blank">Xem hướng dẫn</a></label>
                                        {{ form.render("image_meta",{'id':'image_meta'}) }}
                                    </div>
                                    <div class="col-md-6" style="pardding-top:20px">
                                        {% if setting.image_meta != '' %}
                                        <p>{{ image('files/default/' ~ SUB_FOLDER ~ '/' ~ setting.image_meta , 'style':'width:100px;margin-top:10px') }}</p>
                                        <p><a href="/{{ ACP_NAME ~ '/' ~ router.getControllerName() }}/deleteImate?type=image_meta" class="btn btn-sm btn-danger" onclick="if(!confirm('Xác nhận xóa?')) return false"><i class="fa fa-times"></i> Xóa hình</a></p>
                                        {% endif %}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="border-top:2px solid #007bff;padding-top: 5px">
                                    <div class="col-md-3">
                                        <label for="title">Hình bên trái menu top</label>
                                        {{ form.render("image_menu_2",{'id':'image_menu_2'}) }}
                                        {% if setting.image_menu_2 != '' %}
                                            <p>{{ image('files/default/' ~ SUB_FOLDER ~ '/' ~ setting.image_menu_2 , 'style':'width:30px;margin-top:10px') }}</p>
                                            <p><a href="/{{ ACP_NAME ~ '/' ~ router.getControllerName() }}/deleteImate?type=image_menu_2" class="btn btn-sm btn-danger" onclick="if(!confirm('Xác nhận xóa?')) return false"><i class="fa fa-times"></i> Xóa hình</a></p>
                                        {% endif %}
                                    </div>
                                    <div class="col-md-3" style="padding-top:25px">
                                        <label>
                                            <input type="radio" name="enable_image_menu_2" value="1" {% if setting.enable_image_menu_2 == 1 %}checked{% else %}checked{% endif %}> Hiện
                                        </label>
                                        <label>
                                            <input type="radio" name="enable_image_menu_2" value="0" {% if setting.enable_image_menu_2 == 0 %}checked{% endif %}> Ẩn
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                            <div class="col-md-12">
                                <label for="title">Bài trang chủ</label>
                                <p></p>
                                {{ partial('partials/ajaxupload', ['id': setting.id, 'type':'article_home', 'img_upload_paths':img_upload_home_paths]) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group editor-form">
                                    {{ form.render("article_home",{'class':'form-control'}) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="box_label_checkbox">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="enable_form_reg_article_home" value="1" class="minimal"{% if (setting.enable_form_reg_article_home == 1) %} checked{% endif %}> Hiện yêu cầu báo giá bên phải
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="enable_video_article_home" value="1" class="minimal"{% if (setting.enable_video_article_home == 1) %} checked{% endif %} style="margin-top: 0"> Hiện video bên phải
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        {{ form.render("youtube_code",{'class':'form-control'}) }}
                                    </div>
                                    <div class="checkbox" style="margin-top:20px">
                                        <label>
                                             {{ form.render("enable_image_article_home",{'class':'minimal', 'style':'margin-top:0'}) }} Hiện hình ảnh bên phải
                                        </label>
                                    </div>
                                    <div class="checkbox" style="margin-top:20px">
                                        <label>
                                             {{ form.render("enable_search_advance_article_home",{'class':'minimal', 'style':'margin-top:0'}) }} Hiện tìm kiếm bên phải
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>Chọn hình</label>
                                        {{ form.render("image_article_home") }}
                                        {% if setting.image_article_home != '' and file_exists('files/default/' ~ SUB_FOLDER ~ '/' ~ setting.image_article_home) %}
                                            <p>{{ image('../files/default/' ~ SUB_FOLDER ~ '/' ~ setting.image_article_home, 'style':'width:90px;margin-top:10px') }}</p>
                                            <p><a href="/{{ ACP_NAME ~ '/' ~ router.getControllerName() }}/deleteImate?type=image_article_home" class="btn btn-sm btn-danger" onclick="if(!confirm('Xác nhận xóa?')) return false"><i class="fa fa-times"></i> Xóa hình</a></p>
                                        {% endif %}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                            <div class="col-md-8">
                                <div class="form-group editor-form">
                                    <label for="title">Bài Liên Hệ</label>
                                    {{ form.render("contact",{'class':'form-control','id':'contact'}) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="box_label_checkbox" style="margin-top: 25px">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="enable_contact_default" value="1" class="minimal"{% if (setting.enable_contact_default == 1) %} checked{% endif %}> Hiện Liên Hệ Mặc Định
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="enable_map" value="1" class="minimal"{% if (setting.enable_map == 1) %} checked{% endif %}> Hiện bản đồ trong liên hệ
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="enable_form_contact" value="1" class="minimal"{% if (setting.enable_form_contact == 1) %} checked{% endif %}> Hiện Form Liên Hệ
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>Code nhúng bản đồ (hiện trong footer và menu liên hệ)</label>
                                        {{ form.render("map_code",{'class':'form-control'}) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                            <div class="col-md-12">
                                <div class="form-group editor-form">
                                    <label for="title">Bài Footer</label>
                                    <label style="padding-left: 28%">
                                        <input type="checkbox" name="enable_footer_default" value="1" class="minimal"{% if (setting.enable_footer_default == 1) %} checked{% endif %}> Hiện Footer Mặc Định
                                    </label>
                                    {{ form.render("footer",{'class':'form-control','id':'footer'}) }}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                            <div class="col-md-12">
                                <div class="form-group editor-form">
                                    <label for="title">Ghi chú phương thức Thanh toán chuyển khoản qua ngân hàng</label>
                                    {{ form.render("note_payment_method_2",{'class':'form-control','id':'note_payment_method_2'}) }}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                            <div class="col-md-6">
                                <label for="keywords">Thẻ Title</label>
                                <div class="form-group">
                                    {{ form.render("title",{'class':'form-control','id':'title'}) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keywords">Thẻ Keywords</label>
                                    {{ form.render("keywords",{'class':'form-control','id':'keywords', 'rows':1}) }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Thẻ Description</label>
                                    {{ form.render("description",{'class':'form-control','id':'description', 'rows':1}) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Thẻ Head</label>
                                    {{ form.render("head_content",{'class':'form-control','id':'head_content', 'rows':10}) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Thẻ Body</label>
                                    {{ form.render("body_content",{'class':'form-control','id':'body_content', 'rows':10}) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ form.render("facebook",{'class':'form-control','id':'facebook'}) }}
                                </div>

                                <div class="form-group">
                                    {{ form.render("twitter",{'class':'form-control','id':'twitter'}) }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ form.render("google",{'class':'form-control','id':'google'}) }}
                                </div>

                                <div class="form-group">
                                    {{ form.render("youtube",{'class':'form-control','id':'youtube'}) }}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                            <div class="col-md-12">
                                <div class="form-group editor-form-order">
                                    <label for="title">Ghi chú đơn hàng(quản trị)</label>
                                    {{ form.render("order_admin_note",{'class':'form-control'}) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {% for tmp in tmpSubdomainLanguages %}
            {% if tmp.language.code != 'vi' %}
                {% set langCode = tmp.language.code %}
                {% set langName = tmp.language.name %}
                <div class="tab-pane fade tab-other-lang-{{ langCode }}" id="{{ langCode }}">
                    <div class="panel panel-default">                            
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ form.render("name_" ~ langCode,{'class':'form-control'}) }}
                                        {{ form.messages('name_' ~ langCode) }}
                                    </div>

                                    <div class="form-group">
                                        {{ form.render("slogan_" ~ langCode,{'class':'form-control'}) }}
                                        {{ form.messages('slogan_' ~ langCode) }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ form.render("address_" ~ langCode, {'class':'form-control'}) }}
                                    </div>

                                    <div class="form-group">
                                        {{ form.render("copyright_" ~ langCode,{'class':'form-control'}) }}
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ form.render("business_license_" ~ langCode, {'class':'form-control'}) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group" style="border-top:2px solid #007bff;padding-top: 5px">
                                <div class="col-md-12"><label><i class="text-danger">Ghi chú: Nếu sử dụng 1 logo cho tất cả ngôn ngữ chỉ cần upload logo bên tab Tiếng Việt</i></label></div>
                                <div class="col-md-3">
                                    <label>
                                        Logo
                                    </label>
                                    {{ form.render("logo_" ~ langCode) }}
                                    <div class="text-center" style="margin-top:20px;border: 1px solid #ccc; padding: 10px;">
                                        {% if settingLangData[langCode] is defined and file_exists('files/default/' ~ SUB_FOLDER ~ '/' ~ settingLangData[langCode].logo)  %}
                                        
                                        {% endif %}
                                        {% if settingLangData[langCode].logo != '' %}
                                            <p>{{ image('files/default/' ~ SUB_FOLDER ~ '/' ~ settingLangData[langCode].logo, 'style':'margin-top:40px;margin-bottom:40px;max-width:200px') }}</p>
                                            <p><a href="/{{ ACP_NAME ~ '/' ~ router.getControllerName() }}/deleteImate?type=logo&language={{ langCode }}" class="btn btn-sm btn-danger" onclick="if(!confirm('Xác nhận xóa?')) return false"><i class="fa fa-times"></i> Xóa hình</a></p>
                                        {% endif %}
                                        {{ form.render("enable_logo_text_" ~ langCode ,{'class':'form-control', 'style':'width:50%', 'value':0}) }}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                       <div class="col-md-4">
                                            <label for="title">Chữ trên</label>
                                            {{ form.render('logo_text_up_' ~ langCode, {'class':'form-control'}) }}
                                        </div>
                                        <div class="col-md-5">
                                            <label for="title">Chữ dưới</label>
                                            {{ form.render('logo_text_down_' ~ langCode, {'class':'form-control'}) }}
                                        </div>
                                    </div>
                                    <div class="load_logo_fonts" style="padding:10px;border:1px solid #ccc">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="text-center form-group" id="logo_text_type_1">
                                                    <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                    <p class="text2_logo">{{ logo_text_down }}</p>
                                                </div>
                                                <div class="text-center">
                                                    {{ form.render("enable_logo_text_" ~ langCode,{'class':'form-control', 'style':'width:50%', 'value':1}) }}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-center form-group" id="logo_text_type_2">
                                                    <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                    <p class="text2_logo">{{ logo_text_down }}</p>
                                                </div>
                                                <div class="text-center">
                                                    {{ form.render("enable_logo_text_" ~ langCode,{'class':'form-control', 'style':'width:50%', 'value':2}) }}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-center form-group" id="logo_text_type_3">
                                                    <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                    <p class="text2_logo">{{ logo_text_down }}</p>
                                                </div>
                                                <div class="text-center">
                                                    {{ form.render("enable_logo_text_" ~ langCode, {'class':'form-control', 'style':'width:50%', 'value':3}) }}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-center form-group" id="logo_text_type_4">
                                                    <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                    <p class="text2_logo">{{ logo_text_down }}</p>
                                                </div>
                                                <div class="text-center">
                                                    {{ form.render("enable_logo_text_" ~ langCode,{'class':'form-control', 'style':'width:50%', 'value':4}) }}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-center form-group" id="logo_text_type_5">
                                                    <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                    <p class="text2_logo">{{ logo_text_down }}</p>
                                                </div>
                                                <div class="text-center">
                                                    {{ form.render("enable_logo_text_" ~ langCode,{'class':'form-control', 'style':'width:50%', 'value':5}) }}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-center form-group" id="logo_text_type_6">
                                                    <p class="shadow_logo text1_logo text-uppercase txt_web_color">{{ logo_text_up }}</p>
                                                    <p class="text2_logo">{{ logo_text_down }}</p>
                                                </div>
                                                <div class="text-center">
                                                    {{ form.render("enable_logo_text_" ~ langCode,{'class':'form-control', 'style':'width:50%', 'value':6}) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                                <div class="col-md-12">
                                    <label for="title">Bài trang chủ</label>
                                    <p></p>
                                    {% set row_id_key = row_id_lang[langCode]  %}
                                    {{ partial('partials/ajaxupload', ['id': row_id_key, 'type':'article_home' , 'img_upload_paths':img_upload_home_lang_paths[langCode]]) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group editor-form">
                                        {{ form.render("article_home_" ~ langCode,{'class':'form-control'}) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                                <div class="col-md-8">
                                    <div class="form-group editor-form">
                                        <label for="title">Bài Liên Hệ</label>
                                        {{ form.render("contact_" ~ langCode,{'class':'form-control'}) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                                <div class="col-md-12" style="border-top:2px solid #007bff;padding-top: 5px">
                                    <div class="form-group editor-form">
                                        <label for="title">Bài Footer</label>
                                        {{ form.render("footer_" ~ langCode,{'class':'form-control'}) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                                <div class="col-md-12" style="border-top:2px solid #007bff;padding-top: 5px">
                                    <div class="form-group editor-form">
                                        <label for="title">Ghi chú phương thức Thanh toán chuyển khoản qua ngân hàng</label>
                                        {{ form.render("note_payment_method_2_" ~ langCode,{'class':'form-control'}) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="border-top:2px solid #007bff;padding-top: 5px">
                                <div class="col-md-6">
                                    <label for="keywords">Thẻ Title</label>
                                    <div class="form-group">
                                        {{ form.render("title_" ~ langCode,{'class':'form-control'}) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="keywords">Thẻ Keywords</label>
                                        {{ form.render("keywords_" ~ langCode,{'class':'form-control','id':'keywords', 'rows':1}) }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Thẻ Description</label>
                                        {{ form.render("description_"  ~ langCode,{'class':'form-control','id':'description', 'rows':1}) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {% endif %}
            {% endfor %}
        </div>
    </div>

    <div class="box-footer">
        {{ submit_button("Save", "class": "btn btn-primary","data-type":"save") }}
    </div>
    {{ endform() }}
</div>
</div>
</section>
<div class="modal modal fade" id="modalViewConfigGuide" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Hướng dẫn cấu hình</h4>
        </div>
        <div class="modal-body">
        </div>
      </div>
    </div>
</div>
<div class="modal modal fade" id="myModalEditCss" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      {{ form('role':'form','action':'', 'id':'form-edit-css') }}
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Sửa css banner</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" id="save-css" class="btn btn-primary">Lưu</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      {{ endform() }}
      
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
          var target = $(e.target).attr("href") // activated tab
          $('input[name=active]').val(target.replace('#', ''));
        });

        CKEDITOR.replace( 'article_home',{
            allowedContent:true,
            extraAllowedContent: 'code(*){*}[*]',
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('.editor-form').width(),
            height: 200,
            filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
            filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
            filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

            filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
            filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
            filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
        });
        CKEDITOR.replace( 'contact',{
            allowedContent:true,
            extraAllowedContent: 'b i',
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('.editor-form').width(),
            height: 200,
            filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
            filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
            filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

            filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
            filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
            filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
        });
        CKEDITOR.replace( 'footer',{
            allowedContent:true,
            extraAllowedContent: 'b i',
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('.editor-form').width(),
            height: 200,
            filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
            filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
            filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

            filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
            filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
            filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
        });

        CKEDITOR.replace( 'note_payment_method_2',{
            allowedContent:true,
            extraAllowedContent: 'b i',
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('.editor-form').width(),
            height: 100,
            filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
            filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
            filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

            filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
            filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
            filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
        });

        CKEDITOR.replace( 'order_admin_note',{
            allowedContent:true,
            extraAllowedContent: 'b i',
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('.editor-form-order').width(),
            height: 200,
            filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
            filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
            filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

            filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
            filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
            filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
        });

        {% for tmp in tmpSubdomainLanguages %}
            {% set langCode  = tmp.language.code %}
            {% if langCode != 'vi' %}

            CKEDITOR.replace( 'article_home_{{ langCode }}',{
                allowedContent:true,
                extraAllowedContent: 'code(*){*}[*]',
                removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
                uiColor : '#3c8dbc',
                language:'en',
                skin:'moono',
                width: $('.editor-form').width(),
                height: 200,
                filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
                filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
                filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

                filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
                filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
                filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
            });

            CKEDITOR.replace( 'contact_{{ langCode }}',{
                allowedContent:true,
                extraAllowedContent: 'b i',
                removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
                uiColor : '#3c8dbc',
                language:'en',
                skin:'moono',
                width: $('.editor-form').width(),
                height: 200,
                filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
                filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
                filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

                filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
                filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
                filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
            });

            CKEDITOR.replace( 'footer_{{ langCode }}',{
                allowedContent:true,
                extraAllowedContent: 'b i',
                removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
                uiColor : '#3c8dbc',
                language:'en',
                skin:'moono',
                width: $('.editor-form').width(),
                height: 200,
                filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
                filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
                filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

                filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
                filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
                filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
            });

            CKEDITOR.replace( 'note_payment_method_2_{{ langCode }}',{
                allowedContent:true,
                extraAllowedContent: 'b i',
                removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
                uiColor : '#3c8dbc',
                language:'en',
                skin:'moono',
                width: $('.editor-form').width(),
                height: 100,
                filebrowserImageBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Images") }}',
                filebrowserFlashBrowseUrl : '{{ url("ckfinder/ckfinder.html?Type=Flash") }}',
                filebrowserLinkBrowseUrl : '{{ url("ckfinder/ckfinder.html") }}',

                filebrowserImageUploadUrl :'{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images") }}',
                filebrowserFlashUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash") }}',
                filebrowserLinkUploadUrl : '{{ url("ckfinder/core/connector/php/connector.php?command=QuickUpload") }}'
            });
            {% endif %}
        {% endfor %}

        {% if tmpSubdomainLanguages|length > 0 %}
        $('form[name=form_multilang]').submit(function(e) {
            {% for tmp in tmpSubdomainLanguages %}
                {% set langCode  = tmp.language.code %}
                {% set langName  = tmp.language.name %}
                {% if langCode != 'vi' %}
                    if ($('input[name="name_{{ langCode }}"]').val() == '') {
                        toastr.error('Bạn chưa nhập đủ dữ liệu yêu cầu bên ngôn ngữ {{ langName }}');
                        e.preventDefault();
                        return false;
                    }
                {% endif %}
            {% endfor %}
        })
        {% endif %}
        
    });
</script>
