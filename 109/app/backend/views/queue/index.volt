

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed', 'method': 'post', 'action': '/queue/send') }}
            <div class="panel panel-default">
                <div class="panel-heading">Queue</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Mail to</label>
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>

                            <div class="form-group">
                                <label for="type">Subject</label>
                                {{ form.render("subject",{'class':'form-control','id':'subject'}) }}
                                {{ form.messages('subject') }}
                            </div>

                            <div class="form-group">
                                <label for="type">Content</label>
                                {{ form.render("content",{'class':'form-control','id':'content'}) }}
                                {{ form.messages('content') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
                {{ link_to(ACP_NAME ~ "/" ~ controller_name, "Thoát", "class": "btn btn-danger") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>