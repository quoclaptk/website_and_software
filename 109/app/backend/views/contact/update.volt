{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            <div class="panel panel-default">
                <div class="panel-heading">{{ title_bar }}</div>
                <div class="panel-body">
                    <div class="form-group row">
                        <div class="col-md-2"><b>Họ tên</b></div>
                        <div class="col-md-10">{{ item.name }}</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Số điện thoại</b></div>
                        <div class="col-md-10">{{ item.phone }}</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Email</b></div>
                        <div class="col-md-10">{{ item.email }}</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Địa chỉ</b></div>
                        <div class="col-md-10">{{ item.address }}</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Chủ đề</b></div>
                        <div class="col-md-10">{{ item.subject }}</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><b>Nội dung</b></div>
                        <div class="col-md-10">{{ item.comment }}</div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ link_to(ACP_NAME ~ "/" ~ controller_name, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>