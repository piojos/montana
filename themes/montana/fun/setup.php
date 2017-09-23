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
	.acf-field.disabled input { opacity: .5; cursor: not-allowed;}
</style>
<script type="text/javascript">
	(function($){
		$('.acf-field.disabled input[type="text"]').prop('disabled', true);
	})(jQuery);
</script><?php
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
			$selDateInput = get_field('dates_options');
			if($selDateInput == 'dates') {
				$allDays = schedule_days_array();
				$result = $allDays;
			} elseif($selDateInput == 'range') {
				$rDates = array();
				if(have_rows('range_date_picker')){ while (have_rows('range_date_picker')) { the_row();
					$rDates[] = get_sub_field('start_day');
					$rDates[] = get_sub_field('end_day');
				}}
				$result = $rDates;
			} else {
				$result = 'Error';
			}
		} elseif ($post_type == 'cineteca') {
			$result = movieDays_array();
		} else {}
		return $result;
	}

	function get_range_start_day() {
		if(have_rows('range_date_picker')){ while (have_rows('range_date_picker')) { the_row();
			$startDate = get_sub_field('start_day');
		}}
		return $startDate;
	}

// Get dates array
	function montana_jf_dates_firstSchedule() {
		$format = 'YmdHi';
		$sched_array = movieSchedule_array();

		$rows = get_field('dates_picker'); // get all the rows
		$first_row = $rows[0]; // get the first row
		$stday = $first_row['day']; // get the sub field value

		$second_row = $first_row['schedules'];
		$first_hour = $second_row[0]; // get the sub field value
		$sthour = $first_hour['hour']; // get the sub field value
		$strHour = date('H:i', strtotime($sthour)); // Turn to hour

		$stDate = date_i18n($format, strtotime($stday.$strHour));

		return $stDate;
	}


// Get range
	function montana_jf_range_firstSchedule($object) {
		$format = 'YmdHi';

		$d_rows = get_field('range_date_picker'); // get all the rows
		$d_first_row = $d_rows[0]; // get the first row
		$stday = $d_first_row['start_day']; // get the sub field value

		$h_rows = get_field('schedules'); // get all the rows
		$h_first_row = $h_rows[0]; // get the first row
		$sthour = $h_first_row['hour']; // get the sub field value
		$strHour = date('H:i', strtotime($sthour)); // Turn to hour

		$stDate = date_i18n($format, strtotime($stday.$strHour));

		return $stDate;
	}

// Get range for Expos & Talleres
	function montana_jf_exporange_firstSchedule($object) {
		$format = 'YmdHi';

		$d_rows = get_field('range_date_picker'); // get all the rows
		$d_first_row = $d_rows[0]; // get the first row
		$stday = $d_first_row['start_day']; // get the sub field value

		$h_rows = get_field('schedules'); // get all the rows
		$h_first_row = $h_rows[0]; // get the first row
		$sthour = $h_first_row['hour-start']; // get the sub field value
		$strHour = date('H:i', strtotime($sthour)); // Turn to hour

		$stDate = date_i18n($format, strtotime($stday.$strHour));

		return $stDate;
	}


	function orderDayGen() {
		$post_type = get_post_type($post_id);
		if ($post_type == 'agenda') {
			$selDateInput = get_field('dates_options');
			if($selDateInput == 'dates') {
				$result = montana_jf_dates_firstSchedule();
			} elseif($selDateInput == 'range') {
				$result = montana_jf_range_firstSchedule();
			} else {
				$result = 'Error';
			}
		} elseif ($post_type == 'cineteca') {
			$result = montana_jf_dates_firstSchedule();
		} else {
			$result = montana_jf_exporange_firstSchedule();
		}
		return $result;
	}

	function montana_acf_save_post( $post_id ) {
		$value = get_field('everyday');
		update_field('everyday', everydayGen());
		$valueOrd = get_field('order_day');
		update_field('order_day', orderDayGen());
	}

	add_action('acf/save_post', 'montana_acf_save_post', 20);


/*
 *	Sortable Order column
 */
