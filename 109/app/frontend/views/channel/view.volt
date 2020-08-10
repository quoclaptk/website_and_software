<div class="content_main">
    <div class="header">
        <h1 class="title">{{ detail.name }}</h1>
    </div>
    <div id="player_stream">
        {% if(not(detail.iframe is empty)) %}
            <div class="iframe">{{ detail.iframe }}</div>
        {% else %}
            <h4>Kênh đang cập nhật.</h4>
        {% endif %}
        <div class="like_button">
            <div class="fb-like" data-href="{{ router.getRewriteUri() }}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
        </div>
    </div>
</div>