<section class="content-header">
    <ul class="nav nav-tabs">
        <li role="presentation"{% if router.getControllerName() == "layout" and router.getActionName() == 'update' %} class="active"{% endif %}><a href="/{{ ACP_NAME ~ '/layout/update/' ~ item.l.id }}">Giao diện</a></li>
        <li role="presentation"{% if router.getControllerName() == "layout" and router.getActionName() == 'css' %} class="active"{% endif %}><a href="/{{ ACP_NAME ~ '/layout/css/' ~ item.l.id }}">CSS</a></li>
        <li role="presentation"{% if router.getControllerName() == "layout" and router.getActionName() == 'module' %} class="active"{% endif %}><a href="/{{ ACP_NAME ~ '/layout/module/' ~ item.l.id }}">Cấu hình module</a></li>
    </ul>
</section>