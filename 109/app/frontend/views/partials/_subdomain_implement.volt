{% set subdomains = subdomain_service.getSubdomainList() %}
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
              <li class="active"><a href="#tab_2" data-toggle="tab">{{ word['_da_kich_hoat' ]}}</a></li>
              <li><a href="#tab_3" data-toggle="tab">{{ word['_moi_tao' ]}}</a></li>
              <li><a href="#tab_4" data-toggle="tab">{{ word['_tat_ca' ]}}</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_2">
                    <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12">
                                        {% for key,item in subdomains['active'] %}
                                            {% if loop.first %}
                                                <div class="table-responsive mailbox-messages">
                                                <table id="example" class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th width="8%">{{ word['_thu_tu'] }}</th>
                                                            <th width="35%">{{ word['_ten'] }}</th>
                                                            <th>{{ word['Tên miền'] }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                            {% endif %}
                                                <tr>
                                                    <td>{{ key + 1 }}</td>
                                                    <td>
                                                        <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank">{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a>
                                                    </td>
                                                    <td>
                                                        {% if item.domains is defined %}
                                                            {% for k, domain in item.domains %}
                                                                <a href="http://{{ domain['name'] }}" target="_blank" style="font-weight: 500;color: #f00">{{ domain['name'] }}</a>{% if k < (count(item.domains) - 1) %}, {% endif %} 
                                                            {% endfor %}
                                                        {% endif %}
                                                    </td>
                                                </tr>
                                                {% if loop.last %}
                                                    </tbody>
                                                </table>
                                                </div>
                                            {% endif %}
                                        {% endfor %}
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                 <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    {% for key,item in subdomains['list'] %}
                                        {% if loop.first %}
                                            <div class="table-responsive mailbox-messages">
                                            <table id="example" class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th width="8%">{{ word['_thu_tu'] }}</th>
                                                <th width="35%">{{ word['_ten'] }}</th>
                                                <th>{{ word['Tên miền'] }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                        {% endif %}
                                        <tr>
                                            <td>{{ key + 1 }}</td>
                                            <td>
                                                <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank">{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a>
                                            </td>
                                            <td>
                                            {% if item.domains is defined %}
                                                {% for k, domain in item.domains %}
                                                    <a href="http://{{ domain['name'] }}" target="_blank" style="font-weight: 500;color: #f00">{{ domain['name'] }}</a>{% if k < (count(item.domains) - 1) %}, {% endif %} 
                                                {% endfor %}
                                            {% endif %}
                                            </td>
                                        </tr>
                                        {% if loop.last %}
                                            </tbody>
                                            </table>
                                            </div>
                                        {% endif %}
                                    {% endfor %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_4">
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    {% for key,item in subdomains['all'] %}
                                        {% if loop.first %}
                                            <div class="table-responsive mailbox-messages">
                                            <table id="example" class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th width="8%">{{ word['_thu_tu'] }}</th>
                                                <th width="35%">{{ word['_ten'] }}</th>
                                                <th>{{ word['Tên miền'] }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                        {% endif %}
                                        <tr>
                                            <td>{{ key + 1 }}</td>
                                            <td>
                                                <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank">{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a>
                                            </td>
                                            <td>
                                            {% if item.domains is defined %}
                                                {% for k, domain in item.domains %}
                                                    <a href="http://{{ domain['name'] }}" target="_blank" style="font-weight: 500;color: #f00">{{ domain['name'] }}</a>{% if k < (count(item.domains) - 1) %}, {% endif %} 
                                                {% endfor %}
                                            {% endif %}
                                            </td>
                                        </tr>
                                        {% if loop.last %}
                                            </tbody>
                                            </table>
                                            </div>
                                        {% endif %}
                                    {% endfor %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- /.tab-pane -->
            </div>
        </div>
    </div>
{% if position == "header" or position == "footer" %}</div>{% endif %}
</div>