// Agenda
	function montana_agenda_modify_columns( $columns ) {
		$new_columns = array(
			'order_day' => __( 'Orden Monitor', 'montana' ),
		);
		$filtered_columns = array_merge( $columns, $new_columns );
		return $filtered_columns;
	}
	add_filter('manage_agenda_posts_columns' , 'montana_agenda_modify_columns');

	function montana_agenda_custom_column_content( $column ) {
		global $post;
		switch ( $column ) {
			case 'order_day' :
			$start = get_post_meta( $post->ID, 'order_day', true );
			echo $start;
		break;
		}
	}
	add_action( 'manage_agenda_posts_custom_column', 'montana_agenda_custom_column_content' );

	function montana_agenda_custom_columns_sortable( $columns ) {
		$columns['order_day'] = 'order_day';
		// unset( $columns['date'] );
		return $columns;
	}
	add_filter( 'manage_edit-agenda_sortable_columns', 'montana_agenda_custom_columns_sortable' );


// Cineteca
	function montana_cineteca_modify_columns( $columns ) {
		$new_columns = array(
			'order_day' => __( 'Orden Monitor', 'montana' ),
		);
		$filtered_columns = array_merge( $columns, $new_columns );
		return $filtered_columns;
	}
	add_filter('manage_cineteca_posts_columns' , 'montana_cineteca_modify_columns');

	function montana_cineteca_custom_column_content( $column ) {
		global $post;
		switch ( $column ) {
			case 'order_day' :
			$start = get_post_meta( $post->ID, 'order_day', true );
			echo $start;
		break;
		}
	}
	add_action( 'manage_cineteca_posts_custom_column', 'montana_cineteca_custom_column_content' );

	function montana_cineteca_custom_columns_sortable( $columns ) {
		$columns['order_day'] = 'order_day';
		// unset( $columns['date'] );
		return $columns;
	}
	add_filter( 'manage_edit-cineteca_sortable_columns', 'montana_cineteca_custom_columns_sortable' );


// exposiciones
function montana_exposiciones_modify_columns( $columns ) {
	$new_columns = array(
		'order_day' => __( 'Orden Monitor', 'montana' ),
	);
	$filtered_columns = array_merge( $columns, $new_columns );
	return $filtered_columns;
}
add_filter('manage_exposiciones_posts_columns' , 'montana_exposiciones_modify_columns');

function montana_exposiciones_custom_column_content( $column ) {
	global $post;
	switch ( $column ) {
		case 'order_day' :
		$start = get_post_meta( $post->ID, 'order_day', true );
		echo $start;
	break;
	}
}
add_action( 'manage_exposiciones_posts_custom_column', 'montana_exposiciones_custom_column_content' );

function montana_exposiciones_custom_columns_sortable( $columns ) {
	$columns['order_day'] = 'order_day';
	// unset( $columns['date'] );
	return $columns;
}
add_filter( 'manage_edit-exposiciones_sortable_columns', 'montana_exposiciones_custom_columns_sortable' );

// Talleres
	function montana_talleres_modify_columns( $columns ) {
		$new_columns = array(
			'order_day' => __( 'Orden Monitor', 'montana' ),
		);
		$filtered_columns = array_merge( $columns, $new_columns );
		return $filtered_columns;
	}
	add_filter('manage_talleres_posts_columns' , 'montana_talleres_modify_columns');

	function montana_talleres_custom_column_content( $column ) {
		global $post;
		switch ( $column ) {
			case 'order_day' :
			$start = get_post_meta( $post->ID, 'order_day', true );
			echo $start;
		break;
		}
	}
	add_action( 'manage_talleres_posts_custom_column', 'montana_talleres_custom_column_content' );

	function montana_talleres_custom_columns_sortable( $columns ) {
		$columns['order_day'] = 'order_day';
		// unset( $columns['date'] );
		return $columns;
	}
	add_filter( 'manage_edit-talleres_sortable_columns', 'montana_talleres_custom_columns_sortable' );





// Ordenar por meta_value_num
	function montana_customer_post_order ($query) {
		if( $query->is_home() && $query->is_main_query() ) {
			$query->set('meta_key', 'order_day');
			$query->set('orderby', 'meta_value_num');
			$query->set('order', 'DESC');
		}
	}
	add_action('pre_get_posts', 'montana_customer_post_order');




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
