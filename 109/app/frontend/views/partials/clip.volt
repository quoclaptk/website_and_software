<div class="header"><h2 class="title">Clip bóng đá</h2></div>
<ul class="box_clip_left clearfix">
    {% for clip in clip_new %}
        {% set name = clip['name'] %}
        {% set link = url('clip-bong-da/' ~	clip['slug']) %}
        {% set photo = image('files/youtube/thumb/240x180/' ~ clip['folder'] ~ '/' ~ clip['photo'], 'alt':''~ name ~'') %}
        <li class="clearfix">
            <a href="{{ link }}">{{ photo }}</a>
            <h4><a href="{{ link }}">{{ name }}</a></h4>
        </li>
    {% endfor %}
</ul>