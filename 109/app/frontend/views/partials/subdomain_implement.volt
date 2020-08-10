{% set result = elastic_service.searchAllSubdomain() %}
<div class="box_subdomain_implement">
	{% if position == "header" or position == "footer" %}<div class="container">{% endif %}
	<div class="title_bar_center text-uppercase"><h1>{{ word._('_du_an') }}</h1></div>
    <div class="box_page-project">
        <div class="row" style="margin-top:10px">
            <div class="col-md-6">
                <form action="/subdomain-search" id="subdomainSearchFrm"  method="post">
                    <div class="input-group">
                      <input type="text" name="keyword" placeholder="{{ word['_tu_khoa_tim_ten_mien' ]}}" class="form-control">
                      <span class="input-group-btn">
                        <button id="btn-search-subdomain" class="btn btn-warning btn-flat ladda-button" data-style="slide-left"><span class="ladda-label">{{ word['_tim_kiem' ]}}</span></button>
                      </span>
                    </div>
                </form>
            </div>
        </div>
        <div id="subdomainListResult" style="margin-top: 10px"></div>
        <div id="nav-tabs-list-subdomain" class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">{{ word['_tat_ca' ]}}</a></li>
              <li><a href="#tab_2" data-toggle="tab">{{ word['_da_kich_hoat' ]}}</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive mailbox-messages">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="8%">{{ word['_thu_tu'] }}</th>
                                                    <th width="35%">{{ word['_ten'] }}</th>
                                                    <th>{{ word['Tên miền'] }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {% for key,item in result.items %}
                                                    {% set id = item['_id'] %}
                                                    {% set source = item['_source'] %}
                                                    {% set name = source['name'] %}
                                                    {% set sum_rate = source['sum_rate'] %}
                                                    {% set special = source['special'] %}
                                                    {% set duplicate = source['duplicate'] %}
                                                    {% set domains = source['domain']['name'] %}
                                                    {% set display = source['display'] %}
                                                    <tr>
                                                        <td>{{ key + 1 }}</td>
                                                        <td>
                                                            <a href="//{{ name ~ '.' ~ ROOT_DOMAIN }}" target="_blank"{% if display == 'N' %} class="is-disabled"{% endif %}>{{ name ~ '.' ~ ROOT_DOMAIN }}</a>
                                                            {% if display == 'N' %}
                                                            <span class="text-danger bold">| Đã khóa link</span>
                                                            {% endif %}
                                                        </td>
                                                        <td>
                                                            {% if domains is not null %}
                                                                {% set listDomain = explode(',', domains) %}
                                                                {% for kd,domain in listDomain %}
                                                                    <a href="http://{{ trim(domain) }}" target="_blank" style="font-weight: 600;color: #f00">{{ domain }}</a>{% if kd < count(listDomain) - 1 %}, {% endif %}
                                                                {% endfor %}
                                                            {% endif %}
                                                        </td>
                                                    </tr>
                                                {% endfor %}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive mailbox-messages">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="8%">{{ word['_thu_tu'] }}</th>
                                                    <th width="35%">{{ word['_ten'] }}</th>
                                                    <th>{{ word['Tên miền'] }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {% set count = 0 %}
                                                {% for key,item in result.items %}
                                                    {% set id = item['_id'] %}
                                                    {% set source = item['_source'] %}
                                                    {% set name = source['name'] %}
                                                    {% set sum_rate = source['sum_rate'] %}
                                                    {% set special = source['special'] %}
                                                    {% set active = source['active'] %}
                                                    {% set duplicate = source['duplicate'] %}
                                                    {% set domains = source['domain']['name'] %}
                                                    {% if active == 'Y' %}
                                                    {% set count = count + 1 %}
                                                    <tr>
                                                        <td>{{ count + 1 }}</td>
                                                        <td>
                                                            <a href="//{{ name ~ '.' ~ ROOT_DOMAIN }}" target="_blank">{{ name ~ '.' ~ ROOT_DOMAIN }}</a>
                                                        </td>
                                                        <td>
                                                            {% if domains is not null %}
                                                                {% set listDomain = explode(',', domains) %}
                                                                {% for kd,domain in listDomain %}
                                                                    <a href="http://{{ trim(domain) }}" target="_blank" style="font-weight: 600;color: #f00">{{ domain }}</a>{% if kd < count(listDomain) - 1 %}, {% endif %}
                                                                {% endfor %}
                                                            {% endif %}
                                                        </td>
                                                    </tr>
                                                    {% endif %}
                                                {% endfor %}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if result.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'page':result, 'url_page':'/ajax-subdomain', 'html_id':'nav-tabs-list-subdomain'}) }}</div>{% endif %}
            </div>
        </div>
    </div>
{% if position == "header" or position == "footer" %}</div>{% endif %}
</div>