<table class="table table-bordered">
    <tr>
      <th class="text-center">Banner</th>
      <th style="width: 82px">
      <button type="button" class="btn btn-success pull-right"><i class="fa fa-css3"></i> Thêm css
        </button>
      </th>
    </tr>
    {% for i in banner_html %}
    <tr>
      <td>
          <iframe src="{{ HTTP_HOST ~ '/' ~ ACP_NAME ~ '/banner_html/view/' ~ i.id }}" class="select_banner_html" width="100%" height="320" frameborder="0" scrolling="no"></iframe>
      </td>
      <td class="text-center" style="vertical-align: middle">
      		<div class="form-group">
                <button type="button" data-id="{{ i.id }}" class="btn btn-warning openModalEditCss" style="margin-right: 10px">
                <i class="fa fa-css3"></i> Sửa CSS
                </button>
            </div>
           <input type="radio" name="banner_html_id" value="{{ i.id }}" {% if setting.banner_html_id == i.id %}checked{% endif %}>
      </td>
    </tr>
    {% endfor %}
</table>