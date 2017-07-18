<?php

	function montana_fun() {

	// SETUP
		locate_template( array( 'fun/setup.php' ), true, true );
		// 1. Theme Support		– Image sizes
		// 2. Head 				– Style & scripts
		// 3. Image Handling	– Crop original image to max size & delete from server
		// . ACF Style & scripts
		// . linebreaks on WYSYWYIGs
		// Process & save ACF'everyday'

	// CARDS
		locate_template( array( 'fun/cards.php' ), true, true );
		// 1. movie_meta	– Generates movie meta snippet
		// 2. get_place		– Gets Location Name from ACF'location_picker'
		// 3. cards			– Generates a card
		// 4. montana_ftdimg	–
		// 5. montana_post_thumbnail	–
		// 6. list_card 		– Generates a "listed card"
		// 7. deck 			– Generates a 'deck' of 'card's
		// 8. findIn			– Find in Array or String (used in slider_deck)
		// 9. checkOddNum		– Checks for even number
		// 10. slider_deck	– Generates a 'deck' of 'card's
		// 11. getClassofQuery	– Chooses width of cards from query count
		// 12. keyword_box	– Generates .parent_label box in cards
		// 13. keyword_gen	– Generates keywords

	// HOME MODULES
		locate_template( array( 'fun/home-modules.php' ), true, true );

	// OTHER
		locate_template( array( 'fun/other.php' ), true, true );
		// 1. schedule_hours_array –
		// schedule_hours
		// schedule_days_array
		// schedule_days
		// createRangeWeekdays
		// movieSchedule_array
		// movieDays_array
		// movieDays
		// movieFutureSchedule
		// movieFutureSchedule_array
		// movieHoursClosestday
		// prefix_forDay
		// listSelOptions
		// testPlaceSkill
		// get_skills
		// result_list
		// logo_or_title

	}
	add_action( 'after_setup_theme', 'montana_fun' );
