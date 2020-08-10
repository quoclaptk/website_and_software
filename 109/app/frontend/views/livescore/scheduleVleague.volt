<div class="content_main">
    <div class="row">
        <div class="col-md-3 hidden-xs hidden-sm">
            <div class="block-ads-left">
                <a href="#" target="_blank">
                    <img src="{{ url('frontend/images/euro_2016.jpg') }}" />
                </a>
            </div>
            <div class="block-ads-left">
                <a href="#" target="_blank">
                    <img src="{{ url('frontend/images/euro_2016_1.jpg') }}" />
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9">
            <div class="header"><h1 class="title">{{ title_bar }}</h1></div>
            <h4>{{ round }}</h4>
            <div class="table-responsive">
                <div class="table-one">
  
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="position-title">Giờ</th>
                            <th>Ngày</th>
                            <th>Chủ nhà</th>
                            <th>Đội khách</th>
                            <th>Kênh trực tiếp</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for standing  in schedule_info['info'] %}
                            {% set hour = standing['hour'] %}
                            {% set date = standing['date'] %}
                            {% set house = standing['house'] %}
                            {% set guest = standing['guest'] %}
                            {% set chanel = standing['chanel'] %}

                            <tr>
                                <td>{{ hour }}</td>
                                <td>{{ date }}</td>
                                <td>{{ house }}</td>
                                <td>{{ guest }}</td>
                                <td>{{ chanel }}</td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                </div>
                

            </div>
            <div class="like_button">
                <div class="fb-like" data-href="{{ router.getRewriteUri() }}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
            </div>
        </div>
    </div>
</div>
