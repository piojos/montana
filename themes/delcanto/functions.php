<?php

	function adc_functions() {

	// SETUP
		locate_template( array( 'func/setup.php' ), true, true );
		// 1. Theme Support		– Image sizes
		// 2. Head 				– Style & scripts
		// 3. Image Handling	– Crop original image to max size & delete from server
		// . ACF Style & scripts
		// . linebreaks on WYSYWYIGs
		// Process & save ACF'everyday'

	// SETTINGS & GENERAL USE
		locate_template( array( 'func/settings.php' ), true, true );

	// CARDS
		locate_template( array( 'func/cards.php' ), true, true );

	// SEARCH PAGES
	// (Agenda, Cineteca, Exposiciones, Talleres, Convocatorias)
		locate_template( array( 'func/searchers.php' ), true, true );

	// SINGLES
		locate_template( array( 'func/singles.php' ), true, true );

	// OTHER - (ACTIVE ONLY DURING DEV)
		// locate_template( array( 'fun/other.php' ), true, true );

	}
	add_action( 'after_setup_theme', 'adc_functions' );
