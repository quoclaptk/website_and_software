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

    {% if(not(other_channel is empty)) %}
    <div class="block-related-news" style="margin-bottom:10px">
        <div class="header"><h2 class="title">Xem kênh khác</h2></div>
        <div class="container-fluid">
        	<div class="row">
	        {% for key, value in other_channel %}
	            {% set link = url('live-tv/' ~ value['slug'] ~ '/' ) %}
	            {% set name =  value['name'] %}
	            {% set photo = image('files/tv/' ~ value['photo'], 'alt':''~ name ~'') %}
	            <div class="col-xs-6 col-sm-4 col-md-2 list_tv">
	                <div class="item_list_tv">
	                    <div class="item_tv">
	                        <a href="{{ link }}" title="{{ name }}">
	                            {{ photo }}
	                        </a>
	                    </div>
	                </div>
	            </div>
	        {% endfor %}
	    	</div>
    	</div>        
	</div>
    {% endif %}

</div>

<div class="content_main">
{% for cate in channel_group %}
    {% set name = cate['name'] %}
    {% set tv = cate['tv'] %}
    {% if(not(tv is empty)) %}
    <div class="header">
        <h2 class="title">{{ name }}</h2>
    </div>
    <div class="container-fluid">
        <div class="row">
        {% for key, value in tv %}
            {% set link = url('live-tv/' ~ value['slug'] ~ '/' ) %}
            {% set name =  value['name'] %}
            {% set photo = image('files/tv/' ~ value['photo'], 'alt':''~ name ~'') %}
            <div class="col-xs-6 col-sm-4 col-md-2 list_tv">
                <div class="item_list_tv">
                    <div class="item_tv">
                        <a href="{{ link }}" title="{{ name }}">
                            {{ photo }}
                        </a>
                    </div>
                </div>
            </div>
        {% endfor %}
        </div>
    </div>
    {% endif %}
{% endfor %}
</div>