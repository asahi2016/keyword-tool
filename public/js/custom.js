$(document).ready(function () {
$('#google').show();
$('.social-link li').eq(0).find('a').addClass('active');
   $('.social-link li a').click(function(){
		$('.search-form').hide();
		$('.social-link li a').removeClass('active');
		$(this).addClass('active');
		var rel = $(this).attr('rel');
		$('#'+rel).show();
   });
});


