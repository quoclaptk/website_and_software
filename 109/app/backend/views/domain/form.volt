<div class="cart-box-container">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ title_bar }}</h4>
    </div>

    {% if message == "success" %}
        {{ flashSession.output() }}
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Thêm domain thành công!</strong> {{ link_to("javascript:;", "Đóng", "class": "", "data-dismiss":"modal") }}
            </div>
        </div>
        <div class="clear"></div>
    {% endif %}

    {{ form('role':'form','action':ACP_NAME ~ '/domain/edit/' ~ item.id, 'id':'form-add-domain') }}
    {{ form.render("id") }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Tên domain</label>
                    {{ form.render("name",{'class':'form-control'}) }}
                    {{ form.messages('name') }}
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
