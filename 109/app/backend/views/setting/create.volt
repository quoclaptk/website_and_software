<form method="post" autocomplete="off">

<ul class="pager">
    <li class="pull-right">
        {{ submit_button("Save", "class": "btn btn-success") }}
    </li>
</ul>

{{ content() }}


<div class="center scaffold">
    <h2>Update setting</h2>

    <div class="clearfix">
        <label for="name">Name</label>
        {{ form.render("name") }}
    </div>

	<div class="clearfix">
		<label for="name">Email</label>
		{{ form.render("email") }}
	</div>



</div>


</form>