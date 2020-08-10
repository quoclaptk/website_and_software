<ul class="slides">
    {% for i in productPhotos %}
        {% set pr_photo = "/files/product/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo   %}
        {% if file_exists("files/product/" ~ subdomain.folder ~ "/thumb/120x120/" ~ i.folder ~ "/" ~ i.photo) %}
            {% set pr_thumb = "/files/product/" ~ subdomain.folder ~ "/thumb/120x120/" ~ i.folder ~ "/" ~ i.photo %}
        {% else %}
            {% set pr_thumb = "/files/product/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo %}
        {% endif %}
        <li data-thumb="{{ pr_thumb }}">
            <a href="{{ pr_photo }}" rel="fancybox-thumb" class="fancybox-thumb">
                <img src="{{ pr_photo }}">
            </a>
        </li>
    {% endfor %}
</ul>
<ol class="flex-control-nav flex-control-thumbs">
    {% if productPhotos|length > 1 %}
        {% for k,i in productPhotos %}
            {% set pr_photo = "/files/product/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo   %}
            {% if file_exists("files/product/" ~ subdomain.folder ~ "/thumb/120x120/" ~ i.folder ~ "/" ~ i.photo) %}
                {% set pr_thumb = "/files/product/" ~ subdomain.folder ~ "/thumb/120x120/" ~ i.folder ~ "/" ~ i.photo %}
            {% else %}
                {% set pr_thumb = "/files/product/" ~ subdomain.folder ~ "/" ~ i.folder ~ "/" ~ i.photo %}
            {% endif %}
            <li class="flex-thum-customize"><img src="{{ pr_thumb }}" {% if k == 0 %}class="flex-active"{% endif %} draggable="false"></li>
        {% endfor %}
    {% endif %}
</ol>