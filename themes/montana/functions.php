<?php

// Functions index

	// – General Settings & Theme support
	add_theme_support( 'post-thumbnails' );

function montana_fun() {

	// – Cards
	locate_template( array( 'fun/cards.php' ), true, true );

	// – Home Modules
	locate_template( array( 'fun/home-modules.php' ), true, true );

	// – Other
	locate_template( array( 'fun/other.php' ), true, true );

}
add_action( 'after_setup_theme', 'montana_fun' );
