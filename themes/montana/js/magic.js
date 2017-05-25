
jQuery( function($) {

// Home
	$('.slider').slick({
		arrows : false
	});
	$('.dropdown a.toggle_button').click(function() {
		$('.dropdown').toggleClass('open closed');
	});

// Single > Same size info
	function sameSizeBox() {
		var bL = $('.single .post_info').outerHeight();
		var bR = $('.single .post_meta').outerHeight();
		if(bL >= bR) {
			$('.single .post_meta').css('height', bL);
			$('.single .status_label').removeClass('stick');
		} else {
			$('.single .post_info').css('height', bR);
			$('.single .status_label').addClass('stick');
		}
	}
	$(window).bind("load", function() {
		sameSizeBox();
		$('.post_head').removeClass('loading');
	});
	$(window).resize(function() {
		sameSizeBox();
	});




// Agenda: Pass Select fields
	$('input#visibleFecha').datepicker({
		dateFormat: 'M d yy',
		altField: 'input#fecha',
		altFormat: 'yymmdd'
	});

	var valT = location.href.match(/[?&]tipo=(.*?)[$&]/)[1];
	$('select#tipo').val(valT);

	var valL = location.href.match(/[?&]lugar=(.*?)$/)[1];
	$('select#lugar').val(valL);




// Fitvids
	$(".widget iframe").fitVids();

});
