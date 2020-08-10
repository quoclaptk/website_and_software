<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
{% if cf['_turn_off_mobile'] == true %}
<meta name="viewport" content="width=device-width, initial-scale=1">
{% endif %}
<meta name="description" content="{{ description }}" />
<meta name="keywords" content="{{ keywords }}" />
<meta property="og:url" content="{{ protocol ~ HOST ~ router.getRewriteUri() }}" />
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ title }}" />
<meta property="og:description" content="{{ description }}" />
<meta property="og:image" content="{{ image_meta }}" />
<title>{{ title }}</title>
<link rel="shortcut icon" href="{{ favicon }}" type="image/x-icon" />
<!-- Code snippet to speed up Google Fonts rendering: googlefonts.3perf.com -->
<link rel="dns-prefetch" href="https://fonts.gstatic.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
<link rel="preload" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" as="fetch" crossorigin="anonymous">
<script type="text/javascript">
!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Roboto:400,500,700",r="__3perf_googleFonts_c9230";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
</script>
<link rel="preload" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" as="fetch" crossorigin="anonymous">
<script type="text/javascript">
!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700",r="__3perf_googleFonts_f414b";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
</script>
<link rel="preload" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" as="fetch" crossorigin="anonymous">
<script type="text/javascript">
!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700",r="__3perf_googleFonts_70cd6";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
</script>
<link rel="preload" href="https://fonts.googleapis.com/css?family=Chakra+Petch:400,500,600,700&display=swap&subset=vietnamese" as="fetch" crossorigin="anonymous">
<script type="text/javascript">
!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Chakra+Petch:400,500,600,700&display=swap&subset=vietnamese",r="__3perf_googleFonts_5743c";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
</script>
{% if setting.enable_logo_text == 1 or setting.enable_logo_text == 4 or setting.enable_logo_text == 5 or setting.enable_logo_text == 6 %}
<link rel="preload" href="https://fonts.googleapis.com/css?family=Playfair+Display|Sedgwick+Ave+Display" as="fetch" crossorigin="anonymous">
<script type="text/javascript">
!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Playfair+Display|Sedgwick+Ave+Display",r="__3perf_googleFonts_643e7";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
</script>
{% endif %}
{% if setting.enable_logo_text == 2 %}
<link rel="preload" href="https://fonts.googleapis.com/css?family=Anton|Bungee+Inline" as="fetch" crossorigin="anonymous">
<script type="text/javascript">
!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Anton|Bungee+Inline",r="__3perf_googleFonts_a729c";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
</script>
{% endif %}
{% if setting.enable_logo_text == 3 %}
<link rel="preload" href="https://fonts.googleapis.com/css?family=Bungee+Hairline|Bungee+Outline" as="fetch" crossorigin="anonymous">
<script type="text/javascript">
!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Bungee+Hairline|Bungee+Outline",r="__3perf_googleFonts_862bc";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
</script>
{% endif %}
{% if setting.enable_logo_text == 5 %}
<link rel="preload" href="https://fonts.googleapis.com/css?family=Alfa+Slab+One|Faster+One" as="fetch" crossorigin="anonymous">
<script type="text/javascript">
!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Alfa+Slab+One|Faster+One",r="__3perf_googleFonts_fb7b";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
</script>
{% endif %}
{% if setting.enable_logo_text == 6 %}
<link rel="preload" href="https://fonts.googleapis.com/css?family=Great+Vibes" as="fetch" crossorigin="anonymous">
<script type="text/javascript">
!function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Great+Vibes",r="__3perf_googleFonts_d90ec";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
</script>
{% endif %}
{{ stylesheet_link('frontend/css/all.min.css?time?time=' ~ time) }}
{% set folder = subdomain.folder %}
{{ assets.outputCss() }}
{% set styleFileCss = 'assets/css/pages/' ~ folder ~ '/' ~ 'style' ~ layout ~ '.css?time=' ~ time %}
{% set pageFileCss = 'assets/css/pages/' ~ folder ~ '/' ~ 'page' ~ layout ~ '.css' %}
{% if file_exists('assets/css/pages/' ~ folder ~ '/' ~ 'style.min.css') %}
    {% set styleFileCss = 'assets/css/pages/' ~ folder ~ '/' ~ 'style.min.css?time=' ~ time %}
{% endif %}
{% if file_exists('assets/css/pages/' ~ folder ~ '/' ~ 'page.min.css') %}
    {% set pageFileCss = 'assets/css/pages/' ~ folder ~ '/' ~ 'page.min.css?time=' ~ time %}
{% endif %}
{{ stylesheet_link(styleFileCss) }}
{{ stylesheet_link(pageFileCss) }}
{{ setting.head_content }}
{% if headContent is not empty %}
{{ headContent }}
{% endif %}
{{ mainGlobal.getConfigKernel('_cf_kernel_textarea_head_code') }}
{{javascript_include('frontend/js/jquery.min.js')}}
</head>
<body id="page{{layout}}">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
{% if cf['_cf_radio_facebook_messenger'] == true and cf['_cf_text_fanpage_id'] != '' %}
<div class="fb-customerchat"
  attribution=setup_tool
  page_id="{{ cf['_cf_text_fanpage_id'] }}"
  theme_color="#0084ff"
  logged_in_greeting="{{ word['_message_messenger_facebook'] }}"
  logged_out_greeting="{{ word['_message_messenger_facebook'] }}">
</div>
{% endif %}
{{ setting.body_content }}
{% if bodyContent is not empty %}
{{ bodyContent }}
{% endif %}
{{ partial("partials/message") }}
{{ content() }}
{% if demo_router is defined %}
	<input type="hidden" name="demo_router" value="{{ demo_router }}">
{% endif %}
<input type="hidden" name="language" value="{{ languageCode }}">
<input type="hidden" name="languageMessage" value="{{ messageLangDefault }}">
<script defer="defer" src="/frontend/js/all.min.js?time={{ time }}"></script>
{{ assets.outputJs() }}
{% if cf['_cf_radio_javascript_resize'] == true %}
{{javascript_include('frontend/js/resize.js')}}
{% endif %}
{% if APP_ENV == 'production' and protocol == 'http://' %}
{{javascript_include('node_modules/socket.io-client/dist/socket.io.js')}}
<script type="text/javascript">
    var socketUrl = '{{ APP_SOCKET}}';
    var socket = io.connect( socketUrl );
</script>
{% endif %}
{{ mainGlobal.getConfigKernel('_cf_kernel_textarea_body_code') }}
</body>
