{% set newsStatis = news_service.getNewsHot('statistical', 4) %}
<section class="statistic-screen">
	<div class="statistic-bg overlay-block-bg"></div>
	<div class="statistic-photo overlay-block-photo"></div>
	<div class="statistic-content text-center overlay-block-content">
		{% if position == 'header' or position == 'footer' %}<div class="container">{% endif %}
		<div class="centered">
			<div class="row">
				{% for key,i in newsStatis %}
	                {% set id = i.id %}
	                {% set name = i.name %}
	                {% set summary = tag.cut_string(i.summary, 150) %}
					<div class="col-xs-6 col-sm-3 col-md-3">
						<h3 id="statistic-{{ key + 1 }}" data-statistic="{{ name }}">{{ name }}</h3>
						<p>{{ summary }}</p>
					</div>
				{% endfor %}
			</div>
		</div>
	{% if position == 'header' or position == 'footer' %}</div>{% endif %}
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function() {
		showTimeUp();
	});

	function showTimeUp() {
		$(window).scroll(function() {
			var scrollToElement = true;
			var h1 = $('.statistic-screen');
				var hT = $('.statistic-screen').offset().top,
				hH = $('.statistic-screen').outerHeight(),
				wH = $(window).height(),
				wS = $(window).scrollTop();
			if (wS > (hT+hH-wH) && wS < (hT+hH)){
				if (scrollToElement) {
					if (Number.isInteger($('#statistic-1').data('statistic'))) {
						this.timeCountUp($('#statistic-1').data('statistic'), '#statistic-1');
					}
					
					if (Number.isInteger($('#statistic-2').data('statistic'))) {
						this.timeCountUp($('#statistic-2').data('statistic'), '#statistic-2');
					}

					if (Number.isInteger($('#statistic-3').data('statistic'))) {
						this.timeCountUp($('#statistic-3').data('statistic'), '#statistic-3');
					}

					if (Number.isInteger($('#statistic-4').data('statistic'))) {
						this.timeCountUp($('#statistic-4').data('statistic'), '#statistic-4');
					}
					scrollToElement = false;
				}
			} else {
				scrollToElement = true;
			}
		})
	}

	function timeCountUp(max, element) {
		var value = 0, count = 3, time = 100;
		var str = max.toString().length;
		
		var timer = setInterval(function(){
			value = value + count;

			$(element).text(numberWithCommas(value));
			if (value >= max) {
				clearInterval(timer);
				$(element).text(numberWithCommas(max));
			}
		}.bind(this), time);

	}

	function numberWithCommas(x) {
		var number = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		return number;
	}
</script>