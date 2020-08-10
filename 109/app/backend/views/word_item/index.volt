{{ partial('partials/content_header') }}

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            {{ content() }}
            {{ flashSession.output() }}

            {{ form('role':'form','enctype':'multipart/form-data', 'id':'form-fixed') }}
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
                        <div class="panel-heading">{{ title_bar }}</div>
                        <div class="panel-body">
                            <div class="row">
                                {% if wordDatas is defined and wordDatas != '' %}
                                    {% for i in wordDatas %}
                                        <div class="form-group clearfix">
                                            <label class="col-md-3 text-right" style="margin-top: 5px">{{ i['word_key'] }}</label>
                                            <div class="col-md-7">
                                                <div>
                                                    <input type="text" name="word_translate[{{ i['id'] }}]" value="{{ i['word_translate'] }}" class="form-control" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                    {% endfor %}
                                {% else %}
                                    {% for i in list %}
                                        {% if i.word_core.id is defined %}
                                        <div class="form-group clearfix">
                                            <label class="col-md-3 text-right" style="margin-top: 5px">{{ i.word_key }}</label>
                                            <div class="col-md-7">
                                                <div>
                                                    <input type="text" name="word_translate[{{ i.word_core.id }}]" value="{{ i.word_translate }}" class="form-control" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        {% endif %}
                                    {% endfor %}
                                {% endif %}
                            </div>
                        </div>
                    </div>
                </div>
                {% for key,wordDataLang in wordDataLangs %}
                <div class="tab-pane fade" id="{{ key }}">
                    <div class="panel panel-default">
                        <div class="panel-heading">{{ title_bar }}</div>
                        <div class="panel-body">
                            <div class="row">
                                {% for i in wordDataLang %}
                                    <div class="form-group clearfix">
                                        <label class="col-md-3 text-right" style="margin-top: 5px">{{ i['word_key'] }}</label>
                                        <div class="col-md-7">
                                            <div>
                                                <input type="text" name="word_translate_{{ key }}[{{ i['id'] }}]" value="{{ i['word_translate'] }}" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                </div>
                {% endfor %}
            </div>

            <div class="box-footer">
                {{ submit_button("Lưu", "class": "btn btn-primary","data-type":"save") }}
            </div>
            {{ endform() }}
        </div>
    </div>
</section>