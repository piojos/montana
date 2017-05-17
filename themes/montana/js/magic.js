
jQuery( function($) {

	$('.slider').slick({
		arrows : false
	});



	// Single > Same size info
	function sameSizeBox() {
	    var bL = $('.single .post_info').outerHeight();
	    var bR = $('.single .post_meta').outerHeight();
		if(bL > bR) {
			$('.single .post_meta').css('height', bL);
		} else {
		    $('.single .post_info').css('height', bR);
			$('.single .status_label').addClass('stick');
			console.log('black');
		}
	}
	// $(document).ready(function() {
	// 	setTimeout(function(){
	// 		sameSizeBox();
	// 	}, 15000);
	// });
	$(window).bind("load", function() {
		sameSizeBox();
	});
	$(window).resize(function() {
		sameSizeBox();
	});




});
