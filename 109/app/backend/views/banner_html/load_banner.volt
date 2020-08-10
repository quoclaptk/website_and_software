{% if banner_html_sub != '' %}
<div class="form-group text-center" style="border-bottom: 1px solid #ddd">
    <label>
        <input type="radio" name="banner_html" value="{{ banner_html_sub.id }}" checked> Chọn kiểu banner này
    </label>
    <button type="button" id="openModalEditCss" data-id="{{ banner_html_sub.id }}" class="btn btn-warning pull-right" style="margin-right: 10px">
        <i class="fa fa-css3"></i> Sửa CSS
      </button>
    <iframe src="{{ HTTP_HOST }}/public/bannerhtml/{{ folder }}/{{ banner_html_sub.id }}/index.html" data-banner="1" data-banner="1" id="banner_1" class="select_banner_html" width="100%" height="320" frameborder="0" scrolling="no"></iframe>
</div>
{% endif %}
{% for key,i in banner_html %}
<div class="form-group text-center" style="border-bottom: 1px solid #ddd">
    <label>
        <input type="radio" name="banner_html" value="{{ i.id }}" {% if i == setting.banner_html_id %}chec
        {% elseif key == 0 and banner_html_sub == '' %}checked{% endif %}> Chọn kiểu banner này
    </label>
    <iframe src="{{ HTTP_HOST }}/public/bannerhtml/{{ i.subdomain.folder }}/{{ i.id }}/index.html" class="select_banner_html" width="100%" height="320" frameborder="0" scrolling="no"></iframe>
</div>
{% endfor %}