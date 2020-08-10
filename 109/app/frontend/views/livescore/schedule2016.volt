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
            {% if(not(schedule_info is empty)) %}
                <div id="result_result">
                {% for key,value in schedule_info %}
                    <table cellpadding="0" cellspacing="0" class="kqtd">
                        <thead>
                            <tr>
                                <th colspan="3" class="title_round">{{ key }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        {% for item in value %}
                            {% set time = item['time'] %}
                            {% set team_home = item['team_home'] %}
                            {% set team_home_logo = item['team_home_logo'] %}
                            {% set team_away = item['team_away'] %}
                            {% set team_away_logo = item['team_away_logo'] %}
                            <tr>
                                <td width="45%" class="home_team">
                                    {{ team_home }}
                                    <span><img height="30" src="{{ team_home_logo }}"></span>
                                </td>
                                <td width="10%" class="result">{{ time }}</td>
                                <td width="45%">
                                    <span><img height="30" src="{{ team_away_logo }}"></span>
                                    {{ team_away }}  
                                </td>
                            </tr>
                        {% endfor %} 
                        </tbody>
                    </table>
               {% endfor %} 
                </div>
                {% endif %} 
       
                

            </div>
            <div class="like_button">
                <div class="fb-like" data-href="{{ router.getRewriteUri() }}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
            </div>
        </div>
    </div>
</div>