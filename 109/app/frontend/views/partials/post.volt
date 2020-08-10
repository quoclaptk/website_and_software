{% set post = post_service.getPost(module_id) %}
{% if post != "" %}
    <div class="box_post">
        {% if position == "header" or position == "footer" %}<div class="container">{% endif %}
            {% if post.name != '' %}
            <div class="title_bar_center bar_web_bgr text-uppercase"><h2>{{ post.name }}</h2></div>
            {% endif %}
            <div id="{{ html_id ~ "_" ~ id }}" class="post_static clearfix">
                {% if post.mic_support_head == 'Y' %}
                {{ partial('partials/mic_support_page', {'form_name':'frm_mic_support_post_header'}) }}
                {% endif %}
                {% if post.messenger_form == 'Y' %}<div class="row"><div class="col-md-6 col-post-content">{% endif %}
                <div class="post_static_content">{{ htmlDisplayShortCode(htmlspecialchars_decode(post.content)) }}</div>
                {% if post.messenger_form == 'Y' %}</div>{% endif %}
                {% if post.messenger_form == 'Y' %}
                <div class="col-md-6 col-post-form">{{ partial("partials/customer_message_module") }}</div></div>
                {% if post.mic_support_foot == 'Y' %}
                {{ partial('partials/mic_support_page', {'form_name':'frm_mic_support_post_footer'}) }}
                {% endif %}
                {% endif %}
            </div>
         {% if position == "header" or position == "footer" %}</div>{% endif %}
    </div>
{% endif %}
