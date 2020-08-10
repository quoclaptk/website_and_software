<div class="box_page">
    {{ partial("partials/breadcrumb") }}
    <div class="node-content">
        <div class="title_bar_center text-uppercase"><h1>{{ title_bar }}</h1></div>
        <div class="box_page-project">
            <div class="row" style="margin-top:10px">
                <div class="col-md-5">
                    <form action="/subdomain-search" id="subdomainSearchFrm"  method="post">
                        <div class="input-group">
                          <input type="text" name="keyword" placeholder="Từ khóa ..." class="form-control">
                          <span class="input-group-btn">
                            <button id="btn-search-subdomain" class="btn btn-warning btn-flat ladda-button" data-style="slide-left"><span class="ladda-label">Tìm kiếm</span></button>
                          </span>
                        </div>
                    </form>
                </div>
            </div>
            <div id="subdomainListResult" style="margin-top: 10px"></div>
            <div id="nav-tabs-list-subdomain" class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_2" data-toggle="tab">Đã kích hoạt</a></li>
                  <li><a href="#tab_3" data-toggle="tab">Mới tạo</a></li>
                  <li><a href="#tab_4" data-toggle="tab">Tất cả</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_2">
                        <div class="panel panel-default">
                            
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-xs-12">
                                            {% for key,item in active_subdomain %}
                                                {% if loop.first %}
                                                    <div class="table-responsive mailbox-messages">
                                                    <table id="example" class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th width="7%">Thứ tự</th>
                                                                <th>Tên</th>
                                                            </tr>
                                                        </thead>
                                                    <tbody>
                                                {% endif %}
                                                <tr>
                                                    <td>{{ key + 1 }}</td>
                                                    <td>
                                                        <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank">{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a>
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
                                        {% for key,item in list %}
                                            {% if loop.first %}
                                                <div class="table-responsive mailbox-messages">
                                                <table id="example" class="table table-bordered table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th width="7%">Thứ tự</th>
                                                    <th>Tên</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                            {% endif %}
                                            <tr>
                                                <td>{{ key + 1 }}</td>
                                                <td>
                                                    <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank">{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a>
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
                                        {% for key,item in all %}
                                            {% if loop.first %}
                                                <div class="table-responsive mailbox-messages">
                                                <table id="example" class="table table-bordered table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th width="7%">Thứ tự</th>
                                                    <th>Tên</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                            {% endif %}
                                            <tr>
                                                <td>{{ key + 1 }}</td>
                                                <td>
                                                    <a href="//{{ item.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank">{{ item.name ~ '.' ~ ROOT_DOMAIN }}</a>
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
    </div>
</div>