{% set hour = item.hour %}
{% set day = date('d-m-Y', strtotime(item.day)) %}
{% set house_team = item.house_team %}
{% set guest_team = item.guest_team %}
{% set house_logo = image('files/teams/' ~ item.house_logo, 'alt':house_team) %}
{% set guest_logo = image('files/teams/' ~ item.guest_logo, 'alt':guest_team) %}

<div class="content_main">
    <div id="streaming_media">
        <div class="team home">
            <div class="logo">{{ house_logo }}</div>
            <h1 class="name">{{ house_team }}</h1>
        </div>
        <div class="middle">
            <span class="cname">{{ league_name }}</span>
            <span class="hour">{{ hour }}</span>
            <span class="date">{{ day }}</span>
        </div>

        <div class="team away">
            <div class="logo">{{ guest_logo }}</div>
            <h1 class="name">{{ guest_team }}</h1>
        </div>
    </div>
    <div id="player_stream">
    {% if(not(channel.iframe is empty)) %}
        <div class="iframe">{{ channel.iframe }}</div>
    {% else %}
        <h4>Link trực tiếp sẽ được cập nhật khoảng 45 phút trước khi bắt đầu trận đấu.</h4>
    {% endif %}
    <div class="clear"></div>
    <div class="like_button">
        <div class="fb-like" data-href="{{ router.getRewriteUri() }}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
    </div>


    <h4 class="list_link_stream">Link trực tiếp</h4>
    <div id="list_channel">
        <ul>
        {% for channel in list_channel %}
            {% if channel.type == 1 %}
            {% set logo = image('frontend/images/bdv.png') %}
            {% elseif channel.type == 2 %}
                {% set logo = image('frontend/images/youtube.png') %}
            {% elseif channel.type == 3 %}
                {% set logo = image('frontend/images/talktv.png') %}
            {% endif %}
            {% set title_link = '/channel/' ~ channel.slug ~ '/' ~ channel.id ~ '/' %}
        {% set link = url('channel/' ~ channel.slug ~ '/' ~ channel.id ~ '/') %}
            {% set bitrate = channel.bitrate ~ ' Kbps' %}
            {% set name = channel.name %}
            <li class="chanel_tivi">
                <div class="logo">
                    <div class="logobg">
                        <div class="logobg">{{ logo }} </div>
                    </div>
                </div>
                <div class="link">
                    <span class="name"><a href="{{ link }}">{{ name }}</a></span>
                    <span class="box tivi"> <span class="type">BĐV</span> <a href="{{ link }}" title="Link kênh {{ name }}">{{ title_link }}</a> </span>
                </div>
                <div class="colinfo">
                    <span class="bitrate">{{ bitrate }}</span>
                </div>
                <a class="view_link" href="{{ link }}">Xem</a>
            </li>
        {% endfor %}
        {% for sop in sopcast %}
            {% set logo = image('frontend/images/sopcast.png') %}
            {% set name = sop['name'] %}
            {% set link = sop['link'] %}
            {% set bitrate = sop['bitrate'] %}
            <li class="chanel_tivi">
                <div class="logo">
                    <div class="logobg">
                        <div class="logobg">{{ logo }} </div>
                    </div>
                </div>
                <div class="link">
                    <span class="name"><a href="{{ link }}">Sopcast {{ bitrate }}</a></span>
                    <span class="box tivi"> <span class="type">Sopcast</span> <a href="{{ link }}" title="Link kênh {{ name }}">{{ link }}</a> </span>
                </div>
                <div class="colinfo">
                    <span class="bitrate">{{ bitrate }}</span>
                </div>
                <a class="view_link" href="{{ link }}">Xem</a>
            </li>
        {% endfor %}
        </ul>
    </div>


    </div>
</div>