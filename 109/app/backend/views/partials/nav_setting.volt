<section class="content-header">
    <ul class="nav nav-tabs">
        <li role="presentation"{% if router.getControllerName() == "setting" %} class="active"{% endif %}><a href="/{{ ACP_NAME ~ '/setting' }}">Thông tin chung</a></li>
        {% for i in config_group %}
        <li role="presentation"{% if item is defined AND router.getControllerName() == "config_item" AND item.module == i.module %} class="active"{% endif %}><a href="/{{ ACP_NAME ~ '/config_item/group/' ~ i.module }}">{{ i.name }}</a></li>
        {% endfor %}
    </ul>
</section>