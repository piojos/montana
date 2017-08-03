<?php

// SETUP : THEME SUPPORT


	if (function_exists('add_theme_support')) {
		// Add Menu Support
		add_theme_support('menus');

		// Add Thumbnail Theme Support
		add_theme_support('post-thumbnails');
		set_post_thumbnail_size( 350, 180, array( 'center', 'center'));
		add_image_size('medium', 700, 360, array( 'center', 'center')); // Cards
		add_image_size('poster', 260, 400, array( 'center', 'center')); // Movie Posters
		// add_image_size('medium-post', 700, ''); // Medium Post content
		add_image_size('large', 1100, 560); // Large Images
		add_image_size('huge', 1660, ''); // Huge Thumbnail
	}


// SETUP : ADD TO <HEAD>

	function montana_scripts() {
	// ADD ALL EXTERNAL scripts & styles FIRST. AVOID shame.css

	// STYLES
		wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );

		wp_enqueue_style( 'Slick', '//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css');
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'Style', get_stylesheet_uri() );

	// SCRIPTS
		wp_enqueue_script( 'Slickjs', '//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js', array('jquery') ); //
		wp_enqueue_script( 'Modernizr', get_template_directory_uri() . '/js/modernizr.custom.63321.js' ); // Home
		wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'Magic', get_template_directory_uri() . '/js/magic.js', array('jquery') );

	// CONDITIONAL LOADS
		// if(is_page('agenda')) {
		// 	wp_enqueue_script( 'Acerca', get_template_directory_uri() . '/js/acerca.js', array('jquery'));
		// }
	}
	add_action( 'wp_enqueue_scripts', 'montana_scripts' );




	// Register Menus

	function register_my_menus() {
		register_nav_menus(
			array(
				'main-menu' => __( 'Principal' ),
				'second-menu' => __( 'Secundario' ),
				'legal-menu' => __( 'Legal' )
			)
		);
	}
	add_action( 'init', 'register_my_menus' );








// SETUP : IMAGE HANDLING


//    2 Borrar tamaño original de disco y opción

	function replace_uploaded_image($image_data) {
		// if there is no large image : return
		if (!isset($image_data['sizes']['huge'])) return $image_data;

		// paths to the uploaded image and the large image
		$upload_dir = wp_upload_dir();
		$uploaded_image_location = $upload_dir['basedir'] . '/' .$image_data['file'];
		$large_image_filename = $image_data['sizes']['huge']['file'];

		// Do what wordpress does in image_downsize() ... just replace the filenames ;)
		$image_basename = wp_basename($uploaded_image_location);
		$large_image_location = str_replace($image_basename, $large_image_filename, $uploaded_image_location);

		// delete the uploaded image
		unlink($uploaded_image_location);

		// rename the large image
		rename($large_image_location, $uploaded_image_location);

		// update image metadata and return them
		$image_data['width'] = $image_data['sizes']['huge']['width'];
		$image_data['height'] = $image_data['sizes']['huge']['height'];
		unset($image_data['sizes']['huge']);

		// Check if other size-configurations link to the large-file
		foreach($image_data['sizes'] as $size => $sizeData) {
		  if ($sizeData['file'] === $large_image_filename)
		      unset($image_data['sizes'][$size]);
		}

		return $image_data;
	}
	add_filter('wp_generate_attachment_metadata', 'replace_uploaded_image');



// Excerpt Length
	function custom_excerpt_length( $length ) {
		return 15;
	}
	add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

	function new_excerpt_more( $more ) {
		return '…';
	}
	add_filter('excerpt_more', 'new_excerpt_more');





// SETUP : Rules for ACF
	function my_acf_admin_head() { ?>
		<style type="text/css">

		</style>

		<script type="text/javascript">
		(function($){

		})(jQuery);
		</script>
		<?php
	}

	add_action('acf/input/admin_head', 'my_acf_admin_head');






// Allow linebreaks on WYSYWYIGs

	function clear_br($content){
		return str_replace("<br />","<br clear='none'/>", $content);
	}
	add_filter('the_content', 'clear_br');





/*
*	Process days schedules and update $everyday
*/

	function everydayGen() {
		$post_type = get_post_type($post_id);
		if ($post_type == 'agenda') {
			$result = schedule_days_array();
		} elseif ($post_type == 'cineteca') {
			$result = movieDays_array();
		} else {}
		return $result;
	}

	function my_acf_save_post( $post_id ) {
		$value = get_field('everyday');
		update_field('everyday', everydayGen());
	}

	add_action('acf/save_post', 'my_acf_save_post', 20);









/* Options Page */
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_page(array(
			'page_title' 	=> 'Opciones Generales',
			'menu_title'	=> 'Opciones',
			'menu_slug' 	=> 'general-options',
			'capability'	=> 'edit_posts',
			'redirect'		=> false
		));
	}
