
jQuery( function($) {

// Home
	$('.slider').slick({
		arrows : false
	});

	$('.slider_deck').slick({
		arrows : false,
		variableWidth: true,
		infinite: false,
		swipeToSlide: true
	});


	// Dropdown
	$('.dropdown a.toggle_button').click(function() {
		$('.dropdown').toggleClass('open closed');
	});


	// Special formation
	// if()
	var gS = $('#special .slick-track > .card').length;
	if(gS == 3) {
		console.log('changing');
		$('#special .slick-track .card:nth-child(3)').removeClass('fours').addClass('twos');
	} else {
		console.log('x');
	}


	// Collections Switcher
	$('.collections a.trig').click(function(e) {
		e.preventDefault();
		var slideID = $(this).attr("href");
		$('.details_container').hide();
		$(slideID).show();
	});
	$('.collections a.close').click(function() {
		$('.details_container').hide();
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

	var valT = location.href.match(/[?&]disciplina=(.*?)[$&]/)[1];
	$('select#disciplina').val(valT);

	var valL = location.href.match(/[?&]lugar=(.*?)$/)[1];
	$('select#lugar').val(valL);




// Fitvids (not working)
	$(".widget iframe").fitVids();






});
