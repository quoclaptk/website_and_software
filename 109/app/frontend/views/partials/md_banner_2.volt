{% set banners = banner_service.getBannerAdsType('md_banner_2')  %}
<div id="banner_static_{{ module_id }}" class="md_banner_2">
    {% for key,value in banners %}
        {% set photo = "/files/ads/" ~ subdomain.folder ~ "/" ~ value.photo %}
    <div class="text-center banner_static_elm">
        <a href="{{ value.link }}" target="_blank">
            <img src="{{ photo }}" alt="{{ value.name }}">
        </a>
    </div>
    {% endfor %}
</div>