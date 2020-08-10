{{ partial('partials/content_header') }}

<section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      {#<p><b class="text-danger">Chú ý: Website đa ngôn ngữ thì phải nhập dữ liệu đa ngôn ngữ bên Danh mục sản phẩm trước khi quản trị phần này để đồng bộ dữ liệu (VD: Website có 2 ngôn ngữ Việt Nam + English thì phải nhập cả phần tiếng Việt và tiếng Anh bên Danh mục sản phẩm trước)</b></p>#}
      <div id="errorValid">
        {{ content() }}
      </div>
      {{ flashSession.output() }}

      {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed', 'name':'form_multilang') }}
      {{ form.render("id") }}

      <div class="panel panel-default">
        <div class="panel-heading">{{ title_bar }}</div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-8">
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
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Tên<span class="text-danger">(*)</span></label>
                            {{ form.render("name",{'class':'form-control set_url','id':'name'}) }}
                            {{ form.messages('name') }}
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Mã sản phẩm</label>
                            {{ form.render("code",{'class':'form-control','id':'code'}) }}
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Giá gốc</label>
                            {{ form.render("cost",{'class':'form-control','id':'cost'}) }}
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Giá khuyến mãi</label>
                            {{ form.render("price",{'class':'form-control','id':'price'}) }}
                          </div>
                        </div>
                      </div>

                      {% if cf['_cf_text_price_usd_currency'] is defined %}
                      <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Giá gốc ({{ cf['_cf_text_price_usd_currency'] }})</label>
                            {{ form.render("cost_usd",{'class':'form-control'}) }}
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Giá khuyến mãi ({{ cf['_cf_text_price_usd_currency'] }})</label>
                            {{ form.render("price_usd",{'class':'form-control'}) }}
                          </div>
                        </div>
                      </div>
                      {% endif %}

                      {% if product_element %}
                      <div class="row">
                        {% for i in product_element %}
                        {% set product_element_detail = i['product_element_detail'] %}
                        {% if product_element_detail != '' %}
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>{{ i['name'] }}</label>
                            <select class="form-control" name="product_element[{{ i['id'] }}]">
                              <option value="">Chọn {{ i['name'] }}</option>
                              {% for j in product_element_detail %}
                              <option value="{{ j['id'] }}"{% if tmp_product_product_element_detail_arr is defined and tmp_product_product_element_detail_arr[j['id']] is defined  %} selected="selected"{% endif %}>{{ j['name'] }}</option>
                              {% endfor %}
                            </select>
                          </div>
                        </div>
                        {% endif %}
                        {% endfor %}
                      </div>
                      {% endif %}
                      {% if productElementPrices %}
                      <div class="row">
                        {% for i in productElementPrices %}
                        {% set product_element_detail = i['product_element_detail'] %}
                        {% if product_element_detail != '' %}
                        <div class="col-md-12">
                          <label>{{ i['name'] }}</label>
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Tên</th>
                                <th class="text-center">Chọn</th>
                                <th>Giá gốc</th>
                                <th>Giá khuyến mãi</th>
                                {% if cf['_cf_text_price_usd_currency'] is defined %}
                                <th width="15%">Giá gốc ({{ cf['_cf_text_price_usd_currency'] }})</th>
                                <th width="15%">Giá khuyến mãi ({{ cf['_cf_text_price_usd_currency'] }})</th>
                                {% endif %}
                              </tr>
                            </thead>
                            <tbody>
                              {% for j in product_element_detail %}
                              <tr>
                                <td>{{ j['name'] }}</td>
                                <td scope="row" class="text-center"><input type="checkbox" name="product_element_detail_select[]" value="{{ j['id'] }}"{% if tmp_product_product_element_detail_arr is defined and tmp_product_product_element_detail_arr[j['id']] is defined  %} checked{% endif %}></td>
                                <td><input type="text" name="product_element_detail_cost[{{ j['id'] }}]" class="form-control"{% if tmp_product_product_element_detail_arr is defined and tmp_product_product_element_detail_arr[j['id']] is defined  %} value="{{tmp_product_product_element_detail_arr[j['id']]['cost'] }}"{% endif %}></td>
                                <td><input type="text" name="product_element_detail_price[{{ j['id'] }}]" class="form-control"{% if tmp_product_product_element_detail_arr is defined and tmp_product_product_element_detail_arr[j['id']] is defined  %} value="{{tmp_product_product_element_detail_arr[j['id']]['price'] }}"{% endif %}></td>
                                {% if cf['_cf_text_price_usd_currency'] is defined %}
                                <td><input type="text" name="product_element_detail_cost_usd[{{ j['id'] }}]" class="form-control"{% if tmp_product_product_element_detail_arr is defined and tmp_product_product_element_detail_arr[j['id']] is defined  %} value="{{tmp_product_product_element_detail_arr[j['id']]['cost_usd'] }}"{% endif %}></td>
                                <td><input type="text" name="product_element_detail_price_usd[{{ j['id'] }}]" class="form-control"{% if tmp_product_product_element_detail_arr is defined and tmp_product_product_element_detail_arr[j['id']] is defined  %} value="{{tmp_product_product_element_detail_arr[j['id']]['price_usd'] }}"{% endif %}></td>
                                {% endif %}
                              </tr>
                              {% endfor %}
                            </tbody>
                          </table>
                        </div>
                        {% endif %}
                        {% endfor %}
                      </div>
                      {% endif %}

                      {% if productElmCombins is not null %}
                      <div class="row">
                        <div class="col-md-12">
                          <label>Com bo giá</label>
                          <div style="height:350px;overflow:auto">
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th width="15%">Tên</th>
                                  <th class="text-center">Chọn</th>
                                  <th width="15%">Giá gốc</th>
                                  <th width="15%">Giá khuyến mãi</th>
                                  {% if cf['_cf_text_price_usd_currency'] is defined %}
                                  <th width="15%">Giá gốc ({{ cf['_cf_text_price_usd_currency'] }})</th>
                                  <th width="15%">Giá khuyến mãi ({{ cf['_cf_text_price_usd_currency'] }})</th>
                                  {% endif %}
                                  <th width="12%" class="text-center">Mặc định</th>
                                </tr>
                              </thead>
                              <tbody>
                                {% for productElmCombin in productElmCombins %}
                                <tr>
                                  <td>{{ productElmCombin['name'] }}</td>
                                  <td scope="row" class="text-center"><input type="checkbox" name="product_element_detail_combo_select[]" value="{{ productElmCombin['id'] }}"{% if tmp_product_product_element_detail_combo_arr is defined and tmp_product_product_element_detail_combo_arr[productElmCombin['id']] is defined  %} checked{% endif %}></td>
                                  <td>
                                    <input type="text" name="product_element_detail_combo_cost[{{ productElmCombin['id'] }}]"{% if tmp_product_product_element_detail_combo_arr is defined and tmp_product_product_element_detail_combo_arr[productElmCombin['id']] is defined  %} value="{{tmp_product_product_element_detail_combo_arr[productElmCombin['id']]['cost'] }}"{% endif %} class="form-control">
                                  </td>
                                  <td>
                                    <input type="text" name="product_element_detail_combo_price[{{ productElmCombin['id'] }}]"{% if tmp_product_product_element_detail_combo_arr is defined and tmp_product_product_element_detail_combo_arr[productElmCombin['id']] is defined  %} value="{{tmp_product_product_element_detail_combo_arr[productElmCombin['id']]['price'] }}"{% endif %} class="form-control">
                                  </td>
                                  {% if cf['_cf_text_price_usd_currency'] is defined %}
                                  <td>
                                    <input type="text" name="product_element_detail_combo_cost_usd[{{ productElmCombin['id'] }}]"{% if tmp_product_product_element_detail_combo_arr is defined and tmp_product_product_element_detail_combo_arr[productElmCombin['id']] is defined  %} value="{{tmp_product_product_element_detail_combo_arr[productElmCombin['id']]['cost_usd'] }}"{% endif %} class="form-control">
                                  </td>
                                  <td>
                                    <input type="text" name="product_element_detail_combo_price_usd[{{ productElmCombin['id'] }}]"{% if tmp_product_product_element_detail_combo_arr is defined and tmp_product_product_element_detail_combo_arr[productElmCombin['id']] is defined  %} value="{{tmp_product_product_element_detail_combo_arr[productElmCombin['id']]['price_usd'] }}"{% endif %} class="form-control">
                                  </td>
                                  {% endif %}
                                  <td class="text-center">
                                    <input type="radio" name="selected_combo" value="{{ productElmCombin['id'] }}"{% if tmp_product_product_element_detail_combo_arr is defined and tmp_product_product_element_detail_combo_arr[productElmCombin['id']] is defined and tmp_product_product_element_detail_combo_arr[productElmCombin['id']]['selected'] == 1 %} checked{% endif %}>
                                  </td>
                                </tr>
                                {% endfor %}
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      {% endif %}

                      <!-- Nav tabs -->
                      <ul id="tab-setting" class="nav nav-tabs" role="tablist" style="margin-bottom: 10px">
                        {% for k,i in product_detail %}
                        <li role="presentation" {% if request.get('active') == i['id']  or (k == 0 and request.get('active') == '') %}class="active"{% endif %}><a href="#tab{{ i['id'] }}" aria-controls="tab{{ i['id'] }}" role="tab" data-toggle="tab">{{ i['name'] }}</a></li>
                        {% endfor %}
                      </ul>


                      <div class="tab-content">
                        {% for k,i in product_detail %}
                        {% set tabId = i['id'] %}
                        <div role="tabpanel" class="tab-pane{% if request.get('active') == 'tab{{ tabId }}' or (k == 0 and request.get('active') == '') %} active{% endif %}" id="tab{{ tabId }}">
                          <textarea name="product_detail[{{ tabId }}]"class="form-control" rows="5">{% if i['content'] is defined %}{{ i['content'] }}{% endif %}</textarea>
                          <script type="text/javascript">
                          $(document).ready(function(){
                            var editor = CKEDITOR.replace( 'product_detail[{{ i['id'] }}]',{
                              allowedContent:true,
                              removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
                              uiColor : '#3c8dbc',
                              language:'en',
                              skin:'moono',
                              width: $('.editor-form-tab2').width(),
                              height: 200
                            });
                          });
                          </script>
                        </div>
                        {% endfor %}
                      </div>


                      <div class="form-group editor-form">
                        <label for="description">Tóm tắt</label>
                        {{ form.render("summary",{'class':'form-control','id':'summary'}) }}
                      </div>

                      <div class="panel panel-default">
                        <div class="panel-heading">SEO</div>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="title">Title</label>
                                {{ form.render("title",{'class':'form-control','id':'title'}) }}
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="keywords">Keywords</label>
                                {{ form.render("keywords",{'class':'form-control','id':'keywords'}) }}
                              </div>
                            </div>

                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="description">Description</label>
                                {{ form.render("description",{'class':'form-control','id':'description'}) }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Đường dẫn<span class="text-danger">(*)</span></label>
                            {{ form.render("slug",{'class':'form-control','id':'slug','readonly':true}) }}
                            {{ form.messages('slug') }}
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Link khác</label>
                            <div class="row">
                              <div class="col-md-2 text-center" style="padding-top: 6.5px"><input type="checkbox" name="enable_link" value="1" {% if item is defined and item.enable_link == 1 %}checked{% endif %}></div>
                              <div class="col-md-10">{{ form.render("link",{'class':'form-control'}) }}</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col-md-6">
                          <label>Link khác khi click vào nút mua hàng</label>
                          {{ form.render("cart_link",{'class':'form-control'}) }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                {% for tmp in tmpSubdomainLanguages %}
                {% if tmp.language.code != 'vi' %}
                {% set langCode = tmp.language.code %}
                {% set langName = tmp.language.name %}
                <div class="tab-pane fade" id="{{ langCode }}">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-12">
                         <div class="form-group">
                          <label for="name">Tên<span class="text-danger">(*)</span></label>
                          {{ form.render("name_" ~ langCode, {'class':'form-control set_url_' ~ langCode}) }}
                          {{ form.messages('name_' ~ langCode) }}
                        </div>
                      </div>
                    </div>

                    <!-- Nav tabs -->
                    {% if productDetailLang|length > 0 %}
                    <ul id="tab-setting" class="nav nav-tabs" role="tablist" style="margin-bottom: 10px">
                      {% for k,i in productDetailLang[langCode] %}
                      <li role="presentation" {% if request.get('active') == i['id']  or (k == 0 and request.get('active') == '') %}class="active"{% endif %}><a href="#tab{{ i['id'] }}" aria-controls="tab{{ i['id'] }}" role="tab" data-toggle="tab">{{ i['name'] }}</a></li>
                      {% endfor %}
                    </ul>


                    <div class="tab-content">
                      {% for k,i in productDetailLang[langCode] %}
                      {% set tabId = i['id'] %}
                      <div role="tabpanel" class="tab-pane{% if request.get('active') == 'tab{{ tabId }}' or (k == 0 and request.get('active') == '') %} active{% endif %}" id="tab{{ tabId }}">
                        <textarea name="product_detail_{{ langCode }}[{{ tabId }}]"class="form-control" rows="5">{% if i['content'] is defined %}{{ i['content'] }}{% endif %}</textarea>
                        <script type="text/javascript">
                        $(document).ready(function(){
                          var editor = CKEDITOR.replace( 'product_detail_{{ langCode }}[{{ i['id'] }}]',{
                            allowedContent:true,
                            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
                            uiColor : '#3c8dbc',
                            language:'en',
                            skin:'moono',
                            width: $('.editor-form-tab2').width(),
                            height: 200
                          });
                        });
                        </script>
                      </div>
                      {% endfor %}
                    </div>
                    {% endif %}

                    <div class="form-group editor-form">
                      <label for="description">Tóm tắt</label>
                      {{ form.render("summary_" ~ langCode,{'class':'form-control'}) }}
                    </div>

                    <div class="panel panel-default">
                      <div class="panel-heading">SEO</div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Title</label>
                              {{ form.render("title_" ~ langCode,{'class':'form-control'}) }}
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Keywords</label>
                              {{ form.render("keywords_" ~ langCode,{'class':'form-control', 'rows':1}) }}
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Description</label>
                              {{ form.render("description_" ~ langCode,{'class':'form-control', 'rows':1}) }}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="name">Đường dẫn<span class="text-danger">(*)</span></label>
                          {{ form.render("slug_" ~ langCode,{'class':'form-control', 'readonly':true}) }}
                          {{ form.messages('slug_' ~ langCode) }}
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

          <div class="col-md-4">
            {% if category != '' %}
            <div class="panel panel-default">
              <div class="panel-heading">Danh mục</div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="list-controls" style="margin-bottom: 10px;font-size: 16px">
                        <a href="javascript:void(0)" onclick="selectAll(this)" class="select-all text-primary"><div class="btn btn-default btn-sm checkbox-toggle" style="padding: 2px 6px;margin-right: 7px;"><i class="fa fa-square-o" style="font-size: 12px"></i></div><span style="vertical-align: middle;font-size: 15px">Chọn tất cả</span></a>
                      </div>
                      <ul class="list list_category">
                        {% for i in category %}
                        <li>
                          <input type="checkbox" name="category[]" value="{{ i.id }}"{% if tmp_product_category_arr is defined %}{% if i.id in tmp_product_category_arr %}checked="checked"{% endif %}{% endif %} class="menu-item-checkbox">
                          <span>{{ i.name }}</span>
                        </li>
                        {% endfor %}
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {% endif %}
            <div class="panel panel-default">
              <div class="panel-heading" style="display: block">Up hình để đưa vào bài viết</div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-12">
                    {{ partial('partials/ajaxupload', ['id': row_id, 'type':'product', 'img_upload_paths':img_upload_paths]) }}
                    <input type="hidden" name="row_id" value="{{ row_id }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading" style="display: block">Hình đại diện chính</div>
              <div class="panel-body">
                <div class="form-group">
                  {{ form.render("photo",{'id':'photo'}) }}
                  {{ form.messages('photo') }}
                  <p></p>
                  <label class="text-danger">- Ghi chú: Để up ảnh không bị mờ, ảnh cần nhẹ hơn 100kb | <a href="https://docs.google.com/document/d/1zaf8d_BAFSI41l413th-UulivgLU99yP9_Yo_EUDtwc/edit" target="_blank" style="text-decoration: underline;color: #f00"><i>Click xem hướng dẫn</i></a></label>
                  {% if item is defined and item.photo != '' %}
                  {{ image('files/product/' ~ SUB_FOLDER ~ '/' ~ item.folder ~ '/' ~ item.photo , 'width':'100', 'style':'margin-top:10px') }}
                  <p></p>
                  {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/deletePhoto/' ~ item.id ~ '/' ~ page, 'Xóa','onclick':'if(!confirm("Bạn muốn xóa hình đại diện?")) return false', 'class':'btn btn-sm btn-danger', 'style':'margin-left:25px' ) }}
                  {% endif %}
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading" style="display: block">Hình đại diện phụ</div>
              <div class="panel-body">
                <div class="form-group">
                  {{ form.render("photo_secondary",{'id':'photo_secondary'}) }}
                  {{ form.messages('photo_secondary') }}
                  <p></p>
                  <label class="text-danger">- Ghi chú: Để up ảnh không bị mờ, ảnh cần nhẹ hơn 100kb | <a href="https://docs.google.com/document/d/1zaf8d_BAFSI41l413th-UulivgLU99yP9_Yo_EUDtwc/edit" target="_blank" style="text-decoration: underline;color: #f00"><i>Click xem hướng dẫn</i></a></label>
                  {% if item is defined and item.photo_secondary != '' %}
                  {{ image('files/product/' ~ SUB_FOLDER ~ '/' ~ item.folder ~ '/' ~ item.photo_secondary , 'width':'100', 'style':'margin-top:10px') }}
                  <p></p>
                  {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/deletePhoto/' ~ item.id ~ '/' ~ page ~ '?type=secondary', 'Xóa','onclick':'if(!confirm("Bạn muốn xóa hình đại diện?")) return false', 'class':'btn btn-sm btn-danger', 'style':'margin-left:25px' ) }}
                  {% endif %}
                </div>
              </div>
            </div>
            <div class="panel panel-default" id="other_product_photo">
              <div class="panel-heading" style="display: block">Hình ảnh con</div>
              <div class="panel-body">
                {% if product_photo is defined and product_photo|length > 0 %}
                <div class="row">
                  <div class="col-md-12"><label>Hình ảnh con hiện tại</label></div>
                  {% for i in product_photo %}
                  <div class="col-md-4">
                    <div class="form-group text-center box_other_photo">
                      {{ image('files/product/' ~ SUB_FOLDER ~ '/' ~ item.folder ~ '/' ~ i.photo) }}
                      <div>
                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_deleteproductphoto/' ~ item.id ~ '/' ~ i.id ~ '/' ~ page, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}
                        {% if i.active == 'Y' %}
                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hideproductphoto/' ~ item.id ~ '/' ~ i.id ~ '/' ~ page, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                        {% else %}
                        {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/showproductphoto/' ~ item.id ~ '/' ~ i.id ~ '/' ~ page, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                        {% endif %}
                      </div>

                    </div>
                  </div>
                  {% endfor %}
                </div>
                {% endif %}
                {% if productElmPrPhotos is null %}
                <div class="row" id="load_photo_input">
                  {% for i in 1..5 %}
                  <div class="col-md-12">
                    <div class="form-group text-center box_other_photo">
                     <input type="file" name="product_photo[]">
                   </div>
                 </div>
                 {% endfor %}
               </div>
               {% endif %}
               {% if productElmPrPhotos is not null %}
               <div>
                <ul class="nav nav-tabs" role="tablist">
                  {% for key,productElmPrPhoto in productElmPrPhotos %}
                  <li role="presentation"{% if key == 0 %} class="active"{% endif %}><a href="#productElmPrPhoto{{ productElmPrPhoto['id'] }}" role="tab" data-toggle="tab">{{ productElmPrPhoto['name'] }}</a></li>
                  {% endfor %}
                </ul>
                <div class="tab-content">
                  {% for key,productElmPrPhoto in productElmPrPhotos %}
                  <div role="tabpanel" class="tab-pane{% if key == 0 %} active{% endif %}" id="productElmPrPhoto{{ productElmPrPhoto['id'] }}">
                    <p></p>
                    <p style="color:#f00;font-size:16px">Up hình cho màu <span class="bold" style="color:#000">{{ productElmPrPhoto['name'] }}</span></p>
                    {% if productElmPrPhoto['productPhotos'] is defined %}
                    <div class="row">
                      {% for i in productElmPrPhoto['productPhotos'] %}
                      <div class="col-md-4">
                        <div class="form-group text-center box_other_photo">
                          {{ image('files/product/' ~ SUB_FOLDER ~ '/' ~ item.folder ~ '/' ~ i['photo']) }}
                          <div>
                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/_deleteproductphoto/' ~ item.id ~ '/' ~ i['id'] ~ '/' ~ page, '<i class="fa fa-times"></i>','onclick':'if(!confirm("Xác nhận xóa?")) return false' ) }}
                            {% if i['active'] == 'Y' %}
                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/hideproductphoto/' ~ item.id ~ '/' ~ i['id'] ~ '/' ~ page, '<i class="fa fa-check-square-o fa-lg"></i>' ) }}
                            {% else %}
                            {{ link_to(ACP_NAME ~ '/' ~ controller_name ~ '/showproductphoto/' ~ item.id ~ '/' ~ i['id'] ~ '/' ~ page, '<i class="fa fa-square-o fa-lg"></i>' ) }}
                            {% endif %}
                          </div>
                        </div>
                      </div>
                      {% endfor %}
                    </div>
                    {% endif %}
                    {% for i in 1..5 %}
                    <div class="col-md-12">
                      <div class="form-group text-center box_other_photo">
                       <input type="file" name="product_photo[{{ productElmPrPhoto['id'] }}][]">
                     </div>
                   </div>
                   {% endfor %}
                 </div>
                 {% endfor %}
               </div>
             </div>
             {% endif %}
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
 
 <div class="row" style="padding-bottom:20px">
  <div class="col-md-2">
    <div class="form-group">
      <label for="sort">Thứ tự</label>
      {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
      {{ form.messages('sort') }}
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <label for="active">Hiển thị</label>
      {{ form.render("active",{'class':'form-control','style':'width:80px'}) }}
    </div>
  </div>
</div>

<div class="box-footer">
  {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
  {{ submit_button("Lưu & Thêm Mới", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}
  {{ submit_button("Lưu & Đóng", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}
  {{ link_to(ACP_NAME ~ "/" ~ controller_name, "Thoát", "class": "btn btn-danger") }}
</div>
{{ endform() }}
</div>
</div>
</section>
<script type="text/javascript">
$(document).ready(function(){
  var editor = CKEDITOR.replace( 'summary',{
    allowedContent:true,
    removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
    uiColor : '#3c8dbc',
    language:'en',
    skin:'moono',
    width: $('.editor-form').width(),
    height: 150,
  });

  {% for tmp in tmpSubdomainLanguages %}
  {% set langCode  = tmp.language.code %}
  {% if langCode != 'vi' %}
  var editor = CKEDITOR.replace( 'summary_{{ langCode }}',{
    allowedContent:true,
    removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
    uiColor : '#3c8dbc',
    language:'en',
    skin:'moono',
    width: $('.editor-form').width(),
    height: 150,
  });
  {% endif %}

  $(".set_url_{{ langCode }}").bind("change keyup input",function() {
    var obj = $(this).val();
    var als = locdau(obj);
    $("input[name=slug_{{ langCode }}]").val(als);
  });
  {% endfor %}

  $('#select_number_photo').change(function () {
    $('#load_photo_input').html('');
    var number = $(this).val();
    var html = '';
    for (i = 0; i < number; i++) {
      var html = html + '<div class="col-md-4"><div class="form-group"><input type="file" name="product_photo[]"></div></div>';
    }

    $('#load_photo_input').append(html);
  });

  {% if tmpSubdomainLanguages|length > 0 %}
  $('form[name=form_multilang]').submit(function(e) {
    {% for tmp in tmpSubdomainLanguages %}
    {% set langCode  = tmp.language.code %}
    {% set langName  = tmp.language.name %}
    {% if langCode != 'vi' %}
    if ($('input[name="name_{{ langCode }}"]').val() == '' || $('input[name="slug_{{ langCode }}"]').val() == '') {
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
