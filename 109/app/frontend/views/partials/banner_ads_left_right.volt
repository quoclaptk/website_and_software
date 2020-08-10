{% set left_ads = banner_service.getBannerAdsType('left_ads')  %}
{% set right_ads = banner_service.getBannerAdsType('right_ads')  %}
{% if cf['_cf_radio_banner_ads_left'] == true %}
<div id="divAdLeft" style="display: none;position: absolute; z-index:99">
    {% if left_ads|length > 0 %}
    {% for i in left_ads %}
    {% set photo = "/files/ads/" ~ subdomain.folder ~ "/" ~ i.photo %}
    <a href="{{ i.link }}" target="_blank">
        <img src="{{ photo }}" alt="{{ i.name }}" width="{{ cf['_cf_text_width_banner_ads_left'] }}px" />
    </a><br />
    {% endfor %}
    {% endif %}
</div>
{% endif %}
{% if cf['_cf_radio_banner_ads_right'] == true %}
<div id="divAdRight" style="display: none;position: absolute; z-index:99">
    {% if right_ads|length > 0 %}
    {% for i in right_ads %}
    {% set photo = "/files/ads/" ~ subdomain.folder ~ "/" ~ i.photo %}
    <a href="{{ i.link }}" target="_blank">
        <img src="{{ photo }}" alt="{{ i.name }}" width="{{ cf['_cf_text_width_banner_ads_right'] }}px" />
    </a><br />
    {% endfor %}
    {% endif %}
</div>
{% endif %}
{% if left_ads|length > 0 or right_ads|length > 0 %}
<script>
    function FloatTopDiv() {
        var elements = document.getElementsByClassName('container');
        for (i = 0; i < elements.length; i++) {
            var MainContentW = elements[i].clientWidth;
        }
        startLY = TopAdjustLeft;
        startRY = TopAdjustRight;
        startLX = ((document.body.clientWidth - MainContentW )/2) - LeftBannerW - LeftAdjust; 
        startRX = ((document.body.clientWidth - MainContentW)/2) - RightBannerW - RightAdjust; 
        var d = document;
        function ml(id) {
            var el = d.getElementById ? d.getElementById(id) : d.all ? d.all[id] : d.layers[id];
            el.sP = function(x, y) {
                this.style.right = x + 'px';
                this.style.top = y + 'px';
            };
            el.x = startRX;
            el.y = startRY;
            return el;
        }

        function m2(id)
        {
            var e2 = d.getElementById ? d.getElementById(id) : d.all ? d.all[id] : d.layers[id];
            e2.sP = function(x, y) {
                this.style.left = x + 'px';
                this.style.top = y + 'px';
            };

            e2.x = startLX;
            e2.y = startLY;
            return e2;
        }
        window.stayTopLeft = function() {
            if (document.documentElement && document.documentElement.scrollTop)
                var pY = document.documentElement.scrollTop;
            else if (document.body)
                var pY = document.body.scrollTop;

            ftlObj.y += (pY + startRY - ftlObj.y) / 16;
            ftlObj.sP(ftlObj.x, ftlObj.y);
            ftlObj2.y += (pY + startLY - ftlObj2.y) / 16;
            ftlObj2.sP(ftlObj2.x, ftlObj2.y);

            setTimeout("stayTopLeft()", 1);
        }

        ftlObj = ml("divAdRight");
        ftlObj2 = m2("divAdLeft");
        stayTopLeft();
    }

    function ShowAdDiv() {
        var objAdDivRight = document.getElementById("divAdRight");
        var objAdDivLeft = document.getElementById("divAdLeft");

        if (document.body.clientWidth < 1000) {
            objAdDivRight.style.display = "none";
            objAdDivLeft.style.display = "none";
        } else {
            objAdDivRight.style.display = "block";
            objAdDivLeft.style.display = "block";
            FloatTopDiv();
        }
    }
</script>

<script>
    document.write("<script type='text/javascript' language='javascript'>LeftBannerW = {{ cf['_cf_text_width_banner_ads_left'] }};RightBannerW = {{ cf['_cf_text_width_banner_ads_right'] }};LeftAdjust = {{ cf['_cf_text_left_adjust_banner_ads_left'] is not null ? cf['_cf_text_left_adjust_banner_ads_left'] : 0 }};RightAdjust = {{ cf['_cf_text_right_adjust_banner_ads_right'] is not null ? cf['_cf_text_right_adjust_banner_ads_right'] : 0 }};TopAdjustLeft = {{ cf['_cf_text_margin_top_banner_ads_left'] is not null ? cf['_cf_text_margin_top_banner_ads_left'] : 0 }};TopAdjustRight = {{ cf['_cf_text_margin_top_banner_ads_right'] is not null ? cf['_cf_text_margin_top_banner_ads_right'] : 0 }};ShowAdDiv();window.onresize=ShowAdDiv();<\/script>");
</script>
{% endif %}