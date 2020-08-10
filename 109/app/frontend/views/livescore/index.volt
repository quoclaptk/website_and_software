<div class="content_main">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="header"><h1 class="title">{{ title_bar }}</h1></div>
            <div class="lsBoxr">
                <div class="dateM">
                    <ul>
                    {% for item_date in livescore['date'] %}
                        {% set title = item_date['date'] %}
                        {% set link = url('livescore/' ~ item_date['date'] ~ '/' ) %}
                        {% set day = item_date['day'] %}
                        {% set month = item_date['month'] %}
                        <li{% if item_date['date'] == date('d-m-Y', time()) %} class="active"{% endif %}>
                            <a title="{{ title }}" href="{{ link }}">
                                <span>{{ day }}</span>
                                <span>{{ month }}</span>
                            </a>
                        </li>
                    {% endfor %}                                   
                    </ul>
                </div>
            </div>
            
            <div class="lvsc">{{ livescore['data'] }}</div>
            <div class="like_button">
                <div class="fb-like" data-href="{{ router.getRewriteUri() }}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
            </div>
        </div>
    </div>
</div>
