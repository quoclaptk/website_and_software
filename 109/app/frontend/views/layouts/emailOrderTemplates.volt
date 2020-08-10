<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Simple Transactional Email</title>
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; }

      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #f6f6f6;
        width: 100%; }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        margin: 0 auto !important;
        /* makes it centered */
        max-width: 680px;
        padding: 10px;
        width: 680px; }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        margin: 0 auto;
        max-width: 580px;
        padding: 10px; 
      }
      .bold{font-weight: bold}
      .red{color: #f00}
      .blue{color: blue}

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%; }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; }

      .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
      }

      .footer {
        clear: both;
        margin-top: 10px;
        text-align: center;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: center; }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        margin-bottom: 10px; }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        margin-bottom: 12px; }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; }

      a {
        color: #3498db;
        text-decoration: underline; 
      }
      ul{padding-left: 15px}
      ul li{margin: 0}

      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          text-align: center; }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; }

      .btn-primary table td {
        background-color: #3498db; }

      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; }

      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0; }

      .first {
        margin-top: 0; }

      .align-center {
        text-align: center; }

      .align-right {
        text-align: right; }

      .align-left {
        text-align: left; }

      .clear {
        clear: both; }

      .mt0 {
        margin-top: 0; }

      .mb0 {
        margin-bottom: 0; }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }

      .powered-by a {
        text-decoration: none; }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; }
        table[class=body] .content {
          padding: 0 !important; }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
          width: 100% !important; }
        table[class=body] .btn a {
          width: 100% !important; }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; }
        .btn-primary table td:hover {
          background-color: #34495e !important; }
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; } }

    </style>
  </head>
  <body class="">
    <table border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">
            <table class="main">
              <tr>
                <td class="wrapper">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <h4 class="red bold" style="font-size:15px">{{ word['_thong_tin_khach_hang'] }}</h4>
                        {% if formData is defined %}
                        <ul>
                          {% if formData['name'] is defined and formData['name'] != '' %}
                          <li>{{ word['_ho_ten'] }}: {{ formData['name'] }}</li>
                          {% endif %}
                          {% if formData['email'] is defined and formData['email'] != '' %}
                          <li>{{ word['_email'] }}: <a href="mailto:{{ formData['email'] }}">{{ formData['email'] }}</a></li>
                          {% endif %}
                          {% if formData['phone'] is defined and formData['phone'] != '' %}
                          <li>{{ word['_dien_thoai'] }}: <a href="tel:{{ formData['phone'] }}">{{ formData['phone'] }}</a></li>
                          {% endif %}
                          {% if formData['address'] is defined and formData['address'] != '' %}
                          <li>{{ word['_dia_chi'] }}: {{ formData['address'] }}</li>
                          {% endif %}
                          {% if formData['comment'] is defined and formData['comment'] != '' %}
                          <li>Ghi chú: {{ formData['comment'] }}</li>
                          {% endif %}
                          {% if formData['payment_method'] is defined and formData['payment_method'] != '' %}
                          <li>Phương thức thanh toán: {{ formData['payment_method'] }}</li>
                          {% endif %}
                        </ul>
                        {% endif %}
                        {% set order_info = json_decode(formData['order_info']) %}
                        {% set order_currency = formData['order_info'] is not empty ? formData['order_currency'] : 'VNĐ' %}
                        <h4 class="red bold" style="font-size:15px">Danh sách sản phẩm</h4
                        <table style="border-collapse:collapse;clear:both;width:100%;margin:0 0 10px;border:1px solid #c6c6c6" id="table-order-info">
                          <thead>
                              <tr>
                                  <th style="border:1px solid #c6c6c6;padding:5px">STT</th>
                                  <th style="border:1px solid #c6c6c6;padding:5px">Tên sản phẩm</th>
                                  <th style="border:1px solid #c6c6c6;padding:5px">Số lượng</th>
                                  <th style="border:1px solid #c6c6c6;padding:5px" nowrap="">Giá</th>
                                  <th style="border:1px solid #c6c6c6;padding:5px">Thành tiền</th>
                              </tr>
                          </thead>
                          <tbody>
                            {% for key,value in order_info %}
                              {% set currency = (value.currency is not empty) ? value.currency : 'VNĐ' %}
                              <tr>
                                  <td style="border:1px solid #c6c6c6;padding:5px;font-size:12px">{{ key + 1 }}</td>
                                  <td style="border:1px solid #c6c6c6;padding:5px;font-size:12px"><a href="{{ value.link }}" target="_blank">{{ value.name }}</a></td>
                                  <td style="border:1px solid #c6c6c6;padding:5px;font-size:12px">{{ value.qty }}</td>
                                  <td style="border:1px solid #c6c6c6;padding:5px;text-align:right;font-size:12px">{{ tag.number_format(value.price) ~ ' ' ~ currency }}</td>
                                  <td style="border:1px solid #c6c6c6;padding:5px;text-align:right;font-size:12px">{{ tag.number_format(value.total) ~ '' ~ currency }}</td>
                              </tr>
                              {% endfor %}
                              <tr>
                                  <td colspan="4" style="border:1px solid #c6c6c6;padding:5px;text-align:right;font-size:13px">Tổng tiền thanh toán</td>
                                  <td style="border:1px solid #c6c6c6;padding:5px;text-align:right;font-size:13px">{{ tag.number_format(formData['total']) ~ ' ' ~ order_currency }}</td>
                              </tr>
                          </tbody>
                      </table>
                        <p class="red bold">Chú ý: Để xem thêm đơn hàng khác và quản lý tốt hơn, vui lòng truy cập vào trong link sau</p>
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <td><a href="{{ url }}" target="_blank">Click xem thêm đơn hàng khác</a></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>
