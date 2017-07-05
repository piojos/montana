
jQuery( function($) {

// Home
	$('.slider').slick({
		arrows : false,
		dots : true,
		autoplay: true,
		autoplaySpeed: 4000
	});
	$( ".slick-dots" ).wrap( "<div class='max_wrap'></div>" );
	$('.slider_deck').slick({
		arrows : false,
		variableWidth: true,
		infinite: false,
		swipeToSlide: true
	});

	var wfp = $('.row.ftd_row');
	$('.slider_deck.this_week').slick('slickAdd', wfp,0,1);

	var maxHeight = -1;

	$('.slider_deck.this_week .row').each(function() {
		maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
	});
	$('.row.ftd_row .img_background').each(function() {
		$(this).height(maxHeight-126);
	});


// Home: News Dropdown
	$('.dropdown a.toggle_button').click(function() {
		$('.dropdown').toggleClass('open closed');
	});


// Home: Today's events Special formation
	// if()
	var gS = $('.area.special .slick-track > .card').length;
	if(gS == 3) {
		$('.area.special .slick-track .card:nth-child(3)').removeClass('fours').addClass('twos');
	}


// Home: Collections Switcher
	$('.collections li:nth-child(1) a.trig').addClass('current');
	$('.details_container').first().show();

	$('.collections a.trig').click(function(e) {
		$('.collections a.trig').removeClass('current');
		$(this).addClass('current');
		e.preventDefault();
		var slideID = $(this).attr("href");
		$('.details_container').hide();
		$(slideID).show();
	});
	$('.collections a.close').click(function() {
		$('.details_container').hide();
	});



// Single: Same size info
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
	// DEPR! Url doesn't change now
	// var valT = location.href.match(/[?&]disciplina=(.*?)[$&]/)[1];
	// $('select#disciplina').val(valT);
	//
	// var valL = location.href.match(/[?&]lugar=(.*?)$/)[1];
	// $('select#lugar').val(valL);


// Agenda: Ajax filtering
	$('form#searchform').on('submit',function(e){
		e.preventDefault();
		$('img.loader').show();
		$('#agenda input[type="submit"]').addClass('disabled');
		var action = $(this).attr('action');
		var serial = $(this).serialize();
		$.ajax({
			type     : "GET",
			cache    : false,
			url      : action,
			data     : serial,
			success  : function(data) {
				$('.ag_results').html($('#result_area', data).html());
				$('img.loader').fadeOut();
				$('#agenda input[type="submit"]').removeClass('disabled');
			}
		});
	});




// Fitvids (not working)
	$(".widget iframe").fitVids();




});
