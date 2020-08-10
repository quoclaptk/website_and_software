<div class="box_page">
    {{ partial("partials/breadcrumb") }}
    <div class="node-content">
        {# <div class="text-uppercase title"><h1>{{ title_bar }}</h1></div> #}
        <div class="title_bar_center text-uppercase"><h1>{{ title_bar }}</h1></div>
        {{ partial('partials/customer_comment_content', ['customerComments':customerComments]) }}
    </div>
</div>