{% if position == 'header' or position == 'footer' %}
<div class="container">
	<div class="hidden-xs fb-comment-area">
	    <div class="fb-comments" data-href="{{ mainGlobal.getCurrentUrl() }}" data-numposts="5"></div>
	</div>
</div>
{% else %}
<div class="hidden-xs fb-comment-area">
    <div class="fb-comments" data-href="{{ mainGlobal.getCurrentUrl() }}" data-numposts="5"></div>
</div>
{% endif %}