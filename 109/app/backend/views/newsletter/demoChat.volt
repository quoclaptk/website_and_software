{{ partial('partials/content_header') }}

<!-- Main content -->
<section class="content">
 	<div class="container-fluid">
		<h1>Integration test NodeJS + PHP</h1>
		<p>
			This is a simple application, showing integration between nodeJS and PHP.
		</p>
		
		<form class="form-inline" id="messageForm">
			<input id="nameInput" type="text" class="input-medium" placeholder="Name" />
			<input id="messageInput" type="text" class="input-xxlarge" placeHolder="Message" />
		
			<input type="submit" value="Send" />
		</form>
		
		<div>
			<ul id="messages">
			</ul>
		</div>
		<!-- End #messages -->
	</div>
</section>
<!-- /.content -->
