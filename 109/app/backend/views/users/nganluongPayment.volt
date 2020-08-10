{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Nạp tiền online qua cổng thanh toán Ngân Lượng</h3>
                </div>
                {{ content() }}
                {{ form("role":"form") }}
                <div class="row">
                    <div class="col-md-4">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="password">Số tiền</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    {{ form.render("amount",{'class':'form-control','id':'amount'}) }}
                                </div>
                                {{ form.messages('amount') }}
                            </div>

                            <div class="form-group">
                                {{ submit_button("Nạp tiền", "class": "btn btn-primary", "id":"submit-change-pass") }}
                            </div>

                        </div>
                    </div>
                </div>
                {{ endform() }}
            </div>
        </div>
    </div>
</section>