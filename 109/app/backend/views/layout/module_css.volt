<div class="cart-box-container">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ title_bar }}</h4>
    </div>
    {{ form("role":"form", "action":"", "id":"form-create-domain", "class":"form-horizontal") }}
    <div class="modal-body">

        <div class="form-group">
            <label class="col-md-3 control-label">Màu nền</label>
            <div class="col-md-6">
                <input type="text" name="header_background" {% if(css_item != "") %}value="{{ css_item.background }}"{% endif %} class="form-control my-colorpicker1 ">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Màu chữ</label>
            <div class="col-md-6">
                <input type="text" name="color" {% if(css_item != "") %}value="{{ css_item.color }}"{% endif %} class="form-control my-colorpicker1 ">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Cỡ chữ (px)</label>
            <div class="col-md-3">
                <select name="font_size" class="form-control">
                    <option value="">--- Chọn px ---</option>
                    {% for i in 1..30 %}
                        <option value="{{ i }}" {% if(css_item != "" AND css_item.font_size == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Margin (px)</label>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3">
                        <select name="margin_top" class="form-control">
                            <option value="">Top</option>
                            {% for i in 1..30 %}
                                <option value="{{ i }}"{% if(css_item != "" AND css_item.margin_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="margin_bottom" class="form-control">
                            <option value="">Bottom</option>
                            {% for i in 1..30 %}
                                <option value="{{ i }}"{% if(css_item != "" AND css_item.margin_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="margin_left" class="form-control">
                            <option value="">Left</option>
                            {% for i in 1..30 %}
                                <option value="{{ i }}"{% if(css_item != "" AND css_item.margin_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="margin_right" class="form-control">
                            <option value="">Right</option>
                            {% for i in 1..30 %}
                                <option value="{{ i }}"{% if(css_item != "" AND css_item.margin_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Padding (px)</label>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3">
                        <select name="padding_top" class="form-control">
                            <option value="">Top</option>
                            {% for i in 1..30 %}
                                <option value="{{ i }}"{% if(css_item != "" AND css_item.padding_top == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="padding_bottom" class="form-control">
                            <option value="">Bottom</option>
                            {% for i in 1..30 %}
                                <option value="{{ i }}"{% if(css_item != "" AND css_item.padding_bottom == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="padding_left" class="form-control">
                            <option value="">Left</option>
                            {% for i in 1..30 %}
                                <option value="{{ i }}"{% if(css_item != "" AND css_item.padding_left == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="padding_right" class="form-control">
                            <option value="">Right</option>
                            {% for i in 1..30 %}
                                <option value="{{ i }}"{% if(css_item != "" AND css_item.padding_right == i) %} selected{% endif %}>{{ i ~ 'px'}}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" name="ok">Lưu</button>
        {{ link_to("javascript:;", "Đóng", "class": "btn btn-danger", "data-dismiss":"modal") }}
    </div>
    {{ endform() }}
</div>