{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            {{ content() }}
            {{ flashSession.output() }}
            {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
            <div class="panel panel-default">
                <div class="panel-heading">Danh sách</div>
                <div class="panel-body">
                    {% if auth.getIdentity()['role'] == 1 %}
                    <div class="clearfix">
                        <div class="pull-left">{{ link_to(ACP_NAME ~ "/" ~ controller_name ~ "/create", "Thêm mới", "class": "btn btn-primary") }}</div>
                        <p class="clear"></p>
                    </div>
                    {% endif %}
                    <div class="row">
                        <div class="col-md-12">
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
                                            {% for key,item in list %}
                                                {% if loop.first %}
                                                    <div class="table-responsive mailbox-messages">
                                                    <table id="example" class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th width="7%">STT</th>
                                                        <th>Nội dung</th>
                                                    </thead>

                                                    <tbody>
                                                {% endif %}
                                                <tr>
                                                    <td>{{ key + 1 }}</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <input type="text" name="name[{{ item.id }}]" value="{{ item.name }}" class="form-control">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox" name="messenger_form[{{ item.id }}]" value="Y" {% if item.messenger_form == 'Y' %}checked{% endif %}> Hiện box tin nhắn</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox" name="mic_support_head[{{ item.id }}]" value="Y" {% if item.mic_support_head == 'Y' %}checked{% endif %}> Hiện yêu cầu tư vấn bên trên</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox" name="mic_support_foot[{{ item.id }}]" value="Y" {% if item.mic_support_foot == 'Y' %}checked{% endif %}> Hiện yêu cầu tư vấn bên dưới</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group editor-form">
                                                            <textarea name="content[{{ item.id }}]" class="form-control">{{ item.content }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                {% if loop.last %}
                                                    </tbody>

                                                    </table>
                                                    </div>
                                                {% endif %}
                                            {% endfor %}
                                        </div>
                                        {% for tmp in tmpSubdomainLanguages %}
                                        {% if tmp.language.code != 'vi' %}
                                            {% set langCode = tmp.language.code %}
                                            {% set langName = tmp.language.name %}
                                            <div class="tab-pane fade tab-other-lang-{{ langCode }}" id="{{ langCode }}">
                                                {% for key,item in listLang[langCode] %}
                                                    {% if loop.first %}
                                                        <div class="table-responsive mailbox-messages">
                                                        <table id="example" class="table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th width="7%">STT</th>
                                                            <th>Nội dung</th>
                                                        </thead>

                                                        <tbody>
                                                    {% endif %}
                                                    <tr>
                                                        <td>{{ key + 1 }}</td>
                                                        <td>
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <input type="text" name="name[{{ langCode }}][{{ item.id }}]" value="{{ item.name }}" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group editor-form">
                                                                <textarea name="content[{{ langCode }}][{{ item.id }}]" class="form-control">{{ item.content }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    {% if loop.last %}
                                                        </tbody>

                                                        </table>
                                                        </div>
                                                    {% endif %}
                                                {% endfor %}
                                            </div>
                                        {% endif %}
                                        {% endfor %}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="display: block">Up hình để đưa vào bài viết</div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {{ partial('partials/ajaxupload', ['id': row_id, 'type':'post']) }}
                                                    <input type="hidden" name="row_id" value="{{ row_id }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>


<script type="text/javascript">
    $(document).ready(function(){
        {% for key,item in list %}
        var editor = CKEDITOR.replace( 'content[{{ item.id }}]',{
            allowedContent:true,
            removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
            uiColor : '#3c8dbc',
            language:'en',
            skin:'moono',
            width: $('.editor-form').width(),
            height: 200
        });
        {% endfor %}
        {% for tmp in tmpSubdomainLanguages %}
        {% if tmp.language.code != 'vi' %}
            {% set langCode = tmp.language.code %}
            {% set langName = tmp.language.name %}
            {% for key,item in listLang[langCode] %}
            var editor = CKEDITOR.replace( 'content[{{ langCode }}][{{ item.id }}]',{
                allowedContent:true,
                removeButtons: 'Save,NewPage,Preview,Print,Cut,Copy,Paste,Undo,Redo,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,,Select,Button,HiddenField,RemoveFormat,CreateDiv,Anchor,Flash,InsertPre,ShowBlocks,About,Image',
                uiColor : '#3c8dbc',
                language:'en',
                skin:'moono',
                width: $('.editor-form').width(),
                height: 200
            });
            {% endfor %}
        {% endif %}
        {% endfor %}
    });
</script>
