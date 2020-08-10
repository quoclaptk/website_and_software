{% if position != 'left' and position != 'right' %}
    <div class="sidebar-above-footer">
        <div class="container theme-clearfix">
            <div class="row">
                <div class="col-lg-3 col-md-3 hidden-xs hidden-sm col-module-newsleter-social">
                    <div class="socials-wrap">
                        <ul>
                            <li><a href="{{ setting.facebook }}" target="_blank"><span class="fa fa-facebook"></span></a></li>
                            <li><a href="{{ setting.google }}" target="_blank"><span class="fa fa-google"></span></a></li>
                            <li><a href="{{ setting.twitter }}" target="_blank"><span class="fa fa-twitter"></span></a></li>
                            <li><a href="{{ setting.youtube }}" target="_blank"><span class="fa fa-youtube"></span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 col-module-newsleter">
                    <div class="block-subscribe-footer">
                        <div class="row">
                            <div class="col-md-6 hidden-xs hidden-sm">
                                <div class="title-block">
                                    <h2 class="text-uppercase">{{ word['_dang_ky_nhan_mail'] }}</h2>
                                    <p>{{ word['_cap_nhat_cac_thong_tin_khuyen_mai_moi_nhat'] }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="block-content">
                                    <form method="post" id="frm-newsletter-{{ position }}" action="/newsletter">
                                        <div class="mc4wp-form-fields">
                                            <div class="newsletter-content">
                                                <div class="input-group" id="frm_newsletter">
                                                    <input type="email" class="form-control" name="newsletter_email_{{ position }}" placeholder="Email" required />
                                                    <button class="newsletter-submit btn btn-primary ladda-button bar_web_bgr btn-send-newsletter" data-position="{{ position }}" data-style="slide-left"><span class="ladda-label">{{ word['_dang_ky'] }}</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>     
    </div>
{% else %}
    {% if position == "right" %}
        {% set box_class = "box_right_element" %}
    {% else %}
        {% set box_class = "box_left_element" %}
    {% endif %}
    <div class="{{ box_class }}">
        <div class="title_bar_right bar_web_bgr text-uppercase">{{ word['_dang_ky_nhan_mail'] }}</div>
        <div class="box_content_lr">
            <form method="post" id="frm-newsletter-{{ position }}" action="/newsletter">
                <div class="mc4wp-form-fields">
                    <div class="newsletter-content">
                        <div class="input-group">
                            <input type="email" class="form-control" name="newsletter_email_{{ position }}" placeholder="Email của bạn" required />
                            <button class="newsletter-submit btn btn-primary ladda-button btn-send-newsletter bar_web_bgr" data-position="{{ position }}" data-style="slide-left"><span class="ladda-label">{{ word['_dang_ky'] }}</span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
{% endif %}