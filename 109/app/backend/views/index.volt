<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		{% if file_exists('uploads/favicon.ico') %}
		<link rel="icon" type="image/x-icon"  href="/uploads/favicon.ico">
		{% endif %}
		<title>Administrator</title>
		{{ stylesheet_link('backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}
        {{ stylesheet_link('backend/bower_components/Ionicons/css/ionicons.min.css') }}
        {{ stylesheet_link('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}
        {{ stylesheet_link('backend/bower_components/font-awesome/css/font-awesome.min.css') }}
		{{ stylesheet_link('backend/dist/css/AdminLTE.css') }}
		{{ stylesheet_link('backend/plugins/iCheck/all.css') }}
		{{ stylesheet_link('backend/plugins/iCheck/flat/red.css') }}
		{{ stylesheet_link('backend/dist/css/skins/_all-skins.min.css') }}
        {{ stylesheet_link('backend/bower_components/jvectormap/jquery-jvectormap.css') }}
        {{ stylesheet_link('backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}
        {{ stylesheet_link('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}
        {{ stylesheet_link('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}
        {{ stylesheet_link('backend/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}
    	{{ stylesheet_link('backend/plugins/toastr/toastr.min.css') }}
		{{ stylesheet_link('backend/plugins/ladda/ladda-themeless.min.css') }}
		{{ stylesheet_link('backend/plugins/swal/sweetalert.css') }}
		{{ stylesheet_link('backend/plugins/jquery-star-rating/rating.css') }}
        {{ assets.outputCss() }}
		{{ stylesheet_link('ajaxupload/css/jquery.fileupload.css') }}
		{{ stylesheet_link('backend/dist/css/me.css?time=' ~ time) }}
		<!-- Code snippet to speed up Google Fonts rendering: googlefonts.3perf.com -->
		<link rel="dns-prefetch" href="https://fonts.gstatic.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
		<link rel="preload" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic",r="__3perf_googleFonts_1e6a7";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>
		
		<link rel="preload" href="https://fonts.googleapis.com/css?family=Cormorant+Upright:300,400,500,600,700" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Cormorant+Upright:300,400,500,600,700",r="__3perf_googleFonts_75492";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>

		<link rel="preload" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800",r="__3perf_googleFonts_70cd6";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>

		<link rel="preload" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700",r="__3perf_googleFonts_f414b";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>

		<link rel="preload" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Roboto:400,500,700",r="__3perf_googleFonts_c9230";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>

		<link rel="preload" href="https://fonts.googleapis.com/css?family=Great+Vibes" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Great+Vibes",r="__3perf_googleFonts_d90ec";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>

		<link rel="preload" href="https://fonts.googleapis.com/css?family=Anton|Bungee+Inline" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Anton|Bungee+Inline",r="__3perf_googleFonts_a729c";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>

		<link rel="preload" href="https://fonts.googleapis.com/css?family=Bungee+Hairline|Bungee+Outline" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Bungee+Hairline|Bungee+Outline",r="__3perf_googleFonts_862bc";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>

		<link rel="preload" href="https://fonts.googleapis.com/css?family=Playfair+Display|Sedgwick+Ave+Display" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Playfair+Display|Sedgwick+Ave+Display",r="__3perf_googleFonts_643e7";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>

		<link rel="preload" href="https://fonts.googleapis.com/css?family=Alfa+Slab+One|Faster+One" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Alfa+Slab+One|Faster+One",r="__3perf_googleFonts_fb7b";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>

		<link rel="preload" href="https://fonts.googleapis.com/css?family=Chakra+Petch:400,500,600,700&display=swap&subset=vietnamese" as="fetch" crossorigin="anonymous">
		<script type="text/javascript">
		!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Chakra+Petch:400,500,600,700&display=swap&subset=vietnamese",r="__3perf_googleFonts_5743c";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
		</script>

        {{javascript_include('backend/bower_components/jquery/dist/jquery.min.js')}}
        {{javascript_include('backend/bower_components/bootstrap/dist/js/bootstrap.min.js')}}
 	</head>
	<body class="hold-transition {% if router.getControllerName()  == 'index'  and router.getActionName() == 'login' %}login-page{% else %}skin-blue sidebar-mini{% endif %}">
			{{ content() }}
			<div class="modal modal fade" id="modalViewUrlIp" role="dialog">
			    <div class="modal-dialog modal-md">
			      <!-- Modal content-->
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Danh sách link truy cập</h4>
			        </div>
			        <div class="modal-body">
			        	
			        </div>
			      </div>
			    </div>
			</div>
			<input type="hidden" name="tab_active" value="first">
		{{javascript_include('backend/bower_components/jquery-ui/jquery-ui.min.js')}}
		<script>
		  $.widget.bridge('uibutton', $.ui.button);
		</script>
			
		<!-- DataTables -->
		{{javascript_include('backend/bower_components/datatables.net/js/jquery.dataTables.min.js')}}
		{{javascript_include('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}
		{{javascript_include('backend/bower_components/raphael/raphael.min.js')}}
		{{javascript_include('backend/bower_components/morris.js/morris.min.js')}}
		{{javascript_include('backend/bower_components/moment/min/moment.min.js')}}
		{{javascript_include('backend/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}
		{{javascript_include('backend/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}
		{{javascript_include('backend/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}
		{{javascript_include('backend/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}
		{{javascript_include('backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}
		{{javascript_include('backend/bower_components/moment/min/moment.min.js')}}
		{{javascript_include('backend/bower_components/jquery-knob/dist/jquery.knob.min.js')}}
		{{javascript_include('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}
		{{javascript_include('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}
		{{javascript_include('backend/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}
		{{javascript_include('backend/plugins/iCheck/icheck.min.js')}}
		{{javascript_include('backend/bower_components/fastclick/lib/fastclick.js')}}
		{{javascript_include('backend/plugins/toastr/toastr.min.js')}}
		{{javascript_include('backend/plugins/ladda/spin.min.js')}}
		{{javascript_include('backend/plugins/ladda/ladda.min.js')}}
		{{javascript_include('backend/plugins/swal/sweetalert.min.js')}}
		{{javascript_include('backend/plugins/jquery-star-rating/rating.js')}}
		{{javascript_include('backend/dist/js/adminlte.min.js')}}
		{{javascript_include('backend/dist/js/pages/dashboard.js')}}
		{{javascript_include('ajaxupload/js/vendor/jquery.ui.widget.js')}}
		{{javascript_include('ajaxupload/js/jquery.fileupload.js')}}
		{#{{javascript_include('backend/dist/js/demo.js')}}#}
		{{javascript_include('backend/dist/js/autosize.min.js')}}
		{{javascript_include('backend/dist/js/ajaxupload.js')}}
		
		{{javascript_include('backend/dist/js/huy.js')}}
		{{javascript_include('backend/dist/js/me.js?time=' ~ time)}}
		{{javascript_include('ckeditor/ckeditor.js')}}
		{{ assets.outputJs() }}
		{% if APP_ENV == 'production' and protocol == 'http://' %}
		{{javascript_include('node_modules/socket.io-client/dist/socket.io.js')}}
		<script type="text/javascript">
			var socketUrl = '{{ APP_SOCKET}}';
			var socket = io.connect( socketUrl );
		</script>
		{% endif %}
		<script>
			$(function () {
				//Enable iCheck plugin for checkboxes
				//iCheck for checkbox and radio inputs
				$('.mailbox-messages input[type="checkbox"]').iCheck({
					checkboxClass: 'icheckbox_flat-blue',
					radioClass: 'iradio_flat-blue'
				});

				//Enable check and uncheck all functionality
				$(".checkbox-toggle").click(function () {
					var clicks = $(this).data('clicks');
					if (clicks) {
						//Uncheck all checkboxes
						$(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
						$(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
					} else {
						//Check all checkboxes
						$(".mailbox-messages input[type='checkbox']").iCheck("check");
						$(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
					}
					$(this).data("clicks", !clicks);
				});

			});
		</script>

		<script>
		  $(function () {
			$('input:not(.rating)').iCheck({
			  checkboxClass: 'icheckbox_square-blue',
			  radioClass: 'iradio_square-blue',
			  increaseArea: '20%' // optional
			});
			//Red color scheme for iCheck
    		$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		      checkboxClass: 'icheckbox_square-blue',
		      radioClass   : 'iradio_flat-blue'
		    })
			//Red color scheme for iCheck
		    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
		      checkboxClass: 'icheckbox_minimal-red',
		      radioClass   : 'iradio_minimal-red'
		    })
		    //Flat red color scheme for iCheck
		    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		      checkboxClass: 'icheckbox_square-red',
		      radioClass   : 'iradio_square-red'
		    })

		    $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
		      checkboxClass: 'icheckbox_square-green',
		      radioClass   : 'iradio_square-green'
		    })
			  /*$(".timepicker").timepicker({
				  showInputs: false
			  });
		  	$( "#day" ).datepicker({
			});*/

			// tab active
			$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	          var target = $(e.target).attr("href") // activated tab
	          $('input[name=tab_active]').val(target.replace('#', ''));
	        });

			$('input[name=select-all-item]').on('ifChecked', function(event){
		        $('input[name=select_all]').iCheck("check")
		    });
		    $('input[name=select-all-item]').on('ifUnchecked', function(event){
		        $('input[name=select_all]').iCheck("uncheck")
		    });

		    // select all acive
		    $('input[name=select-all-active]').on('ifChecked', function(event){
		        $('input[class=active-item]').iCheck("check")
		    });
		    $('input[name=select-all-active]').on('ifUnchecked', function(event){
		        $('input[class=active-item]').iCheck("uncheck")
		    });

		    // select all hot
		    $('input[name=select-all-hot]').on('ifChecked', function(event){
		        $('input[class=hot-item]').iCheck("check")
		    });
		    $('input[name=select-all-hot]').on('ifUnchecked', function(event){
		        $('input[class=hot-item]').iCheck("uncheck")
		    });

		    // select all new
		    $('input[name=select-all-new]').on('ifChecked', function(event){
		        $('input[class=new-item]').iCheck("check")
		    });
		    $('input[name=select-all-new]').on('ifUnchecked', function(event){
		        $('input[class=new-item]').iCheck("uncheck")
		    });

		    // select all selling
		    $('input[name=select-all-selling]').on('ifChecked', function(event){
		        $('input[class=selling-item]').iCheck("check")
		    });
		    $('input[name=select-all-selling]').on('ifUnchecked', function(event){
		        $('input[class=selling-item]').iCheck("uncheck")
		    });

		    // select all promotion
		    $('input[name=select-all-promotion]').on('ifChecked', function(event){
		        $('input[class=promotion-item]').iCheck("check")
		    });
		    $('input[name=select-all-promotion]').on('ifUnchecked', function(event){
		        $('input[class=promotion-item]').iCheck("uncheck")
		    });

		    // select all introduct
		    $('input[name=select-all-introduct]').on('ifChecked', function(event){
		        $('input[class=introduct-item]').iCheck("check")
		    });
		    $('input[name=select-all-introduct]').on('ifUnchecked', function(event){
		        $('input[class=introduct-item]').iCheck("uncheck")
		    });

		    // select all most view
		    $('input[name=select-all-most-view]').on('ifChecked', function(event){
		        $('input[class=most-view-item]').iCheck("check")
		    });
		    $('input[name=select-all-most-view]').on('ifUnchecked', function(event){
		        $('input[class=most-view-item]').iCheck("uncheck")
		    });

		    // select all out stock
		    $('input[name=select-all-out-stock]').on('ifChecked', function(event){
		        $('input[class=out-stock-item]').iCheck("check")
		    });
		    $('input[name=select-all-out-stock]').on('ifUnchecked', function(event){
		        $('input[class=out-stock-item]').iCheck("uncheck")
		    });
		  });
		</script>

		<script type="text/javascript">
			$('ul.nav li.dropdown').hover(function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
			}, function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(200);
			});
			$(function () {
			    //Add text editor
			    $(".compose-textarea").wysihtml5();
		  });
		</script>
		<script>
		  $(function () {
		    $('#ipAccessTable').DataTable({
		      'paging'      : true,
		      'lengthChange': true,
		      'searching'   : true,
		      'ordering'    : true,
		      'info'        : true,
		      'autoWidth'   : false,
		      "order": [[ 2, "desc" ]],
		      "pageLength": 10
		    })
		  });
		</script>
	</body>
</html>
