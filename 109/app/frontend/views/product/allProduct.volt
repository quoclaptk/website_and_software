<div class="bs-all-product row">
    {% for i in page.items %}
    {% set name = i.name %}
    {% if i.subdomain_name != '@' %}
    {% set url = tag.site_url('//' ~ i.subdomain_name ~ '.' ~ ROOT_DOMAIN ~ '/' ~ i.slug) %}
    {% else %}
    {% set url = tag.site_url('//' ~ ROOT_DOMAIN ~ '/' ~ i.slug) %}
    {% endif %}
    {% if i.photo != '' and file_exists('files/product/' ~ i.subdomain_folder ~ '/' ~ i.folder ~ '/' ~ i.photo) %}
    {% set photo = 'files/product/' ~ i.subdomain_folder ~ '/' ~ i.folder ~ '/' ~ i.photo  %}
    {% else %}
    {% set photo = "assets/images/no-image.png" %}
    {% endif %}
    <div class="media col-md-6">
        <div class="media-left"> 
            <a href="{{ url }}" target="_blank"> 
                {{ image(photo, 'alt':name, 'class':'media-object') }}
            </a> 
        </div>
        <div class="media-body">
            <h4 class="media-heading"><a href="{{ url }}" target="_blank">{{ name }}</a></h4>
        </div>
    </div>
    {% endfor %}
</div>
{% if page.items|length > 0 %}<div class="text-center">{{ partial('partials/pagination_ajax', {'url_page':'all-product', 'html_id':'load-page-all-product'}) }}</div>{% endif %}