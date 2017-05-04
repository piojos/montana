<?php

	function montana_fun() {

	// SETUP
		locate_template( array( 'fun/setup.php' ), true, true );
		// 1. Theme Support
		// 2. Head
		// 3. Image Handling

	// CARDS
		locate_template( array( 'fun/cards.php' ), true, true );

	// HOME MODULES
		locate_template( array( 'fun/home-modules.php' ), true, true );

	// OTHER
		locate_template( array( 'fun/other.php' ), true, true );

	}
	add_action( 'after_setup_theme', 'montana_fun' );
