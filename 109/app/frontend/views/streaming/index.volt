<div class="content_main">
    <div class="header">
        <h1 class="title">{{ title_bar }}</a></h1>
    </div>
    <div id="match_streaming">
    {% if(not(page is empty)) %}
        <div class="row">
        {% for streaming in page.items %}
            {% set item = streaming.streaming %}
            {% set date_time = strtotime(item.date_time) %}
            {% set end_time = date('Y-m-d H:i:s' , strtotime('+2 hours', date_time)) %}
            {% set hour = item.hour %}
            {% set day = date('d-m-Y', strtotime(item.day)) %}
            {% set house_team = item.house_team %}
            {% set guest_team = item.guest_team %}
            {% set house_logo = image('files/teams/' ~ item.house_logo, 'alt':house_team) %}
            {% set guest_logo = image('files/teams/' ~ item.guest_logo, 'alt':guest_team) %}
            {% set league = streaming.league_name %}
            {% set link = url('truc-tiep/' ~ item.link_stream ~ '/') %}
            {% set title = item.house_team ~ ' vs ' ~ item.guest_team ~ ' ' ~ streaming.league_name %}
            {% set hot_class = '' %}
            {% if item.hot == 'Y' %}
            {% set hot_class = 'hot' %}
            {% endif %}
            <div class="col-xs-12 col-sm-4">
                <div class="item {{ hot_class }}">
                    <a href="{{ link }}" title="{{ title }}">
                        <div class="logo_team pkg" align="center">
                            <div class="team1 fl-left">
                                {{ house_logo }}
                            </div>
                            <span class="time_match"><strong>{{ hour }}</strong>{{ day }}</span>
                            <div class="team2 fl-right">
                                {{ guest_logo }}
                            </div>
                        </div>
                        <div class="info_match">
                            <div class="name_team">
                                {{ house_team }} vs {{ guest_team }}
                                {% if current_time > end_time %}
                                {{ image('frontend/images/live.gif') }}
                                {% endif %}
                            </div>
                            <span>{{ league }}</span>
                        </div>
                    </a>
                </div>
            </div>
        {% endfor %}
        </div>
        {{ partial('partials/pagination') }}
    {% endif %}
    </div>
</div>