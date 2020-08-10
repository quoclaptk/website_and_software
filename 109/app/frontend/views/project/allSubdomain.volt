<div class="bs-all-subdomain">
    <ul class="list-group">
        {% for i in page.items %}
        <li class="list-group-item"><a href="//{{ i.name ~ '.' ~ ROOT_DOMAIN }}" target="_blank">{{ i.name ~ '.' ~ ROOT_DOMAIN }}</a></li>
        {% endfor %}
    </ul>
</div>
{% if page.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'url_page':'all-subdomain', 'html_id':'load-page-all-subdomain'}) }}</div>{% endif %}