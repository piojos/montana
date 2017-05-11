<?php

// SETUP : THEME SUPPORT


	if (function_exists('add_theme_support')) {
		// Add Menu Support
		add_theme_support('menus');

		// Add Thumbnail Theme Support
		add_theme_support('post-thumbnails');
		// add_image_size('medium', 450, '', true); // Medium Thumbnail
		// add_image_size('large', 1200, '', true); // Large Thumbnail
		// add_image_size('huge', 2000, '', true); // Huge Thumbnail
	}


// SETUP : ADD TO <HEAD>

	function montana_scripts() {
	// ADD ALL EXTERNAL scripts & styles FIRST. AVOID shame.css

	// STYLES
		// wp_enqueue_style( 'Normalizer', get_template_directory_uri() . '/css/normalize.min.css');
		wp_enqueue_style( 'Slick', get_template_directory_uri() . '/css/slick.css');
		// wp_enqueue_style( 'Dropdown', get_template_directory_uri() . '/css/dropdown.css');
		wp_enqueue_style( 'Style', get_stylesheet_uri() );
		// wp_enqueue_style( 'Shame', get_template_directory_uri() . '/css/shame.css');

	// SCRIPTS
		wp_enqueue_script( 'Slickjs', '//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js', array('jquery') ); //
		wp_enqueue_script( 'Modernizr', get_template_directory_uri() . '/js/modernizr.custom.63321.js' ); // Home
		// wp_enqueue_script( 'Dropdown', get_template_directory_uri() . '/js/jquery.dropdown.js' ); // Home
		wp_enqueue_script( 'Magic', get_template_directory_uri() . '/js/magic.js', array('jquery') );

	// CONDITIONAL LOADS
		// if(is_page('acerca')) {
		// 	wp_enqueue_script( 'Acerca', get_template_directory_uri() . '/js/acerca.js', array('jquery'));
		// }
	}
	add_action( 'wp_enqueue_scripts', 'montana_scripts' );




	// Register Menus

	// function register_my_menus() {
	// 	register_nav_menus(
	// 		array(
	// 			'header-menu' => __( 'Menu principal' ),
	// 			'extra-menu' => __( 'Menu secundario' ),
	// 			'footer-menu' => __( 'Menu inferior' )
	// 		)
	// 	);
	// }
	// add_action( 'init', 'register_my_menus' );








// SETUP : IMAGE HANDLING


//    2 Borrar tamaño original de disco y opción

	// function replace_uploaded_image($image_data) {
	// 	// if there is no large image : return
	// 	if (!isset($image_data['sizes']['huge'])) return $image_data;
	//
	// 	// paths to the uploaded image and the large image
	// 	$upload_dir = wp_upload_dir();
	// 	$uploaded_image_location = $upload_dir['basedir'] . '/' .$image_data['file'];
	// 	$large_image_filename = $image_data['sizes']['huge']['file'];
	//
	// 	// Do what wordpress does in image_downsize() ... just replace the filenames ;)
	// 	$image_basename = wp_basename($uploaded_image_location);
	// 	$large_image_location = str_replace($image_basename, $large_image_filename, $uploaded_image_location);
	//
	// 	// delete the uploaded image
	// 	unlink($uploaded_image_location);
	//
	// 	// rename the large image
	// 	rename($large_image_location, $uploaded_image_location);
	//
	// 	// update image metadata and return them
	// 	$image_data['width'] = $image_data['sizes']['huge']['width'];
	// 	$image_data['height'] = $image_data['sizes']['huge']['height'];
	// 	unset($image_data['sizes']['huge']);
	//
	// 	// Check if other size-configurations link to the large-file
	// 	foreach($image_data['sizes'] as $size => $sizeData) {
	// 	  if ($sizeData['file'] === $large_image_filename)
	// 	      unset($image_data['sizes'][$size]);
	// 	}
	//
	// 	return $image_data;
	// }
	// add_filter('wp_generate_attachment_metadata', 'replace_uploaded_image');








	// SETUP : ...
