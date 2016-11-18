<!-- nav-up-down -->
  <div style="padding:20px; position:fixed; bottom:10px; right:30px;" class="fa fa-caret-square-o-up fa-2x" id="nav_up">
  </div>
  <div style="padding:20px; position:fixed; bottom:10px; right:70px;" class="fa fa-caret-square-o-down fa-2x" id="nav_down">
  </div>
  <script>
	$(function() {
		var $elem = $('body');
		$('#nav_up').fadeIn('slow');
		$('#nav_down').fadeIn('slow');
		$(window).bind('scrollstart', function() {
			$('#nav_up, #nav_down').stop().animate({'opacity' : '0.2'});
		});
		$(window).bind('scrollstop', function() {
			$('#nav_up, #nav_down').stop().animate({'opacity' : '1'});
		});
		$('#nav_down').click(function (e) {
			$('html, body').animate({scrollTop: $elem.height()}, 800);
		});
		$('#nav_up').click( function (e) {
			$('html, body').animate({scrollTop: '0px'}, 800);
		});
	});
  </script>
<!-- nav-up-down end -->
