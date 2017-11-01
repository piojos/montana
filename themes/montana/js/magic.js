
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
	$('.autoslider').slick({
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 2000,
		arrows : false,
		adaptiveHeight: true
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
	var gS = $('.area.for_today .slick-track > .card').length;
	if(gS === 3) {
		$('.area.for_today').addClass('special');
		$('.area.for_today .slick-track .card:nth-child(3)').removeClass('fours').addClass('twos');
	}


// Home: Collections Switcher
	$('.collections li:nth-child(1) a.trig').addClass('current');
	$('.area.collections').each(function(){
		$(this).find('.details_container:first').show();
	});


	$('.collections a.trig').click(function(e) {
		e.preventDefault();
		var pID = $(this).closest('.area.collections').attr('id');
		var slideID = $(this).attr("href");

		$('#'+pID).find('a.trig').removeClass('current');
		$(this).addClass('current');
		$('#'+pID).find('.details_container').hide();

		$('#'+pID).find(slideID).show();
	});

	$('.collections a.close').click(function() {
		var pID = $(this).closest('.area.collections').attr('id');
		$('#'+pID).find('.details_container').hide();
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


// Card: add no-image class
	$('.card .img_container').each(function() {
		if ($(this).find('img').length) {
			$(this).closest('.card').addClass('has-image');
		} else {
			$(this).closest('.card').addClass('no-image');
		}
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
	$('form#searchfilter').on('submit',function(e){
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


// Header: Searchbar
	$('header form.searchform input#s').after('<a id="close_search">×</a>');
	$('header .screen-reader-text').remove();
	$('header form.searchform input#s').attr('placeholder', 'Busca en todo CONARTE');
	$('header a#open_search').click(function() {
		$('header .navigation, header').addClass('active_search');
	});
	$('header a#close_search').click(function() {
		$('header .navigation, header').removeClass('active_search');
	});


// Header: Menu
	$('header a#nav_toggle_button').click(function() {
		$('header').toggleClass('open_menu close_menu');
	});





// Fitvids (not working)
	$(".widget iframe").fitVids();





// Arrange by hours
	$('.ag_results li .schedule p').each(function() {
		var hoursString = $(this).html();
		var n = hoursString.indexOf(',');
		hoursString = hoursString.substring(0, n != -1 ? n : hoursString.length);
		$(this).closest('li').attr('data-position', $.trim(hoursString));
	});

	$('.ag_results ul').each(function() {
		var childLi = $(this).find('li');
		// var parentUl = $(this).find('ul');
		// var thisID = $(this).attr('id');
		console.log('id '+childLi);
		$(childLi).sort(sort_li).appendTo($(this));
		function sort_li(a, b) {
			return ($(b).data('position')) < ($(a).data('position')) ? 1 : -1;
		}
	});

	// $(".ag_results li").sort(sort_li).appendTo($(this).parent('ul'));
	// $(".ag_results li").sort(sort_li).appendTo($(this).closest('ul'));
	// function sort_li(a, b) {
	// 	return ($(b).data('position')) < ($(a).data('position')) ? 1 : -1;
	// }



});
