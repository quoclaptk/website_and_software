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
            
            <div class="table-responsive">
                <div class="table-one">    
  
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="position-title">Vị Trí</th>
                                <th>Tên Đội</th>
                                <th>ST</th><th>T</th>
                                <th>H</th>
                                <th>B</th>
                                <th>TG</th>
                                <th>TH</th><th>HS</th>
                                <th>Đ</th>
                            </tr>
                        </thead>
                        <tbody>
                        {% for standing  in standings %}
                            {% set xh = standing['xh'] %}
                            {% set team_name = standing['team_name'] %}
                            {% set team_link = standing['team_link'] %}
                            {% set game_number = standing['game_number'] %}
                            {% set win = standing['win'] %}
                            {% set d = standing['d'] %}
                            {% set lost = standing['lost'] %}
                            {% set gs = standing['gs'] %}
                            {% set ga = standing['ga'] %}
                            {% set offsets = standing['offsets'] %}
                            {% set mark = standing['mark'] %}
                            {% set logo = standing['logo'] %}
                            {% set label_class = 'same-color' %}
                            {% if xh in standings_cl %}
                                {% set label_class = 'label-primary' %}
                            {% endif %}
                            {% if xh in standings_vlcl %}
                                {% set label_class = 'label-info' %}
                            {% endif %}
                            {% if xh in standings_c2 %}
                                {% set label_class = 'label-success' %}
                            {% endif %}
                            {% if xh in standings_xh %}
                                {% set label_class = 'label-danger' %}
                            {% endif %}
                            <tr>
                                <td><span class="label {{ label_class }}">{{ xh }}</span></td>
                                <td class="teams"><div class="bxh-anh-doi-bong-da"><img width="30" height="30" alt="logo {{ team_name }}<" src="{{ logo }}" class="logo-bxh"></div><div class="bxh-ten-doi-bong-da">{{ team_name }}</div></td>
                                <td>{{ game_number }}</td>
                                <td>{{ win }}</td>
                                <td>{{ d }}</td>
                                <td>{{ lost }}</td>
                                <td>{{ gs }}</td>
                                <td>{{ ga }}</td>
                                <td>{{ offsets }}</td>
                                <td><b>{{ mark }}</b></td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                </div>
                
                <div class="table-two">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ST: <span>Số trận</span></th>
                                <th>T: <span>Thắng</span></th>
                                <th>H: <span>Hòa</span></th>
                                <th>B: <span>Bại</span></th>
                                <th>Tg: <span>Bàn thắng</span></th>
                                <th>Th: <span>Bàn thua</span></th><th>Hs: <span>Hiệu số</span></th>
                                <th>Đ: <span>Điểm</span></th>
                            </tr>
                        </thead>
                    </table>	
                </div>
                
                <div class="table-three">
                    <table class="table">
                        <tbody> <tr>
                            <td><span class="label label-primary"> </span><div>
                                Dự Champions League 
                            </div></td>
                        </tr> <tr>
                            <td><span class="label label-info"> </span><div>
                                Sơ loại Champions League 
                            </div></td>
                        </tr> <tr>
                            <td><span class="label label-success"> </span><div>
                                Dự Europa League 
                            </div></td>
                        </tr> <tr>
                            <td><span class="label label-danger"> </span><div>
                                Xuống hạng 
                            </div></td>
                        </tr> </tbody>
                    </table>
                </div>
            </div>

            <div class="like_button">
                <div class="fb-like" data-href="{{ router.getRewriteUri() }}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
            </div>
        </div>
    </div>
</div>
